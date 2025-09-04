<?php

use Illuminate\Database\Seeder;
use App\Comment;
use App\Post;

class CommentSeeder extends Seeder
{
    public function run()
    {
        Post::all()->each(function ($post) {
            factory(Comment::class, rand(2, 6))->create([
                'post_id' => $post->id
            ]);
        });
    }
}
