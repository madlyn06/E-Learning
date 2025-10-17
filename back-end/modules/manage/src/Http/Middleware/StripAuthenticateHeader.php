<?php

namespace Modules\Manage\Http\Middleware;

use Closure;

class StripAuthenticateHeader
{
  public function handle($request, Closure $next)
  {
    header_remove('X-Powered-By');
    return $next($request);
  }
}
