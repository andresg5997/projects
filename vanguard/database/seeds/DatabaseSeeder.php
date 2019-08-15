<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CamposAdicionalesSeeder::class);
        // $this->call(UsuariosSeeder::class);
        // $this->call(MarcasSeeder::class);
        // $this->call(EstadosSeeder::class);
        // $this->call(SubtareasSeeder::class);
        // $this->call(ProcesosSeeder::class);
        $this->call(correos::class);
    }
}
