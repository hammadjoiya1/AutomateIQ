<x-admin-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-display font-bold text-text">Tool Analytics</h1>
            <p class="text-text-muted">Success rate, cost efficiency, and paid conversion by tool.</p>
        </div>
        <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Manage Tools</a>
    </div>

    <div class="card overflow-hidden border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">Tool</th>
                        <th class="px-6 py-4">Runs</th>
                        <th class="px-6 py-4">Success Rate</th>
                        <th class="px-6 py-4">Avg Tokens</th>
                        <th class="px-6 py-4">Avg Cost (Credits)</th>
                        <th class="px-6 py-4">Conversion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($tools as $row)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4 text-text font-medium">{{ $row['tool']->name }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $row['total_runs'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-success/10 text-success">
                                    {{ $row['success_rate'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-muted">{{ $row['avg_tokens'] }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $row['avg_cost_credits'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                    {{ $row['paid_share'] }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-text-muted">No tool analytics available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
