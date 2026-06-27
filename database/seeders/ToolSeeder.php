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

        $tools = [
            [
                'name' => 'YouTube Hook Generator',
                'description' => 'Create viral hooks for your YouTube Shorts.',
                'category' => 'Video',
                'tool_type' => 'generator',
                'icon' => 'youtube',
                'prompt_template' => 'Create 7 high-retention hooks for {{input}}.\\nTarget audience: {{audience}}\\nPlatform: {{format}}\\nTone: {{tone}}\\nGoal: {{goal}}\\nReturn a numbered list. Each hook includes a 1-sentence payoff.',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'audience',
    'type' => 'text',
    'label' => 'Audience',
    'required' => false,
    'placeholder' => 'Creators, marketers, founders',
    'default' => '',
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'format',
    'type' => 'select',
    'label' => 'Platform',
    'required' => false,
    'placeholder' => '',
    'default' => 'YouTube Shorts',
    'options' => 'YouTube Shorts,YouTube Long-form,TikTok,Reels',
  ),
  2 => 
  array (
    'name' => 'tone',
    'type' => 'select',
    'label' => 'Tone',
    'required' => false,
    'placeholder' => '',
    'default' => 'Direct',
    'options' => 'Direct,Curious,Contrarian,Emotional',
  ),
  3 => 
  array (
    'name' => 'goal',
    'type' => 'select',
    'label' => 'Goal',
    'required' => false,
    'placeholder' => '',
    'default' => 'CTR',
    'options' => 'CTR,Watch time,Comments,Shares',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Viral Video Ideas Generator',
                'description' => 'Get unlimited ideas for viral videos.',
                'category' => 'Ideas',
                'tool_type' => 'generator',
                'icon' => 'lightbulb',
                'prompt_template' => 'Generate {{count}} viral video ideas for {{input}}.\\nAudience: {{audience}}\\nAngle: {{angle}}\\nSeries style: {{series_style}}\\nReturn a list with title + 1-sentence hook.',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'audience',
    'type' => 'text',
    'label' => 'Audience',
    'required' => false,
    'placeholder' => 'New creators, SaaS buyers',
    'default' => '',
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'angle',
    'type' => 'select',
    'label' => 'Content Angle',
    'required' => false,
    'placeholder' => '',
    'default' => 'Problem/Solution',
    'options' => 'Problem/Solution,Myth-busting,Story,Challenge,Comparison,Behind-the-scenes',
  ),
  2 => 
  array (
    'name' => 'series_style',
    'type' => 'select',
    'label' => 'Series Style',
    'required' => false,
    'placeholder' => '',
    'default' => 'Standalone',
    'options' => 'Standalone,Series,Daily',
  ),
  3 => 
  array (
    'name' => 'count',
    'type' => 'number',
    'label' => 'Idea Count',
    'required' => false,
    'placeholder' => '10',
    'default' => 10,
    'options' => '',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Script Generator (Short)',
                'description' => 'Write compelling scripts for 60s videos.',
                'category' => 'Video',
                'tool_type' => 'generator',
                'icon' => 'file-text',
                'prompt_template' => 'Write a short-form video script about {{input}}.\\nLength: {{length_seconds}} seconds\\nPlatform: {{platform}}\\nVoice: {{voice}}\\nCTA: {{cta}}\\nInclude: Hook, Body, CTA, Outro. Use natural spoken style.',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'length_seconds',
    'type' => 'number',
    'label' => 'Length (seconds)',
    'required' => false,
    'placeholder' => '60',
    'default' => 60,
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'platform',
    'type' => 'select',
    'label' => 'Platform',
    'required' => false,
    'placeholder' => '',
    'default' => 'YouTube Shorts',
    'options' => 'YouTube Shorts,TikTok,Reels',
  ),
  2 => 
  array (
    'name' => 'voice',
    'type' => 'select',
    'label' => 'Voice',
    'required' => false,
    'placeholder' => '',
    'default' => 'Narrator',
    'options' => 'Narrator,Presenter,Documentary',
  ),
  3 => 
  array (
    'name' => 'cta',
    'type' => 'text',
    'label' => 'Call To Action',
    'required' => false,
    'placeholder' => 'Subscribe for more',
    'default' => '',
    'options' => '',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Caption Generator',
                'description' => 'Generate engaging captions for Instagram & TikTok.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'instagram',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Hashtag Generator',
                'description' => 'Find trending hashtags for your niche.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'hash',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'SEO Title Generator',
                'description' => 'Optimize your video titles for search.',
                'category' => 'SEO',
                'tool_type' => 'optimizer',
                'icon' => 'search',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Blog Outline Generator',
                'description' => 'Structure your blog posts for success.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'align-left',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Product Description Generator',
                'description' => 'Sell more with persuasive product copy.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'shopping-bag',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Tweet Thread Generator',
                'description' => 'Turn ideas into viral Twitter threads.',
                'category' => 'Social Media',
                'tool_type' => 'generator',
                'icon' => 'twitter',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Motivational Quote Generator',
                'description' => 'Inspire your audience with daily quotes.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'sun',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Story Generator',
                'description' => 'Create family-friendly stories for your channel.',
                'category' => 'Writing',
                'tool_type' => 'generator',
                'icon' => 'book-open',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Prompt Builder Tool',
                'description' => 'Refine your AI prompts for better results.',
                'category' => 'Ideas',
                'tool_type' => 'optimizer',
                'icon' => 'terminal',
                'prompt_template' => NULL,
                'input_schema' => NULL,
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Repurpose: Twitter Thread',
                'description' => 'Turn a script into a viral thread.',
                'category' => 'Social Media',
                'tool_type' => 'repurpose',
                'icon' => 'twitter',
                'prompt_template' => 'Repurpose into a Twitter/X thread.\\nAudience: {{audience}}\\nGoal: {{goal}}\\nThread length: {{thread_length}}\\nUse numbered tweets under 280 chars.\\nContent:\\n{{input}}',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'audience',
    'type' => 'text',
    'label' => 'Audience',
    'required' => false,
    'placeholder' => 'Creators, growth marketers',
    'default' => '',
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'goal',
    'type' => 'select',
    'label' => 'Goal',
    'required' => false,
    'placeholder' => '',
    'default' => 'Engagement',
    'options' => 'Engagement,Followers,Clicks',
  ),
  2 => 
  array (
    'name' => 'thread_length',
    'type' => 'number',
    'label' => 'Thread Length',
    'required' => false,
    'placeholder' => '8',
    'default' => 8,
    'options' => '',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Repurpose: LinkedIn Post',
                'description' => 'Professional business takeaway from content.',
                'category' => 'Social Media',
                'tool_type' => 'repurpose',
                'icon' => 'linkedin',
                'prompt_template' => 'Repurpose the content into a LinkedIn post.\\nAudience: {{audience}}\\nGoal: {{goal}}\\nStructure: hook, insights, bullet takeaways, CTA.\\nContent:\\n{{input}}',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'audience',
    'type' => 'text',
    'label' => 'Audience',
    'required' => false,
    'placeholder' => 'Founders, marketers',
    'default' => '',
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'goal',
    'type' => 'select',
    'label' => 'Goal',
    'required' => false,
    'placeholder' => '',
    'default' => 'Authority',
    'options' => 'Leads,Engagement,Authority,Newsletter signups',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Repurpose: Newsletter',
                'description' => 'A TL;DR summary for email subscribers.',
                'category' => 'Writing',
                'tool_type' => 'repurpose',
                'icon' => 'mail',
                'prompt_template' => 'Repurpose into a newsletter edition.\\nAudience: {{audience}}\\nTone: {{tone}}\\nSections: subject line, intro, 3 key points, CTA.\\nContent:\\n{{input}}',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'audience',
    'type' => 'text',
    'label' => 'Audience',
    'required' => false,
    'placeholder' => 'Subscribers, founders',
    'default' => '',
    'options' => '',
  ),
  1 => 
  array (
    'name' => 'tone',
    'type' => 'select',
    'label' => 'Tone',
    'required' => false,
    'placeholder' => '',
    'default' => 'Professional',
    'options' => 'Professional,Friendly,Direct',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'AI Video Generator',
                'description' => 'Generate REAL .mp4 video files from text prompts.',
                'category' => 'Video',
                'tool_type' => 'video',
                'icon' => 'film',
                'prompt_template' => 'Generate a text-to-video prompt based on {{input}}.\\nStyle: {{visual_style}}. Camera: {{camera}}. Lighting: {{lighting}}. Aspect: {{aspect}}.\\nReturn only the prompt.',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'visual_style',
    'type' => 'select',
    'label' => 'Visual Style',
    'required' => false,
    'placeholder' => '',
    'default' => 'Cinematic',
    'options' => 'Cinematic,Minimal,Animated,Photoreal',
  ),
  1 => 
  array (
    'name' => 'camera',
    'type' => 'select',
    'label' => 'Camera',
    'required' => false,
    'placeholder' => '',
    'default' => 'Wide',
    'options' => 'Wide,Close-up,Drone,Handheld',
  ),
  2 => 
  array (
    'name' => 'lighting',
    'type' => 'select',
    'label' => 'Lighting',
    'required' => false,
    'placeholder' => '',
    'default' => 'Soft',
    'options' => 'Soft,High-contrast,Neon,Natural',
  ),
  3 => 
  array (
    'name' => 'aspect',
    'type' => 'select',
    'label' => 'Aspect Ratio',
    'required' => false,
    'placeholder' => '',
    'default' => '9:16',
    'options' => '16:9,9:16,1:1',
  ),
  4 => 
  array (
    'name' => 'quality',
    'type' => 'select',
    'label' => 'Quality',
    'required' => true,
    'placeholder' => '',
    'default' => 'hd',
    'options' => 'standard,hd,premium',
  ),
),
                'output_format' => 'text',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
            [
                'name' => 'Scene Splitter (Video Factory)',
                'description' => 'Turn a script into a visual production list (JSON).',
                'category' => 'Video',
                'tool_type' => 'splitter',
                'icon' => 'scissors',
                'prompt_template' => 'You are a film director AI. Break the script into 3-5 second scenes.\\nReturn a JSON array with scene_number, voiceover, visual_prompt.\\nSCRIPT:\\n{{input}}\\nStyle: {{visual_style}}. Camera: {{camera}}. Lighting: {{lighting}}.',
                'input_schema' => array (
  0 => 
  array (
    'name' => 'visual_style',
    'type' => 'select',
    'label' => 'Visual Style',
    'required' => false,
    'placeholder' => '',
    'default' => 'Cinematic',
    'options' => 'Cinematic,Minimal,Animated,Photoreal',
  ),
  1 => 
  array (
    'name' => 'camera',
    'type' => 'select',
    'label' => 'Camera',
    'required' => false,
    'placeholder' => '',
    'default' => 'Wide',
    'options' => 'Wide,Close-up,Drone,Handheld',
  ),
  2 => 
  array (
    'name' => 'lighting',
    'type' => 'select',
    'label' => 'Lighting',
    'required' => false,
    'placeholder' => '',
    'default' => 'Soft',
    'options' => 'Soft,High-contrast,Neon,Natural',
  ),
),
                'output_format' => 'json',
                'cost_credits' => null,
                'daily_budget_credits' => null,
            ],
        ];

        foreach ($tools as $t) {
            Tool::updateOrCreate(
                ['slug' => Str::slug($t['name'])],
                [
                    'name' => $t['name'],
                    'description' => $t['description'],
                    'category_id' => $catIds[$t['category']] ?? null,
                    'tool_type' => $t['tool_type'],
                    'icon' => $t['icon'],
                    'prompt_template' => $t['prompt_template'],
                    'input_schema' => $t['input_schema'],
                    'output_format' => $t['output_format'],
                    'cost_credits' => $t['cost_credits'],
                    'daily_budget_credits' => $t['daily_budget_credits'],
                    'is_featured' => rand(0, 1),
                    'status' => true,
                    'usage_limit' => 10,
                ]
            );
        }
    }
}
