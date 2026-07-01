<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-text leading-tight flex items-center gap-2">
                <a href="{{ route('admin.blog-posts.index') }}" class="text-text-muted hover:text-text transition-colors">Posts</a>
                <span class="text-white/20">/</span>
                Edit Post
            </h2>
            <a href="{{ route('blog.show', $blogPost->slug) }}" target="_blank" class="btn btn-sm btn-ghost text-primary border border-primary/20 hover:bg-primary/10 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                View Live
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8 bg-surface border border-border rounded-3xl">
                
                @if ($errors->any())
                    <div class="bg-danger/10 text-danger border border-danger/20 p-4 rounded-xl mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">Post Title</label>
                            <input type="text" name="title" required value="{{ old('title', $blogPost->title) }}"
                                class="w-full rounded-xl border border-border bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3 px-4 transition-all">
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">URL Slug</label>
                            <input type="text" name="slug" required value="{{ old('slug', $blogPost->slug) }}"
                                class="w-full rounded-xl border border-border bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3 px-4 transition-all font-mono text-sm">
                            <p class="text-xs text-text-muted mt-2">Example: {{ url('/blog/') }}/<span class="text-primary">{{ $blogPost->slug }}</span></p>
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">Featured Image</label>
                            @if($blogPost->featured_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $blogPost->featured_image) }}" alt="Current featured image" class="h-32 rounded-lg object-cover border border-border">
                                </div>
                            @endif
                            <input type="file" name="featured_image" accept="image/*"
                                class="w-full rounded-xl border border-border bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-2 px-4 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                        </div>

                        <!-- Content -->
                        <div>
                            <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2 flex items-center justify-between">
                                Markdown Content
                            </label>
                            <textarea name="content" required rows="20"
                                class="w-full rounded-xl border border-border bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3 px-4 transition-all font-mono text-sm no-scrollbar">{{ old('content', $blogPost->content) }}</textarea>
                        </div>

                        <!-- Toggles -->
                        <div class="flex items-center gap-8 py-4 border-t border-border">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="is_published" class="peer sr-only" value="1" {{ old('is_published', $blogPost->is_published) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-background border border-border rounded-full peer peer-checked:bg-primary peer-checked:border-primary transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-surface rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                                </div>
                                <span class="text-sm font-semibold text-text group-hover:text-primary transition-colors">Published Status</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="is_featured" class="peer sr-only" value="1" {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-background border border-border rounded-full peer peer-checked:bg-primary peer-checked:border-primary transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-surface rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                                </div>
                                <span class="text-sm font-semibold text-text group-hover:text-primary transition-colors">Featured Post</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-border">
                            <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-ghost text-text-muted hover:text-text">Cancel</a>
                            <button type="submit" class="btn btn-primary shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>
