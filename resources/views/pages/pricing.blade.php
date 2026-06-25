<x-public-layout>
    @php
        $creditsFree = (int) \App\Models\Setting::get('credits.monthly_credits.free', config('credits.monthly_credits.free'));
        $creditsPro = (int) \App\Models\Setting::get('credits.monthly_credits.pro', config('credits.monthly_credits.pro'));
        $creditsTeam = (int) \App\Models\Setting::get('credits.monthly_credits.team', config('credits.monthly_credits.team'));
        $pricing = app(\App\Services\CreditPricingService::class);
        $exampleShortCredits = $pricing->estimateTextCreditsFromTokens(120, 240);
        $exampleScriptCredits = $pricing->estimateTextCreditsFromTokens(250, 900);
        $exampleVideoCredits = $pricing->estimateVideoRunCredits();
        $packKeys = ['starter' => 'Starter', 'growth' => 'Growth', 'scale' => 'Scale'];
        $topupPacks = [];
        foreach ($packKeys as $key => $label) {
            $url = \App\Models\Setting::get("lemonsqueezy.topup_checkout_urls.{$key}", null);
            $credits = \App\Models\Setting::get("lemonsqueezy.topup_variants.{$key}.credits", null);
            $price = \App\Models\Setting::get("credits.topup_display_prices.{$key}", null);
            if ($url && $credits) {
                $topupPacks[] = [
                    'key' => $key,
                    'label' => $label,
                    'credits' => (int) $credits,
                    'price' => $price,
                ];
            }
        }
    @endphp
    <div class="bg-background py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-text sm:text-4xl">Simple Pricing</h2>
                <p class="mt-4 text-base text-text/70">Start free. Upgrade when you’re ready.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Free Plan -->
                <div class="rounded-2xl p-6 border border-white/10 bg-surface/30">
                    <h3 class="text-lg font-semibold text-text">Free</h3>
                    <p class="mt-1 text-sm text-text/60">Try the core tools.</p>
                    <div class="mt-4 text-3xl font-bold text-text">$0<span class="text-sm font-medium text-text/60">/mo</span></div>
                    <ul class="mt-5 space-y-2 text-sm text-text/70">
                        <li>{{ number_format($creditsFree) }} credits / month</li>
                        <li>20 library items</li>
                        <li>Video generation not included</li>
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" class="mt-6 btn btn-ghost w-full">Go to dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="mt-6 btn btn-ghost w-full">Get started</a>
                    @endauth
                </div>

                <!-- Pro Plan -->
                <div class="rounded-2xl p-6 border border-primary/40 bg-primary/5">
                    <div class="text-xs font-semibold text-primary">Most Popular</div>
                    <h3 class="text-lg font-semibold text-text mt-1">Pro</h3>
                    <p class="mt-1 text-sm text-text/60">For creators scaling output.</p>
                    <div class="mt-4 text-3xl font-bold text-text">$29<span class="text-sm font-medium text-text/60">/mo</span></div>
                    <ul class="mt-5 space-y-2 text-sm text-text/70">
                        <li>{{ number_format($creditsPro) }} credits / month</li>
                        <li>Unlimited library</li>
                        <li>Video generation included</li>
                        <li>Automated workflows</li>
                        <li>Top-up credits available</li>
                    </ul>
                    @auth
                        @if(in_array(Auth::user()->plan, ['pro', 'team']))
                            <a href="{{ route('billing.portal') }}" class="mt-6 btn btn-primary w-full">Manage billing</a>
                        @else
                            <a href="{{ route('billing.checkout', 'pro') }}" class="mt-6 btn btn-primary w-full">Upgrade to Pro</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="mt-6 btn btn-primary w-full">Get started</a>
                    @endauth
                </div>

                <!-- Team Plan -->
                <div class="rounded-2xl p-6 border border-white/10 bg-surface/30">
                    <h3 class="text-lg font-semibold text-text">Team</h3>
                    <p class="mt-1 text-sm text-text/60">For agencies and teams.</p>
                    <div class="mt-4 text-3xl font-bold text-text">$99<span class="text-sm font-medium text-text/60">/mo</span></div>
                    <ul class="mt-5 space-y-2 text-sm text-text/70">
                        <li>{{ number_format($creditsTeam) }} credits / month</li>
                        <li>Video generation included</li>
                        <li>5 user seats</li>
                        <li>Top-up credits available</li>
                    </ul>
                    <a href="{{ route('demo') }}" class="mt-6 btn btn-ghost w-full">Book demo</a>
                </div>
            </div>

            <div class="mt-10 text-center text-sm text-text/60">
                Questions? <a href="{{ route('contact') }}" class="text-primary hover:underline">Contact us</a>.
            </div>

            @if(!empty($topupPacks))
                <div class="mt-16">
                    <div class="mx-auto max-w-2xl text-center">
                        <h3 class="text-2xl font-bold text-text">Credit Top-Ups</h3>
                        <p class="mt-2 text-sm text-text/70">Need more this month? Buy credits instantly.</p>
                    </div>
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($topupPacks as $pack)
                            <div class="rounded-2xl p-6 border border-white/10 bg-surface/30">
                                <div class="text-sm text-text/60">{{ $pack['label'] }}</div>
                                <div class="mt-2 text-3xl font-bold text-text">{{ number_format($pack['credits']) }} credits</div>
                                @if(!empty($pack['price']))
                                    <div class="mt-1 text-sm text-text/60">{{ $pack['price'] }}</div>
                                @endif
                                <a href="{{ route('billing.topup', $pack['key']) }}" class="mt-6 btn btn-primary w-full">Buy Credits</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="bg-surface/30 border-t border-white/10 py-16">
                <div class="mx-auto max-w-6xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <h3 class="text-2xl font-bold text-text">How credits work</h3>
                        <p class="mt-2 text-sm text-text/70">Each run uses credits based on model costs. Credits reset monthly for subscriptions.</p>
                    </div>
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="rounded-2xl p-6 border border-white/10 bg-background/40">
                            <div class="text-sm text-text/60">Short text (hooks, captions)</div>
                            <div class="mt-2 text-2xl font-bold text-text">~{{ number_format($exampleShortCredits) }} credits</div>
                            <div class="mt-2 text-xs text-text/60">Typical 1–2 paragraph output</div>
                        </div>
                        <div class="rounded-2xl p-6 border border-white/10 bg-background/40">
                            <div class="text-sm text-text/60">Long-form scripts</div>
                            <div class="mt-2 text-2xl font-bold text-text">~{{ number_format($exampleScriptCredits) }} credits</div>
                            <div class="mt-2 text-xs text-text/60">Longer outputs and higher token usage</div>
                        </div>
                        <div class="rounded-2xl p-6 border border-white/10 bg-background/40">
                            <div class="text-sm text-text/60">AI video generation</div>
                            <div class="mt-2 text-2xl font-bold text-text">~{{ number_format($exampleVideoCredits) }} credits</div>
                            <div class="mt-2 text-xs text-text/60">Default frame count and FPS</div>
                        </div>
                    </div>
                    <div class="mt-8 text-center text-sm text-text/60">
                        Free plan includes {{ number_format($creditsFree) }} credits per month. Subscription credits reset each month; top-ups never expire.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>