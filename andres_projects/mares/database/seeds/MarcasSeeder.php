<?php

use App\Archivo;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        App\Marca::truncate();
        App\Tarea::truncate();
        App\Transaccion::truncate();

        $marcas = [
            ['nombre' => 'AMOVENCA', 'signo_distintivo' => 'amovenca.jpg', 'user_id' => 1],
            ['nombre' => 'GemaCar', 'signo_distintivo' => 'gemacar.png', 'user_id' => 1],
            ['nombre' => 'ASECA', 'signo_distintivo' => 'aseca.png', 'user_id' => 1],
            ['nombre' => 'Torta Lista', 'signo_distintivo' => 'tortalista.png', 'user_id' => 1],
            ['nombre' => 'Tu Visión Contable', 'signo_distintivo' => 'tuvisioncontable.png', 'user_id' => 1]
        ];
        foreach($marcas as $marca){
            $mark = App\Marca::create($marca);

            for ($i=0; $i < mt_rand(1, 60); $i++) {
                $datos = [
                    ['requisito' => $faker->word, 'tipo' => 'text', 'valor' => $faker->paragraph(2)]
                ];
                if(mt_rand(0,1) === 1){
                    $filename = str_random() . '.' . $faker->fileExtension;
                    $requisito = ucfirst(implode(' ', $faker->words(mt_rand(1,3))));

                    $datos[] = ['requisito' => $requisito, 'tipo' => 'file', 'valor' => $filename];
                    $archivo = Archivo::create([
                        'titulo' => $requisito,
                        'nombre_archivo' => $filename,
                        'tarea_id' => (mt_rand(0,1) === 1) ? mt_rand(1, 75) : null ,
                        'marca_id' => $mark->id,
                        'user_id' => mt_rand(1,21)
                    ]);
                }
                
                $transaccion = new App\Transaccion([
                    'user_id' => (isset($archivo)) ? $archivo->user_id : mt_rand(1,22),
                    'estado_id' => mt_rand(1,5),
                    'tarea_id' => mt_rand(1,15),
                    'datos' => json_encode($datos),
                    'fecha' => date('Y-m-d', strtotime('-' . mt_rand(1,12) . ' weeks'))
                ]);
                $mark->transacciones()->save($transaccion);
                unset($archivo);
            }

            for ($i=0; $i < 15; $i++) {
                App\Tarea::create([
                    'titulo' => $faker->sentence(),
                    'descripcion' => $faker->paragraph(mt_rand(1,3)),
                    'status' => (string)mt_rand(0,1),
                    'user_id' => mt_rand(1,21),
                    'estado_id' => mt_rand(1,22),
                    'asignado' => mt_rand(1,22),
                    'marca_id' => $mark->id,
                    'fecha_vencimiento' => date('Y-m-d', strtotime('+' . mt_rand(0,12) . ' days'))
                ]);
            }
        }
    }
}
