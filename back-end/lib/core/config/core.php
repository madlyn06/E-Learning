<?php
return [
    'admin_domain' => env('ADMIN_DOMAIN'),

    'admin_prefix' => env('ADMIN_PREFIX', 'admin'),

    'admin_middleware' => ['web', 'admin.auth', 'admin.locale'],

    'admin_menu' => [
        'auto_activate'    => true,
        'activate_parents' => true,
        'active_class'     => 'mm-active',
        'restful'          => false,
        'cascade_data'     => true,
        'rest_base'        => '',      // string|array
        'active_element'   => 'item',  // item|link
    ],

    'debug_blacklist' => [
        '_ENV'    => array_keys($_ENV),
        '_SERVER' => array_keys($_SERVER),
        '_COOKIE' => array_keys($_COOKIE),
        '_POST'   => [
            'password',
        ],
    ],
];
