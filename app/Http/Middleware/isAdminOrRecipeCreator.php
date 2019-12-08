<?php

namespace App\Http\Middleware;

use Closure;

class isAdminOrRecipeCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $recipeUser = $request->get('user_id');

        if (auth()->user()->id === (int)$recipeUser || (int)auth()->user()->role === 2) {
            return $next($request);
        }

        return response()->json(['error' => 'You can only change your own recipes'], 403);
    }
}
