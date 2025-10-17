<?php

namespace Newnet\Customer\Http\Middleware;

use Illuminate\Auth\AuthenticationException;

class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectToWithGuard($request, $guards)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $guards
     * @return string|null
     */
    protected function redirectToWithGuard($request, $guards)
    {
        if (!$request->expectsJson()) {
            if (in_array('customer', $guards)) {
                return route('customer.web.customer.login');
            } elseif (\Route::has('customer.web.customer.login')) {
                return route('customer.web.customer.login');
            } else {
                return url('customer/login');
            }
        }
    }
}
