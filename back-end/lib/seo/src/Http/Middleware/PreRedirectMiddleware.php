<?php

namespace Newnet\Seo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Newnet\Seo\Models\PreRedirect;

class PreRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('cms.seo.enable_pre_redirect')) {
            return $next($request);
        }

        if ($request->input('skip') == 'rewrite' || $request->is(config('core.admin_prefix').'*')) {
            return $next($request);
        }

        $targetPath = ltrim($request->getRequestUri(), '/');

        if ($preRedirect = PreRedirect::where('from_path', $targetPath)->first()) {
            return redirect($preRedirect->to_url, $preRedirect->status_code);
        }

        return $next($request);
    }
}
