<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Recipe;
use Faker\Generator as Faker;

$factory->define(Recipe::class, function (Faker $faker) {
    $userId = App\User::all()->pluck('id')->toArray();

    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'user_id' => $faker->randomElement($userId),
        'preparation_time' => $faker->numberBetween(1, 60),
        'number_of_servings' => $faker->numberBetween(1, 10),
        'calories' => $faker->numberBetween(500, 3000),
        'image_url' => $faker->url,
        'directions' => $faker->paragraph,
    ];
});
