<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Activity Logs</h1>
    </div>

    <div class="card overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Tool</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($runs as $run)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4 text-text font-medium">{{ $run->user->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $run->tool->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold {{ $run->status === 'completed' ? 'bg-green-500/10 text-green-500' : ($run->status === 'failed' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500') }}">
                                    {{ ucfirst($run->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-muted">
                                {{ $run->duration_ms ? number_format($run->duration_ms / 1000, 2) . 's' : '—' }}
                            </td>
                            <td class="px-6 py-4 text-text-muted">{{ $run->created_at->format('M d, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.logs.show', $run) }}" class="btn btn-sm btn-ghost">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-text-muted">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">
            {{ $runs->links() }}
        </div>
    </div>
</x-admin-layout>