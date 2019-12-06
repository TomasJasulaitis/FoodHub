<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ingredient;
use Faker\Generator as Faker;

$factory->define(Ingredient::class, function (Faker $faker) {
    $recipeId = App\Recipe::all()->pluck('id')->toArray();

    return [
        'name' => $faker->colorName,
        'quantity' => $faker->randomFloat(false, 1, 10).' '.$faker->word,
        'recipe_id' => $faker->randomElement($recipeId)
    ];
});
