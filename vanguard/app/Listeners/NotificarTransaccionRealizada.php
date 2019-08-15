<?php

namespace App\Listeners;

use App\Events\TransaccionRealizada;
use App\Marca;
use App\Notifications\TransaccionRealizada as TransaccionRealizadaNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificarTransaccionRealizada
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
	 * @param  TransaccionRealizada  $event
	 * @return void
	 */
	public function handle(TransaccionRealizada $event)
	{
		User::find(Marca::find($event->transaccion->marca_id)->user_id)->notify(new TransaccionRealizadaNotification($event->transaccion));
	}
}
