<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('users')->insert([

            [
                'id'             => 1,
                'type'           => 'user',
                'username'       => 'Guest',
                'email'          => 'user@user.com',
                'password'       => bcrypt('guest'),
                'remember_token' => '6Ziz3pR15eIYaKgoKqrpcuKldoit23cXvyfxcXh68rjBgEwvVob8WIN2GRx9',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
                'affiliate_id'   => str_random(10),
                'confirmed'       => '1',
            ],

            [
                'id'             => 2,
                'type'           => 'admin',
                'username'       => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('admin'),
                'remember_token' => '6Ziz3pR15eIYaKgoKqrpcuKldoit23cXvyfxcXh68rjBgEwvVob8WIN2GRx9',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
                'affiliate_id'   => str_random(10),
                'confirmed'       => '1',
            ],
        ]);
    }
}
