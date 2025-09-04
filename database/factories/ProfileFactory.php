<?php

use App\Profile;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'bio' => $faker->sentence,
        'avatar' => $faker->imageUrl(200, 200, 'people'),
    ];
});
