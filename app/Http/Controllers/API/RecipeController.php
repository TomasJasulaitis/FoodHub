<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RecipeFormRequest;
use App\Http\Resources\RecipeResource;
use App\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index (): AnonymousResourceCollection {
        return RecipeResource::collection(Recipe::paginate(25));
    }

    /**
     * Display the specified resource.
     *
     * @param  Recipe  $recipe
     *
     * @return RecipeResource
     */
    public function show (Recipe $recipe): RecipeResource {
        return new RecipeResource($recipe);
    }

    /**
     * @param $userId
     *
     * @return AnonymousResourceCollection
     */
    public function getUserRecipes ($userId): AnonymousResourceCollection {
        return RecipeResource::collection(Recipe::where('user_id', $userId)->paginate(25));
    }

    /**
     * @param  RecipeFormRequest  $request
     *
     * @return RecipeResource
     */
    public function store (RecipeFormRequest $request): RecipeResource {
        /**
         * @var $recipe Recipe
         */
        $recipe = auth()->user()->recipes()->create($request->except('nutrients', 'ingredients'));

        $recipe->nutrients()->createMany($request->input('nutrients'));
        $recipe->ingredients()->createMany($request->input('ingredients'));

        return new RecipeResource($recipe->load(['user', 'ingredients', 'nutrients']));
    }

    /**
     * @param  RecipeFormRequest  $request
     * @param  int  $recipeId
     *
     * @return RecipeResource
     */
    public function update (RecipeFormRequest $request, $recipeId): RecipeResource {
        /**
         * @var Recipe $recipe
         */
        $recipe = auth()->user()->recipes()->findOrFail($recipeId)->first();

        $recipe->update($request->except(['nutrients', 'ingredients']));

        //Deleting previously saved nutrients and ingredients to replace them with new ones.
        $recipe->nutrients()->delete();
        $recipe->ingredients()->delete();

        $recipe->nutrients()->createMany($request->input('nutrients'));
        $recipe->ingredients()->createMany($request->input('ingredients'));

        return new RecipeResource($recipe);
    }

    /**
     * @param  Request  $request
     * @param $recipeId
     *
     * @return JsonResponse
     */
    public function destroy (Request $request, $recipeId): JsonResponse {
        $recipe = auth()->user()->recipes()->findOrFail($recipeId)->first();

        $recipe->delete();

        return response()->json(NULL , 204);
    }
}
