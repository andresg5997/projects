<?php

use App\Proceso;
use Illuminate\Database\Seeder;

class ProcesosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procesos = [
        	['nombre' => 'DirecTV', 'descripcion' => 'Instalación de servicio DirecTV'],
        	['nombre' => 'Inter', 'descripcion' => 'Instalación de servicio Inter de telefonia e internet']
        ];
        foreach ($procesos as $proceso) {
        	Proceso::create($proceso);
        }
    }
}

