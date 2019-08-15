<?php

namespace App\Events;

use App\Marca;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MarcaCreada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $marca;
    public $user_id;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
        $this->user_id = $marca->user_id;
    }

    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
    }
}
