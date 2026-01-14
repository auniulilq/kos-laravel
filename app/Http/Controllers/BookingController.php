<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'duration' => 'required|in:Mingguan,Bulanan,Tahunan',
            'start_date' => 'required|date',
            'payment_method' => 'required|in:full,dp',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $start = Carbon::parse($validated['start_date']);
        $durationType = $validated['duration']; // Simpan untuk dimasukkan ke DB

        // Hitung end_date dan total_price
        switch ($durationType) {
            case 'Mingguan':
                $end = $start->copy()->addDays(7)->subDay();
                $total_price = (int) ceil($room->price / 4);
                break;
            case 'Bulanan':
                $end = $start->copy()->addMonth()->subDay();
                $total_price = (int) $room->price;
                break;
            case 'Tahunan':
                $end = $start->copy()->addYear()->subDay();
                $total_price = (int) ($room->price * 12);
                break;
            default:
                $end = $start->copy()->addMonth()->subDay();
                $total_price = (int) $room->price;
        }

        $paymentMethod = $validated['payment_method'];
        if ($durationType === 'Mingguan') {
            $paymentMethod = 'full';
        }

        $paymentAmount = $paymentMethod === 'dp'
            ? (int) $room->price 
            : (int) $total_price;

        try {
            // Gunakan $durationType di dalam closure transaction
            $result = DB::transaction(function () use ($room, $start, $end, $total_price, $paymentAmount, $durationType) {
    // 1. Simpan booking
    $booking = Booking::create([
        'user_id' => Auth::id(),
        'room_id' => $room->id,
        'start_date' => $start->toDateString(),
        'end_date' => $end->toDateString(),
        'duration_type' => $durationType,
        'total_price' => $total_price,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'invoice_number' => Booking::generateInvoiceNumber(),
    ]);

    // 2. Buat payment (TAMBAHKAN month_year DI SINI)
    $payment = Payment::create([
        'invoice_number' => $booking->invoice_number,
        'user_id' => Auth::id(),
        'room_id' => $room->id,
        'amount' => $paymentAmount,
        'type' => 'booking',
        'status' => 'pending',
        'month_year' => $start->format('F Y'), // Contoh: "January 2026"
    ]);

    return [$booking, $payment];
});
            [$booking] = $result;
            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');

        } catch (\Throwable $e) {
            // Log error untuk mempermudah debugging jika masih gagal
            \Log::error('Booking Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal membuat booking: ' . $e->getMessage()]);
        }
    }
    public function show($id)
    {
        $booking = Booking::with(['room', 'user'])->findOrFail($id);
        // Cari payment berdasarkan nomor invoice booking
        $payment = Payment::where('invoice_number', $booking->invoice_number)->first();
        return view('bookings.show', compact('booking', 'payment'));
    }

    public function handleNotification(Request $request)
    {
        // ... existing code ...
    }
}