<?php

namespace App\Listeners;

use App\Estado;
use App\Events\TransaccionRealizada;
use App\Tarea;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CrearNuevaTarea
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

    public function handle(TransaccionRealizada $event)
    {
        // Si el estado posterior es el default, entonces no hay
        if($event->estado_posterior === 0){
            return;
        }

        $transaccion = $event->transaccion;

        $posterior = Estado::find($event->estado_posterior);

        if(!$asignar = $event->asignar){
            $asignar = null;
        }

        if(count($posterior->tareas()) > 0){
            if($asignar){
                $user = User::find($asignar);
            }else{
                $user = User::find($transaccion->user_id);
            }
            $user->tareas()->saveMany($posterior->tareas($event->fecha_vencimiento));
        }

        Tarea::create([
            'descripcion' => 'Creada automáticamente por el sistema.',
            'titulo' => $posterior->titulo_tarea,
            'estado_id' => $posterior->id,
            'user_id' => $event->transaccion->user_id,
            'asignado' => $asignar,
            'marca_id' => $transaccion->marca_id,
            'fecha_vencimiento' => $event->fecha_vencimiento
        ]);
    }
}
