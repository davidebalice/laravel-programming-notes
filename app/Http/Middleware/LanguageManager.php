<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultLanguage = Config::get('app.default_language');
        App::setLocale($defaultLanguage);
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
         
        return $next($request);
    }
}
