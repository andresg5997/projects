<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarcaCreada extends Notification
{
    use Queueable;

    public $marca;
    public $usuario;

    public function __construct($marca, $user_id)
    {
        $this->marca = $marca;
        $this->usuario = User::find($user_id);
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
        // return ['mail', 'database'];
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
            ->view('notificaciones.marcacreada',
                [
                    'marca'     => $this->marca,
                    'usuario'   => $this->usuario,
                    'fecha'     => $this->fecha
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
            'mensaje' => $this->usuario->fullName() . ' creó la marca ' . $this->marca->nombre . '.',
            'url' => '/marcas/' . $this->marca->id
        ];
    }
}
