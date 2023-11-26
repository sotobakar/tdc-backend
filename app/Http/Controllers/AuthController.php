<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        // Verify login
        if (Auth::attempt($request->validated())) {
            $user = User::where('email', $request->validated('email'))->first();

            $token = $user->createToken("auth");

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'access_token' => $token->plainTextToken
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'Your email or password is incorrect'
            ], 400);
        }
    }
}
