<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ThemeController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'theme' => 'required|string',
        ]);

        $themeSlug = $request->input('theme');
        $allThemes = config('themes.themes');

        if (!array_key_exists($themeSlug, $allThemes)) {
            return back()->with('error', 'Invalid theme selected.');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $user->theme = $themeSlug;
            $user->save();
        }

        // Always set cookie as fallback/guest
        Cookie::queue('theme', $themeSlug, 60 * 24 * 365); // 1 year

        return back();
    }
}
