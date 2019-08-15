<?php

namespace App\Console\Commands;

use App\User;
use App\Marca;
use Illuminate\Console\Command;
use App\Notifications\VencimientoMarcas;

class AdvertirVencimiento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advertir:vencimiento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $porVencer = Marca::whereDate('fecha_vencimiento', '<=', date('Y-m-d', strtotime('+6 months')))
        ->whereDate('fecha_vencimiento', '>', date('Y-m-d'))
        ->get();
        $vencidas = Marca::whereDate('fecha_vencimiento', '<', date('Y-m-d'))->get();

        $users = User::all();

        foreach($users as $user){
            $user->notify(new VencimientoMarcas($porVencer, $vencidas));
        }
    }
}
