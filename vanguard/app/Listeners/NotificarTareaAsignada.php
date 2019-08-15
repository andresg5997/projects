<?php

namespace App\Listeners;

use App\Events\TareaAsignada;
use App\Notifications\TareaAsignada as TareaAsignadaNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificarTareaAsignada
{
    public function __construct()
    {

    }

    public function handle(TareaAsignada $event)
    {
        $event->tarea->asignadoA->notify(new TareaAsignadaNotification($event->tarea));
    }
}
