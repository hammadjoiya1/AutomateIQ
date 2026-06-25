<x-app-layout>
    @php
        $user = Auth::user();
        $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
        $isPro = in_array($user->plan, ['pro', 'team']) || $trialActive;
        $plan = $isPro ? 'pro' : 'free';
        $credits = $user->credits ?? 0;
        $subscriptionCredits = $user->subscription_credits ?? 0;
        $topupCredits = $user->topup_credits ?? 0;
        $monthlyCredits = (int) \App\Models\Setting::get("credits.monthly_credits.{$plan}", config("credits.monthly_credits.{$plan}"));
        $subscriptionUsed = max(0, $monthlyCredits - $subscriptionCredits);
        $usagePercent = min(100, $monthlyCredits > 0 ? ($subscriptionUsed / $monthlyCredits) * 100 : 0);
        $lowCredits = $credits <= max(20, (int) round($monthlyCredits * 0.1));
        $upgradeUrl = $user->plan === 'pro' || $user->plan === 'team' ? route('billing.portal') : route('billing.checkout', 'pro');
        $trialEnds = $trialActive ? $user->trial_ends_at : null;
        $onboardingDone = !empty($user->onboarding_completed_at);
        $topupPacks = [];
        foreach (['starter', 'growth', 'scale'] as $packKey) {
            $settingUrl = \App\Models\Setting::get("lemonsqueezy.topup_checkout_urls.{$packKey}", null);
            $configUrls = config('lemonsqueezy.topup_checkout_urls', []);
            $url = $settingUrl ?: ($configUrls[$packKey] ?? null);
            if ($url) {
                $topupPacks[$packKey] = $url;
            }
        }
    @endphp

    <div class="space-y-10 animate-fade-in">
        <!-- Welcome Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <p class="text-xs uppercase tracking-widest text-text-muted">Overview</p>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-text mt-2">Dashboard</h1>
                <p class="text-text-muted mt-2 text-lg">Welcome back, {{ Auth::user()->name }}. Here's a quick snapshot
                    of your workspace.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tools.index') }}" class="btn btn-primary" data-analytics-event="cta_new_generation">
                    New Generation
                </a>
                <a href="{{ route('workflows.create') }}" class="btn btn-secondary" data-analytics-event="cta_workflow_create">
                    Create Workflow
                </a>
                <a href="{{ route('tools.history') }}" class="btn btn-ghost" data-analytics-event="cta_history">
                    History
                </a>
                <a href="{{ route('library.index') }}" class="btn btn-ghost" data-analytics-event="cta_library">
                    Library
                </a>
            </div>
        </div>

        @if(!$onboardingDone)
            <div class="card p-4 border border-primary/20 bg-primary/5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="text-sm text-text">
                        <span class="font-bold text-primary">Get your first win:</span>
                        Finish the 5‑minute onboarding to unlock your workflow.
                    </div>
                    <a href="{{ route('onboarding.show') }}" class="btn btn-sm btn-primary" data-analytics-event="cta_onboarding">
                        Start Onboarding
                    </a>
                </div>
            </div>
        @endif

        @if($lowCredits)
            <div class="card p-4 border border-yellow-500/20 bg-yellow-500/5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="text-sm text-text">
                        <span class="font-bold text-yellow-500">Low credits:</span>
                        You have {{ number_format($credits) }} credits left. Upgrade to avoid interruptions.
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(!empty($topupPacks))
                            <a href="{{ route('billing.topup', array_key_first($topupPacks)) }}" class="btn btn-sm btn-primary"
                                data-analytics-event="cta_topup_low_credits">
                                Buy Credits
                            </a>
                        @endif
                        <a href="{{ $upgradeUrl }}" class="btn btn-sm btn-secondary" data-analytics-event="cta_upgrade_low_credits">
                            Upgrade
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6 border border-white/5 bg-surface/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-text">Recent Generations</h3>
                        <a href="{{ route('tools.history') }}" class="btn btn-sm btn-ghost hover:bg-primary/5">View All</a>
                    </div>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-text-muted uppercase border-b border-primary/10">
                                <tr>
                                    <th class="py-3">Tool</th>
                                    <th class="py-3">Output</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary/5">
                                @forelse(Auth::user()->toolRuns()->with('tool')->latest()->take(6)->get() as $run)
                                    <tr class="bg-card hover:bg-primary/2 transition-colors">
                                        <td class="py-3 font-medium text-text">{{ $run->tool->name ?? 'Unknown Tool' }}</td>
                                        <td class="py-3 text-text-muted max-w-xs truncate">Generated content...</td>
                                        <td class="py-3 text-text-muted">{{ $run->created_at->diffForHumans() }}</td>
                                        <td class="py-3">
                                            <span class="badge badge-success shadow-sm">Completed</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-text-muted">No activity yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card p-6 border border-white/5 bg-surface/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-text">Workflows</h3>
                        <a href="{{ route('workflows.index') }}" class="btn btn-sm btn-ghost hover:bg-primary/5">Manage</a>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse(Auth::user()->workflows()->latest()->take(3)->get() as $workflow)
                            <div class="flex items-center justify-between rounded-xl border border-primary/10 bg-background/50 px-4 py-3">
                                <div>
                                    <div class="text-sm font-semibold text-text">{{ $workflow->name }}</div>
                                    <div class="text-xs text-text-muted">{{ $workflow->updated_at->diffForHumans() }}</div>
                                </div>
                                <a href="{{ route('workflows.index') }}" class="text-xs text-primary">Open</a>
                            </div>
                        @empty
                            <div class="text-sm text-text-muted">No workflows created.</div>
                            <a href="{{ route('workflows.create') }}" class="btn btn-sm btn-secondary mt-3">Create Workflow</a>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-6 border border-white/5 bg-surface/50">
                    <div class="text-sm text-text-muted">Monthly Credits</div>
                    <div class="text-xl font-bold text-text mt-1">
                        {{ strtoupper($plan) }} plan — {{ number_format($subscriptionCredits) }} / {{ number_format($monthlyCredits) }} remaining
                    </div>
                    <div class="h-2 rounded-full bg-primary/10 overflow-hidden mt-4">
                        <div class="h-full bg-primary" style="width: {{ $usagePercent }}%"></div>
                    </div>
                    <div class="text-xs text-text-muted mt-2">{{ round($usagePercent) }}% of subscription credits used</div>
                    @if($plan !== 'pro')
                        <a href="{{ $upgradeUrl }}" class="btn btn-sm btn-secondary mt-4" data-analytics-event="cta_upgrade_usage_meter">
                            Upgrade
                        </a>
                    @endif
                </div>

                <div class="card p-6 border border-white/5 bg-surface/50">
                    <div class="text-sm text-text-muted">Credits</div>
                    <div class="text-3xl font-display font-bold text-text mt-2">{{ number_format($credits) }}</div>
                    <div class="text-xs text-text-muted mt-1">Top-up: {{ number_format($topupCredits) }} · Subscription: {{ number_format($subscriptionCredits) }}</div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @forelse($topupPacks as $pack => $url)
                            <a href="{{ route('billing.topup', $pack) }}" class="btn btn-sm btn-primary">Buy {{ ucfirst($pack) }}</a>
                        @empty
                            <a href="{{ route('pricing') }}" class="btn btn-sm btn-ghost">Buy more credits</a>
                        @endforelse
                    </div>
                </div>

                @if(!$onboardingDone)
                    <div class="card p-6 border border-primary/20 bg-primary/5">
                        <div class="text-sm font-semibold text-text">Quick start</div>
                        <div class="text-xs text-text-muted mt-2">Run a tool, save to library, then automate.</div>
                        <a href="{{ route('onboarding.show') }}" class="btn btn-sm btn-primary mt-4">Start onboarding</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>