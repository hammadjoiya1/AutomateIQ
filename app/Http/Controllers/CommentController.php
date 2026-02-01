<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, BlogPost $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'email' => Auth::check() ? 'nullable' : 'required|email|max:255',
        ]);

        /** @var \App\Models\User|null $authenticatedUser */
        $authenticatedUser = Auth::user();

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'name' => $authenticatedUser ? $authenticatedUser->name : $request->name,
            'email' => $authenticatedUser ? $authenticatedUser->email : $request->email,
            'content' => $request->input('content'),
            'is_approved' => false, // Default to pending
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
    public function index()
    {
        $comments = Auth::user()->comments()->with('post')->latest()->paginate(20);
        return view('dashboard.comments', compact('comments'));
    }
}
