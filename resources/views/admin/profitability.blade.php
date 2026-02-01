<x-admin-layout>
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-text">Profitability Dashboard</h1>
                <p class="text-text/60">30‑day revenue and cost estimates.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Estimated MRR</div>
                <div class="text-3xl font-bold text-text">${{ number_format($mrrCents / 100, 2) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Estimated Costs (30d)</div>
                <div class="text-3xl font-bold text-text">${{ number_format($costCents / 100, 2) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Overage Estimate</div>
                <div class="text-3xl font-bold text-text">${{ number_format($overageCents / 100, 2) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Active Plans</div>
                <div class="text-3xl font-bold text-text">{{ $proUsers + $teamUsers }}</div>
                <div class="text-xs text-text/60">Pro: {{ $proUsers }} · Team: {{ $teamUsers }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Tool Runs (30d)</div>
                <div class="text-2xl font-bold text-text">{{ number_format($toolRuns) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Video Generations (30d)</div>
                <div class="text-2xl font-bold text-text">{{ number_format($videoRuns) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Workflow Runs (30d)</div>
                <div class="text-2xl font-bold text-text">{{ number_format($workflowRuns) }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>
