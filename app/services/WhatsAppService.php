<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://api.fonnte.com/send');
        $this->apiKey = config('services.whatsapp.api_key', '');
    }

    /**
     * Kirim pesan WhatsApp dasar
     */
    public function sendMessage(string $phoneNumber, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::warning('WhatsApp API Key is empty. Please check your .env file.');
            return false;
        }

        try {
            $phone = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->apiUrl, [
                'target'  => $phone,
                'message' => $message,
                'delay'   => '2', // Jeda opsional agar tidak dianggap spam
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent to {$phone}");
                return true;
            }

            Log::error("WhatsApp API Error", [
                'status' => $response->status(),
                'body'   => $response->json()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reminder Pembayaran (Tagihan Baru & Penolakan)
     */
    public function sendPaymentReminder(User $user, Payment $payment): bool
    {
        $period = Carbon::parse($payment->month_year)->translatedFormat('F Y');
        $isRejected = $payment->status === 'rejected';

        if ($isRejected) {
            $header = "❌ *PEMBAYARAN DITOLAK*";
            $statusLabel = "DITOLAK";
            $closing = "Mohon unggah kembali bukti transfer yang valid. Jika ada kendala, hubungi admin.";
        } else {
            $header = "🔔 *TAGIHAN SEWA KOS*";
            $statusLabel = "MENUNGGU PEMBAYARAN";
            $closing = "Silakan lakukan pembayaran dan unggah bukti transfer melalui aplikasi.";
        }

        $message = "{$header}\n\n"
            . "Halo *{$user->name}*,\n"
            . "Berikut adalah detail tagihan Anda:\n"
            . "━━━━━━━━━━━━━━━━━\n"
            . "📄 Invoice : `{$payment->invoice_number}`\n"
            . "🏠 Kamar   : {$payment->room->room_number}\n"
            . "📅 Bulan   : {$period}\n"
            . "💰 Jumlah  : *{$payment->formatted_amount}*\n"
            . "📝 Status  : {$statusLabel}\n";

        if ($isRejected && !empty($payment->notes)) {
            $message .= "📌 Alasan  : {$payment->notes}\n";
        }

        $message .= "━━━━━━━━━━━━━━━━━\n\n"
            . "{$closing}\n\n"
            . "Terima kasih! 🙏";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Konfirmasi Pembayaran Berhasil
     */
    public function sendPaymentConfirmation(User $user, Payment $payment): bool
    {
        $period = Carbon::parse($payment->month_year)->translatedFormat('F Y');

        $message = "✅ *PEMBAYARAN TERVERIFIKASI*\n\n"
            . "Halo *{$user->name}*,\n"
            . "Terima kasih, pembayaran Anda telah kami terima:\n"
            . "━━━━━━━━━━━━━━━━━\n"
            . "📄 Invoice : `{$payment->invoice_number}`\n"
            . "🏠 Kamar   : {$payment->room->room_number}\n"
            . "📅 Bulan   : {$period}\n"
            . "💰 Jumlah  : *{$payment->formatted_amount}*\n"
            . "━━━━━━━━━━━━━━━━━\n\n"
            . "Pembayaran Anda sudah masuk ke dalam sistem. Have a nice day! ✨";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Update Permintaan Layanan (Service)
     */
    public function sendServiceUpdate(User $user, $serviceRequest, string $status): bool
    {
        $statusMap = [
            'approved'  => '✅ DISETUJUI',
            'rejected'  => '❌ DITOLAK',
            'completed' => '✅ SELESAI',
        ];

        $statusText = $statusMap[$status] ?? '📋 UPDATE';

        $message = "🛎 *UPDATE LAYANAN*\n\n"
            . "Halo *{$user->name}*,\n"
            . "Permintaan layanan Anda telah diperbarui:\n"
            . "━━━━━━━━━━━━━━━━━\n"
            . "🔧 Jenis   : {$serviceRequest->service_type_name}\n"
            . "📝 Status  : *{$statusText}*\n";
        
        if ($status === 'approved' && $serviceRequest->price > 0) {
            $message .= "💰 Harga   : {$serviceRequest->formatted_price}\n";
        }
        
        if (!empty($serviceRequest->admin_notes)) {
            $message .= "📌 Catatan : {$serviceRequest->admin_notes}\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━\n\n"
            . "Cek detail lengkapnya di dashboard aplikasi.";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Helper untuk standarisasi format nomor telepon (62)
     */
    private function formatPhoneNumber(string $number): string
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }

        return $number;
    }
}