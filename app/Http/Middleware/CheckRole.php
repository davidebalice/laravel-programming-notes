<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && in_array($request->user()->role, ['admin', 'vendor'])) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
