<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        App\User::truncate();
        App\User::create(['nombre' => 'admin', 'password' => bcrypt('admin'), 'email' => 'admin@admin.com', 'type' => 'admin']);
        App\User::create(['nombre' => 'user', 'password' => bcrypt('admin'), 'email' => 'user@admin.com', 'type' => 'member']);

        for ($i=0; $i < 20; $i++) {
            App\User::create([
                'nombre' => $faker->firstName(),
                'apellido' => $faker->lastName(),
                'email' => $faker->freeEmail(),
                'password' => bcrypt('admin')
            ]);
        }
    }
}
