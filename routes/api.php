<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;

/*
|--------------------------------------------------------------------------
| API Routes - Sikeluh Mobile
|--------------------------------------------------------------------------
*/

// Public Routes (Bisa diakses langsung oleh Flutter tanpa token)
// FIX: Satukan login dan register ke AuthController sesuai file controller asli kamu
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); 

// Endpoint aduan (Public untuk memudahkan testing Flutter di IP Lokal)
Route::get('/aduan', [ComplaintController::class, 'index']);
Route::post('/aduan/store', [ComplaintController::class, 'store']);

// Protected Routes (Wajib Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // FIX: Tambahkan endpoint PUT untuk /complaints/{id} agar fungsi Edit Laporan di Flutter kamu bekerja asli
    Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
    Route::put('/complaints/{id}', [ComplaintController::class, 'update']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);

    // Routes Kategori
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Routes Komentar
    Route::get('/comments', [CommentController::class, 'index']);
    Route::get('/comments/{id}', [CommentController::class, 'show']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // Routes Lampiran
    Route::get('/attachments', [AttachmentController::class, 'index']);
    Route::get('/attachments/{id}', [AttachmentController::class, 'show']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
    Route::put('/attachments/{id}', [AttachmentController::class, 'update']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);
    
    // Proses Logout API
    Route::post('/logout', [AuthController::class, 'logout']);
});