<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if authenticated and has admin role
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }
        
        // If user tries to access admin route, redirect to home
        if (auth()->check() && auth()->user()->role === 'user') {
            return redirect('/home')->with('error', 'Users cannot access admin pages');
        }
        
        // For guests or invalid roles
        return redirect('/login')->with('error', 'Please login as an administrator');
    }
}