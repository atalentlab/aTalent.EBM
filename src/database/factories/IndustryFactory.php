<?php

use Faker\Generator as Faker;
use App\Models\Industry;

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

$factory->define(Industry::class, function (Faker $faker) {
    return [
        'name'      => $faker->unique()->name,
        'published' => rand(0, 1),
        'order'     => rand(0, 20)
    ];
});
