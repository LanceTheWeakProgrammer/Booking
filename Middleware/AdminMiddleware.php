<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {

        if (Auth::guard('admin')->check() && $request->routeIs('admin.login') === false) {
            return Redirect::route('admin.dashboard');
        }

        return $next($request);
    }
}
