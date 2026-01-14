<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();
        $service = app(MidtransService::class);

        if (!$service->verifySignature($payload)) {
            Log::warning('Midtrans signature verification failed', $payload);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'missing order_id'], 400);
        }

        $payment = Payment::where('invoice_number', $orderId)->first();
        if (!$payment) {
            return response()->json(['message' => 'not found'], 404);
        }

        // Map Midtrans status to our status
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $payment->status = 'pending';
                } else {
                    $payment->status = 'paid';
                    $payment->paid_at = now();
                }
                break;
            case 'settlement':
                $payment->status = 'paid';
                $payment->paid_at = now();
                break;
            case 'pending':
                $payment->status = 'pending';
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $payment->status = 'rejected';
                break;
        }

        // append notes log
        $logMsg = 'Midtrans: status='.$transactionStatus.' order_id='.$orderId;
        $payment->notes = trim(($payment->notes ? $payment->notes."\n" : '').$logMsg);
        $payment->save();

        // Optionally consolidate to invoice on paid
        if ($payment->status === 'paid') {
            try {
                app(\App\Services\InvoiceConsolidationService::class)->attachPaymentToInvoice($payment);
            } catch (\Throwable $e) {
                // swallow
            }
        }

        return response()->json(['message' => 'ok']);
    }
}