<?php

namespace App\Events;

use App\Marca;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarcaEditada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $marca;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
    }
}
