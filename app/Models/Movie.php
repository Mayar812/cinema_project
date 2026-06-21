<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
