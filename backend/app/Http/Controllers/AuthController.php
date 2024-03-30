<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:developer,HR',
            'skills' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'skills' => $request->skills,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login with email
        if (Auth::attempt(['email' => $request->identity, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User login successful',
                'token' => $token,
                'role' => $user->role,
                'name' => $user->name,
            ], 200);
        }

        // If login with email fails, attempt login with username
        if (Auth::attempt(['username' => $request->identity, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User login successful',
                'token' => $token,
                'role' => $user->role,
                'name' => $user->name,
            ], 200);
        }

        // If both attempts fail, return error response
        throw ValidationException::withMessages([
            'identity' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => true, 'message' => 'User logged out successfully']);
    }
}
