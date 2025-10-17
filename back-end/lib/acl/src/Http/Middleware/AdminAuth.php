<?php

namespace Newnet\Acl\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Route;

class AdminAuth
{
    /**
     * The authentication factory instance.
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    protected $guard = 'admin';

    /**
     * Create a new middleware instance.
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $this->authenticate($request);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws AuthenticationException
     */
    protected function authenticate($request)
    {
        if ($this->auth->guard($this->guard)->check()) {
            return $this->auth->shouldUse($this->guard);
        }

        $this->unauthenticated($request);
    }

    /**
     * Handle an unauthenticated user.
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws AuthenticationException
     */
    protected function unauthenticated($request)
    {
        throw new AuthenticationException(
            'Unauthenticated.', [$this->guard], $this->redirectTo($request)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
    }
}
