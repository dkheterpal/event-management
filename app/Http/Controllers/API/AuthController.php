<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate( [
        'email' => 'required|email',
        'password'=> 'required'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect']);
        }

        if(!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email'=> 'The provided credentials are incorrect']);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'=> $token
            ]
        );
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'messaged' => 'Logged out successfully!'
        ]);
    }
}
