<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsAppService;
use App\Services\InvoiceConsolidationService;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'room']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month_year) {
            $query->where('month_year', $request->month_year);
        }

        $payments = $query->latest()->paginate(6)->appends($request->query());

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('user', 'room', 'verifier');
        return view('admin.payments.show', compact('payment'));
    }

    public function generateBulk(Request $request)
{
    $validated = $request->validate([
        'month_year' => 'required|date_format:Y-m',
    ]);

    $occupiedRooms = Room::where('status', 'occupied')->with('user')->get();
    $createdCount = 0;
    $notifiedCount = 0;
    $whatsapp = new WhatsAppService();

    foreach ($occupiedRooms as $room) {
        $user = $room->user;
        if (!$user) {
            continue;
        }

        $exists = Payment::where('user_id', $room->user_id)
            ->where('room_id', $room->id)
            ->where('month_year', $validated['month_year'])
            ->where('type', 'rent')
            ->exists();

        if (!$exists) {
            $payment = Payment::create([
                'invoice_number' => Payment::generateInvoiceNumber(),
                'user_id' => $room->user_id,
                'room_id' => $room->id,
                'amount' => $room->price,
                'type' => 'rent',
                'month_year' => $validated['month_year'],
                'status' => 'pending',
            ]);

            // Konsolidasikan ke invoice bulanan
            try {
                app(InvoiceConsolidationService::class)->attachPaymentToInvoice($payment);
            } catch (\Throwable $e) {
                // optional log
            }

            $createdCount++;

            // Kirim notifikasi ke user dan admin
            app(NotificationService::class)->paymentCreated($payment);

            // Send WhatsApp reminder if phone available, but don't fail the loop
            try {
                if (!empty($user->phone) && ($user->whatsapp_opt_in ?? false)) {
                    $whatsapp->sendPaymentReminder($user, $payment);
                    $notifiedCount++;
                }
            } catch (\Throwable $e) {
                // Optional: logger()->warning('WA reminder failed: '.$e->getMessage());
            }
        }
    }

    return back()->with('success', "Berhasil generate {$createdCount} tagihan & notifikasi terkirim ke {$notifiedCount} penyewa");
}

   public function verify(Payment $payment)
{
    if ($payment->status !== 'paid') {
        return back()->with('error', 'Payment belum dibayar');
    }

    $payment->update([
        'status' => 'verified',
        'verified_at' => now(),
        'verified_by' => auth()->id(),
        // Tambahkan ini agar masuk ke laporan bulan berjalan:
        'month_year' => now()->format('Y-m'), 
    ]);

    // Aktivasi booking & kamar jika ada booking terkait invoice ini
    try {
        \DB::transaction(function () use ($payment) {
            $booking = \App\Models\Booking::where('invoice_number', $payment->invoice_number)
                ->where('status', 'pending')
                ->first();

            if ($booking) {
                // Update booking ke aktif & lunas
                $booking->update([
                    'payment_status' => 'lunas',
                    'status' => 'active',
                    'amount_paid' => $booking->total_price,
                ]);

                // Update kamar menjadi occupied & assign user
                if ($booking->room) {
                    $booking->room->update([
                        'status' => 'occupied',
                        'user_id' => $booking->user_id,
                    ]);
                }
            }
        });
    } catch (\Throwable $e) {
        // Optional: log error aktivasi
    }

    // Pastikan terkonsolidasi ke invoice
    try {
        app(InvoiceConsolidationService::class)->attachPaymentToInvoice($payment);
    } catch (\Throwable $e) {
        // optional log
    }

    // Send notification
    app(NotificationService::class)->paymentCompleted($payment);

    // Send WhatsApp notification
    $whatsapp = new WhatsAppService();
    if (!empty($payment->user->phone) && ($payment->user->whatsapp_opt_in ?? false)) {
        $whatsapp->sendPaymentConfirmation($payment->user, $payment);
    }

    return back()->with('success', 'Pembayaran berhasil diverifikasi & booking diaktifkan');
}
    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $payment->update([
            'status' => 'rejected',
            'notes' => $validated['notes'],
        ]);

        // Send notification to user
        $payment->user->notifications()->create([
            'type' => 'payment_rejected',
            'title' => 'Pembayaran Ditolak',
            'message' => "Pembayaran {$payment->invoice_number} ditolak. Alasan: {$validated['notes']}",
            'data' => ['payment_id' => $payment->id],
        ]);

        // Optional: Send WhatsApp rejection (respect opt-in)
        $whatsapp = new WhatsAppService();
        if (!empty($payment->user->phone) && ($payment->user->whatsapp_opt_in ?? false)) {
            $message = "Halo {$payment->user->name}, pembayaran {$payment->invoice_number} ditolak. Alasan: {$validated['notes']}. Silakan cek aplikasi untuk detail.";
            $whatsapp->sendMessage($payment->user->phone, $message);
        }

        return back()->with('success', 'Pembayaran ditolak');
    }

    public function sendWaReminder(Payment $payment)
    {
        $payment->load('user');
        $user = $payment->user;
        if (!$user || empty($user->phone)) {
            return back()->with('error', 'Nomor telepon tidak tersedia');
        }
        if (!($user->whatsapp_opt_in ?? false)) {
            return back()->with('error', 'User tidak mengizinkan WhatsApp');
        }

        $wa = new WhatsAppService();
        $wa->sendPaymentReminder($user, $payment);

        return back()->with('success', 'WA Reminder dikirim');
    }

    public function print(Payment $payment)
    {
        $payment->load('user', 'room', 'verifier');
        
        $pdf = Pdf::loadView('admin.payments.print', compact('payment'));
        
        return $pdf->download("invoice-{$payment->invoice_number}.pdf");
    }

    public function testForm()
    {
        return view('admin.notifications.test');
    }

    public function testSend(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $wa = new WhatsAppService();
        $ok = false;
        $debug = '';
        try {
            $ok = $wa->sendMessage($validated['phone'], $validated['message']);
            $debug = $ok ? 'Send OK' : 'Send failed';
        } catch (\Throwable $e) {
            $ok = false;
            $debug = 'Exception: ' . $e->getMessage();
        }

        return back()->with($ok ? 'success' : 'error', $ok ? 'Pesan dikirim' : 'Gagal mengirim pesan')
                     ->with('debug', $debug);
    }
}