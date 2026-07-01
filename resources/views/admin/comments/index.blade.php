<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ request()->routeIs('admin.comments.pending') ? __('Pending Comments') : __('All Comments') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.comments.index') }}" class="px-3 py-1 text-sm rounded-md {{ !request()->routeIs('admin.comments.pending') ? 'bg-primary text-white' : 'bg-primary/10 text-primary hover:bg-primary/20' }}">
                    All
                </a>
                <a href="{{ route('admin.comments.pending') }}" class="px-3 py-1 text-sm rounded-md {{ request()->routeIs('admin.comments.pending') ? 'bg-primary text-white' : 'bg-primary/10 text-primary hover:bg-primary/20' }}">
                    Pending
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    <table class="min-w-full divide-y divide-primary/20">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">Post</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/10">
                            @forelse($comments as $comment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="font-bold">{{ $comment->user ? $comment->user->name : $comment->name }}</div>
                                        <div class="text-xs text-text/60">{{ $comment->user ? $comment->user->email : $comment->email }}</div>
                                        <div class="text-xs text-text/40">{{ $comment->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm max-w-xs truncate" title="{{ $comment->content }}">
                                        {{ Str::limit($comment->content, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('blog.show', $comment->post) }}" target="_blank" class="text-primary hover:underline">
                                            {{ Str::limit($comment->post->title, 20) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $comment->is_approved ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                                            {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            @if(!$comment->is_approved)
                                                <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="is_approved" value="1">
                                                    <button type="submit" class="text-success hover:text-success" title="Approve">✓</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Leaving is_approved empty or handling logic to unapprove if needed, but simplistic update usually toggles or sets. My controller checked has('is_approved'). -->
                                                    <!-- If I do NOT send is_approved, the controller sets it to false. -->
                                                    <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Reject/Unapprove">✕</button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                                x-data @submit.prevent="$dispatch('confirm', { message: 'Delete this comment?', form: $el })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger hover:text-danger" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-text/50">No comments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
