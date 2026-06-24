<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Movie data is stored in the existing showtimes table seeded by the project.
#[Fillable([
    'movie_title',
    'image',
    'genre',
    'hall_number',
    'show_date',
    'start_time',
    'end_time',
    'available_seats',
    'ticket_price',
    'movie_status',
])]
class Movie extends Model
{
    use HasFactory;

    protected $table = 'showtimes';

    protected $primaryKey = 'show_id';

    protected function casts(): array
    {
        return [
            'show_date' => 'date',
            'ticket_price' => 'decimal:2',
        ];
    }

    // Every reservation made against this showtime's seat grid.
    public function seatReservations(): HasMany
    {
        return $this->hasMany(SeatReservation::class, 'show_id', 'show_id');
    }

    public function scopeComingSoon(Builder $query): Builder
    {
        return $query->where('movie_status', 'Coming Soon');
    }

    public function scopeAvailableForBooking(Builder $query): Builder
    {
        return $query
            ->where('movie_status', 'Showing')
            ->where('available_seats', '>', 0);
    }

    // The fixed number of seats in every hall (rows × columns).
    public function getTotalSeatsAttribute(): int
    {
        return count((array) config('cinema.seat_rows'))
            * count((array) config('cinema.seat_columns'));
    }

    // How many of those seats are actually reserved right now.
    public function getBookedSeatsCountAttribute(): int
    {
        return $this->seatReservations()->count();
    }

    // Sold out when every seat in the grid is reserved, regardless of the
    // available_seats counter (which can drift out of sync).
    public function getIsSoldOutAttribute(): bool
    {
        return $this->booked_seats_count >= $this->total_seats;
    }

    public function getDurationInMinutesAttribute(): ?int
    {
        if (! $this->start_time || ! $this->end_time) {
            return null;
        }

        $startsAt = Carbon::createFromFormat('H:i:s', $this->normalizeTime($this->start_time));
        $endsAt = Carbon::createFromFormat('H:i:s', $this->normalizeTime($this->end_time));

        if ($endsAt->lessThanOrEqualTo($startsAt)) {
            $endsAt->addDay();
        }

        return (int) $startsAt->diffInMinutes($endsAt);
    }

    private function normalizeTime(string $time): string
    {
        return strlen($time) === 5 ? $time.':00' : $time;
    }
}
