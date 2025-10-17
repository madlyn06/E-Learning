<?php

return [
    'page' => [
        'page_layout' => [
            'options' => [
                'default'   => 'Default',
                'home'      => 'Home',
                'about'     => 'About',
                'contact'   => 'Contact',
                'post_list' => 'Post List',
            ],
            'default' => 'default',
        ],
    ],

    'category' => [
        'item_per_page' => 6,
    ],
    'media_manager' => true,
    'story' => [
        'layouts' => [
            'owlcarousel2',
        ],
    ],
    'translate' => [
        'openai_api_key' => env('OPENAI_API_KEY'),
        'openai_api_url' => env('OPENAI_API_URL', 'https://api.openai.com/v1/'),
    ]
];
