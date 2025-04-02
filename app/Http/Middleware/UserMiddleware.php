<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isUser()) {
            abort(403, 'User access only');
        }

        return $next($request);
    }
}