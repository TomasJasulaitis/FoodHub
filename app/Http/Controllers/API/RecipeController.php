<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\RecipeResource;
use App\Recipe;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index (): AnonymousResourceCollection {
        return RecipeResource::collection(
            Recipe::with('ingredients')
                ->with('nutrients')
                ->with('user')
                ->paginate(25));
    }

    /**
     * Display the specified resource.
     *
     * @param  Recipe  $recipe
     *
     * @return RecipeResource
     */
    public function show (Recipe $recipe): RecipeResource {
        return new RecipeResource($recipe->load(['ingredients', 'nutrients', 'user']));
    }

    /**
     * @param  Request  $request
     * @param $userId
     *
     * @return AnonymousResourceCollection
     */
    public function getRecipes (Request $request, $userId): AnonymousResourceCollection {
        return RecipeResource::collection(
            Recipe::with(['ingredients', 'nutrients', 'user'])
                ->where('user_id', $userId)
                ->paginate(25)
        );
    }

    /**
     * @param  Request  $request
     * @param $userId
     * @param $recipeId
     *
     * @return RecipeResource
     */
    public function getRecipe (Request $request, $userId, $recipeId): RecipeResource {
        return new RecipeResource(
            Recipe::with(['ingredients', 'nutrients', 'user'])
                ->where('user_id', $userId)
                ->findOrFail($recipeId)
        );
    }

    /**
     * @param  Request  $request
     * @param int $userId
     *
     * @return RecipeResource
     */
    public function createRecipe (Request $request, $userId): RecipeResource {
        /**
         * @var $recipe Recipe
         */
        $recipe = User::find($userId)->recipes()->create($request->except('nutrients', 'ingredients'));

        $nutrients   = $request->input('nutrients');
        $ingredients = $request->input('ingredients');

        foreach ($nutrients as $nutrient) {
            $recipe->nutrients()->create(['name'     => $nutrient['name'],
                                          'quantity' => $nutrient['quantity'],]);
        }

        foreach ($ingredients as $ingredient) {
            $recipe->ingredients()->create(['name'     => $ingredient['name'],
                                            'quantity' => $ingredient['quantity'],]);
        }

        return new RecipeResource($recipe->load(['ingredients', 'nutrients', 'user']));
    }

    /**
     * @param  Request  $request
     * @param int $userId
     * @param int $recipeId
     *
     * @return RecipeResource
     */
    public function updateRecipe(Request $request, $userId, $recipeId): RecipeResource {
        /**
         * @var Recipe $recipe
         */
        $recipe = Recipe::with(['ingredients', 'nutrients', 'user'])
            ->where('user_id', $userId)
            ->findOrFail($recipeId)
            ->first();

        $validation = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'ingredients' => 'required',
            'preparation_time' => 'required',
            'nutrients' => 'required',
            'calories' => 'required',
            'number_of_servings' => 'required',
            'image_url' => 'required',
            'directions' => 'required',
        ]);

        $recipe->update($request->except(['nutrients', 'ingredients']));

        //Deleting previously saved nutrients and ingredients to replace them with new ones.
        $recipe->nutrients()->delete();
        $recipe->ingredients()->delete();

        $nutrients   = $request->input('nutrients');
        $ingredients = $request->input('ingredients');

        foreach ($nutrients as $nutrient) {
            $recipe->nutrients()->create(['name'      => $nutrient['name'],
                                          'recipe_id' => $recipe->id,
                                          'quantity'  => $nutrient['quantity']]);
        }

        foreach ($ingredients as $ingredient) {
            $recipe->ingredients()->create(['name'      => $ingredient['name'],
                                            'recipe_id' => $recipe->id,
                                            'quantity'  => $ingredient['quantity']]);
        }

        return new RecipeResource($recipe->load(['ingredients', 'nutrients', 'user']));
    }

    /**
     * @param  Request  $request
     * @param $userId
     * @param $recipeId
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteRecipe(Request $request, $userId, $recipeId): JsonResponse {
        $recipe = Recipe::with(['ingredients', 'nutrients', 'user'])
            ->where('user_id', $userId)
            ->findOrFail($recipeId)
            ->first();

        $recipe->delete();

        return response()->json(NULL , 204);
    }
}
