<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $method;
    public $old_user_id;

    /**
     * Create a new event instance.
     */
    public function __construct($room, $method = 'store', $old_user_id = null)
    {
        $this->room = $room;
        $this->method = $method;
        $this->old_user_id = $old_user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
//    public function broadcastOn(): array
//    {
//        return [
//            new PrivateChannel('channel-name'),
//        ];
//    }
}
