<?php

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->unique()->word;
    return [
        'name' => ucfirst($name),
        'slug' => Str::slug($name),
    ];
});
