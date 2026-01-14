<?php

namespace App\Listeners;

use App\Events\PaymentStatusUpdated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePaymentNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentStatusUpdated $event): void
    {
        $notificationService = app(NotificationService::class);
        $payment = $event->payment;
        $oldStatus = $event->oldStatus;

        // Kirim notifikasi berdasarkan status baru
        switch ($payment->status) {
            case 'paid':
                $notificationService->paymentCompleted($payment);
                break;
            case 'verified':
                // Notifikasi tambahan jika status berubah dari paid ke verified
                if ($oldStatus === 'paid') {
                    $notificationService->paymentCompleted($payment);
                }
                break;
            case 'rejected':
                // Notifikasi pembayaran ditolak
                $notificationService->sendToUser(
                    $payment->user_id,
                    'payment_rejected',
                    'Pembayaran Ditolak',
                    'Pembayaran ' . $payment->description . ' ditolak.',
                    ['payment_id' => $payment->id, 'reason' => $payment->notes]
                );
                break;
        }
    }
}
