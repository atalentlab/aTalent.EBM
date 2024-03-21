<?php

use Faker\Generator as Faker;
use App\Models\Organization;
use App\Models\OrganizationData;
use App\Models\Period;
use App\Models\Channel;

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

$factory->define(OrganizationData::class, function (Faker $faker) {
    $organization = Organization::get()->random()->id;
    $period = Period::get()->random()->id;
    $channel = Channel::get()->random()->id;

    return [
        'organization_id'   => $organization,
        'period_id'         => $period,
        'channel_id'        => $channel,
        'follower_count'    => rand(0, 10000000),
    ];
});
