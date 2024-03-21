<?php

use App\Models\PostData;
use App\Models\Period;
use App\Models\Post;

class PostDataTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        if ($this->config['post_data']) {
            $periods = Period::all();
            $posts = Post::all();

            foreach ($periods as $period) {
                foreach ($posts as $post) {
                    // 1 chance out of 20 that a post has no data
                    if (rand(1, 20) !== 1) {
                        factory(PostData::class)->create([
                            'period_id'     => $period->id,
                            'post_id'       => $post->id,
                            'like_count'    => $post->channel->can_collect_likes_data ? rand(0, 10000) : null,
                            'comment_count' => $post->channel->can_collect_comments_data ? rand(0, 1000) : null,
                            'share_count'   => $post->channel->can_collect_shares_data ? rand(0, 500) : null,
                            'view_count'    => $post->channel->can_collect_views_data ? rand(0, 50000) : null,
                        ]);
                    }
                }
            }
        }
    }

    private function clearData()
    {
        $postData = PostData::all();

        foreach ($postData as $pd) {
            $pd->delete();
        }
    }
}
