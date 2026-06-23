<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Contoh untuk Presentasi
        User::create([
            'name' => 'Faujan Achmad (Admin)',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Faujan Achmad (Mahasiswa)',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Buat Kategori Master
        Category::create(['name' => 'Fasilitas & Infrastruktur']);
        Category::create(['name' => 'Layanan Akademik']);
        Category::create(['name' => 'Lingkungan Kampus']);
    }
}