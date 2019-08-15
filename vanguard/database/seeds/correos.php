<?php

use Illuminate\Database\Seeder;

class correos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('correos')->insert([
                'id_plantilla'  => 'directv_calificar',
                'variables'      => '["cliente.nombre","cliente.telefono","cliente.direccion"]',
                'correo_destino'   => 'jesusr@gemacar.com'
            ]);
    }
}
