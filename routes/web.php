<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShowtimeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    ob_start();
    include base_path('index.php');

    return response(ob_get_clean());
});

Route::get('/login.php', function () {
    return redirect()->route('login');
});

Route::get('/dashboard.php', function () {
    return redirect()->route('showtimes.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('username.session')->group(function () {
    Route::resource('showtimes', ShowtimeController::class)->except(['show']);
});
