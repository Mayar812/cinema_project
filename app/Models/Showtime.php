<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// These fields are allowed to be saved using create() and update().
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
class Showtime extends Model
{
    use HasFactory;

    // The showtimes table uses show_id instead of Laravel's default id column.
    protected $primaryKey = 'show_id';

    // Cast database values into better PHP formats when reading them.
    protected function casts(): array
    {
        return [
            // show_date becomes a date object, so Blade can call format().
            'show_date' => 'date',
            // ticket_price is always treated as a decimal with 2 digits.
            'ticket_price' => 'decimal:2',
        ];
    }
}
