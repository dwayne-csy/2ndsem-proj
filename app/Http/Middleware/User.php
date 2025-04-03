<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class User
{
    public function handle(Request $request, Closure $next)
    {
        // Check if authenticated and has user role
        if (auth()->check() && auth()->user()->role === 'user') {
            return $next($request);
        }
        
        // If admin tries to access user route, redirect to admin dashboard
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Admins cannot access user pages');
        }
        
        // For guests or invalid roles
        return redirect('/login')->with('error', 'Please login as a regular user');
    }
}