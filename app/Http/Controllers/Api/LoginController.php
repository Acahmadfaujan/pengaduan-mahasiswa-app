<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // FIX: Memastikan namespace Model didefinisikan dengan jelas agar VS Code tidak bingung
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input terpisah untuk memaksa Intelephense VS Code menghilangkan warning
        $rules = [
            'email'    => 'required',
            'password' => 'required',
        ];

        $request->validate($rules);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cocokkan password menggunakan Hash::check standar Laravel
        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'user'    => [
                    'id'    => $user->id,
                    'email' => $user->email,
                    'role'  => $user->role ?? 'user',
                    'name'  => $user->name ?? 'User'
                ]
            ], 200);
        }

        // Jika data tidak cocok atau password salah
        return response()->json([
            'success' => false, 
            'message' => 'Email atau Password salah'
        ], 401);
    }
}