<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'unique'         => $faker->userName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Media::class, function (Faker\Generator $faker) {
    $type = ['video', 'picture'];

    return [
        'category_id' => rand(1, 2),
        'user_id'     => rand(1, 2),
        'views'       => 0,
        'title'       => $as = $faker->jobTitle,
        'slug'        => make_slug($as),
        'type'        => $type[rand(0, 1)],
        'media_links' => $faker->paragraph,
    ];
});

$factory->define(App\Setting::class, function (Faker\Generator $faker) {
});
