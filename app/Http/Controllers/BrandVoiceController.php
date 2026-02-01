<?php

namespace App\Http\Controllers;

use App\Models\BrandVoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandVoiceController extends Controller
{
    public function index()
    {
        $voices = Auth::user()->brandVoices()->latest()->get();
        return view('brand-voices.index', compact('voices'));
    }

    public function create()
    {
        return view('brand-voices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prompt' => 'required|string',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            // Unset other defaults
            Auth::user()->brandVoices()->update(['is_default' => false]);
        }

        Auth::user()->brandVoices()->create($validated);

        return redirect()->route('brand-voices.index')->with('success', 'Voice created successfully!');
    }

    public function edit(BrandVoice $brandVoice)
    {
        if ($brandVoice->user_id !== Auth::id())
            abort(403);
        return view('brand-voices.edit', compact('brandVoice'));
    }

    public function update(Request $request, BrandVoice $brandVoice)
    {
        if ($brandVoice->user_id !== Auth::id())
            abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prompt' => 'required|string',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Auth::user()->brandVoices()->where('id', '!=', $brandVoice->id)->update(['is_default' => false]);
        }

        $brandVoice->update($validated);

        return redirect()->route('brand-voices.index')->with('success', 'Voice updated successfully!');
    }

    public function destroy(BrandVoice $brandVoice)
    {
        if ($brandVoice->user_id !== Auth::id())
            abort(403);
        $brandVoice->delete();
        return back()->with('success', 'Voice deleted.');
    }
}
