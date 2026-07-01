<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function pricing()
    {
        return view('pages.pricing');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function submitDemo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'volume' => 'required|string|max:255',
            'message' => 'nullable|string'
        ]);

        // Here we could save to DB or send an email. For now we just log it and redirect with success.
        \Log::info('Enterprise Demo Request:', $validated);

        return redirect()->back()->with('success', 'Thank you! Your enterprise demo request has been received. Our team will contact you shortly to schedule a time.');
    }
}
