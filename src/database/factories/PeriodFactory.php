<?php

use Faker\Generator as Faker;
use App\Models\Period;
use Carbon\Carbon;

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


$factory->define(Period::class, function (Faker $faker) {
    $startDate = Carbon::instance($faker->dateTimeBetween('-3 months', '-2 weeks'))->startOfWeek();

    return [
        'published'     => 1,
        'name'          => 'Week ' . $startDate->weekOfYear,
        'start_date'    => $startDate,
        'end_date'      => $startDate->copy()->endOfWeek(),
    ];
});
