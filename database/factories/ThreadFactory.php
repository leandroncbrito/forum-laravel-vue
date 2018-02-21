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

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'body' => $faker->paragraph,
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'user_id'=> function () {
            return factory('App\User')->create()->id;
        },
        'visits' => 0,
        'slug' => str_slug($title),
        'locked' => false
    ];
});
