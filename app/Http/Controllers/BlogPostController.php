<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)->latest()->paginate(9);
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function($query) use ($post) {
                if ($post->category_id) {
                    $query->where('category_id', $post->category_id);
                }
            })
            ->latest()
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $excludeIds = $relatedPosts->pluck('id')->push($post->id)->all();
            $extraPosts = BlogPost::where('is_published', true)
                ->whereNotIn('id', $excludeIds)
                ->latest()
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($extraPosts);
        }

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
