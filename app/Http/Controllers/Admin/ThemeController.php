<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::all();
        return view('admin.themes.index', compact('themes'));
    }

    public function activate(Theme $theme)
    {
        Theme::query()->update(['is_default' => false]);
        $theme->update(['is_default' => true]);
        return back()->with('success', 'Default theme updated.');
    }
}
