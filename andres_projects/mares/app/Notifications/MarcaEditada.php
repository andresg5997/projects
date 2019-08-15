<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MarcaEditada extends Notification
{
    use Queueable;

    public $marca;
    public $fecha;

    public function __construct($marca)
    {
        $this->marca = $marca;
        $this->fecha = date('d-m-Y');
    }

    public function via($notifiable)
    {
        return ['database'];
        // return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('notificaciones.marcaeditada',
                [
                    'marca'     => $this->marca,
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
            'mensaje' => 'La marca ' . $this->marca->nombre . ' fue editada',
            'url' => '/marcas/' . $this->marca->id
        ];
    }
}
