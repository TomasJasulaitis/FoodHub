<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegistrationFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(RegistrationFormRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 0,
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return $this->respondWithToken($token);
    }
    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }
    public function logout(Request $request)
    {
        try {
            $this->validate($request, ['token' => 'required']);
        } catch (ValidationException $e) {
        }

        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
