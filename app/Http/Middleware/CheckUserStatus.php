<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If the user is inactive, log them out and redirect
            if ($user->status === 'inactive') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ]);
            }
        }

        return $next($request);
    }
}