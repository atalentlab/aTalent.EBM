<?php

use App\Models\Channel;

class ChannelsTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        factory(Channel::class)->create([
            'published'                 => 1,
            'name'                      => 'LinkedIn',
            'order'                     => 1,
            'logo'                      => null,
            'ranking_weight'            => 50,
            'weight_activity'           => 40,
            'weight_popularity'         => 30,
            'weight_engagement'         => 30,
            'post_max_fetch_age'        => 30,
            'organization_url_prefix'   => 'https://www.linkedin.com/company/',
            'organization_url_suffix'   => null,
            'can_collect_views_data'    => 0,
            'can_collect_likes_data'    => 1,
            'can_collect_comments_data' => 1,
            'can_collect_shares_data'   => 0,
        ]);

        factory(Channel::class)->create([
            'published'                 => 1,
            'name'                      => 'WeChat',
            'order'                     => 2,
            'logo'                      => null,
            'ranking_weight'            => 70,
            'weight_activity'           => 40,
            'weight_popularity'         => 30,
            'weight_engagement'         => 30,
            'post_max_fetch_age'        => 30,
            'organization_url_prefix'   => null,
            'organization_url_suffix'   => null,
            'can_collect_views_data'    => 1,
            'can_collect_likes_data'    => 1,
            'can_collect_comments_data' => 0,
            'can_collect_shares_data'   => 0,
        ]);

        factory(Channel::class)->create([
            'published'                 => 1,
            'name'                      => 'Weibo',
            'order'                     => 3,
            'logo'                      => null,
            'ranking_weight'            => 40,
            'weight_activity'           => 40,
            'weight_popularity'         => 30,
            'weight_engagement'         => 30,
            'post_max_fetch_age'        => 30,
            'organization_url_prefix'   => 'https://weibo.com/p/',
            'organization_url_suffix'   => null,
            'can_collect_views_data'    => 1,
            'can_collect_likes_data'    => 1,
            'can_collect_comments_data' => 1,
            'can_collect_shares_data'   => 0,
        ]);

        factory(Channel::class)->create([
            'published'                 => 0,
            'name'                      => 'Kanzhun',
            'order'                     => 4,
            'logo'                      => null,
            'ranking_weight'            => 35,
            'weight_activity'           => 40,
            'weight_popularity'         => 30,
            'weight_engagement'         => 30,
            'post_max_fetch_age'        => 30,
            'organization_url_prefix'   => 'https://www.kanzhun.com/',
            'organization_url_suffix'   => '.html',
            'can_collect_views_data'    => 0,
            'can_collect_likes_data'    => 1,
            'can_collect_comments_data' => 1,
            'can_collect_shares_data'   => 0,
        ]);
    }

    private function clearData()
    {
        $channels = Channel::all();

        foreach ($channels as $channel) {
            $channel->delete();
        }
    }
}
