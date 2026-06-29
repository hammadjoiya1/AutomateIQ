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
                 <div class="text-xs text-text/60 mt-1">Pro: ${{ number_format($proAvgCostCents / 100, 2) }} · Team: ${{ number_format($teamAvgCostCents / 100, 2) }}</div>
             </div>
         </div>
         <!-- Historical Revenue vs Cost Trend Chart -->
         <div class="card p-6 bg-surface/50 border border-white/5">
             <h3 class="text-lg font-bold text-text mb-4">Historical Revenue vs Cost (Last 6 Months)</h3>
             <div id="profitability-history-chart" class="w-full h-80"></div>
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
                             <th class="py-2 text-right">Monthly Credits</th>
                             <th class="py-2 text-right">Sub Price / Mo (Actual)</th>
                             <th class="py-2 text-right">Avg Cost / User (30d)</th>
                             <th class="py-2 text-right">Implied Margin</th>
                             <th class="py-2 text-right">Target-Aligned Credits</th>
                         </tr>
                     </thead>
                     <tbody class="divide-y divide-white/5">
                         <tr>
                             <td class="py-3 font-semibold text-text">Pro</td>
                             <td class="py-3 text-right">{{ number_format($proCredits) }}</td>
                             <td class="py-3 text-right">${{ number_format($proPriceCents / 100, 2) }}</td>
                             <td class="py-3 text-right">${{ number_format($proAvgCostCents / 100, 2) }}</td>
                             <td class="py-3 text-right">
                                 @if($proMargin !== null)
                                     <span class="{{ $proMargin < 0.70 ? 'text-red-500 font-semibold' : 'text-emerald-500' }}">
                                         {{ number_format($proMargin * 100, 1) }}%
                                     </span>
                                 @else
                                     <span class="text-text/40">—</span>
                                 @endif
                             </td>
                             <td class="py-3 text-right font-semibold text-primary">{{ number_format($proRecommendedCredits) }}</td>
                         </tr>
                         <tr>
                             <td class="py-3 font-semibold text-text">Team</td>
                             <td class="py-3 text-right">{{ number_format($teamCredits) }}</td>
                             <td class="py-3 text-right">${{ number_format($teamPriceCents / 100, 2) }}</td>
                             <td class="py-3 text-right">${{ number_format($teamAvgCostCents / 100, 2) }}</td>
                             <td class="py-3 text-right">
                                 @if($teamMargin !== null)
                                     <span class="{{ $teamMargin < 0.70 ? 'text-red-500 font-semibold' : 'text-emerald-500' }}">
                                         {{ number_format($teamMargin * 100, 1) }}%
                                     </span>
                                 @else
                                     <span class="text-text/40">—</span>
                                 @endif
                             </td>
                             <td class="py-3 text-right font-semibold text-primary">{{ number_format($teamRecommendedCredits) }}</td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>

         <!-- Top 5 Heavy Users -->
         <div class="card p-6 bg-surface/50 border border-white/5">
             <h3 class="text-lg font-bold text-text mb-2">Top 5 Resource-Heavy Users (Last 30 Days)</h3>
             <p class="text-text/60 text-sm mb-4">Users driving the highest API costs, showing total runs and gross margin impact.</p>
             
             <div class="overflow-x-auto">
                 <table class="w-full text-sm text-left">
                     <thead class="text-xs text-text/60 uppercase border-b border-white/5">
                         <tr>
                             <th class="py-2">User</th>
                             <th class="py-2">Plan</th>
                             <th class="py-2 text-right">Tool Runs</th>
                             <th class="py-2 text-right">Videos</th>
                             <th class="py-2 text-right">Est. Cost (30d)</th>
                             <th class="py-2 text-right">Implied Margin</th>
                         </tr>
                     </thead>
                     <tbody class="divide-y divide-white/5">
                         @forelse($heavyUsers as $u)
                             <tr>
                                 <td class="py-3">
                                     <div class="font-semibold text-text">{{ $u['name'] }}</div>
                                     <div class="text-xs text-text/50">{{ $u['email'] }}</div>
                                 </td>
                                 <td class="py-3">
                                     <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase {{ $u['plan'] === 'team' ? 'bg-primary/20 text-primary' : ($u['plan'] === 'pro' ? 'bg-indigo-500/20 text-indigo-400' : 'bg-gray-700/20 text-gray-400') }}">
                                         {{ $u['plan'] }}
                                     </span>
                                 </td>
                                 <td class="py-3 text-right">{{ number_format($u['total_runs']) }}</td>
                                 <td class="py-3 text-right">{{ number_format($u['total_videos']) }}</td>
                                 <td class="py-3 text-right font-semibold">${{ number_format($u['total_cost_cents'] / 100, 2) }}</td>
                                 <td class="py-3 text-right">
                                     @if($u['plan'] === 'free')
                                         <span class="text-red-500 font-semibold">-100% (Free Plan Cost)</span>
                                     @elseif($u['margin'] < 0.70)
                                         <span class="text-red-500 font-semibold">{{ number_format($u['margin'] * 100, 1) }}%</span>
                                     @else
                                         <span class="text-emerald-500 font-semibold">{{ number_format($u['margin'] * 100, 1) }}%</span>
                                     @endif
                                 </td>
                             </tr>
                         @empty
                             <tr>
                                 <td colspan="6" class="py-4 text-center text-text/40">No active users recorded in the last 30 days.</td>
                             </tr>
                         @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>

     @push('scripts')
     <script>
         document.addEventListener("DOMContentLoaded", function() {
             const chartEl = document.querySelector("#profitability-history-chart");
             if (!chartEl) return;
             
             const options = {
                 series: [{
                     name: 'Subscription Revenue ($)',
                     data: @json($monthlyRevenues)
                 }, {
                     name: 'Incurred API Costs ($)',
                     data: @json($monthlyCosts)
                 }],
                 chart: {
                     type: 'line',
                     height: 320,
                     background: 'transparent',
                     foreColor: '#9ca3af',
                     toolbar: {
                         show: false
                     }
                 },
                 colors: ['#8b5cf6', '#ef4444'],
                 stroke: {
                     curve: 'smooth',
                     width: 3
                 },
                 grid: {
                     borderColor: 'rgba(255, 255, 255, 0.05)',
                     xaxis: {
                         lines: {
                             show: true
                         }
                     }
                 },
                 xaxis: {
                     categories: @json($monthLabels),
                     axisBorder: {
                         show: false
                     },
                     axisTicks: {
                         show: false
                     }
                 },
                 yaxis: {
                     labels: {
                         formatter: function(val) {
                             return '$' + val.toFixed(2);
                         }
                     }
                 },
                 tooltip: {
                     theme: 'dark',
                     y: {
                         formatter: function(val) {
                             return '$' + val.toFixed(2);
                         }
                     }
                 },
                 legend: {
                     position: 'top',
                     horizontalAlign: 'right'
                 }
             };

             const chart = new ApexCharts(chartEl, options);
             chart.render();
         });
     </script>
     @endpush
 </x-admin-layout>
