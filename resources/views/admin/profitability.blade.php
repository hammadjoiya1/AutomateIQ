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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Credits Charged (30d)</div>
                <div class="text-2xl font-bold text-text">{{ number_format($creditsCharged) }}</div>
                <div class="text-xs text-text/60 mt-1">Active paid users: {{ number_format($activePaidUsers) }}</div>
            </div>
            <div class="card p-6 bg-surface/50 border border-white/5">
                <div class="text-sm text-text/60">Avg Cost / Paid User (30d)</div>
                <div class="text-2xl font-bold text-text">${{ number_format($avgCostPerPaidUserCents / 100, 2) }}</div>
                <div class="text-xs text-text/60 mt-1">Recommended credits/user: {{ number_format($recommendedCredits) }}</div>
            </div>
        </div>

        <div class="card p-6 bg-surface/50 border border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-text">Pricing & Credit Coverage</h3>
                    <p class="text-text/60 text-sm">Based on last 30 days of usage.</p>
                </div>
                <form action="{{ route('admin.profitability.apply') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="apply_to" class="bg-surface border border-border rounded-lg px-3 py-2 text-xs text-text">
                        <option value="both">Apply to Pro + Team</option>
                        <option value="pro">Apply to Pro</option>
                        <option value="team">Apply to Team</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Apply Recommended</button>
                </form>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-text/60 uppercase border-b border-white/5">
                        <tr>
                            <th class="py-2">Plan</th>
                            <th class="py-2">Monthly Credits</th>
                            <th class="py-2">Revenue / User</th>
                            <th class="py-2">Implied Margin</th>
                            <th class="py-2">Recommended Credits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr>
                            <td class="py-3 font-semibold text-text">Pro</td>
                            <td class="py-3">{{ number_format($proCredits) }}</td>
                            <td class="py-3">${{ number_format($proRevenueCents / 100, 2) }}</td>
                            <td class="py-3">
                                @if($proMargin !== null)
                                    {{ number_format($proMargin * 100, 1) }}%
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3">{{ number_format($recommendedCredits) }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 font-semibold text-text">Team</td>
                            <td class="py-3">{{ number_format($teamCredits) }}</td>
                            <td class="py-3">${{ number_format($teamRevenueCents / 100, 2) }}</td>
                            <td class="py-3">
                                @if($teamMargin !== null)
                                    {{ number_format($teamMargin * 100, 1) }}%
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3">{{ number_format($recommendedCredits) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
