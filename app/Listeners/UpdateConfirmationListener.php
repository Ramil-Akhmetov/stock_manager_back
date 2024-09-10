<?php

namespace App\Listeners;

use App\Events\ItemEvent;
use App\Models\Confirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateConfirmationListener
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
    public function handle(ItemEvent $event): void
    {
        Confirmation::create([
            'item_id' => $event->item->id,
            'quantity' => $event->item->quantity,
            'user_id' => \request()->user()->id,
        ]);
    }
}
