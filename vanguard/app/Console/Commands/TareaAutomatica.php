<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TareaAutomatica extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tarea:automatica';
    protected $description = 'Revisar si hay tareas automaticas para enviar hoy';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->comment('Envio de tareas automatizadas de hoy en proceso.');
        $tareas = DB::table('tarea_automatica')->get();
        foreach ($tareas as $tarea) {
            $exploded = explode('.', $tarea->plantilla);
            $plantilla = $exploded[0];
            $hoy = date('Y-n-j');
            if(($tarea->fecha_envio == $hoy) && ($plantilla == 'correos') && ($tarea->enviado == '0')) {
                $this->comment('Se encontró un mensaje para enviar hoy, enviando...');
                $data = json_decode($tarea->datos);
                $data = (array)$data;
                $data = ['data' => $data];
                $datos_envio['email'] = $tarea->destino;
                Mail::send($tarea->plantilla, $data, function($m) use ($datos_envio) {
                    $m->to($datos_envio['email']);
                    $m->subject('Tarea automatizada');
                    $m->from('info@networklatino.us');
                });
                DB::table('tarea_automatica')->where('id', $tarea->id)->update(['enviado' => '1']);
            }
        }
    }
}
