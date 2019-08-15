<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\NotificacionDiaria;
use App\Console\Commands\AdvertirVencimiento;
use App\Console\Commands\TareaAutomatica;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        NotificacionDiaria::class,
        AdvertirVencimiento::class,
        TareaAutomatica::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tarea:automatica')->everyMinute();

        // $schedule->command('notificacion:diaria')
        //     ->weekdays()
        //     ->daily()
        //     ->at('7:00');

        // $schedule->command('advertir:vencimiento')
        //     ->weekdays()
        //     ->daily()
        //     ->at('7:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
