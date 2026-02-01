<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}
