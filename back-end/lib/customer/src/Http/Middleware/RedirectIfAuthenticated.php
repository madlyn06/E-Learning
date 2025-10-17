<?php

namespace Newnet\Customer\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $config = config('cms.customer.redirect_if_authenticated', route('customer.web.customer.profile'));

            $redirect = is_callable($config) ? $config() : $config;

            return redirect($redirect);
        }

        return $next($request);
    }
}
