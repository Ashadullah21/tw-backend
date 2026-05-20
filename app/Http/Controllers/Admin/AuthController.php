<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * GET /admin/login
     */
    public function showLogin()
    {
        if (session('admin_logged_in') === true) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle the admin login submission.
     *
     * POST /admin/login
     */
    public function login(Request $request)
    {
        // Validate incoming credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD', 'secret123');

        // Verify credentials against hardcoded values in .env
        if ($credentials['email'] === $adminEmail && $credentials['password'] === $adminPassword) {
            // Set session indicator to authenticate admin manually
            session(['admin_logged_in' => true]);

            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Return back with error message on mismatch
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout.
     *
     * POST /admin/logout
     */
    public function logout(Request $request)
    {
        // Flush the custom admin session variable
        session()->forget('admin_logged_in');

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
