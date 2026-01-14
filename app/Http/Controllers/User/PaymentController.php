<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $midtransService;
// App\Http\Controllers\User\PaymentController.php
public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
public function index(Request $request)
    {
        $query = Payment::with(['room'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('month_year') && $request->month_year != '') {
            $query->where('month_year', 'like', $request->month_year . '%');
        }

        $payments = $query->paginate(6);
        return view('user.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('room')->findOrFail($id);

        if ($payment->status === 'pending') {
            $statusMidtrans = $this->midtransService->checkStatus($payment->invoice_number);

            if (empty($payment->snap_token) || in_array($statusMidtrans, ['expire', 'cancel', 'deny'])) {
                $params = $this->midtransService->buildSnapParams($payment);
                $token = $this->midtransService->createSnapToken($params);

                if ($token) {
                    DB::table('payments')->where('id', $id)->update(['snap_token' => $token]);
                    $payment = $payment->fresh();
                }
            }
        }

        return view('user.payments.show', compact('payment'));
    }

    public function uploadProof(Request $request, $id)
{
    // 1. Sesuaikan nama field menjadi 'proof_image' sesuai di Blade
    $request->validate([
        'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $payment = Payment::findOrFail($id);

    // 2. Gunakan 'proof_image' untuk mengambil filenya
    if ($request->hasFile('proof_image')) {
        // Simpan ke folder yang benar
        $path = $request->file('proof_image')->store('payment_proofs', 'public');
        
        // 3. Update kolom 'proof_image' di database (sesuaikan nama kolom tabel Anda)
        $payment->update([
            'proof_image' => $path, 
            'status' => 'paid', // Ubah ke status 'paid' agar admin tahu sudah bayar
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah!');
    }

    return redirect()->back()->with('error', 'Gagal mengunggah gambar.');
}
    public function uploadManual(Request $request, Payment $payment)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment_proofs', 'public');
            
            $payment->update([
                'proof_image' => $path,
                'status' => 'paid', // Status berubah jadi 'sudah bayar' tapi butuh verifikasi admin
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }
    public function handleNotification(Request $request)
{
    $payload = $request->all();
    $midtransService = new \App\Services\MidtransService();

    // 1. Verifikasi Signature agar data tidak bisa dipalsukan
    if (!$midtransService->verifySignature($payload)) {
        return response()->json(['message' => 'Invalid Signature'], 403);
    }

    $orderId = $payload['order_id'];
    $transactionStatus = $payload['transaction_status'];
    $type = $payload['payment_type'];

    // 2. Cari data berdasarkan Invoice Number
    $payment = \App\Models\Payment::where('invoice_number', $orderId)->first();

    if (!$payment) {
        return response()->json(['message' => 'Payment not found'], 404);
    }

    // 3. Update Status berdasarkan status transaksi Midtrans
    if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
        $payment->update([
            'status' => 'verified',
            'paid_at' => now(),
        ]);
    } elseif ($transactionStatus == 'pending') {
        $payment->update(['status' => 'pending']);
    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
        $payment->update(['status' => 'rejected']);
    }

    return response()->json(['message' => 'OK']);
}

   

}