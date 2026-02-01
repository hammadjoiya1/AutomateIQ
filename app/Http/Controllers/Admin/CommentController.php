<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('post', 'user')->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function pending()
    {
        $comments = Comment::with('post', 'user')->where('is_approved', false)->latest()->paginate(20);
        return view('admin.comments.index', compact('comments'));
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update([
            'is_approved' => $request->has('is_approved'),
        ]);

        return back()->with('success', 'Comment status updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }
}
