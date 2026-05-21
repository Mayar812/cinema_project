<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUsernameSession
{
    // This middleware runs before protected dashboard routes.
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        // If the username is not in the session, the user is not logged in.
        if (! $request->session()->has('username')) {
            return redirect()->route('login');
        }

        // Continue to the requested page when the session is valid.
        return $next($request);
    }
}
