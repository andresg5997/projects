<?php

namespace App\Events;

use App\Transaccion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransaccionRealizada
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaccion;
    public $estado_posterior = 0;
    public $asignado;
    public $fecha_vencimiento;

    public function __construct(Transaccion $transaccion, $estado_posterior, $asignar, $fecha_vencimiento)
    {
        $this->transaccion = $transaccion;
        $this->fecha_vencimiento = $fecha_vencimiento;
        $this->estado_posterior = $estado_posterior;
        if($asignar === 0){
            $this->asignar = null;
        }else{
            $this->asignar = $asignar;
        }
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
