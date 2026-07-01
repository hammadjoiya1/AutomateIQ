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

    <div class="space-y-10 pb-10">
        <!-- Welcome Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 scroll-reveal">
            <div>
                {{-- Live system indicator strip --}}
                <div class="flex items-center gap-3 mb-3">
                    <x-ui.badge variant="accent" class="gap-1.5">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-accent)"></span>
                            <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: var(--color-accent)"></span>
                        </span>
                        Live
                    </x-ui.badge>
                    {{-- Waveform system-live indicator --}}
                    <div class="waveform" aria-hidden="true">
                        @for ($i = 0; $i < 8; $i++)<div class="waveform-bar"></div>@endfor
                    </div>
                    <span class="text-xs font-mono" style="color: var(--color-text-muted)">System active</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-text">Dashboard</h1>
                <p class="text-text-muted mt-2 text-lg">Welcome back, <span class="text-primary font-semibold">{{ Auth::user()->name }}</span>. Here's a quick snapshot
                    of your workspace.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="primary" href="{{ route('tools.index', ['layout' => 'dashboard']) }}" data-analytics-event="cta_new_generation">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    New Generation
                </x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('workflows.create') }}" data-analytics-event="cta_workflow_create">
                    Create Workflow
                </x-ui.button>
                <x-ui.button variant="ghost" href="{{ route('tools.history') }}" data-analytics-event="cta_history">
                    History
                </x-ui.button>
            </div>
        </div>

        @if(!$onboardingDone)
            <x-ui.card padding="p-5" class="border-primary/30 bg-primary/10 shadow-lg shadow-primary/5" :hoverEffect="false">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-sm text-text">
                        <span class="font-bold text-primary flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Get your first win
                        </span>
                        Finish the 5‑minute onboarding to unlock your workflow automation.
                    </div>
                    <x-ui.button variant="primary" href="{{ route('onboarding.show') }}" data-analytics-event="cta_onboarding">
                        Start Onboarding
                    </x-ui.button>
                </div>
            </x-ui.card>
        @endif

        @if($lowCredits)
            <x-ui.card padding="p-5" class="border-yellow-500/30 bg-yellow-500/10 shadow-lg shadow-yellow-500/5 animate-pulse" :hoverEffect="false">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-sm text-text">
                        <span class="font-bold text-yellow-500 flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Low Credits
                        </span>
                        You have {{ number_format($credits) }} credits left. Upgrade to avoid interruptions.
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(!empty($topupPacks))
                            <x-ui.button variant="primary" href="{{ route('billing.topup', array_key_first($topupPacks)) }}" data-analytics-event="cta_topup_low_credits">
                                Buy Credits
                            </x-ui.button>
                        @endif
                        <x-ui.button variant="secondary" href="{{ $upgradeUrl }}" data-analytics-event="cta_upgrade_low_credits">
                            Upgrade
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Generations (Grid format) -->
                <div>
                    <div class="flex items-center justify-between mb-4 scroll-reveal">
                        <h3 class="text-xl font-display font-bold text-text">Recent Generations</h3>
                        <a href="{{ route('tools.history') }}" class="text-sm text-primary hover:underline font-semibold transition-all">View All →</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse(Auth::user()->toolRuns()->with('tool')->latest()->take(4)->get() as $run)
                            <x-ui.card padding="p-5" class="cursor-pointer group relative overflow-hidden" :hoverEffect="true">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-text-muted">{{ $run->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h4 class="font-bold text-text mb-1">{{ $run->tool->name ?? 'Unknown Tool' }}</h4>
                                    <p class="text-sm text-text-muted line-clamp-2">Generated output content preview...</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <x-ui.badge variant="signal" class="text-xs">Completed</x-ui.badge>
                                    </div>
                                </div>
                            </x-ui.card>
                        @empty
                            <div class="col-span-1 md:col-span-2">
                                <x-ui.card padding="p-8" class="text-center" :hoverEffect="false">
                                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-text mb-2">No generations yet</h4>
                                    <p class="text-sm text-text-muted mb-4">Start generating content using our AI tools.</p>
                                    <x-ui.button variant="primary" href="{{ route('tools.index', ['layout' => 'dashboard']) }}">Try a Tool</x-ui.button>
                                </x-ui.card>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Workflows -->
                <div>
                    <div class="flex items-center justify-between mb-4 scroll-reveal">
                        <h3 class="text-xl font-display font-bold text-text">Active Workflows</h3>
                        <a href="{{ route('workflows.index') }}" class="text-sm text-primary hover:underline font-semibold transition-all">Manage All →</a>
                    </div>
                    
                    <x-ui.card padding="p-1" class="overflow-hidden" :hoverEffect="false">
                        <div class="divide-y divide-border">
                            @forelse(Auth::user()->workflows()->latest()->take(3)->get() as $workflow)
                                <div class="group flex items-center justify-between p-4 hover:bg-surface/80 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-text">{{ $workflow->name }}</div>
                                            <div class="text-xs text-text-muted mt-0.5">Updated {{ $workflow->updated_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('workflows.index') }}" class="text-xs font-semibold text-primary/0 group-hover:text-primary transition-all translate-x-2 group-hover:translate-x-0">Open &rarr;</a>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <div class="text-sm text-text-muted mb-3">No workflows created. Automation saves you hours of work.</div>
                                    <x-ui.button variant="secondary" size="sm" href="{{ route('workflows.create') }}">Create Workflow</x-ui.button>
                                </div>
                            @endforelse
                        </div>
                    </x-ui.card>
                </div>
            </div>

            <!-- Right Sidebar Panel -->
            <div class="space-y-8">
                <!-- Usage Ring Chart Card -->
                <x-ui.card padding="p-6" class="relative overflow-hidden scroll-reveal" :hoverEffect="false">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent pointer-events-none"></div>
                    
                    <h3 class="text-lg font-bold text-text mb-4">Credit Usage</h3>
                    
                    <div class="flex justify-center my-4 relative">
                        <!-- ApexCharts Donut will render here -->
                        <div id="creditUsageChart" class="relative z-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center flex-col z-0">
                            <span class="text-3xl font-display font-bold text-text" data-mono>{{ number_format($subscriptionCredits) }}</span>
                            <span class="text-xs text-text-muted uppercase tracking-wider font-semibold">Remaining</span>
                        </div>
                    </div>

                    <div class="mt-4 bg-background/50 rounded-control-md p-4 border border-border">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-text-muted">Total Allowance</span>
                            <span class="text-sm font-bold text-text" data-mono>{{ number_format($monthlyCredits) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-text-muted">Plan</span>
                            <span class="text-sm font-bold text-primary uppercase tracking-wider">{{ $plan }}</span>
                        </div>
                    </div>

                    @if($plan !== 'pro' && $plan !== 'team')
                        <div class="mt-6">
                            <x-ui.button variant="primary" class="w-full" href="{{ $upgradeUrl }}" data-analytics-event="cta_upgrade_usage_meter">
                                Upgrade Plan
                            </x-ui.button>
                        </div>
                    @endif
                </x-ui.card>

                <!-- Total Credits Overview -->
                <x-ui.card padding="p-6" class="scroll-reveal" :hoverEffect="false">
                    <div class="text-sm text-text-muted uppercase tracking-wider font-semibold">Total Balance</div>
                    <div class="text-4xl font-display font-bold mt-2 flex items-baseline gap-2">
                        <span class="text-primary font-mono" data-mono>{{ number_format($credits) }}</span>
                        <span class="text-sm text-text-muted font-normal">credits</span>
                    </div>
                    
                    <div class="mt-6 flex flex-col gap-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-muted flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-primary"></span> Subscription
                            </span>
                            <span class="font-bold text-text" data-mono>{{ number_format($subscriptionCredits) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-muted flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" style="background: var(--color-signal)"></span> Top-ups
                            </span>
                            <span class="font-bold text-text" data-mono>{{ number_format($topupCredits) }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-ui.button variant="secondary" class="w-full" href="{{ route('pricing') }}">Buy more credits</x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        (function() {
            const getThemeColor = (varName, fallback) => {
                return getComputedStyle(document.documentElement).getPropertyValue(varName).trim() || fallback;
            };
            const accentColor = getThemeColor('--color-accent', '#D4FF3D');
            const surfaceColor = getThemeColor('--color-surface-raised', '#1F1F1F');

            var options = {
                series: [{{ $subscriptionUsed }}, {{ max(0, $subscriptionCredits) }}],
                chart: {
                    type: 'donut',
                    height: 250,
                    fontFamily: 'inherit',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                colors: [accentColor, surfaceColor],
                labels: ['Used', 'Remaining'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            background: 'transparent',
                            labels: {
                                show: false
                            }
                        },
                        expandOnClick: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    colors: [getThemeColor('--color-border', '#2A2A2A')],
                    width: 2
                },
                legend: {
                    show: false
                },
                tooltip: {
                    enabled: true,
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return val + " credits"
                        }
                    }
                }
            };

            // Detect theme to adjust chart colors
            const theme = document.documentElement.getAttribute('data-theme') || 'dark';
            if (theme === 'light') {
                options.tooltip.theme = 'light';
            }

            const chartEl = document.querySelector("#creditUsageChart");
            if (chartEl && typeof ApexCharts !== 'undefined') {
                var chart = new ApexCharts(chartEl, options);
                chart.render();
            }
        })();
    </script>
</x-app-layout>