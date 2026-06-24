<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token]);
}

public function login(Request $request)
{
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token]);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
}

public function profile(Request $request)
{
    return response()->json($request->user());
}
public function updateProfile(Request $request)
{
    $user = $request->user();
    $user->update($request->only('name', 'email'));
    return response()->json($user);
}

public function changePassword(Request $request)
{
    $request->validate(['old_password' => 'required', 'new_password' => 'required|min:6']);
    $user = $request->user();

    if (!Hash::check($request->old_password, $user->password)) {
        return response()->json(['message' => 'Old password incorrect'], 400);
    }

    $user->update(['password' => Hash::make($request->new_password)]);
    return response()->json(['message' => 'Password changed']);
}
}
