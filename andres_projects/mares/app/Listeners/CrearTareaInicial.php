<?php

namespace App\Listeners;

use App\Tarea;
use App\Estado;
use App\Transaccion;
use App\Events\MarcaCreada;
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
     * @param  MarcaCreada  $event
     * @return void
     */
    public function handle(MarcaCreada $event)
    {
        $estado = Estado::find(1);

        Tarea::create([
            'descripcion' => 'Creada automáticamente por el sistema.',
            'titulo' => $estado->titulo_tarea,
            'estado_id' => $estado->id,
            'user_id' => $event->marca->user_id,
            'marca_id' => $event->marca->id,
            'fecha_vencimiento' => date('Y-m-d', strtotime('+2 Weeks'))
        ]);
    }
}
