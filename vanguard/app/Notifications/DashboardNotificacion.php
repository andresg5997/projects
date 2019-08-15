<?php

namespace App\Notifications;

use App\Marca;
use Illuminate\Bus\Queueable;
use App\Http\Controllers\HomeController;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DashboardNotificacion extends Notification
{
    use Queueable;

    public $dias_negadas = 15;
    public $dias_publicacion = 30;
    public $dias_concedidas = 30;
    public $dias_devueltas = 30;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $marcas_negadas = $this->getMarcas($this->dias_negadas, 'Oferta rechazada');
        $marcas_publicacion = $this->getMarcas($this->dias_publicacion, 'Publicación en prensa');
        $marcas_concedidas = $this->getMarcas($this->dias_concedidas, 'Marca concedida');
        $marcas_devueltas = $this->getMarcas($this->dias_devueltas, 'Subsanar la solicitud');

        $dias = [
            'dias_negadas' => $this->dias_negadas,
            'dias_publicacion' => $this->dias_publicacion,
            'dias_concedidas' => $this->dias_concedidas,
            'dias_devueltas' => $this->dias_devueltas
        ];
        return (new MailMessage)
                    ->view('notificaciones.dashboard',
                            compact('marcas_negadas', 'marcas_publicacion', 'marcas_concedidas', 'marcas_devueltas', 'dias'))
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

    public function getMarcas($dias, $nombre_estado)
    {
        $this->nombre_estado = $nombre_estado;
        $marcas = Marca::whereDate('updated_at', '>', date('Y-m-d', strtotime("-$dias days")))->get();
        foreach($marcas as $marca){
            $marca->estado();
        }
        return $marcas->filter(function($value, $key){
            return $value->estado === $this->nombre_estado;
        });
    }
}
