<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = User::where('username', $request->session()->get('username'))->first();

        // The hero uses only movies explicitly marked as Coming Soon in the seeded database.
        $comingSoonMovies = Movie::query()
            ->comingSoon()
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->limit(6)
            ->get();

        // Bookable movies are currently showing and still have seats available.
        $availableMovies = Movie::query()
            ->availableForBooking()
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->get();

        return view('User.home', [
            'comingSoonMovies' => $comingSoonMovies,
            'availableMovies' => $availableMovies,
            'userBookings' => $user
                ? Booking::query()
                    ->with('showtime')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get()
                : collect(),
            'username' => $request->session()->get('username'),
        ]);
    }
}
