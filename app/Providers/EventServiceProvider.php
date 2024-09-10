<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ConfirmationEvent;
use App\Listeners\UpdateItemListener;
use App\Events\ItemEvent;
use App\Listeners\UpdateConfirmationListener;
use App\Events\RoomEvent;
use App\Listeners\UpdateResponsibilityListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ConfirmationEvent::class => [
            UpdateItemListener::class,
        ],
        ItemEvent::class => [
            UpdateConfirmationListener::class,
        ],

        RoomEvent::class => [
            UpdateResponsibilityListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
