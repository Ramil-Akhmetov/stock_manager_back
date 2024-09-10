<?php

namespace App\Listeners;

use App\Events\ConfirmationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateItemListener
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
    public function handle(ConfirmationEvent $event): void
    {
        $event->confirmation->item()->update([
            'quantity' => $event->confirmation->quantity,
        ]);
    }
}
