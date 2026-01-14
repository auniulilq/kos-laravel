<?php

namespace App\Listeners;

use App\Events\RoomStatusUpdated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRoomNotification implements ShouldQueue
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
    public function handle(RoomStatusUpdated $event): void
    {
        $notificationService = app(NotificationService::class);
        $room = $event->room;
        $oldStatus = $event->oldStatus;

        // Kirim notifikasi berdasarkan status baru kamar
        $notificationService->roomStatusUpdated($room, $oldStatus);
    }
}
