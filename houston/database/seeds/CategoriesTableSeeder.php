<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('categories')->insert([

            [
                'slug'       => 'funny',
                'name'       => 'Funny',
                'order'      => 1,
                'icon'       => 'fa-gift',
                'created_at' => Carbon\Carbon::now(),
            ],

            [
                'slug'       => 'movies',
                'name'       => 'Movies',
                'order'      => 2,
                'icon'       => 'fa-tv',
                'created_at' => Carbon\Carbon::now(),
            ],

            [
                'slug'       => 'music',
                'name'       => 'Music',
                'order'      => 3,
                'icon'       => 'fa-music',
                'created_at' => Carbon\Carbon::now(),
            ],

            [
                'slug'       => 'sport',
                'name'       => 'Sport',
                'order'      => 4,
                'icon'       => 'fa-soccer-ball-o',
                'created_at' => Carbon\Carbon::now(),
            ],

            [
                'slug'       => 'other',
                'name'       => 'Other',
                'order'      => 5,
                'icon'       => 'fa-archive',
                'created_at' => Carbon\Carbon::now(),
            ],

        ]);
    }
}
