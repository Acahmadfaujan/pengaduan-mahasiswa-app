<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    // Menampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Autentikasi Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Menampilkan Halaman Register (Pendaftaran)
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES DAFTAR AKUN BARU (Langsung Mengunci Hak Akses)
    public function register(Request $request)
    {
        // 1. Validasi Input Form Register termasuk pilihan Role
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin', // Memastikan input role wajib diisi 'user' atau 'admin'
        ]);

        // 2. Simpan Akun Baru ke Database dengan Role Pilihan
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // FIX: Kolom role langsung diisi sesuai pilihan di Form Register!
        ]);

        // 3. Otomatis Login setelah berhasil mendaftar
        Auth::login($user);

        // 4. Alihkan ke Dashboard Utama
        return redirect('/dashboard');
    }

    // Proses Keluar Aplikasi (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}