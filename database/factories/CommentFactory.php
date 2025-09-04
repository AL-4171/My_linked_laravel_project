<?php

use App\Comment;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Comment::class, function (Faker $faker) {
    return [
        'post_id' => function () {
            return factory(App\Post::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'content' => $faker->sentence,
    ];
});