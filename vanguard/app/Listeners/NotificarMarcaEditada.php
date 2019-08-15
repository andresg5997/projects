<?php

namespace App\Listeners;

use App\Events\MarcaEditada;
use App\Notifications\MarcaEditada as MarcaEditadaNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificarMarcaEditada
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
     * @param  MarcaEditada  $event
     * @return void
     */
    public function handle(MarcaEditada $event)
    {
        User::find($event->marca->user_id)->notify(new MarcaEditadaNotification($event->marca));
    }
}
