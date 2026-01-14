<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
class MidtransService
{
    private string $serverKey;
    private string $clientKey;
    private bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key');
        $this->clientKey = (string) config('services.midtrans.client_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
    }

    private function baseUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }

    /**
     * Build Snap transaction params yang fleksibel
     * Bisa menerima Model Booking atau Model Payment
     */
    public function checkStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            // Mengembalikan status transaksi (misal: 'expire', 'pending', 'settlement')
            return $status->transaction_status;
        } catch (\Exception $e) {
            // Jika order_id tidak ditemukan atau error lainnya
            return null;
        }
    }
    public function buildSnapParams($order, ?int $overrideAmount = null)
    {
        // Deteksi User (dari relasi atau auth)
        $user = $order->user ?? Auth::user();
        
        // Deteksi Harga (Booking pakai total_price, Payment pakai amount)
        $amount = $overrideAmount ?? (int) ($order->amount ?? $order->total_price);
        
        // Deteksi Nama Item
        $itemName = isset($order->room) 
            ? 'Sewa Kamar Unit ' . ($order->room->room_number ?? $order->room_id)
            : 'Pembayaran Tagihan #' . ($order->invoice_number ?? $order->id);

        return [
            'transaction_details' => [
                'order_id' => ($order->invoice_number ?? 'ORDER-' . $order->id),
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $order->id,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => substr($itemName, 0, 50), // Limit 50 karakter sesuai aturan Midtrans
                ]
            ],
            'callbacks' => [
                // Mengarahkan kembali ke halaman detail yang sesuai
                'finish' => $order instanceof \App\Models\Booking 
                            ? route('bookings.show', $order->id) 
                            : route('user.payments.show', $order->id),
            ],
        ];
    }

    public function createSnapToken(array $params): string
    {
        $client = new Client([
            'base_uri' => $this->baseUrl(),
            'timeout'  => 15,
        ]);

        try {
            $response = $client->post('/snap/v1/transactions', [
                'auth' => [$this->serverKey, ''],
                'json' => $params,
            ]);
            
            $data = json_decode((string) $response->getBody(), true);
            return $data['token'] ?? '';
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap token error: ' . $e->getMessage());
            return '';
        }
    }

    public function verifySignature(array $payload): bool
    {
        $orderId     = $payload['order_id'] ?? '';
        $statusCode  = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signature   = $payload['signature_key'] ?? '';

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($expected, (string) $signature);
    }
}