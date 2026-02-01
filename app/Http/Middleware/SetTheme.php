<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultTheme = \App\Models\Setting::get('default_theme', config('themes.default', 'ocean-breeze'));
        $activeThemeSlug = $defaultTheme;

        if (Auth::check()) {
            $activeThemeSlug = Auth::user()->theme ?? $defaultTheme;
        } elseif ($request->hasCookie('theme')) {
            $activeThemeSlug = $request->cookie('theme');
        }

        // Validate theme exists
        $allThemes = config('themes.themes');
        if (!array_key_exists($activeThemeSlug, $allThemes)) {
            $activeThemeSlug = $defaultTheme;
        }

        $activeTheme = $allThemes[$activeThemeSlug];
        $activeTheme['slug'] = $activeThemeSlug;

        // Share globally with all views
        View::share('activeTheme', $activeTheme);
        View::share('allThemes', $allThemes);

        return $next($request);
    }
}
