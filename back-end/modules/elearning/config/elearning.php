<?php

return [
    // 'config_key' => env('ENV_CONFIG_KEY', 'default_value'),
    'video_provider' => env('VIDEO_PROVIDER', 'server'),
    'video_provider_options' => [
        'server' => 'Server',
        'bunny' => 'Bunny',
        'digitalocean' => 'DigitalOcean',
        's3' => 'S3',
    ],
    's3_config' => [
        'region' => env('AWS_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'cdn_base_url' => env('AWS_CDN_BASE_URL'),
    ],
    'digitalocean_config' => [
        'region' => env('DIGITALOCEAN_REGION'),
        'bucket' => env('DIGITALOCEAN_BUCKET'),
        'cdn_base_url' => env('DIGITALOCEAN_CDN_BASE_URL'),
    ],
    'server_config' => [
        'endpoint' => env('SERVER_ENDPOINT'),
        'token' => env('SERVER_TOKEN'),
    ],
];
