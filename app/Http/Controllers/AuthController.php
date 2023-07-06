<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try
        {
            if (!Auth::attempt($request->only(['email','password'])))
            {
                return response()->json([
                    'message' => 'The provided credentials are invalid.'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->status)
            {
                $token = $user->createToken('authToken')->plainTextToken;
            }
            else
            {
                return response()->json([
                    'message' => 'Account suspended, please contact your admin.'
                ], 401);
            }

            return response()->json([
                'message' => 'Logged in.',
                'user' => $user,
                'role' => $user->roles->first()->display_name,
                'access_token' => $token
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(): JsonResponse
    {
        try
        {
            Auth::user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Logged out.'
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
