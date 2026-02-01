<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting; // Assuming Setting model exists
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \DB::table('settings')->pluck('value', 'key'); // Simple key-value store
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Simple key-value update
        foreach ($request->except('_token') as $key => $value) {
            \DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Settings updated.');
    }
}
