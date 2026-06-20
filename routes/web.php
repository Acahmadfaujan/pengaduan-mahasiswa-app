<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ComplaintWebController;
use App\Http\Controllers\Web\CommentWebController;
use App\Http\Controllers\Web\AttachmentWebController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::resource('complaints', ComplaintWebController::class);
Route::get('/complaints', [ComplaintWebController::class, 'index']);

Route::resource('comments', CommentWebController::class);

Route::resource('attachments', AttachmentWebController::class);

Route::get('/tes', function () {
    return 'TES BERHASIL';
});