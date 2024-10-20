<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on the user's role
            if ($user->role === 'admin') {
                return redirect('/admin'); // Redirect admins to the admin panel
            } elseif ($user->role === 'user') {
                return redirect('/'); // Redirect normal users to a user dashboard
            }
        }

        return $next($request); // Let the request proceed if no specific role is found
    }
}
