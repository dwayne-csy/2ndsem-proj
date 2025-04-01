<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Default for regular users

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the authenticated method to check for the user's role and status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Debugging: Check if the method is being called
        Log::info('User logged in:', ['user_id' => $user->id, 'role' => $user->role, 'status' => $user->status]);

        // Check if the user is inactive
        if ($user->status === 'inactive') {
            Auth::logout(); // Log out the user
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        // Check if the user is an admin
        if ($user->role === 'admin') {
            Log::info('Redirecting admin to dashboard');
            return redirect()->route('admin.dashboard');
        }

        // Default redirect for regular users
        Log::info('Redirecting regular user to home');
        return redirect()->route('home');
    }

    /**
     * Log the user out and redirect to the login page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->logout(); // Log out the user
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        // Redirect to the login page
        return redirect()->route('login');
    }
}