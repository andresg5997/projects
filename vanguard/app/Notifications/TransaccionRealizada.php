<?php

namespace App\Notifications;

use App\Estado;
use App\Marca;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransaccionRealizada extends Notification
{
    use Queueable;

    public $transaccion;
    public $marca;
    public $estado;
    public $fecha;

    public function __construct($transaccion)
    {
        $this->transaccion = $transaccion;
        $this->marca = Marca::find($transaccion->marca_id);
        $this->estado = Estado::find($transaccion->estado_id);
        $this->fecha = date('d-m-Y');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('notificaciones.transaccionrealizada',
                [
                    'transaccion'   => $this->transaccion,
                    'marca'         => $this->marca,
                    'estado'        => $this->estado,
                    'fecha'         => $this->fecha
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'mensaje' => $this->estado->nombre . ' en ' . $this->marca->nombre,
            'url' => '/tareas/' . $this->transaccion->tarea_id
        ];
    }
}
