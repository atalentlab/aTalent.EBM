<?php

use Faker\Generator as Faker;
use App\Models\Post;
use App\Models\Channel;
use App\Models\Organization;

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

$factory->define(Post::class, function (Faker $faker) {
    $channel = Channel::get()->random()->id;
    $organization = Organization::get()->random()->id;

    return [
        'channel_id'            => $channel,
        'organization_id'       => $organization,
        'post_id'               => $faker->userName,
        'posted_date'           => $faker->dateTimeBetween('-2 months', 'now'),
        'title'                 => $faker->sentence,
        'is_actively_fetching'  => 1,
        'url'                   => $faker->url,
    ];
});
