<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::whereHas('invite_code', function ($query) use ($validated) {
            $query->where('code', $validated['invite_code']);
        })->first();

        $user->update($validated);
        $user->invite_code()->delete();

        dispatch(function () use ($user) {
            event(new Registered($user));
        });

        $token = $user->createToken('auth')->accessToken;
        return response()->json([
            'token' => $token,
        ]);
    }
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            $token = $user->createToken('auth')->accessToken;
            return response()->json([
                'token' => $token,
                'user' => $user, 
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response([], 200);
    }

    public function user(Request $request)
    {
        return new UserResource($request->user());
    }
}
