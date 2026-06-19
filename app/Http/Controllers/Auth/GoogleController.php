<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    // Redirect user to Google login
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        // Find or create user
        $user = User::firstOrNew(['email' => $googleUser->getEmail()]);
        $user->name = $googleUser->getName();
        $user->google_id = $googleUser->getId();

        if (! $user->exists) {
            $user->username = $this->uniqueUsername($googleUser->getEmail(), $googleUser->getName());
            $user->password = Str::password();
        }

        $user->save();

        // Login user
        Auth::login($user, true);
        request()->session()->regenerate();
        request()->session()->put('username', $user->username);

        return redirect()->route('showtimes.index');
    }

    private function uniqueUsername(string $email, ?string $name): string
    {
        $base = Str::slug(Str::before($email, '@') ?: $name ?: 'google-user');
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base.'-'.$counter++;
        }

        return $username;
    }
}
