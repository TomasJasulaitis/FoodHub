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
Route::group(['prefix' => 'v1', 'namespace' => 'API'], static function () {

    Route::apiResource('recipes', 'RecipeController')->only('show', 'index');
    Route::post('register', ['uses' => 'AuthController@register',
                                  'as'   => 'register',]);
    Route::post('login', ['uses' => 'AuthController@login',
                               'as'   => 'login',]);

    Route::group(['middleware' => 'jwt.auth'], static function () {
        Route::group(['middleware' => 'isAdmin'], static function () {
            Route::apiResource('users', 'UserController')->only('destroy', 'index', 'store');
        });

        Route::apiResource('users', 'UserController')->only('show', 'update')->middleware(['isAdminOrSelf']);
        Route::apiResource('recipes', 'RecipeController')->only('destroy', 'update')->middleware(['isAdminOrRecipeCreator']);
        Route::apiResource('recipes', 'RecipeController')->only('store');

        Route::get('users/{user_id}/recipes', ['uses' => 'RecipeController@getUserRecipes',
                                               'as'   => 'users.recipes',]);

        Route::post('logout', ['uses' => 'AuthController@logout',
                                    'as'   => 'logout',]);
    });
});
