<?php

namespace Newnet\Acl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Newnet\Acl\Models\Admin;

class AdminPermission
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param                          $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        /** @var Admin $user */
        $user = Auth::guard('admin')->user();
        if ($user && $user->hasPermission($permission)) {
            return $next($request);
        }

        return abort(403);
    }
}
