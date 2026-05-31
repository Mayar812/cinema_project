<?php

use App\Http\Controllers\Api\MovieApiController;
use Illuminate\Support\Facades\Route;

Route::get('/movies/search', [MovieApiController::class, 'search']);
Route::get('/movies/{imdbId}', [MovieApiController::class, 'show'])
    ->where('imdbId', 'tt[0-9]+');
