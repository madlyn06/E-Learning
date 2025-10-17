<?php

namespace Newnet\Acl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class AdminLocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->is(config('core.admin_prefix').'*')) {
            $adminLocale = get_admin_setting('admin_locale');
            if ($adminLocale) {
                App::setLocale($adminLocale);
            }
        }

        return $next($request);
    }
}
