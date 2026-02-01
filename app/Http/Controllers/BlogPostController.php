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
        return view('blog.show', compact('post'));
    }
}
