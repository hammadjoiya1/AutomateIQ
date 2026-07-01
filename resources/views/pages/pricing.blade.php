<x-public-layout meta-title="Pricing - AutomateIQ">
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

    <style>
        /* Premium Slider Styles */
        input[type=range].premium-slider {
            -webkit-appearance: none;
            width: 100%;
            background: transparent;
        }
        input[type=range].premium-slider:focus {
            outline: none;
        }
        input[type=range].premium-slider::-webkit-slider-runnable-track {
            width: 100%;
            height: 8px;
            cursor: pointer;
            background: rgba(var(--primary-rgb, 91, 33, 182), 0.2);
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
        }
        input[type=range].premium-slider::-webkit-slider-thumb {
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: rgba(var(--primary-rgb, 91, 33, 182), 1);
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -8px;
            box-shadow: 0 0 15px rgba(var(--primary-rgb, 91, 33, 182), 0.8), 0 0 5px rgba(255,255,255,0.5);
            border: 2px solid white;
            transition: transform 0.1s;
        }
        input[type=range].premium-slider::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }
    </style>
    
    <div class="py-24 sm:py-32 relative" x-data="{
        serviceType: 'both',
        contentVolume: 30,
        priorityAccess: false,
        customBranding: false,
        engineTier: 'ultra',
        numberFormat(val) {
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
        get agencyCost() {
            let base = 3500;
            if (this.serviceType === 'script') base = 1500;
            if (this.serviceType === 'video') base = 2000;
            let scale = this.contentVolume / 30;
            let addOn = (this.priorityAccess ? 200 : 0) + (this.customBranding ? 300 : 0);
            return Math.max(800, Math.round((base * scale) + addOn));
        },
        get freelancerCost() {
            let base = 1200;
            if (this.serviceType === 'script') base = 500;
            if (this.serviceType === 'video') base = 800;
            let scale = this.contentVolume / 30;
            let addOn = (this.priorityAccess ? 100 : 0) + (this.customBranding ? 150 : 0);
            return Math.max(300, Math.round((base * scale) + addOn));
        },
        get automateiqCost() {
            let base = 29;
            if (this.contentVolume <= 10) base = 0;
            else if (this.contentVolume > 150) base = 99;
            
            let addOn = (this.priorityAccess ? 10 : 0) + (this.customBranding ? 15 : 0);
            let engineFee = 0;
            if (this.engineTier === 'turbo') engineFee = 10;
            if (this.engineTier === 'ultra') engineFee = 25;
            
            return base + addOn + engineFee;
        },
        get recommendedPlan() {
            if (this.contentVolume <= 10) return 'free';
            if (this.contentVolume <= 150) return 'pro';
            return 'team';
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
                <div class="section-badge mb-4 text-center inline-block">💎 Project Estimation</div>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text mb-4">Flexible pricing for every stage. <br>Try project estimation calculator.</h2>
                <p class="mt-4 text-lg text-text/70">Start free. Scale your automated content production instantly.</p>
            </div>

            <!-- Two-Column StratStudio Pricing Calculator -->
            <div class="strat-card no-tilt scroll-reveal mt-12 max-w-5xl mx-auto p-0 mb-20 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <!-- Left Column: Inputs -->
                    <div class="lg:col-span-7 p-8 md:p-10 space-y-8">
                        <!-- Service Type -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-text uppercase tracking-wider">What kind of service do you need?</h4>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="serviceType" value="script" x-model="serviceType" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                    <span class="text-sm font-medium text-text/80 group-hover:text-text">Only Scriptwriting (AI scripts, hooks, ideas)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="serviceType" value="video" x-model="serviceType" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                    <span class="text-sm font-medium text-text/80 group-hover:text-text">Only Video Automation (Scene lists, cues, overlays)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="serviceType" value="both" x-model="serviceType" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                    <span class="text-sm font-medium text-text/80 group-hover:text-text">Full Production Suite (All tools + automated workflows)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Content Volume -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-semibold text-text uppercase tracking-wider">Select number of monthly outputs:</h4>
                                <span class="text-primary font-bold text-lg" x-text="contentVolume"></span>
                            </div>
                            <input type="range" min="5" max="500" step="5" x-model="contentVolume" class="premium-slider">
                            <div class="text-[10px] text-text/40 flex justify-between">
                                <span>5</span>
                                <span>500</span>
                            </div>
                        </div>

                        <!-- Add-ons -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-text uppercase tracking-wider">Add-ons:</h4>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex items-center gap-3">
                                        <input type="checkbox" x-model="priorityAccess" class="w-4 h-4 rounded text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                        <span class="text-sm font-medium text-text/80 group-hover:text-text">Priority AI Queue Access</span>
                                    </span>
                                    <span class="text-xs font-semibold text-primary">+ $10/mo</span>
                                </label>
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex items-center gap-3">
                                        <input type="checkbox" x-model="customBranding" class="w-4 h-4 rounded text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                        <span class="text-sm font-medium text-text/80 group-hover:text-text">Custom Branding Preset Library</span>
                                    </span>
                                    <span class="text-xs font-semibold text-primary">+ $15/mo</span>
                                </label>
                            </div>
                        </div>

                        <!-- Speed Selector -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-text uppercase tracking-wider">Select AI Engine Tier:</h4>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="engineTier" value="standard" x-model="engineTier" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                        <span class="text-sm font-medium text-text/80 group-hover:text-text">Standard Engine (Balanced speed)</span>
                                    </span>
                                    <span class="text-xs font-semibold text-primary">Included</span>
                                </label>
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="engineTier" value="turbo" x-model="engineTier" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                        <span class="text-sm font-medium text-text/80 group-hover:text-text">High-Speed Turbo API</span>
                                    </span>
                                    <span class="text-xs font-semibold text-primary">+ $10/mo</span>
                                </label>
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="engineTier" value="ultra" x-model="engineTier" class="w-4 h-4 text-primary bg-background border-border focus:ring-primary focus:ring-2">
                                        <span class="text-sm font-medium text-text/80 group-hover:text-text">Ultra-Quality Models (GPT-4o/Claude)</span>
                                    </span>
                                    <span class="text-xs font-semibold text-primary">+ $25/mo</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Estimated Costs -->
                    <div class="lg:col-span-5 p-8 md:p-10 bg-bg/50 border-l border-border space-y-6 flex flex-col justify-center">
                        <div>
                            <h4 class="text-lg font-bold text-text">Estimated Cost</h4>
                            <p class="text-xs text-text/60 mt-1">This is an instant estimation to give you an idea of your potential monthly savings.</p>
                        </div>

                        <!-- Card 1: Traditional copywriter -->
                        <div class="bg-surface/50 border border-border rounded-xl p-4 space-y-1">
                            <span class="text-[10px] text-text/50 font-bold uppercase tracking-wider">Traditional Copywriter / Editor</span>
                            <div class="text-2xl font-extrabold text-text" x-text="'$' + numberFormat(agencyCost)"></div>
                            <span class="text-[10px] text-danger font-semibold block">+ Slow turnaround & high management overhead</span>
                        </div>

                        <!-- Card 2: Regular freelancer -->
                        <div class="bg-surface/50 border border-border rounded-xl p-4 space-y-1">
                            <span class="text-[10px] text-text/50 font-bold uppercase tracking-wider">Standard Freelancer Mini-Studio</span>
                            <div class="text-2xl font-extrabold text-text" x-text="'$' + numberFormat(freelancerCost)"></div>
                            <span class="text-[10px] text-danger font-semibold block">+ Requires endless review & back‑and‑forth loops</span>
                        </div>

                        <!-- Card 3: With AutomateIQ -->
                        <div class="relative rounded-xl border border-primary/30 p-5 overflow-hidden bg-gradient-to-br from-primary/10 to-accent/5 shadow-xl shadow-primary/5">
                            <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>
                            <span class="text-[10px] text-primary font-bold uppercase tracking-wider block">With AutomateIQ</span>
                            <div class="text-3xl font-black text-text mt-1" x-text="'$' + numberFormat(automateiqCost)"></div>
                            <span class="text-[10px] text-success font-semibold block mt-1">✓ Instant production & total creative control</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-stretch scroll-reveal-stagger">
                <!-- Free Plan -->
                <div 
                    :class="recommendedPlan === 'free' ? 'pricing-card-strat featured flex flex-col justify-between' : 'pricing-card-strat flex flex-col justify-between opacity-80 scale-95'"
                >
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-text font-display">Starter</h3>
                            <span x-show="recommendedPlan === 'free'" class="text-[10px] bg-primary px-2.5 py-1 rounded-full text-white font-bold uppercase tracking-wider font-mono shadow-lg">Recommended</span>
                        </div>
                        <div class="text-5xl font-extrabold text-text mt-4">$0</div>
                        <div class="text-xs text-text/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ number_format($creditsFree) }} credits / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                20 library items
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Video generation not included
                            </li>
                        </ul>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Go to dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Get Started</a>
                    @endauth
                </div>

                <!-- Pro Plan -->
                <div 
                    :class="recommendedPlan === 'pro' ? 'pricing-card-strat featured flex flex-col justify-between' : 'pricing-card-strat flex flex-col justify-between'"
                >
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-text font-display">Pro Creator</h3>
                            <span x-show="recommendedPlan === 'pro'" class="text-[10px] bg-primary px-2.5 py-1 rounded-full text-white font-bold uppercase tracking-wider font-mono shadow-lg">Recommended</span>
                            <span x-show="recommendedPlan !== 'pro'" class="text-[10px] bg-surface border border-border px-2.5 py-1 rounded-full text-text/60 font-bold uppercase tracking-wider font-mono shadow-lg">Popular</span>
                        </div>
                        <div class="text-5xl font-extrabold text-text mt-4">$29</div>
                        <div class="text-xs text-text/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ number_format($creditsPro) }} credits / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Unlimited library
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Video generation included
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Full preset engine workflows
                            </li>
                        </ul>
                    </div>
                    @auth
                        @if(in_array(Auth::user()->plan, ['pro', 'team']))
                            <a href="{{ route('billing.portal') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Manage billing</a>
                        @else
                            <a href="{{ route('billing.checkout', 'pro') }}" class="btn-glow w-full text-center mt-8 justify-center">Upgrade to Pro</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn-glow w-full text-center mt-8 justify-center">Get Started</a>
                    @endauth
                </div>

                <!-- Team Plan -->
                <div 
                    :class="recommendedPlan === 'team' ? 'pricing-card-strat featured flex flex-col justify-between' : 'pricing-card-strat flex flex-col justify-between opacity-80 scale-95'"
                >
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-text font-display">Agency</h3>
                            <span x-show="recommendedPlan === 'team'" class="text-[10px] bg-primary px-2.5 py-1 rounded-full text-white font-bold uppercase tracking-wider font-mono shadow-lg">Recommended</span>
                        </div>
                        <div class="text-5xl font-extrabold text-text mt-4">$99</div>
                        <div class="text-xs text-text/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ number_format($creditsTeam) }} credits / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                5 user seats
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-text/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Priority Support channels
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('demo') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Book Demo</a>
                </div>
            </div>
            
            <div class="mt-10 text-center text-sm text-text/60 scroll-reveal">
                Questions? <a href="{{ route('contact') }}" class="text-primary hover:underline">Contact us</a>.
            </div>

            @if(!empty($topupPacks))
                <div class="mt-24 scroll-reveal">
                    <div class="mx-auto max-w-2xl text-center">
                        <h3 class="text-3xl font-bold text-text">Credit Top-Ups</h3>
                        <p class="mt-2 text-base text-text/70">Need more this month? Buy credits instantly.</p>
                    </div>
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($topupPacks as $pack)
                            <div class="strat-card p-8 flex flex-col justify-center items-center text-center">
                                <div class="text-sm font-bold tracking-wider text-primary uppercase">{{ $pack['label'] }}</div>
                                <div class="mt-3 text-4xl font-extrabold text-text">{{ number_format($pack['credits']) }}</div>
                                <div class="text-xs text-text/40 mb-6 mt-1">credits</div>
                                @if(!empty($pack['price']))
                                    <div class="text-lg text-text/80 font-semibold">{{ $pack['price'] }}</div>
                                @endif
                                <a href="{{ route('billing.topup', $pack['key']) }}" class="mt-6 btn-glow w-full justify-center">Buy Credits</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>