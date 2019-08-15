<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VencimientoMarcas extends Notification
{
    use Queueable;

    public $porVencer;
    public $vencidas;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($porVencer, $vencidas)
    {
        $this->porVencer = $porVencer;
        $this->vencidas = $vencidas;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->view('notificaciones.vencimientoMarcas',
                [
                    'porVencer'   => $this->porVencer,
                    'vencidas' => $this->vencidas
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
            //
        ];
    }
}
