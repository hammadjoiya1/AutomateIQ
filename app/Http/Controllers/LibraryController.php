<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\LibraryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $collections = Auth::user()->collections()->withCount('items')->get();
        return view('library.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        if ($collection->user_id !== Auth::id()) {
            abort(403);
        }
        $items = $collection->items()->latest()->paginate(20);
        return view('library.show', compact('collection', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->collections()->create([
            'name' => $request->name,
            'type' => 'custom',
        ]);

        return back()->with('success', 'Collection created.');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'tool_name' => 'nullable|string|max:255',
            'input' => 'nullable|string|max:2000',
        ]);

        $collection = Auth::user()->collections()->firstOrCreate([
            'name' => 'My Library',
        ], [
            'type' => 'default',
        ]);

        $item = $collection->items()->create([
            'content' => $request->content,
            'meta_data' => [
                'tool' => $request->tool_name,
                'input' => $request->input,
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'id' => $item->id,
        ]);
    }
}
