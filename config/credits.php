<?php

return [
    // Price per credit in cents (what you charge users).
    'credit_price_cents' => (int) env('CREDIT_PRICE_CENTS', 10),

    // Target gross margin as a decimal: 0.70 = 70%.
    'target_gross_margin' => (float) env('CREDIT_TARGET_GROSS_MARGIN', 0.70),

    // Monthly subscription credit grants per plan.
    'monthly_credits' => [
        'free' => (int) env('PLAN_FREE_MONTHLY_CREDITS', 50),
        'pro' => (int) env('PLAN_PRO_MONTHLY_CREDITS', 3000),
        'team' => (int) env('PLAN_TEAM_MONTHLY_CREDITS', 12000),
    ],

    // OpenAI blended pricing by model in cents per 1K tokens (input+output).
    'openai_models' => [
        // Example pricing; adjust to your actual contract rates.
        'gpt-4' => [
            'blended_cents_per_1k' => (float) env('OPENAI_GPT4_BLEND_CENTS_PER_1K', 6.00),
        ],
        'gpt-4o' => [
            'blended_cents_per_1k' => (float) env('OPENAI_GPT4O_BLEND_CENTS_PER_1K', 1.25),
        ],
        'gpt-4o-mini' => [
            'blended_cents_per_1k' => (float) env('OPENAI_GPT4O_MINI_BLEND_CENTS_PER_1K', 0.30),
        ],
    ],

    // Replicate pricing by model in cents per second of video.
    'replicate_models' => [
        'cjwbw/damo-text-to-video' => [
            'cents_per_second' => (float) env('REPLICATE_DAMO_CENTS_PER_SECOND', 6.00),
        ],
    ],

    // Default assumptions for video generation used to estimate duration/cost.
    'video_defaults' => [
        'num_frames' => 16,
        'fps' => 8,
    ],
];
