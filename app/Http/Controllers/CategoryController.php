<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Category::all()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => Category::findOrFail($id)
        ]);
    }
}