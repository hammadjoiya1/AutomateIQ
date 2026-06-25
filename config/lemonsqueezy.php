<?php

return [
    'checkout_urls' => [
        'pro' => env('LEMONSQUEEZY_CHECKOUT_URL_PRO'),
        'team' => env('LEMONSQUEEZY_CHECKOUT_URL_TEAM'),
    ],
    'checkout_urls_annual' => [
        'pro' => env('LEMONSQUEEZY_CHECKOUT_URL_PRO_ANNUAL'),
        'team' => env('LEMONSQUEEZY_CHECKOUT_URL_TEAM_ANNUAL'),
    ],
    'portal_url' => env('LEMONSQUEEZY_PORTAL_URL'),
    'webhook_secret' => env('LEMONSQUEEZY_WEBHOOK_SECRET'),
    'variant_ids' => [
        'pro' => env('LEMONSQUEEZY_VARIANT_ID_PRO'),
        'team' => env('LEMONSQUEEZY_VARIANT_ID_TEAM'),
    ],
    'topup_variants' => [
        // Map one-time variant IDs to credit amounts.
        // Example: '123456' => 1000,
    ],
    'topup_checkout_urls' => [
        // Map pack keys to Lemon Squeezy checkout URLs.
        // Example: 'starter' => env('LEMONSQUEEZY_TOPUP_URL_STARTER'),
    ],
    'trial_days' => (int) env('LEMONSQUEEZY_TRIAL_DAYS', 7),
];
