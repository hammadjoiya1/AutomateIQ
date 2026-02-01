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
    'trial_days' => (int) env('LEMONSQUEEZY_TRIAL_DAYS', 7),
];
