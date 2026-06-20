<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Akademik']);
        Category::create(['name' => 'Fasilitas']);
        Category::create(['name' => 'Administrasi']);
    }
}