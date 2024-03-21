<?php

use App\Models\Post;

class PostsTableSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->clearData();

        if ($this->config['posts'] > 0) {
            factory(Post::class, $this->config['posts'])->create();
        }
    }

    private function clearData()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $post->delete();
        }
    }
}
