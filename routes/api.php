<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', static function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','namespace' => 'API'], static function(){
    Route::apiResource('users', 'UserController');
    Route::apiResource('recipes', 'RecipeController');

    Route::group(['prefix' => 'users'], static function(){

        Route::get('/{id}/recipes' ,['uses' => 'RecipeController@getRecipesByUser',
                                     'as'   => 'users.recipes',]);

        Route::get('/{id}/recipes/{recipe_id}', ['uses' => 'RecipeController@getRecipeByUser',
                                                 'as'   => 'users.recipe',]);

        Route::post('/{id}/recipes', ['uses' => 'RecipeController@createRecipeByUser',
                                      'as'   => 'users.createRecipe',]);
    });

});
