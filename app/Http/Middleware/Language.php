<?php

namespace JP_COMMUNITY\Http\Middleware;


use Closure;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Session::has('myLocale') and array_key_exists(\Session::get('myLocale'), \Config::get('app.locales'))) {
            \App::setLocale(\Session::get('myLocale'));
        } else {
            \App::setLocale(\Config::get('app.fallback_locale'));
        }

        return $next($request);
    }
}