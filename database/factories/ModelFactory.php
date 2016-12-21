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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    $status = [
        'invited',
        'approved',
        'declined',
    ];

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'status' => $status[array_rand($status)],
        'wishlist' => $faker->sentences(5, true),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Group::class, function (Faker\Generator $faker) {

    $status = [
        'created',
        'started',
    ];

    return [
        'name' => $faker->name,
        'date' => $faker->date('Y-m-d'),
        'status' => $status[array_rand($status)],
    ];
});
