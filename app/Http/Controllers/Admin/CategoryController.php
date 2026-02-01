<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tools')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $request->description,
            'type' => 'tool'
        ]);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category->update($validated);
        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
