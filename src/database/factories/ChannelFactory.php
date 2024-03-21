<?php

use Faker\Generator as Faker;
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

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'published'                 => 1,
        'name'                      => $faker->word,
        'order'                     => rand(1, 20),
        'logo'                      => null,
        'organization_url_prefix'   => null,
        'organization_url_suffix'   => null,
        'ranking_weight'            => rand(1, 5) / 10,
        'weight_activity'           => rand(1, 5) / 10,
        'weight_popularity'         => rand(1, 5) / 10,
        'weight_engagement'         => rand(1, 5) / 10,
        'post_max_fetch_age'        => 30,
        'can_collect_views_data'    => rand(0, 1),
        'can_collect_likes_data'    => rand(0, 1),
        'can_collect_comments_data' => rand(0, 1),
        'can_collect_shares_data'   => rand(0, 1),
    ];
});
