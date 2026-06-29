<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogGeneratorController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        return view('admin.blog-generator');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'tone' => 'nullable|string|max:100',
            'keywords' => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->openAIService->generateBlogPost([
                'topic' => $request->topic,
                'tone' => $request->tone ?? 'professional',
                'keywords' => $request->keywords ?? '',
            ]);

            if ($result['success']) {
                return back()->with('success', 'Blog post generated successfully!')
                             ->with('generated_content', $result['content'])
                             ->with('topic', $request->topic);
            }

            return back()->with('error', 'Failed to generate blog post.')->withInput();
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        try {
            BlogPost::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . uniqid(),
                'content' => $request->content,
                'is_published' => true,
            ]);

            return redirect()->route('admin.blog.generator')->with('success', 'Blog post published successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to publish post: ' . $e->getMessage())->withInput();
        }
    }
}
