<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// These user fields are allowed to be filled by Laravel.
#[Fillable(['name', 'username', 'role', 'email', 'password', 'google_id'])]
// These fields are hidden when the user model is converted to arrays or JSON.
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Convert email verification date to a date/time object.
            'email_verified_at' => 'datetime',
            // Automatically hash passwords when they are saved.
            'password' => 'hashed',
        ];
    }
}
