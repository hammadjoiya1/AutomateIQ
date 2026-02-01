<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Contact Messages</h1>
    </div>

    <div class="space-y-4">
        @forelse($messages as $message)
            <div
                class="card p-6 border border-white/5 bg-surface {{ $message->is_read ? 'opacity-75' : 'border-primary/20' }}">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-bold text-lg text-text">{{ $message->subject ?? 'No Subject' }}</h3>
                        <p class="text-sm text-text-muted">From: <span
                                class="text-text font-medium">{{ $message->name }}</span> ({{ $message->email }})</p>
                    </div>
                    <span class="text-xs text-text-muted">{{ $message->created_at->diffForHumans() }}</span>
                </div>

                <div class="bg-bg-2 p-4 rounded-lg text-sm text-text mb-4 whitespace-pre-wrap">{{ $message->message }}</div>

                <div class="flex justify-end gap-3">
                    @if(!$message->is_read)
                        <form action="{{ route('admin.messages.read', $message) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">Mark as Read</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST"
                        onsubmit="return confirm('Delete message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-medium">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-text-muted">
                No messages found.
            </div>
        @endforelse

        <div class="pt-4">
            {{ $messages->links() }}
        </div>
    </div>
</x-admin-layout>