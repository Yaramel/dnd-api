<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends BaseController
{
    use HasApiTokens, Notifiable;
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:player,master',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // public function userProfile(Request $request)
    // {
    //     $user = $request->user(); // The middleware ensures this will always be an authenticated user
    
    //     return response()->json([
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'role' => $user->role
    //     ]);
    // }
    public function userProfile(Request $request)
    {
        
        try {
            $user = $request->user();
    

            // Log the user details for debugging
            \Log::info('Authenticated user:', ['user' => $user]);
    
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error in userProfile:', ['message' => $e->getMessage()]);
    
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
