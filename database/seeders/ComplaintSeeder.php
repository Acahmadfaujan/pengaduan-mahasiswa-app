<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        Complaint::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'AC kelas rusak',
            'description' => 'AC di ruang kelas A203 tidak dingin',
            'status' => 'pending'
        ]);

        Complaint::create([
            'user_id' => 1,
            'category_id' => 2,
            'title' => 'WiFi lambat',
            'description' => 'Koneksi internet kampus sangat lambat',
            'status' => 'process'
        ]);
    }
}