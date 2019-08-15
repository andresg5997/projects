<?php

namespace App\Listeners;

use App\Tarea;
use App\Events\ProcesoIniciado;
use App\Events\TransaccionRealizada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CrearTareaInicial
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProcesoIniciado  $event
     * @return void
     */
    public function handle(ProcesoIniciado $event)
    {
        Tarea::create([
            'descripcion' => 'Creada automáticamente por el sistema.',
            'titulo' => $event->estado->titulo_tarea,
            'estado_id' => $event->estado->id,
            'user_id' => $event->marca->user_id,
            'marca_id' => $event->marca->id,
            'fecha_vencimiento' => date('Y-m-d', strtotime('+' . $event->estado->tiempo_seguimiento . ' days'))
        ]);
    }
}
