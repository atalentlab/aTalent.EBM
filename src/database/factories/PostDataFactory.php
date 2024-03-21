<?php

use Faker\Generator as Faker;
use App\Models\Post;
use App\Models\PostData;
use App\Models\Period;

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

$factory->define(PostData::class, function (Faker $faker) {
    $post = Post::get()->random();
    $period = Period::get()->random()->id;
    $channel = $post->channel;

    return [
        'post_id'       => $post->id,
        'period_id'     => $period,
        'like_count'    => $channel->can_collect_likes_data ? rand(0, 10000) : null,
        'comment_count' => $channel->can_collect_comments_data ? rand(0, 1000) : null,
        'share_count'   => $channel->can_collect_shares_data ? rand(0, 500) : null,
        'view_count'    => $channel->can_collect_views_data ? rand(0, 50000) : null,
    ];
});
