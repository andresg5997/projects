<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\DeleteOldMedia::class,
        Commands\DeleteUnconvertedMedia::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // delete old media that hasn't been viewed in x days
        $schedule->command('media:delete-old')->daily();

        // delete unconverted media to save storage
        $schedule->command('media:delete-old')->daily();

        // delete failed and stopped chunk uploads
        $schedule->command('uploads:clear')->daily();

        // upgrade youtube-dl
        if (command_exist('youtube-dl --help')) {
            $schedule->exec('youtube-dl -U')->everyMinute();
        }

        // backups the database and app files
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');

        // work the queue
        $schedule->command('queue:work')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
