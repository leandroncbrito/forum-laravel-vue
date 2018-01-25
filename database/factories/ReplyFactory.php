<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'thread_id'=> function () {
            return factory('App\Thread')->create()->id;
        },
        'user_id'=> function () {
            return factory('App\User')->create()->id;
        }
    ];
});
