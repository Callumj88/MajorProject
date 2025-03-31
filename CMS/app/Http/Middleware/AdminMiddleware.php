<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not logged in or not an admin (authLevel !== 1), abort with 403.
        if (!Auth::check() || Auth::user()->authLevel !== 1) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}
