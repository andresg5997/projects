<?php

use Illuminate\Database\Seeder;

class ChatterCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('chatter_categories')->insert([

            [
                'order'      => '1',
                'name'       => 'News',
                'slug'       => 'news',
                'color'      => '#001f3f',
                'created_at' => \Carbon\Carbon::now(),
            ],

            [
                'order'      => '2',
                'name'       => 'General',
                'slug'       => 'general',
                'color'      => '#0074D9',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '3',
                'name'       => 'Support',
                'slug'       => 'support',
                'color'      => '#7FDBFF',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '4',
                'name'       => 'Feedback',
                'slug'       => 'feedback',
                'color'      => '#39CCCC',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '5',
                'name'       => 'Affiliate',
                'slug'       => 'affiliate',
                'color'      => '#3D9970',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '6',
                'name'       => 'Funny',
                'slug'       => 'funny',
                'color'      => '#2ECC40',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '7',
                'name'       => 'Movies',
                'slug'       => 'movies',
                'color'      => '#01FF70',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '8',
                'name'       => 'Music',
                'slug'       => 'music',
                'color'      => '#FFDC00',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '9',
                'name'       => 'Sport',
                'slug'       => 'sport',
                'color'      => '#FF851B',
                'created_at' => \Carbon\Carbon::now(),

            ],

            [
                'order'      => '10',
                'name'       => 'Other',
                'slug'       => 'other',
                'color'      => '#FF4136',
                'created_at' => \Carbon\Carbon::now(),
            ],

        ]);
    }
}
