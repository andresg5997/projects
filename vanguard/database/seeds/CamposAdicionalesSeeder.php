<?php

use Illuminate\Database\Seeder;

class CamposAdicionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campos = [
            ['cliente_id' => -1, 'categoria' => 'telefono', 'nombre' => 'Teléfono 2'],
            ['cliente_id' => -1, 'categoria' => 'telefono', 'nombre' => 'Teléfono 3'],
            ['cliente_id' => -1, 'categoria' => 'otro', 'nombre' => 'Vendedor'],
            ['cliente_id' => -1, 'categoria' => 'otro', 'nombre' => 'Idioma']
        ];
        foreach ($campos as $campo) {
            DB::table('datos_adicionales')->insert([
                'categoria'  => $campo['categoria'],
                'nombre'      => $campo['nombre'],
                'cliente_id'   => $campo['cliente_id']
            ]);
        }
    }
}
