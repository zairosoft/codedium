<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if (Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }
        if (Cookie::has('locale')) {
            App::setLocale(Cookie::get('locale'));
        }

        return $next($request);
    }
}
