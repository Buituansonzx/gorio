<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for third-party integrations
    |
    */

    'payment' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', false),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        
        'vnpay' => [
            'enabled' => env('VNPAY_ENABLED', false),
            'merchant_id' => env('VNPAY_MERCHANT_ID'),
            'secret_key' => env('VNPAY_SECRET_KEY'),
            'return_url' => env('VNPAY_RETURN_URL'),
        ],
    ],

    'notification' => [
        'email' => [
            'enabled' => env('EMAIL_NOTIFICATION_ENABLED', true),
        ],
        'sms' => [
            'enabled' => env('SMS_NOTIFICATION_ENABLED', false),
        ],
    ],

    'external_apis' => [
        'timeout' => env('EXTERNAL_API_TIMEOUT', 30),
        'retry_attempts' => env('EXTERNAL_API_RETRY_ATTEMPTS', 3),
    ],
];