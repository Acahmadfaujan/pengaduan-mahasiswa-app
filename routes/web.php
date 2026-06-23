<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ComplaintWebController;
use App\Http\Controllers\Web\CommentWebController;
use App\Http\Controllers\Web\AuthWebController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login']);
    Route::get('/register', [AuthWebController::class, 'showRegister']);
    Route::post('/register', [AuthWebController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('complaints', ComplaintWebController::class);
    Route::resource('comments', CommentWebController::class);
    
    Route::get('/profile', function() {
        return view('profile.index');
    });

    Route::post('/logout', [AuthWebController::class, 'logout']);
});