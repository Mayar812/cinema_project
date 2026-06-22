<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUsernameSession
{
    // This middleware runs before protected dashboard routes.
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response|RedirectResponse
    {
        // If the username is not in the session, the user is not logged in.
        if (! $request->session()->has('username')) {
            return redirect()->route('login');
        }

        $role = $request->session()->get('role');

        // Upgrade sessions created before roles were introduced using the database account.
        if (! $role) {
            $user = User::where('username', $request->session()->get('username'))->first();
            $role = $user?->role;

            if ($user) {
                $request->session()->put([
                    'user_id' => $user->id,
                    'role' => $role,
                ]);
            }
        }

        if (! in_array($role, ['admin', 'user'], true)) {
            $request->session()->invalidate();

            return redirect()->route('login');
        }

        if ($requiredRole && $role !== $requiredRole) {
            return redirect()->route($role === 'admin' ? 'showtimes.index' : 'user.home');
        }

        // Continue to the requested page when the session is valid.
        return $next($request);
    }
}
