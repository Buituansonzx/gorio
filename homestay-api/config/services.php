<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'vietqr' => [
        'client_id' => env('VIETQR_CLIENT_ID'),
        'api_key' => env('VIETQR_API_KEY'),
        'base_url' => env('VIETQR_BASE_URL', 'https://api.vietqr.io'),
    ],
    'sms' => [
        'url' => env('SMS_URL'),
        'key' => env('SMS_KEY'),
        'from' => env('SMS_FROM'),
    ],
    'hong_manage' => [
        'api_key' => env('HONG_MANAGE_API_KEY'),
        'url' => env('HONG_MANAGE_URL') . '/api',
        'domain' => env('HONG_MANAGE_URL'),
    ],

    'telegram' => [

        'bot' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
        ],

        'booking_request' => [
            'chat_id' => env('TELEGRAM_BOOKING_REQUEST_CHAT'),
            'name' => 'Gorio - Yêu cầu đặt phòng'
        ],

        'booking_success' => [
            'chat_id' => env('TELEGRAM_BOOKING_SUCCESS_CHAT'),
            'name' => 'Gorio - Đặt phòng thành công'
        ],
        'otp' => [
            'chat_id' => env('TELEGRAM_OTP_CHAT'),
            'name' => 'Gorio - OTP'
        ],

        'sms_failed' => [
            'chat_id' => env('TELEGRAM_SMS_FAILED_CHAT'),
            'name' => 'Gorio - SMS Failed'
        ],
    ],

];
