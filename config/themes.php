<?php

return [
    'default' => 'light',

    'themes' => [
        'light' => [
            'name' => 'Light Modern',
            'class' => 'theme-light',
            'colors' => [
                'primary' => '#2563eb', // Blue-600 (Stripe-like blue)
                'background' => '#ffffff',
                'surface' => '#f8fafc', // Slate-50
                'text' => '#0f172a', // Slate-900
                'text-muted' => '#64748b', // Slate-500
                'border' => '#e2e8f0', // Slate-200
                'accent' => '#3b82f6', // Blue-500
                'card' => '#ffffff',
                'ring' => '#cbd5e1', // Slate-300
            ],
        ],
        'dark' => [
            'name' => 'Dark Pro',
            'class' => 'theme-dark',
            'colors' => [
                'primary' => '#60a5fa', // Blue-400
                'background' => '#0f172a', // Slate-900
                'surface' => '#1e293b', // Slate-800
                'text' => '#f8fafc', // Slate-50
                'text-muted' => '#94a3b8', // Slate-400
                'border' => '#1e293b', // Slate-800
                'accent' => '#38bdf8', // Sky-400
                'card' => '#1e293b', // Slate-800 + Glass effect in CSS
                'ring' => '#334155', // Slate-700
            ],
        ],
        'neon' => [
            'name' => 'Neon Cyber',
            'class' => 'theme-neon',
            'colors' => [
                'primary' => '#d946ef', // Fuchsia-500
                'background' => '#09090b', // Zinc-950
                'surface' => '#18181b', // Zinc-900
                'text' => '#e4e4e7', // Zinc-200
                'text-muted' => '#a1a1aa', // Zinc-400
                'border' => '#27272a', // Zinc-800
                'accent' => '#8b5cf6', // Violet-500
                'card' => '#18181b',
                'ring' => '#3f3f46', // Zinc-700
            ],
        ],
        'luxury' => [
            'name' => 'Luxury Gold',
            'class' => 'theme-luxury',
            'colors' => [
                'primary' => '#d4af37', // Gold
                'background' => '#0c0c0c', // Rich Black
                'surface' => '#161616', // Charcoal
                'text' => '#f5f5f4', // Stone-100
                'text-muted' => '#a8a29e', // Stone-400
                'border' => '#292524', // Stone-800
                'accent' => '#ca8a04', // Yellow-600
                'card' => '#1c1917', // Stone-900
                'ring' => '#44403c', // Stone-700
            ],
        ],
    ],
];
