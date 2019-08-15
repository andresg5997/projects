<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Notifications\DashboardNotificacion;

class NotificacionDiaria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:diaria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía notificación con los datos del dashboard.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $usuarios = User::all();

        foreach($usuarios as $usuario){
            $usuario->notify(new DashboardNotificacion());
        }
    }
}
