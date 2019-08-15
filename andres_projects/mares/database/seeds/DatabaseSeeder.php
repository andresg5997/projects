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
        $this->call(UsuariosSeeder::class);
        $this->call(MarcasSeeder::class);
        $this->call(EstadosSeeder::class);
        $this->call(SubtareasSeeder::class);
    }
}
