<?php

namespace App\Listeners;

use App\Events\MarcaCreada;
use App\Notifications\MarcaCreada as MarcaCreadaNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificarAdminMarcaCreada
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
        User::where('type','admin')->first()->notify(new MarcaCreadaNotification($event->marca, $event->user_id));
    }
}
