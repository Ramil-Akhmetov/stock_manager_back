<?php

namespace App\Listeners;

use App\Events\RoomEvent;
use App\Models\Responsibility;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateResponsibilityListener
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
    public function handle(RoomEvent $event): void
    {
        $this->{$event->method}($event);
    }

    private function store(RoomEvent $event): void
    {
        Responsibility::create([
            'start_date' => Carbon::now()->toDateString(),
            'user_id' => $event->room->user_id,
            'room_id' => $event->room->id,
        ]);
    }

    private function update(RoomEvent $event): void
    {
        if($event->old_user_id === $event->room->user_id) {
            return;
        }

        if($event->old_user_id !== null) {
            $responsibility = Responsibility::where('user_id', $event->old_user_id)
                ->where('room_id', $event->room->id)
                ->where('end_date', null)
                ->first();
            //TODO work but can happen 500 exception
            //maybe should add if statement or change something in database
            $responsibility->update([
                'end_date' => Carbon::now()->toDateString(),
            ]);
            $responsibility->save();
        }

        Responsibility::create([
            'start_date' => Carbon::now()->toDateString(),
            'user_id' => $event->room->user_id,
            'room_id' => $event->room->id,
        ]);
    }
}
