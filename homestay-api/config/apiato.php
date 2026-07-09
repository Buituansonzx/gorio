<?php

return [
    'defaults' => [
        'app' => 'honestay',
        'api' => [
            'prefix' => 'api',
            'version' => 'v1',
        ],
    ],

    'apps' => [
        'honestay' => [
            'name' => 'HoneStay',
            'url' => env('APP_URL', 'http://localhost:8080'),
        ],
    ],

    'api' => [
        'enable_rate_limiting' => env('API_RATE_LIMITING_ENABLED', true),
        'rate_limit_attempts' => env('API_RATE_LIMIT_ATTEMPTS', 60),
        'rate_limit_expires' => env('API_RATE_LIMIT_EXPIRES', 1),
        'throttle' => [
            'enabled' => env('GLOBAL_API_RATE_LIMIT_ENABLED', true),
            'attempts' => env('GLOBAL_API_RATE_LIMIT_ATTEMPTS_PER_MIN', '30'),
            'expires' => env('GLOBAL_API_RATE_LIMIT_EXPIRES_IN_MIN', '1'),
        ],
        'pagination' => [
            'default_page_size' => env('PAGINATION_DEFAULT_PAGE_SIZE', 10),
            'max_page_size' => env('PAGINATION_MAX_PAGE_SIZE', 100),
        ],
    ],

    'hash_id' => [
        'encode_user_id' => env('HASH_ID_ENCODE_USER_ID', true),
        'salt' => env('HASH_ID_KEY', 'default-salt'),
        'length' => env('HASH_ID_LENGTH', 16),
    ],

    'requests' => [
        'force_valid_json_content' => env('REQUESTS_FORCE_VALID_JSON_CONTENT', true),
    ],

    'debugger' => [
        'enabled' => env('DEBUGGER_ENABLED', false),
    ],
];
