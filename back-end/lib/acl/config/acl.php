<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ACL Config
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admins',
            ],
        ],

        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model'  => \Newnet\Acl\Models\Admin::class,
            ],
        ],

        'passwords' => [
            'admins' => [
                'provider' => 'admins',
                'table'    => 'admin_password_resets',
                'expire'   => 60,
                'throttle' => 60,
            ],
        ],
    ],

    'default_avatar' => '/vendor/newnet-admin/img/default-avatar.png',

    'redirect_after_login' => function () {
        if (Route::has('admin.dashboard.index')) {
            return route('admin.dashboard.index');
        }

        return config('core.admin_prefix');
    },

    'redirect_after_logout' => function () {
        return route('admin.login');
    },

    'redirect_if_authenticated' => function () {
        if (Route::has('admin.dashboard.index')) {
            return route('admin.dashboard.index');
        }

        return config('core.admin_prefix');
    },
];
