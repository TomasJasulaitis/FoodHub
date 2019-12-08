<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;
use App\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index (): AnonymousResourceCollection {
        return UserResource::collection(User::with(['recipes'])->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return UserResource
     */
    public function store (Request $request): UserResource {
        $user = User::create($request->all());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     *
     * @return UserResource
     */
    public function show (User $user): UserResource {
        return new UserResource($user->load('recipes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     *
     * @return UserResource
     */
    public function update (Request $request, User $user): UserResource {
        $user->update($request->only(['email']));
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy (User $user): \Illuminate\Http\JsonResponse {
        $user->delete();

        return response()->json(['message' => 'successfully deleted user'], 204);
    }
}
