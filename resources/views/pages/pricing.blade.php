<x-public-layout>
    <div class="bg-background py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl sm:text-center">
                <h2 class="text-3xl font-bold tracking-tight text-text sm:text-4xl">Simple, Transparent Pricing</h2>
                <p class="mt-6 text-lg leading-8 text-text/70">Start for free, upgrade when you're ready to scale your
                    faceless empire.</p>
            </div>

            <div class="mt-8 flex items-center justify-center gap-3 text-sm text-text/70" data-pricing-toggle>
                <span>Monthly</span>
                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary/20">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-primary transition"></span>
                </button>
                <span>Annual <span class="text-primary font-semibold">(2 months free)</span></span>
            </div>

            <div class="mt-12 card p-8 bg-surface/30 border border-white/5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-bold text-text">Creator Packs included</h3>
                        <p class="text-text/60 mt-2">Ready‑to‑use outputs generated from your tools and presets.</p>
                    </div>
                    <div class="text-sm text-text/60">
                        Included with <span class="text-primary font-semibold">Pro</span> and <span class="text-primary font-semibold">Team</span>
                    </div>
                </div>
                @php
                    $creatorPacks = [
                        ['name' => 'Hook Pack', 'desc' => '150 retention‑first hooks + angles.'],
                        ['name' => 'Script Pack', 'desc' => 'Short‑form scripts in 15s/30s/60s formats.'],
                        ['name' => 'Scene Splitter Pack', 'desc' => 'Shot lists + b‑roll cues from scripts.'],
                        ['name' => 'Repurpose Pack', 'desc' => 'Threads, LinkedIn posts, and newsletters.'],
                        ['name' => 'Idea Calendar', 'desc' => '30‑day idea plan with hooks + angles.'],
                        ['name' => 'Video Prompt Pack', 'desc' => 'High‑signal prompts for AI video creation.'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    @foreach($creatorPacks as $pack)
                        <div class="rounded-2xl border border-primary/10 bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">{{ $pack['name'] }}</div>
                            <div class="text-xs text-text/60 mt-2">{{ $pack['desc'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="card p-6 bg-surface/30 border border-white/5">
                    <h4 class="text-lg font-bold text-text mb-2">Niche versions</h4>
                    <p class="text-sm text-text/60">Fitness, Finance, and SaaS packs included.</p>
                </div>
                <div class="card p-6 bg-surface/30 border border-white/5">
                    <h4 class="text-lg font-bold text-text mb-2">30‑day plan sample</h4>
                    <p class="text-sm text-text/60">Preview a full month of hooks, scripts, and repurposes.</p>
                </div>
                <div class="card p-6 bg-surface/30 border border-white/5">
                    <h4 class="text-lg font-bold text-text mb-2">Free mini‑pack</h4>
                    <p class="text-sm text-text/60">10 hooks + 2 scripts + 1 repurpose template.</p>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card p-8 bg-surface/30 border border-white/5">
                    <h3 class="text-2xl font-bold text-text mb-3">How AI automation works</h3>
                    <ul class="space-y-3 text-sm text-text/70">
                        <li><span class="font-semibold text-text">Chain tools:</span> Ideas → Hooks → Scripts → Scenes → Repurpose.</li>
                        <li><span class="font-semibold text-text">Set inputs once:</span> niche, tone, length, format.</li>
                        <li><span class="font-semibold text-text">Run on schedule:</span> daily outputs saved to your library.</li>
                    </ul>
                </div>
                <div class="card p-8 bg-surface/30 border border-white/5">
                    <h3 class="text-2xl font-bold text-text mb-3">Example workflow</h3>
                    <div class="space-y-2 text-sm text-text/70">
                        <div>1) Generate 10 ideas</div>
                        <div>2) Write 5 hooks</div>
                        <div>3) Draft a 60‑sec script</div>
                        <div>4) Split into scenes</div>
                        <div>5) Repurpose for LinkedIn + X</div>
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('workflows.create') }}" class="btn btn-sm btn-primary">Build this workflow</a>
                    </div>
                </div>
            </div>
            <div
                class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-y-16 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-x-8">
                <!-- Free Plan -->
                <div
                    class="rounded-3xl p-8 ring-1 ring-primary/20 xl:p-10 bg-background hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 class="text-lg font-semibold leading-8 text-text">Free</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-text/70">Perfect for testing the waters.</p>
                    <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full">
                        Free trial with limited credits
                    </div>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold tracking-tight text-text">$0</span>
                        <span class="text-sm font-semibold leading-6 text-text/70">/month</span>
                    </p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-text/70">
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>5 Tool Runs / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Max 20 Library Items</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                            </svg>0 Videos / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Mini‑pack access</li>
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" data-analytics-event="cta_free_current"
                            class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-primary ring-1 ring-inset ring-primary/20 hover:ring-primary/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Go
                            to dashboard</a>
                    @else
                        <a href="{{ route('register') }}" data-analytics-event="cta_signup_free"
                            class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-primary ring-1 ring-inset ring-primary/20 hover:ring-primary/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Sign
                            up for free</a>
                    @endauth
                </div>

                <!-- Pro Plan -->
                <div id="plan-pro"
                    class="rounded-3xl p-8 ring-2 ring-primary xl:p-10 bg-primary/5 hover:shadow-2xl transition-all relative">
                    <span
                        class="absolute top-0 right-0 -mt-3 -mr-3 px-3 py-1 bg-primary text-white text-xs font-bold rounded-full">MOST
                        POPULAR</span>
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 class="text-lg font-semibold leading-8 text-primary">Pro</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-text/70">For serious content creators.</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold tracking-tight text-text" data-price-monthly="$29" data-price-annual="$290">$29</span>
                        <span class="text-sm font-semibold leading-6 text-text/70" data-period-monthly="/month" data-period-annual="/year">/month</span>
                    </p>
                    <p class="text-xs text-text/60 mt-2">Annual plan billed yearly.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-text/70">
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>100 Tool Runs / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Unlimited Library Storage</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                            </svg>5 Videos / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Automated Workflows</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Priority Support</li>
                    </ul>
                    @auth
                        @if(in_array(Auth::user()->plan, ['pro', 'team']))
                            <a href="{{ route('billing.portal') }}" data-analytics-event="cta_manage_billing"
                                class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-white bg-primary hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Manage
                                billing</a>
                        @else
                            <a href="{{ route('billing.checkout', 'pro') }}" data-analytics-event="cta_upgrade_pro"
                                data-checkout-monthly="{{ route('billing.checkout', 'pro') }}"
                                data-checkout-annual="{{ route('billing.checkout', 'pro-annual') }}"
                                class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-white bg-primary hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Upgrade
                                to Pro</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" data-analytics-event="cta_upgrade_pro"
                            class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-white bg-primary hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Get
                            started</a>
                    @endauth
                </div>

                <!-- Team Plan -->
                <div
                    class="rounded-3xl p-8 ring-1 ring-primary/20 xl:p-10 bg-background hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between gap-x-4">
                        <h3 class="text-lg font-semibold leading-8 text-text">Team</h3>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-text/70">For agencies and larger operations.</p>
                    <p class="mt-6 flex items-baseline gap-x-1">
                        <span class="text-4xl font-bold tracking-tight text-text" data-price-monthly="$99" data-price-annual="$990">$99</span>
                        <span class="text-sm font-semibold leading-6 text-text/70" data-period-monthly="/month" data-period-annual="/year">/month</span>
                    </p>
                    <p class="text-xs text-text/60 mt-2">Annual plan billed yearly.</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-text/70">
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>100 Tool Runs / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                            </svg>5 Videos / Day</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>5 User Accounts</li>
                        <li class="flex gap-x-3"><svg class="h-6 w-5 flex-none text-primary" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>Dedicated Account Manager</li>
                    </ul>
                    <a href="{{ route('demo') }}" data-analytics-event="cta_book_demo"
                        class="mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-primary ring-1 ring-inset ring-primary/20 hover:ring-primary/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Book
                        Demo</a>
                </div>
            </div>

            <!-- Pro Features Comparison -->
            <div class="mt-20">
                <h3 class="text-2xl font-bold text-text mb-6">Pro feature comparison</h3>
                <div class="overflow-x-auto card p-6 bg-surface/30 border border-white/5">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-text/60 uppercase">
                            <tr>
                                <th class="py-3">Feature</th>
                                <th class="py-3">Free</th>
                                <th class="py-3">Pro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr>
                                <td class="py-3">Tool Runs / Day</td>
                                <td class="py-3">5</td>
                                <td class="py-3">100</td>
                            </tr>
                            <tr>
                                <td class="py-3">Video Generations / Day</td>
                                <td class="py-3">0</td>
                                <td class="py-3">5</td>
                            </tr>
                            <tr>
                                <td class="py-3">Automated Workflows</td>
                                <td class="py-3">—</td>
                                <td class="py-3">Included</td>
                            </tr>
                            <tr>
                                <td class="py-3">Library Storage</td>
                                <td class="py-3">20 items</td>
                                <td class="py-3">Unlimited</td>
                            </tr>
                            <tr>
                                <td class="py-3">Priority Support</td>
                                <td class="py-3">—</td>
                                <td class="py-3">Included</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ROI Calculator -->
            <div class="mt-20 card p-8 bg-surface/30 border border-white/5">
                <h3 class="text-2xl font-bold text-text mb-4">ROI Calculator</h3>
                <p class="text-text/60 mb-6">Estimate how much time and money you save each month.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-roi>
                    <div>
                        <label class="text-xs text-text/60">Hours saved per week</label>
                        <input type="number" min="0" value="5" class="w-full mt-2 rounded-xl border border-primary/20 bg-background text-text px-4 py-3" data-roi-hours>
                    </div>
                    <div>
                        <label class="text-xs text-text/60">Hourly rate ($)</label>
                        <input type="number" min="0" value="40" class="w-full mt-2 rounded-xl border border-primary/20 bg-background text-text px-4 py-3" data-roi-rate>
                    </div>
                    <div>
                        <label class="text-xs text-text/60">Estimated monthly ROI</label>
                        <div class="mt-2 text-3xl font-bold text-primary" data-roi-result>$800</div>
                        <div class="text-xs text-text/50">Based on 4 weeks</div>
                    </div>
                </div>
            </div>

            <!-- Social Proof -->
            <div class="mt-20">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-bold text-text">Trusted by creators</h3>
                    <span class="text-sm text-text/60">Real outcomes from real users</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="card p-6 bg-surface/50 border border-white/5">
                        <p class="text-text">“We cut our content planning time by 70% and doubled output in 30 days.”</p>
                        <div class="mt-4 text-sm text-text/60">— Maya, YouTube Creator</div>
                    </div>
                    <div class="card p-6 bg-surface/50 border border-white/5">
                        <p class="text-text">“AutomateIQ paid for itself in week one. The workflows are a game changer.”</p>
                        <div class="mt-4 text-sm text-text/60">— Jordan, Agency Lead</div>
                    </div>
                    <div class="card p-6 bg-surface/50 border border-white/5">
                        <p class="text-text">“The AI tools helped us ship 4x more scripts with zero extra hires.”</p>
                        <div class="mt-4 text-sm text-text/60">— Priya, Content Ops</div>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="mt-20 card p-10 bg-surface/30 border border-white/5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-bold text-text">Get growth tips + launch updates</h3>
                        <p class="text-text/60 mt-2">Weekly playbooks to help you scale faceless content profitably.</p>
                    </div>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input type="hidden" name="source" value="pricing">
                        <input type="email" name="email" required
                            class="w-full sm:w-80 rounded-xl border border-primary/20 bg-background text-text px-4 py-3 focus:border-primary focus:ring-0"
                            placeholder="you@example.com">
                        <button type="submit" class="btn btn-primary" data-analytics-event="cta_newsletter">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>