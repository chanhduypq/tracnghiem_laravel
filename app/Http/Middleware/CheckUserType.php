<?php

namespace JP_COMMUNITY\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserType
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

        if (Auth::user()->user_type == 'is_admin' || Auth::user()->user_type == 'is_staff') {
            return $next($request);
        }
        return redirect()->guest('/');
    }
}
