<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-display font-bold text-text">Admin Audit Logs</h1>
            <p class="text-text-muted">Track admin changes across the system.</p>
        </div>
    </div>

    <div class="card overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">Admin</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Path</th>
                        <th class="px-6 py-4">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4 text-text font-medium">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $log->action }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                    {{ $log->method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-muted">/{{ $log->path }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-muted">No audit logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</x-admin-layout>
