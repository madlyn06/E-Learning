<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Video Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default video upload driver that will be used
    | when uploading video files. You can change this to any of the
    | drivers listed below.
    |
    */
    'default' => env('VIDEO_DRIVER', 'server'),

    /*
    |--------------------------------------------------------------------------
    | Video Upload Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the video upload drivers for your application.
    | Each driver has its own configuration options.
    |
    */
    'drivers' => [
        'server' => [
            'driver' => 'server',
            'disk' => 'local',
            'path' => 'videos',
            'max_size' => '500MB',
            'allowed_types' => ['mp4', 'avi', 'mov', 'mkv', 'webm'],
            'chunk_size' => '10MB',
            'concurrent_uploads' => 3,
        ],

        'bunny' => [
            'driver' => 'bunny',
            'api_key' => env('BUNNY_API_KEY'),
            'storage_zone' => env('BUNNY_STORAGE_ZONE'),
            'region' => env('BUNNY_REGION', 'de'),
            'pull_zone' => env('BUNNY_PULL_ZONE'),
            'cdn_url' => env('BUNNY_CDN_URL'),
            'max_size' => '2GB',
            'allowed_types' => ['mp4', 'avi', 'mov', 'mkv', 'webm'],
            'chunk_size' => '100MB',
            'concurrent_uploads' => 5,
            'streaming_enabled' => true,
            'hls_enabled' => true,
        ],

        'digitalocean' => [
            'driver' => 'digitalocean',
            'key' => env('DO_SPACES_KEY'),
            'secret' => env('DO_SPACES_SECRET'),
            'region' => env('DO_SPACES_REGION', 'nyc3'),
            'bucket' => env('DO_SPACES_BUCKET'),
            'endpoint' => env('DO_SPACES_ENDPOINT'),
            'max_size' => '5GB',
            'allowed_types' => ['mp4', 'avi', 'mov', 'mkv', 'webm'],
            'chunk_size' => '100MB',
            'concurrent_uploads' => 3,
            'streaming_enabled' => true,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
            'endpoint' => env('AWS_ENDPOINT'),
            'max_size' => '5GB',
            'allowed_types' => ['mp4', 'avi', 'mov', 'mkv', 'webm'],
            'chunk_size' => '100MB',
            'concurrent_uploads' => 3,
            'streaming_enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Video Processing Settings
    |--------------------------------------------------------------------------
    |
    | Global settings for video processing regardless of driver
    |
    */
    'processing' => [
        'hls_enabled' => env('VIDEO_HLS_ENABLED', true),
        'hls_segment_duration' => env('VIDEO_HLS_SEGMENT_DURATION', 10),
        'hls_segment_list_size' => env('VIDEO_HLS_SEGMENT_LIST_SIZE', 0),
        'hls_multi_bitrate' => env('VIDEO_HLS_MULTI_BITRATE', true),
        'hls_resolutions' => [
            '1080p' => [
                'width' => 1920,
                'height' => 1080,
                'bitrate' => '5000k',
                'audio_bitrate' => '128k',
                'crf' => 18
            ],
            '720p' => [
                'width' => 1280,
                'height' => 720,
                'bitrate' => '2800k',
                'audio_bitrate' => '128k',
                'crf' => 20
            ],
            '480p' => [
                'width' => 854,
                'height' => 480,
                'bitrate' => '1400k',
                'audio_bitrate' => '96k',
                'crf' => 22
            ]
        ],
        'thumbnail_enabled' => env('VIDEO_THUMBNAIL_ENABLED', true),
        'thumbnail_time' => env('VIDEO_THUMBNAIL_TIME', '00:00:05'),
        'compression_enabled' => env('VIDEO_COMPRESSION_ENABLED', false),
        'compression_quality' => env('VIDEO_COMPRESSION_QUALITY', 'medium'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Queue settings for background video processing
    |
    */
    'queue' => [
        'connection' => env('VIDEO_QUEUE_CONNECTION', 'default'),
        'queue' => env('VIDEO_QUEUE_NAME', 'video-processing'),
        'retry_attempts' => env('VIDEO_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('VIDEO_RETRY_DELAY', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Security and validation settings
    |
    */
    'security' => [
        'virus_scan_enabled' => env('VIDEO_VIRUS_SCAN_ENABLED', false),
        'virus_scan_provider' => env('VIDEO_VIRUS_SCAN_PROVIDER', 'clamav'),
        'max_duration' => env('VIDEO_MAX_DURATION', 7200), // 2 hours in seconds
        'allowed_resolutions' => [
            '720p' => ['width' => 1280, 'height' => 720],
            '1080p' => ['width' => 1920, 'height' => 1080],
            '4k' => ['width' => 3840, 'height' => 2160],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Analytics
    |--------------------------------------------------------------------------
    |
    | Settings for video analytics and monitoring
    |
    */
    'monitoring' => [
        'enabled' => env('VIDEO_MONITORING_ENABLED', true),
        'log_uploads' => env('VIDEO_LOG_UPLOADS', true),
        'log_processing' => env('VIDEO_LOG_PROCESSING', true),
        'metrics_enabled' => env('VIDEO_METRICS_ENABLED', true),
        'alert_on_failure' => env('VIDEO_ALERT_ON_FAILURE', true),
    ],
];
