<?php

namespace App\Listeners;

use App\Events\ServiceRequestStatusUpdated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateServiceRequestNotification implements ShouldQueue
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
    public function handle(ServiceRequestStatusUpdated $event): void
    {
        $notificationService = app(NotificationService::class);
        $serviceRequest = $event->serviceRequest;
        $oldStatus = $event->oldStatus;

        // Kirim notifikasi berdasarkan status baru
        $notificationService->serviceRequestStatusUpdated($serviceRequest, $oldStatus);
    }
}
