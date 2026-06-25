<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolPreset;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NichePackSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'admin@faceless.ai')->first()
            ?? User::where('role', 'admin')->first()
            ?? User::first();

        if (!$owner) {
            $owner = User::create([
                'name' => 'Pack Publisher',
                'email' => 'packs@automateiq.local',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'plan' => 'pro',
                'theme' => 'midnight-purple',
            ]);
        }

        $tools = Tool::whereIn('slug', [
            'youtube-hook-generator',
            'script-generator-short',
            'scene-splitter-video-factory',
        ])->get()->keyBy('slug');

        $presets = [
            // Fitness
            [
                'tool' => 'youtube-hook-generator',
                'name' => 'Fitness — High‑Energy Hooks',
                'input' => 'Generate 10 high‑energy hooks for a 30‑second workout video aimed at busy professionals who want quick fat‑loss results. Keep them punchy and action‑oriented.',
                'tone' => 'Direct',
                'length' => 'Short',
                'format' => 'Bullet Points',
            ],
            [
                'tool' => 'script-generator-short',
                'name' => 'Fitness — Transformation Script',
                'input' => 'Write a 60‑second transformation script about losing belly fat with a 10‑minute home routine. Include a hook, 3 simple steps, and a motivating CTA.',
                'tone' => 'Motivational',
                'length' => 'Medium',
                'format' => 'Paragraph',
            ],
            [
                'tool' => 'scene-splitter-video-factory',
                'name' => 'Fitness — Workout Scene List',
                'input' => "Split this script into visual scenes with concise shot ideas: 'No time to work out? Try this 10‑minute routine. Step 1: 30 seconds of jumping jacks. Step 2: 30 seconds of push‑ups. Step 3: 30 seconds of squats. Repeat 3 rounds. You’ll feel the burn fast. Save this and start today.'",
                'tone' => 'Professional',
                'length' => 'Short',
                'format' => 'JSON',
            ],

            // Finance
            [
                'tool' => 'youtube-hook-generator',
                'name' => 'Finance — Clarity Hooks',
                'input' => 'Create 10 clarity hooks that debunk common investing myths for beginners. Make them curiosity‑driven but simple.',
                'tone' => 'Professional',
                'length' => 'Short',
                'format' => 'Bullet Points',
            ],
            [
                'tool' => 'script-generator-short',
                'name' => 'Finance — Myth‑Busting Script',
                'input' => 'Write a 60‑second myth‑busting script about credit card interest and how to avoid paying it. Include a hook, a simple explanation, and a CTA.',
                'tone' => 'Friendly',
                'length' => 'Medium',
                'format' => 'Paragraph',
            ],
            [
                'tool' => 'scene-splitter-video-factory',
                'name' => 'Finance — Visual Breakdown Scenes',
                'input' => "Split this script into visual scenes with concise shot ideas: 'Most people think minimum payments are fine. But interest compounds daily. Here’s how to avoid it: pay your statement balance, set autopay, and track due dates. You keep your score high and pay $0 in interest.'",
                'tone' => 'Professional',
                'length' => 'Short',
                'format' => 'JSON',
            ],

            // SaaS
            [
                'tool' => 'youtube-hook-generator',
                'name' => 'SaaS — Problem/Solution Hooks',
                'input' => 'Create 10 problem/solution hooks for a SaaS that automates invoice follow‑ups for freelancers. Focus on time savings and faster payments.',
                'tone' => 'Direct',
                'length' => 'Short',
                'format' => 'Bullet Points',
            ],
            [
                'tool' => 'script-generator-short',
                'name' => 'SaaS — Demo Script',
                'input' => 'Write a 60‑second demo‑style script for a SaaS that auto‑categorizes expenses. Highlight the problem, the feature, and the outcome.',
                'tone' => 'Professional',
                'length' => 'Medium',
                'format' => 'Paragraph',
            ],
            [
                'tool' => 'scene-splitter-video-factory',
                'name' => 'SaaS — Feature Scene Prompts',
                'input' => "Split this script into visual scenes with concise shot ideas: 'Tracking expenses manually wastes hours. Our app scans receipts, auto‑categorizes spend, and generates reports in seconds. You see cash flow clearly and close your books faster.'",
                'tone' => 'Professional',
                'length' => 'Short',
                'format' => 'JSON',
            ],
        ];

        foreach ($presets as $preset) {
            $tool = $tools->get($preset['tool']);
            if (!$tool) {
                continue;
            }

            ToolPreset::updateOrCreate(
                [
                    'tool_id' => $tool->id,
                    'user_id' => $owner->id,
                    'name' => $preset['name'],
                ],
                [
                    'input_data' => [
                        'input' => $preset['input'],
                        'tone' => $preset['tone'],
                        'length' => $preset['length'],
                        'format' => $preset['format'],
                    ],
                    'visibility' => 'public',
                ]
            );
        }
    }
}
