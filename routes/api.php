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

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'jwt.auth'], static function() {
        Route::get('logout', 'AuthController@logout');

        Route::apiResource('users', 'UserController')->only('show', 'update')->middleware(['isAdminOrSelf']);
        Route::apiResource('users', 'UserController')->only('destroy', 'index', 'store')->middleware(['isAdmin']);
        Route::apiResource('recipes', 'RecipeController')->only('show', 'index');


        Route::group(['prefix' => 'users'], static function(){

            Route::get('/{id}/recipes' ,['uses' => 'RecipeController@getRecipes',
                                         'as'   => 'users.recipes',]);

            Route::post('/{id}/recipes', ['uses' => 'RecipeController@createRecipe',
                                          'as'   => 'users.createRecipe',]);

            Route::get('/{id}/recipes/{recipe_id}', ['uses' => 'RecipeController@getRecipe',
                                                     'as'   => 'users.recipe',]);

            Route::patch('/{id}/recipes/{recipe_id}', ['uses' => 'RecipeController@updateRecipe',
                                                       'as'   => 'users.updateRecipe',]);

            Route::delete('/{id}/recipes/{recipe_id}', ['uses' => 'RecipeController@deleteRecipe',
                                                        'as'   => 'users.deleteRecipe',]);
        });
    });
});
