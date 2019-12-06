<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\RecipeResource;
use App\Recipe;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index () {
        return RecipeResource::collection(
            Recipe::with('ingredients')
                ->with('nutrients')
                ->with('user')
                ->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return RecipeResource
     */
    public function store (Request $request) {
        /**
         * @var Recipe $recipe
         */
        $recipe = Recipe::create($request->all());
        return new RecipeResource($recipe);
    }

    /**
     * Display the specified resource.
     *
     * @param  Recipe  $recipe
     *
     * @return RecipeResource
     */
    public function show (Recipe $recipe) {
        return new RecipeResource($recipe->load(['ingredients', 'nutrients', 'user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Recipe  $recipe
     *
     * @return RecipeResource
     */
        public function update(Request $request, Recipe $recipe) {
            // check if currently authenticated user is the owner of the book
            if ($request->user()->id !== $recipe->user_id) {
                return response()->json(['error' => 'You can only edit your own books.'], 403);
            }

            $recipe->update($request->only(['name', 'description']));

            return new RecipeResource($recipe);
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Recipe  $recipe
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy (Recipe $recipe) {

        $recipe->delete();

        return response()->json(null, 204);
    }

    /**
     * @param  Request  $request
     * @param $id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRecipesByUser (Request $request, $id) {
        return RecipeResource::collection(
            Recipe::with(['ingredients', 'nutrients', 'user'])
                ->where('user_id', $id)
                ->paginate(25)
        );
    }

    /**
     * @param  Request  $request
     * @param $id
     * @param $recipeId
     *
     * @return RecipeResource
     */
    public function getRecipeByUser (Request $request, $id, $recipeId) {
        return new RecipeResource(
            Recipe::with(['ingredients', 'nutrients', 'user'])
                ->where('user_id', $id)
                ->find($recipeId)
        );
    }

    /**
     * @param  Request  $request
     * @param int $id
     *
     * @return RecipeResource
     */
    public function createRecipeByUser (Request $request, $id) {
        /**
         * @var $recipe Recipe
         */
        $recipe = User::find($id)->recipes()->create($request->except('nutrients', 'ingredients'));

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
}
