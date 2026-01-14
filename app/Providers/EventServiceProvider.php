<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PaymentStatusUpdated;
use App\Events\ServiceRequestStatusUpdated;
use App\Events\RoomStatusUpdated;
use App\Listeners\UpdatePaymentNotification;
use App\Listeners\UpdateServiceRequestNotification;
use App\Listeners\UpdateRoomNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PaymentStatusUpdated::class => [
            UpdatePaymentNotification::class,
        ],
        ServiceRequestStatusUpdated::class => [
            UpdateServiceRequestNotification::class,
        ],
        RoomStatusUpdated::class => [
            UpdateRoomNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}