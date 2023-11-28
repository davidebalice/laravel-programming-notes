<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMode
{
    public function handle(Request $request, Closure $next)
    {
        if (config('app.demo_mode')) {
            $notification = array(
                'message' => 'Operation not allowed in DEMO MODE',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        return $next($request);
    }
}
