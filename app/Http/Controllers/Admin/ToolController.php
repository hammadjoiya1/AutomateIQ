<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with('category')->orderBy('name')->paginate(20);
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'prompt_template' => 'nullable|string',
            'input_schema' => 'nullable|json',
            'output_format' => 'required|string',
            'usage_limit' => 'integer|min:0',
            'is_featured' => 'boolean',
            'cost_credits' => 'nullable|integer|min:0',
            'daily_budget_credits' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $validated['status'] = true;

        if (!empty($validated['input_schema'])) {
            $validated['input_schema'] = json_decode($validated['input_schema'], true);
        }

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('tool-icons', 'public');
            $validated['icon'] = '/storage/' . $path;
        }

        $tool = Tool::create($validated);

        $this->syncTags($tool, $request->input('tags'));

        return redirect()->route('admin.tools.index')->with('success', 'Tool created successfully.');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'prompt_template' => 'nullable|string',
            'input_schema' => 'nullable|string', // JSON string from editor
            'output_format' => 'required|string',
            'usage_limit' => 'integer|min:0',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'cost_credits' => 'nullable|integer|min:0',
            'daily_budget_credits' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
        ]);

        $validated['input_schema'] = json_decode($validated['input_schema'], true); // Ensure array

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('tool-icons', 'public');
            $validated['icon'] = '/storage/' . $path;
        }

        $tool->update($validated);

        $this->syncTags($tool, $request->input('tags'));

        return redirect()->route('admin.tools.index')->with('success', 'Tool updated successfully.');
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return back()->with('success', 'Tool deleted.');
    }

    public function reorder(Request $request)
    {
        // Implementation for drag-and-drop reordering would go here
        return response()->json(['status' => 'success']);
    }

    protected function syncTags(Tool $tool, ?string $tagsInput): void
    {
        $tagNames = collect(explode(',', (string) $tagsInput))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique();

        if ($tagNames->isEmpty()) {
            $tool->tags()->detach();
            return;
        }

        $tagIds = $tagNames->map(function ($name) {
            $slug = Str::slug($name);
            $tag = Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
            return $tag->id;
        });

        $tool->tags()->sync($tagIds->all());
    }
}
