<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShowtimeController extends Controller
{
    // Display the dashboard with showtime records, search, statistics, and pagination.
    public function index(Request $request): View
    {
        // Read the search text from the URL query string.
        $search = $request->string('search')->toString();

        // Build the showtime query and apply search only when the user typed something.
        $showtimes = Showtime::query()
            ->when($search, function ($query) use ($search) {
                // Search by movie title, genre, hall number, or show date.
                $query->where('movie_title', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%")
                    ->orWhere('hall_number', 'like', "%{$search}%")
                    ->orWhere('show_date', 'like', "%{$search}%");
            })
            // Sort records by date first, then by start time.
            ->orderBy('show_date')
            ->orderBy('start_time')
            // Show 8 records per page.
            ->paginate(8)
            // Keep the search value in pagination links.
            ->withQueryString();

        // Send all dashboard data to the showtimes index view.
        return view('showtimes.index', [
            'showtimes' => $showtimes,
            'search' => $search,
            'username' => $request->session()->get('username'),
            // Count unique movie titles for the dashboard statistic.
            'totalMovies' => Showtime::distinct('movie_title')->count('movie_title'),
            // Count unique hall numbers for the dashboard statistic.
            'totalHalls' => Showtime::distinct('hall_number')->count('hall_number'),
            // Sum all available seats for the dashboard statistic.
            'availableSeats' => Showtime::sum('available_seats'),
        ]);
    }

    // Show an empty form for adding a new showtime.
    public function create(): View
    {
        return view('showtimes.form', [
            // A new empty model lets the Blade form know this is create mode.
            'showtime' => new Showtime(),
            'username' => session('username'),
        ]);
    }

    // Save a new showtime after the form is submitted.
    public function store(Request $request): RedirectResponse
    {
        // Validate the input first, then insert the showtime into the database.
        Showtime::create($this->validatedShowtime($request));

        return redirect()->route('showtimes.index')->with('status', 'Showtime added successfully.');
    }

    // Show the form with an existing showtime for editing.
    public function edit(Showtime $showtime): View
    {
        return view('showtimes.form', [
            'showtime' => $showtime,
            'username' => session('username'),
        ]);
    }

    // Update an existing showtime after the edit form is submitted.
    public function update(Request $request, Showtime $showtime): RedirectResponse
    {
        // Validate the new values, then update the selected database record.
        $showtime->update($this->validatedShowtime($request));

        return redirect()->route('showtimes.index')->with('status', 'Showtime updated successfully.');
    }

    // Delete one showtime record.
    public function destroy(Showtime $showtime): RedirectResponse
    {
        $showtime->delete();

        return redirect()->route('showtimes.index')->with('status', 'Showtime deleted successfully.');
    }

    // Keep all showtime validation rules in one method so store and update use the same rules.
    private function validatedShowtime(Request $request): array
    {
        return $request->validate([
            // Movie title and genre are required text fields with maximum lengths.
            'movie_title' => ['required', 'string', 'max:100'],
            'genre' => ['required', 'string', 'max:50'],
            // Hall number must be a positive integer.
            'hall_number' => ['required', 'integer', 'min:1'],
            // Show date must be a valid date.
            'show_date' => ['required', 'date'],
            // Times must be in HH:MM format.
            'start_time' => ['required', 'date_format:H:i'],
            // End time must be after the start time.
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            // Seats cannot be negative.
            'available_seats' => ['required', 'integer', 'min:0'],
            // Ticket price must be greater than zero and fit the database decimal size.
            'ticket_price' => ['required', 'numeric', 'gt:0', 'max:9999.99'],
            // Status is limited to the two allowed values used by the dropdown.
            'movie_status' => ['required', Rule::in(['Showing', 'Coming Soon'])],
        ]);
    }
}
