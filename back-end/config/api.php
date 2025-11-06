<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Response Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for API responses including
    | response format, error handling, and debugging options.
    |
    */

    'response' => [
        /*
        |--------------------------------------------------------------------------
        | Response Format
        |--------------------------------------------------------------------------
        |
        | Define the standard format for API responses
        |
        */
        'format' => [
            'success' => 'success',
            'message' => 'message',
            'data' => 'data',
            'errors' => 'errors',
            'timestamp' => 'timestamp',
            'debug' => 'debug',
        ],

        /*
        |--------------------------------------------------------------------------
        | Default Messages
        |--------------------------------------------------------------------------
        |
        | Default messages for common responses
        |
        */
        'messages' => [
            'success' => 'Success',
            'created' => 'Resource created successfully',
            'updated' => 'Resource updated successfully',
            'deleted' => 'Resource deleted successfully',
            'validation_failed' => 'Validation failed',
            'not_found' => 'Resource not found',
            'unauthorized' => 'Unauthorized',
            'forbidden' => 'Forbidden',
            'server_error' => 'Internal server error',
        ],

        /*
        |--------------------------------------------------------------------------
        | HTTP Status Codes
        |--------------------------------------------------------------------------
        |
        | Standard HTTP status codes for different response types
        |
        */
        'status_codes' => [
            'success' => 200,
            'created' => 201,
            'no_content' => 204,
            'bad_request' => 400,
            'unauthorized' => 401,
            'forbidden' => 403,
            'not_found' => 404,
            'method_not_allowed' => 405,
            'validation_failed' => 422,
            'server_error' => 500,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    |
    | Configuration for error handling and logging
    |
    */
    'error_handling' => [
        /*
        |--------------------------------------------------------------------------
        | Include Debug Information
        |--------------------------------------------------------------------------
        |
        | Whether to include debug information in error responses
        | Only works when APP_DEBUG=true
        |
        */
        'include_debug' => env('API_DEBUG', false),

        /*
        |--------------------------------------------------------------------------
        | Log Errors
        |--------------------------------------------------------------------------
        |
        | Whether to log API errors
        |
        */
        'log_errors' => env('API_LOG_ERRORS', true),

        /*
        |--------------------------------------------------------------------------
        | Log Level
        |--------------------------------------------------------------------------
        |
        | Log level for API errors
        |
        */
        'log_level' => env('API_LOG_LEVEL', 'error'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for API rate limiting
    |
    */
    'rate_limiting' => [
        'enabled' => env('API_RATE_LIMITING', true),
        'max_attempts' => env('API_RATE_LIMIT_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('API_RATE_LIMIT_DECAY_MINUTES', 1),
    ],
];
