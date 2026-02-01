<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminToolController extends Controller
{
    public function index()
    {
        $tools = Tool::latest()->paginate(20);
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::where('type', 'tool')->get();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'tool_type' => 'required',
        ]);

        Tool::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'tool_type' => $request->tool_type,
            'category_id' => $request->category_id,
            'status' => true,
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Tool created');
    }
}
