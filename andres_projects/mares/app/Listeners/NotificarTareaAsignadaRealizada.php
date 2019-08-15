<?php

namespace App\Listeners;

use App\Events\TareaAsignadaRealizada;
use App\Notifications\TareaAsignadaRealizada as TareaAsignadaRealizadaNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificarTareaAsignadaRealizada
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
     * @param  TareaAsignadaRealizada  $event
     * @return void
     */
    public function handle(TareaAsignadaRealizada $event)
    {
        User::find($event->tarea->user_id)->notify(new TareaAsignadaRealizadaNotification($event->tarea));
    }
}
