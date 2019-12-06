<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Nutrient;
use Faker\Generator as Faker;

$factory->define(Nutrient::class, function (Faker $faker) {
    $recipeId = App\Recipe::all()->pluck('id')->toArray();

    return [
        'name' => $faker->colorName,
        'quantity' => $faker->randomFloat(false, 1, 100).' '.$faker->word.' '.$faker->city,
        'recipe_id' => $faker->randomElement($recipeId)
    ];
});
