<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
    // keluarkan dari middleware
Route::put('/complaints/{id}', [ComplaintController::class, 'update']);
});