<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if user role is admin or super_admin
        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            return $next($request);
        }

        // Redirect unauthorized users to the login page
        return redirect('/login')->withErrors(['error' => 'Access denied. Only admins are allowed.']);
    }
}
