<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Categories</h1>
        <div class="card p-4 border border-white/5 bg-surface/50">
             <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-2 items-center">
                @csrf
                <input type="text" name="name" placeholder="New Category Name" required class="bg-surface border border-border rounded-lg px-4 py-2 text-text text-sm">
                <input type="text" name="description" placeholder="Description (optional)" class="bg-surface border border-border rounded-lg px-4 py-2 text-text text-sm">
                <button type="submit" class="btn btn-sm btn-primary">Add Category</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="card p-6 border border-white/5 bg-surface hover:border-primary/50 transition-colors group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-text">{{ $category->name }}</h3>
                        <p class="text-xs text-text-muted">{{ $category->slug }}</p>
                    </div>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-text-muted hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>
                
                <p class="text-sm text-text-muted mb-4 h-10 line-clamp-2">{{ $category->description ?? 'No description.' }}</p>
                
                <div class="flex items-center justify-between text-xs font-semibold">
                    <span class="bg-primary/10 text-primary px-2 py-1 rounded-full">{{ $category->tools_count }} Tools</span>
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center gap-1">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" class="w-24 bg-transparent border-b border-border text-text text-right focus:border-primary text-xs p-0">
                        <button class="text-text-muted hover:text-primary">Update</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
