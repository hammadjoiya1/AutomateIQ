<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        ContactMessage::create($request->all());

        return back()->with('success', 'Message sent! We will get back to you shortly.');
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'source' => 'nullable|string|max:50',
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => $data['email'],
            'source' => $data['source'] ?? null,
            'subscribed_at' => now(),
        ]);

        Mail::raw("Thanks for joining AutomateIQ! Your free trial includes limited credits so you can test the tools right away. Upgrade anytime to unlock higher limits.", function ($message) use ($subscriber) {
            $message->to($subscriber->email)
                ->subject('Welcome to AutomateIQ');
        });

        return back()->with('success', 'Thanks for subscribing! Check your email for next steps.');
    }

    // Admin Methods
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.contacts.index', compact('messages'));
    }
}
