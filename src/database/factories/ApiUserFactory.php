<?php

use Faker\Generator as Faker;
use App\Models\ApiUser;
use Illuminate\Support\Str;

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

$factory->define(ApiUser::class, function (Faker $faker) {
    return [
        'activated' => 1,
        'name'      => $faker->name,
        'api_token' => Str::random(60),
    ];
});
