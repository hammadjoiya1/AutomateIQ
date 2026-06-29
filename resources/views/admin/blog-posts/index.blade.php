<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ __('Manage Blog Posts') }}
            </h2>
            <a href="{{ route('admin.blog.generator') }}" class="btn btn-primary btn-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Generate New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 bg-surface border border-white/5 rounded-3xl">
                
                @if (session('success'))
                    <div class="bg-green-500/10 text-green-500 border border-green-500/20 p-4 rounded-xl mb-6 font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-xs uppercase tracking-wider text-text-muted">
                                <th class="pb-4 pl-4 font-semibold">Title</th>
                                <th class="pb-4 font-semibold">Status</th>
                                <th class="pb-4 font-semibold">Featured</th>
                                <th class="pb-4 font-semibold">Created</th>
                                <th class="pb-4 pr-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse ($posts as $post)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="py-4 pl-4 font-semibold text-text max-w-xs truncate">
                                        {{ $post->title }}
                                        <div class="text-xs text-text-muted mt-1 font-normal truncate">{{ $post->slug }}</div>
                                    </td>
                                    <td class="py-4">
                                        @if($post->is_published)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-500 border border-green-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        @if($post->is_featured)
                                            <span class="text-primary"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></span>
                                        @else
                                            <span class="text-text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-text-muted">
                                        {{ $post->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 pr-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-ghost hover:bg-white/10 text-text-muted hover:text-text" title="View Live">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-ghost hover:bg-white/10 text-primary" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-ghost hover:bg-red-500/10 text-red-500" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-text-muted">
                                        <p class="mb-4">No blog posts found.</p>
                                        <a href="{{ route('admin.blog.generator') }}" class="btn btn-primary btn-sm">Generate your first post</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
