<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('My Comments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    <table class="min-w-full divide-y divide-primary/20">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                    Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                    Post</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/10">
                            @forelse($comments as $comment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text/70">
                                        {{ $comment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm max-w-xs truncate" title="{{ $comment->content }}">
                                        {{ Str::limit($comment->content, 60) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('blog.show', $comment->post) }}" target="_blank"
                                            class="text-primary hover:underline">
                                            {{ Str::limit($comment->post->title, 20) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $comment->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-text/50">You haven't posted any
                                        comments yet.</td>
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
</x-app-layout>