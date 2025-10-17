<?php

use Newnet\Customer\Models\Customer;

return [
    /*
    |--------------------------------------------------------------------------
    | Customer Config
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'guards' => [
            'customer' => [
                'driver'   => 'session',
                'provider' => 'customers',
            ],
        ],

        'providers' => [
            'customers' => [
                'driver' => 'eloquent',
                'model'  => Customer::class,
            ],
        ],

        'passwords' => [
            'customers' => [
                'provider' => 'customers',
                'table'    => 'customer__password_resets',
                'expire'   => 60,
                'throttle' => 60,
            ],
        ],
    ],

    'enable_register' => true,

    'default_avatar' => null,

    'redirect_after_login' => function () {
        return route('customer.web.customer.profile');
    },

    'redirect_after_logout' => function () {
        return route('customer.web.customer.login');
    },

    'redirect_if_authenticated' => function () {
        return route('customer.web.customer.profile');
    },
];
