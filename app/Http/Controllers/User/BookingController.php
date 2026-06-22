<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\SeatReservation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    // Show the booking page for one specific showtime (movie).
    public function create(Request $request, Movie $movie): View
    {
        return view('User.booking', [
            'movie' => $movie,
            'bookedSeats' => $this->bookedSeats($this->reservationsFor($movie)),
            'accountName' => $this->accountName($request),
            'username' => $request->session()->get('username'),
            // The current account's own reservations for this showtime, so they
            // can be edited or cancelled from the booking page.
            'userReservations' => $this->userReservationsFor($request, $movie),
        ]);
    }

    // Reserve a seat for this showtime.
    public function store(Request $request, Movie $movie): RedirectResponse
    {
        if ($movie->movie_status !== 'Showing' || $movie->available_seats < 1) {
            return back()->withErrors(['seat_number' => 'This movie is not available for booking.']);
        }

        $this->normalizeSeatNumber($request);
        // The customer is always the logged-in account holder.
        $request->merge(['customer_name' => $this->accountName($request)]);
        $data = $request->validate($this->rules($movie));

        DB::transaction(function () use ($movie, $data, $request) {
            $user = $this->currentUser($request);
            $booking = Booking::create($this->bookingData($movie, $data, $user));

            SeatReservation::create([
                'user_id' => $this->currentUserId($request),
                'booking_id' => $booking->id,
                'show_id' => $movie->show_id,
                'customer_name' => $data['customer_name'],
                'seat_number' => $data['seat_number'],
            ]);

            // Booking a seat reduces the movie's remaining seats.
            $movie->decrement('available_seats');
        });

        return redirect()
            ->route('movies.booking', $movie)
            ->with('status', 'Seat '.$data['seat_number'].' sent to admin for approval.');
    }

    public function edit(Request $request, SeatReservation $seatReservation): View
    {
        $this->ensureOwns($request, $seatReservation);
        $movie = $seatReservation->movie;

        return view('reservations.edit', [
            'reservation' => $seatReservation,
            'movie' => $movie,
            // Exclude this reservation's own seat so it stays selectable.
            'bookedSeats' => $this->bookedSeats(
                $this->reservationsFor($movie)->where('id', '!=', $seatReservation->id)
            ),
            'accountName' => $seatReservation->customer_name,
            'username' => $request->session()->get('username'),
            'booking' => $seatReservation->booking,
        ]);
    }

    public function update(Request $request, SeatReservation $seatReservation): RedirectResponse
    {
        $this->ensureOwns($request, $seatReservation);
        $movie = $seatReservation->movie;

        $this->normalizeSeatNumber($request);
        // Editing only changes the seat; the customer stays the account holder.
        $request->merge(['customer_name' => $seatReservation->customer_name]);
        $data = $request->validate($this->rules($movie, $seatReservation));

        DB::transaction(function () use ($movie, $data, $request, $seatReservation) {
            $booking = $seatReservation->booking;

            if (! $booking) {
                $booking = Booking::create($this->bookingData($movie, $data, $this->currentUser($request)));
            } else {
                $booking->update($this->bookingData($movie, $data, $this->currentUser($request)));
            }

            // Only the customer and seat can change; the showtime stays the same,
            // so available_seats is unaffected.
            $seatReservation->update([
                'booking_id' => $booking->id,
                'customer_name' => $data['customer_name'],
                'seat_number' => $data['seat_number'],
            ]);
        });

        return redirect()->route('movies.booking', $movie)->with('status', 'Reservation updated and sent back to admin as pending.');
    }

    public function destroy(Request $request, SeatReservation $seatReservation): RedirectResponse
    {
        $this->ensureOwns($request, $seatReservation);
        $movie = $seatReservation->movie;

        DB::transaction(function () use ($seatReservation, $movie) {
            $booking = $seatReservation->booking;
            $seatReservation->delete();
            $booking?->delete();
            // Cancelling a reservation frees the seat again.
            $movie?->increment('available_seats');
        });

        return redirect()->route('movies.booking', $movie)->with('status', 'Reservation cancelled.');
    }

    // The current account's reservations for a single showtime.
    private function userReservationsFor(Request $request, Movie $movie): Collection
    {
        $user = $this->currentUser($request);

        if (! $user) {
            return collect();
        }

        return SeatReservation::query()
            ->with('booking')
            ->where('show_id', $movie->show_id)
            ->where('user_id', $user->id)
            ->orderBy('seat_number')
            ->get();
    }

    // Block editing or cancelling a reservation that belongs to someone else.
    private function ensureOwns(Request $request, SeatReservation $seatReservation): void
    {
        $user = $this->currentUser($request);

        abort_unless(
            $user && $seatReservation->user?->is($user),
            403,
            'You can only manage your own reservations.'
        );
    }

    private function reservationsFor(Movie $movie): Collection
    {
        return SeatReservation::query()
            ->where('show_id', $movie->show_id)
            ->orderBy('seat_number')
            ->get();
    }

    private function bookedSeats(Collection $reservations): Collection
    {
        return $reservations->pluck('seat_number')->map(fn ($seat) => strtoupper($seat))->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Movie $movie, ?SeatReservation $reservation = null): array
    {
        $uniqueSeat = Rule::unique('seat_reservations', 'seat_number')
            ->where('show_id', $movie->show_id);

        if ($reservation) {
            $uniqueSeat->ignore($reservation->id);
        }

        return [
            'customer_name' => ['required', 'string', 'max:80'],
            'seat_number' => ['required', 'string', 'max:2', 'regex:/^[ABC][1-6]$/', $uniqueSeat],
            'chair_type' => ['required', Rule::in(['VIP', 'Premium'])],
            'snacks' => ['nullable', Rule::in(['Popcorn combo', 'Nachos', 'Cola', 'Kids popcorn', 'None'])],
            'payment_method' => ['required', Rule::in(['Visa', 'Mastercard', 'Cash'])],
        ];
    }

    private function bookingData(Movie $movie, array $data, ?User $user): array
    {
        $snacks = ($data['snacks'] ?? null) === 'None' ? null : ($data['snacks'] ?? null);
        $chairFee = $data['chair_type'] === 'VIP' ? 8 : 4;
        $snackFee = $snacks ? 7 : 0;

        return [
            'user_id' => $user?->id,
            'showtime_id' => $movie->show_id,
            'customer_name' => $data['customer_name'],
            'customer_email' => $user?->email ?? 'guest@example.com',
            'chair_type' => $data['chair_type'],
            'chair_count' => 1,
            'seat_numbers' => $data['seat_number'],
            'snacks' => $snacks,
            'status' => 'pending',
            'payment_status' => $data['payment_method'] === 'Cash' ? 'unpaid' : 'paid',
            'payment_amount' => (float) $movie->ticket_price + $chairFee + $snackFee,
            'payment_method' => $data['payment_method'],
        ];
    }

    // The logged-in account, resolved from the session username.
    private function currentUser(Request $request): ?User
    {
        return User::where('username', $request->session()->get('username'))->first();
    }

    private function currentUserId(Request $request): ?int
    {
        return $this->currentUser($request)?->id;
    }

    // The display name to book under: the account's name, or its username.
    private function accountName(Request $request): string
    {
        $username = (string) $request->session()->get('username');

        return $this->currentUser($request)?->name ?: $username;
    }

    private function normalizeSeatNumber(Request $request): void
    {
        $request->merge([
            'seat_number' => strtoupper(trim((string) $request->input('seat_number'))),
        ]);
    }
}
