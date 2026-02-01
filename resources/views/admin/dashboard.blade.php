<x-admin-layout>
    <div class="space-y-10 animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking-widest text-text-muted">Admin Control Panel</p>
                <h1 class="text-3xl font-display font-bold text-text mt-2">Overview</h1>
                <p class="text-text-muted mt-1">Monitor usage, health, and recent activity at a glance.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Users</a>
                <a href="{{ route('admin.tools.index') }}" class="btn btn-ghost">Tools</a>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-ghost">Logs</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Users -->
            <div class="card p-6 border border-white/5 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-primary/10 rounded-xl text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text mb-1">{{ $stats['total_users'] }}</div>
                <div class="text-sm text-text-muted">Total Users</div>
            </div>

            <!-- Active Tools -->
            <div class="card p-6 border border-white/5 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-accent/10 rounded-xl text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text mb-1">{{ $stats['total_tools'] }}</div>
                <div class="text-sm text-text-muted">Active Tools</div>
            </div>

            <!-- Total Run -->
            <div class="card p-6 border border-white/5 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text mb-1">{{ $stats['total_runs'] }}</div>
                <div class="text-sm text-text-muted">Total Generations</div>
            </div>

            <!-- Failed Jobs (Health) -->
            <div class="card p-6 border border-red-500/10 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-500/10 rounded-xl text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text mb-1">{{ $stats['failed_jobs'] ?? 0 }}</div>
                <div class="text-sm text-text-muted">Failed Jobs</div>
            </div>

            <!-- Queue Depth -->
            <div class="card p-6 border border-yellow-500/10 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-500/10 rounded-xl text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text mb-1">{{ $stats['pending_jobs'] ?? 0 }}</div>
                <div class="text-sm text-text-muted">Jobs in Queue</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 card p-6 bg-surface/50">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display font-semibold text-lg text-text">Recent Activity</h3>
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-ghost hover:bg-primary/5">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-text-muted uppercase border-b border-primary/10">
                            <tr>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Action</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary/5">
                            @forelse($stats['recent_activity'] ?? [] as $activity)
                                <tr class="hover:bg-primary/2 transition-colors">
                                    <td class="px-6 py-4 flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
                                            {{ substr($activity->user->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-text">{{ $activity->user->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-text-muted">Generated Video</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-bold {{ $activity->status === 'completed' ? 'bg-green-500/10 text-green-500' : ($activity->status === 'failed' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500 animate-pulse') }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-text-muted">{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-text-muted">No recent activity.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-6 bg-surface/50">
                    <h3 class="font-display font-semibold text-lg text-text mb-4">System Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-500/10 border border-green-500/20">
                            <span class="text-sm font-medium text-green-500">API Connection</span>
                            <span class="text-xs font-bold bg-green-500 text-white px-2 py-0.5 rounded-full">Active</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-surface border border-border">
                            <span class="text-sm font-medium text-text-muted">Database</span>
                            <span class="text-xs font-bold text-green-500">Healthy</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-surface border border-border">
                            <span class="text-sm font-medium text-text-muted">Queue Worker</span>
                            <span class="text-xs font-bold text-green-500">Running</span>
                        </div>
                    </div>
                </div>

                <div class="card p-6 bg-surface/50">
                    <h3 class="font-display font-semibold text-lg text-text mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.tools.create') }}" class="btn btn-sm btn-primary w-full justify-center">Add Tool</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary w-full justify-center">Manage Users</a>
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-ghost w-full justify-center">Support Messages</a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-ghost w-full justify-center">Audit Logs</a>
                    </div>
                </div>
            </div>
        </div>
</x-admin-layout>