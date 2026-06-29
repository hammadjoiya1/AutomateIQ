<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);
        return view('admin.blog-posts.index', compact('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.edit', compact('blogPost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
            'is_featured' => $request->has('is_featured'),
        ];

        if ($request->hasFile('featured_image')) {
            if ($blogPost->featured_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($blogPost->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog_images', 'public');
        }

        $blogPost->update($data);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();
        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted successfully.');
    }
}
