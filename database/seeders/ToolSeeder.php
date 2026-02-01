<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Str;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            'Video' => 'tool',
            'Writing' => 'tool',
            'Social Media' => 'tool',
            'SEO' => 'tool',
            'Ideas' => 'tool',
        ];

        $catIds = [];
        foreach ($categories as $name => $type) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => $type]
            );
            $catIds[$name] = $cat->id;
        }

        // Tools
        $tools = [
            // ... (keep existing array content, I will just update the loop below)
        ];

        // I need to be careful not to delete the tools array if I'm just replacing the loop logic. 
        // Actually, I should just replace the logic blocks.


        // Tools
        $tools = [
            [
                'name' => 'YouTube Hook Generator',
                'description' => 'Create viral hooks for your YouTube Shorts.',
                'category' => 'Video',
                'tool_type' => 'generator',
                'icon' => 'youtube',
            ],
            [
                'name' => 'Viral Video Ideas Generator',
                'description' => 'Get unlimited ideas for viral videos.',
                'category' => 'Ideas',
                'tool_type' => 'generator',
                'icon' => 'lightbulb',
            ],
            [
                'name' => 'Script Generator (Short)',
                'description' => 'Write compelling scripts for 60s videos.',
                'category' => 'Video',
                'tool_type' => 'generator',
                'icon' => 'file-text',
            ],
            [
                'name' => 'Caption Generator',
                'description' => 'Generate engaging captions for Instagram & TikTok.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'instagram',
            ],
            [
                'name' => 'Hashtag Generator',
                'description' => 'Find trending hashtags for your niche.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'hash',
            ],
            [
                'name' => 'SEO Title Generator',
                'description' => 'Optimize your video titles for search.',
                'category' => 'SEO',
                'tool_type' => 'optimizer',
                'icon' => 'search',
            ],
            [
                'name' => 'Blog Outline Generator',
                'description' => 'Structure your blog posts for success.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'align-left',
            ],
            [
                'name' => 'Product Description Generator',
                'description' => 'Sell more with persuasive product copy.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'shopping-bag',
            ],
            [
                'name' => 'Tweet Thread Generator',
                'description' => 'Turn ideas into viral Twitter threads.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'twitter',
            ],
            [
                'name' => 'Motivational Quote Generator',
                'description' => 'Inspire your audience with daily quotes.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'sun',
            ],
            [
                'name' => 'Story Generator',
                'description' => 'Create family-friendly stories for your channel.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'book-open',
            ],
            [
                'name' => 'Prompt Builder Tool',
                'description' => 'Refine your AI prompts for better results.',
                'category' => 'Ideas',
                'tool_type' => 'optimizer',
                'icon' => 'terminal',
            ],
            // --- REPURPOSING TOOLS (The Multiplier Engine) ---
            [
                'name' => 'Repurpose: Twitter Thread',
                'description' => 'Turn a script into a viral thread.',
                'category' => 'Social Media',
                'tool_type' => 'repurpose',
                'icon' => 'twitter',
            ],
            [
                'name' => 'Repurpose: LinkedIn Post',
                'description' => 'Professional business takeaway from content.',
                'category' => 'Social Media',
                'tool_type' => 'repurpose',
                'icon' => 'linkedin',
            ],
            [
                'name' => 'Repurpose: Newsletter',
                'description' => 'A TL;DR summary for email subscribers.',
                'category' => 'Writing',
                'tool_type' => 'repurpose',
                'icon' => 'mail',
            ],
            // --- HOLY GRAIL: AI VIDEO ---
            [
                'name' => 'AI Video Generator',
                'description' => 'Generate REAL .mp4 video files from text prompts.',
                'category' => 'Video',
                'tool_type' => 'video',
                'icon' => 'film',
            ],
            // --- VIDEO FACTORY ---
            [
                'name' => 'Scene Splitter (Video Factory)',
                'description' => 'Turn a script into a visual production list (JSON).',
                'category' => 'Video',
                'tool_type' => 'splitter', // Special type
                'icon' => 'scissors',
            ],
        ];

        foreach ($tools as $tool) {
            Tool::updateOrCreate(
                ['slug' => Str::slug($tool['name'])],
                [
                    'name' => $tool['name'],
                    'description' => $tool['description'],
                    'category_id' => $catIds[$tool['category']] ?? null,
                    'tool_type' => $tool['tool_type'],
                    'is_featured' => rand(0, 1),
                    'status' => true,
                    'usage_limit' => 10,
                    'icon' => $tool['icon'],
                ]
            );
        }
    }
}
