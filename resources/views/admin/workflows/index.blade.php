<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Workflow Management</h1>
    </div>

    <div class="card overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">Workflow Name</th>
                        <th class="px-6 py-4">Creator</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($workflows as $workflow)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-text">{{ $workflow->name }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $workflow->user->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold {{ $workflow->active ? 'bg-green-500/10 text-green-500' : 'bg-gray-500/10 text-gray-500' }}">
                                    {{ $workflow->active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-muted">{{ $workflow->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.workflows.destroy', $workflow) }}" method="POST"
                                    onsubmit="return confirm('Delete this workflow?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-400 text-xs font-bold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-muted">No workflows found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">
            {{ $workflows->links() }}
        </div>
    </div>
</x-admin-layout>