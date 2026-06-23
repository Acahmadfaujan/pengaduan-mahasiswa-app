<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Register berhasil',
            'data'    => $user
        ], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

       $user = User::query()->where('email', $request->input('email'))->first();

        // Membuat token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role ?? 'user',
                'name'  => $user->name ?? 'User'
            ]
        ], 200);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        if ($user) {
            $user->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'status'  => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}