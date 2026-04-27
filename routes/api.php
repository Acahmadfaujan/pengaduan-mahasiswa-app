<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;

Route::get('/complaints', [ComplaintController::class, 'index']);
Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::put('/complaints/{id}', [ComplaintController::class, 'update']);
Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);