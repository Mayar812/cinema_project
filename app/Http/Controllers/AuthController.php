<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show the login page to the user.
    public function showLogin(): View
    {
        return view('Admin.auth.login');
    }

    // Handle the login form submission.
    public function login(Request $request): RedirectResponse
    {
        // Validate that username and password are provided before checking the database.
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Find the user record by username.
        $user = User::where('username', $credentials['username'])->first();

        // If the user does not exist or the password is wrong, return with an error.
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Invalid username or password.'])
                ->onlyInput('username');
        }

        // Regenerate the session ID after login for better security.
        $request->session()->regenerate();
        Auth::login($user);
        // Store identity and role so middleware can authorize each application area.
        $request->session()->put([
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
        ]);

        return redirect()->route($user->role === 'admin' ? 'showtimes.index' : 'user.home');
    }

    // Handle logout and remove the login session.
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        // Remove the custom username value from the session.
        $request->session()->forget('username');
        // Invalidate the whole session so old session data cannot be reused.
        $request->session()->invalidate();
        // Regenerate the CSRF token after logout.
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
