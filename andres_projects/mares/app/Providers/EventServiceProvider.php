<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\MarcaCreada' => [
            'App\Listeners\CrearTareaInicial',
            'App\Listeners\NotificarAdminMarcaCreada'
        ],
        'App\Events\TransaccionRealizada' => [
            'App\Listeners\CrearNuevaTarea',
            'App\Listeners\NotificarTransaccionRealizada'
        ],
        'App\Events\MarcaEditada' => [
            'App\Listeners\NotificarMarcaEditada'
        ],
        'App\Events\TareaAsignada' => [
            'App\Listeners\NotificarTareaAsignada'
        ],
        'App\Events\TareaAsignadaRealizada' => [
            'App\Listeners\NotificarTareaAsignadaRealizada'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
