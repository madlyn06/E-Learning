<?php

namespace Newnet\Acl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Newnet\Acl\Models\Admin;

class AdminAcl
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed|never
     */
    public function handle($request, Closure $next)
    {
        $permission = Route::currentRouteName();

        /** @var Admin $user */
        $user = Auth::guard('admin')->user();
        if ($user && $user->hasPermission($permission)) {
            return $next($request);
        }

        return abort(403);
    }
}
