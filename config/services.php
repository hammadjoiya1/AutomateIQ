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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('FACELESS_OPENAI_KEY', env('OPENAI_API_KEY')),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_MODEL', 'gpt-4'),
        'fallback_model' => env('OPENAI_FALLBACK_MODEL', 'gpt-4o-mini'),
    ],

    'replicate' => [
        'api_token' => env('REPLICATE_API_TOKEN'),
    ],

    'webhooks' => [
        'tool_run' => env('TOOL_RUN_WEBHOOK_URL'),
        'slack' => env('SLACK_WEBHOOK_URL'),
    ],

];
