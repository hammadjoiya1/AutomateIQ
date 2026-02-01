<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook' => env('STRIPE_WEBHOOK_SECRET'),
    'currency' => env('CASHIER_CURRENCY', 'usd'),
    'trial_days' => (int) env('CASHIER_TRIAL_DAYS', 7),
    'prices' => [
        'pro' => env('STRIPE_PRICE_PRO'),
        'team' => env('STRIPE_PRICE_TEAM'),
    ],
];
