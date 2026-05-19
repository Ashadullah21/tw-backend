<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the custom session key is present
        if (session('admin_logged_in') !== true) {
            // Redirect to login page if unauthenticated
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
