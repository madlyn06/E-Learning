<?php

namespace Newnet\Acl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->check()) {
            $config = config('cms.acl.redirect_if_authenticated', config('core.admin_prefix'));

            $redirect = is_callable($config) ? $config() : $config;

            return redirect($redirect);
        }

        return $next($request);
    }
}
