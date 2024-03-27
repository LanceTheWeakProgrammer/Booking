<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OperatorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('operator')->check()) {
            return redirect()->route('operator.login'); 
        }

        return $next($request);
    }
}
