<?php

use Faker\Generator as Faker;
use App\Models\Organization;
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

$factory->define(Organization::class, function (Faker $faker) {
    $industry = Industry::get()->random();

    return [
        'published'             => rand(1, 5) !== 1,
        'is_fetching'           => rand(1, 5) !== 1,
        'name'                  => $faker->company,
        'logo'                  => null,
        'industry_id'           => $industry ? $industry->id : null,
        'intro'                 => null,
        'website'               => $faker->url,
        'contact_name'          => $faker->name,
        'contact_email'         => $faker->safeEmail,
        'contact_title'         => $faker->jobTitle,
    ];
});
