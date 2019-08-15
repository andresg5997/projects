<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TareaAsignadaRealizada extends Notification
{
    use Queueable;

    public $tarea;
    public $fecha;

    public function __construct($tarea)
    {
        $this->tarea = $tarea;
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
            ->view('notificaciones.tareaasignadarealizada',
                [
                    'fecha'     => $this->fecha,
                    'tarea'     => $this->tarea
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
            'mensaje' => $this->tarea->usuario->fullName() . ' terminó la tarea ' . $this->tarea->titulo,
            'url' => '/tareas/' . $this->tarea->id
        ];
    }
}
