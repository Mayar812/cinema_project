<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request, Movie $movie): View
    {
        return view('User.booking', [
            'movie' => $movie,
            'username' => $request->session()->get('username'),
        ]);
    }
}
