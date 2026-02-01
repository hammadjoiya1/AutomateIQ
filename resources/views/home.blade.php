<x-public-layout>
    <!-- 1) Hero Section -->
    <div class="hero relative overflow-hidden pt-20 pb-20 lg:pt-32 lg:pb-28" data-hero-3d>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50/50 border border-blue-100 text-primary text-xs font-semibold uppercase tracking-wide mb-8 animate-fade-in-up">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                v2.0 Now Available
            </div>

            <h1
                class="text-5xl lg:text-7xl font-black font-display text-text tracking-tight mb-8 leading-tight animate-fade-in-up delay-100">
                Build Your Empire <br>
                <span class="text-gradient" data-scramble>Without a Camera</span>
            </h1>

            <p class="text-xl text-text/60 max-w-2xl mx-auto mb-6 leading-relaxed animate-fade-in-up delay-200">
                AutomateIQ delivers creator-grade hooks, viral ideas, short scripts, scene splitters, video prompts, and
                multi-platform repurposing—built for faceless growth on YouTube, TikTok, and Reels.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto mb-8">
                <div class="card p-4 bg-surface/40 border border-white/5">
                    <div class="text-2xl font-bold text-text">+4x</div>
                    <div class="text-sm text-text/60">Content output</div>
                </div>
                <div class="card p-4 bg-surface/40 border border-white/5">
                    <div class="text-2xl font-bold text-text">-70%</div>
                    <div class="text-sm text-text/60">Planning time</div>
                </div>
                <div class="card p-4 bg-surface/40 border border-white/5">
                    <div class="text-2xl font-bold text-text">< 5 min</div>
                    <div class="text-sm text-text/60">First win</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-300">
                <a href="{{ route('register') }}" class="btn btn-lg btn-primary" data-analytics-event="cta_signup_hero"
                    data-abtest="hero-cta" data-variants='[{"id":"a","text":"Start Free (No card)"},{"id":"b","text":"Start Free in 60s"}]'>
                    Start Free (No card)
                </a>
                <a href="{{ route('pricing') }}" class="btn btn-lg btn-secondary" data-analytics-event="cta_pricing_hero">
                    See Pricing
                </a>
            </div>

            <!-- Hero Dashboard Mockup -->
            <div class="mt-16 relative mx-auto max-w-5xl animate-fade-in-up delay-500">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary to-accent rounded-2xl blur opacity-30">
                </div>
                <div
                    class="dashboard-3d relative rounded-2xl border border-primary/10 bg-card/50 backdrop-blur-xl shadow-2xl overflow-hidden aspect-[16/9]">
                    <div class="absolute inset-0 bg-gradient-to-t from-background/40 to-transparent z-10"></div>
                    <div
                        class="w-full h-full bg-card/20 flex items-center justify-center text-text/20 font-bold text-2xl relative z-0">
                        <!-- Pseudo UI lines for 4D feel -->
                        <div
                            class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-primary via-transparent to-transparent">
                        </div>
                        Dashboard Preview
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] opacity-50 animate-float parallax"
                data-parallax data-speed="0.3">
            </div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[600px] h-[600px] bg-accent/10 rounded-full blur-[100px] opacity-50 animate-float"
                style="animation-delay: 2s;"></div>
        </div>
    </div>

    <!-- 2) Trust / Social Proof -->
    <div class="py-12 border-y border-primary/5 bg-background/30 backdrop-blur-sm overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold text-text/40 uppercase tracking-widest mb-8">Trusted by creators & teams</p>

            <div class="relative w-full overflow-hidden">
                <div class="flex w-[200%] animate-scroll hover:[animation-play-state:paused] gap-20">
                    <div
                        class="flex items-center gap-20 opacity-40 grayscale hover:grayscale-0 transition-all duration-500 shrink-0">
                        <span class="text-3xl font-black font-display text-text">YouTube</span>
                        <span class="text-3xl font-black font-display text-text">TikTok</span>
                        <span class="text-3xl font-black font-display text-text">Instagram</span>
                        <span class="text-3xl font-black font-display text-text">X</span>
                        <span class="text-3xl font-black font-display text-text">LinkedIn</span>
                        <span class="text-3xl font-black font-display text-text">Pinterest</span>
                    </div>
                    <div
                        class="flex items-center gap-20 opacity-40 grayscale hover:grayscale-0 transition-all duration-500 shrink-0">
                        <span class="text-3xl font-black font-display text-text">YouTube</span>
                        <span class="text-3xl font-black font-display text-text">TikTok</span>
                        <span class="text-3xl font-black font-display text-text">Instagram</span>
                        <span class="text-3xl font-black font-display text-text">X</span>
                        <span class="text-3xl font-black font-display text-text">LinkedIn</span>
                        <span class="text-3xl font-black font-display text-text">Pinterest</span>
                    </div>
                </div>

                <div
                    class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-background to-transparent z-10 pointer-events-none">
                </div>
                <div
                    class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-background to-transparent z-10 pointer-events-none">
                </div>
            </div>
        </div>
    </div>

    <!-- 3) Features Section -->
    <div class="py-24 bg-surface/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold font-display text-text mb-4">Everything you need to ship faceless content</h2>
                <p class="text-text/60 text-lg">From hook to script to scenes and repurposing—built for short‑form growth.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-stagger-reveal>
                <div class="card p-8 rounded-2xl bg-card border border-primary/10 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group card-3d"
                    data-tilt>
                    <div
                        class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center mb-6 text-primary group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-3">Creator‑grade idea → script pipeline</h3>
                    <p class="text-text/60 leading-relaxed mb-4">Hooks • Viral ideas • Short scripts • Scene splitters</p>
                    <p class="text-sm text-text/50">Build retention‑first content for Shorts, TikTok, and Reels in minutes.</p>
                </div>

                <div class="card p-8 rounded-2xl bg-card border border-primary/10 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group card-3d"
                    data-tilt>
                    <div
                        class="w-14 h-14 rounded-xl bg-purple-500/10 flex items-center justify-center mb-6 text-purple-500 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-3">Repurpose across platforms</h3>
                    <p class="text-text/60 leading-relaxed mb-4">LinkedIn • X Threads • Newsletters</p>
                    <p class="text-sm text-text/50">Turn one idea into multi‑platform distribution without extra work.</p>
                </div>

                <div class="card p-8 rounded-2xl bg-card border border-primary/10 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group card-3d"
                    data-tilt>
                    <div
                        class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mb-6 text-accent group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-3">Save & reuse outputs</h3>
                    <p class="text-text/60 leading-relaxed mb-4">Collections • Favorites • Search</p>
                    <p class="text-sm text-text/50">Organize all your generated content in one searchable library.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3.5) Creator Packs -->
    <div class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Creator Packs (ready‑to‑use outputs)</h2>
                <p class="text-lg text-text/60">Productized templates built directly from the tools you’re already using.</p>
            </div>

            @php
                $creatorPacks = [
                    ['name' => 'Hook Pack', 'desc' => '150 retention‑first hooks, angles, and variants.', 'tools' => 'YouTube Hook Generator'],
                    ['name' => 'Script Pack', 'desc' => '15s/30s/60s short‑form script templates.', 'tools' => 'Script Generator (Short)'],
                    ['name' => 'Scene Splitter Pack', 'desc' => 'Shot lists + b‑roll cues from scripts.', 'tools' => 'Scene Splitter'],
                    ['name' => 'Repurpose Pack', 'desc' => 'Threads, LinkedIn posts, and newsletters.', 'tools' => 'Repurpose: X / LinkedIn / Newsletter'],
                    ['name' => 'Idea Calendar', 'desc' => '30‑day idea plan with hooks + angles.', 'tools' => 'Viral Video Ideas Generator'],
                    ['name' => 'Video Prompt Pack', 'desc' => 'High‑signal prompts for AI video creation.', 'tools' => 'AI Video Generator'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($creatorPacks as $pack)
                    <div class="card p-6 rounded-2xl bg-card border border-primary/10 hover:shadow-xl hover:-translate-y-1 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-text">{{ $pack['name'] }}</h3>
                            <span class="text-xs font-semibold text-primary bg-primary/10 px-2 py-1 rounded-full">Included</span>
                        </div>
                        <p class="text-sm text-text/60 mb-4">{{ $pack['desc'] }}</p>
                        <div class="text-xs text-text/50">Built from: <span class="text-text/70">{{ $pack['tools'] }}</span></div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('tools.index') }}" class="btn btn-lg btn-primary">Explore the Packs</a>
                <p class="text-xs text-text/50 mt-3">Packs are generated in‑app using your tools and presets.</p>
            </div>
        </div>
    </div>

    <!-- 3.6) Niche Pack Versions -->
    <div class="py-24 bg-surface/40">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-3xl font-bold font-display text-text mb-3">Choose your niche</h2>
                <p class="text-text/60">Get pre‑tuned packs for your audience and content style.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-lg font-bold text-text mb-2">Fitness</h3>
                    <p class="text-sm text-text/60">High‑energy hooks, transformation scripts, workout scene lists.</p>
                </div>
                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-lg font-bold text-text mb-2">Finance</h3>
                    <p class="text-sm text-text/60">Clarity hooks, myth‑busting scripts, visual breakdown prompts.</p>
                </div>
                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-lg font-bold text-text mb-2">SaaS</h3>
                    <p class="text-sm text-text/60">Problem/solution hooks, demo scripts, feature scene prompts.</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('register') }}" class="btn btn-secondary">Get a niche pack</a>
            </div>
        </div>
    </div>

    <!-- 3.7) Before / After + 30-Day Sample -->
    <div class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div class="card p-8 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-2xl font-bold text-text mb-4">Before → After (example)</h3>
                    <div class="space-y-4 text-sm text-text/70">
                        <div>
                            <div class="text-xs text-text/50 uppercase tracking-widest">Before</div>
                            <div class="mt-2 text-text">“AI tools can help your business.”</div>
                        </div>
                        <div>
                            <div class="text-xs text-text/50 uppercase tracking-widest">After</div>
                            <div class="mt-2 text-text">“I automated 4 client tasks in 1 afternoon—here’s the exact workflow and the 3 mistakes I avoided.”</div>
                        </div>
                    </div>
                </div>

                <div class="card p-8 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-2xl font-bold text-text mb-4">30‑Day Plan Sample</h3>
                    <ul class="space-y-3 text-sm text-text/70">
                        <li>Week 1: 5 hooks + 3 scripts + 1 scene list</li>
                        <li>Week 2: 7 hooks + 3 scripts + 1 repurpose thread</li>
                        <li>Week 3: 7 hooks + 4 scripts + 1 LinkedIn post</li>
                        <li>Week 4: 6 hooks + 4 scripts + 1 newsletter</li>
                    </ul>
                    <div class="mt-6 text-xs text-text/50">Full calendar included in the Idea Calendar pack.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3.8) Free Mini Pack CTA -->
    <div class="py-20 bg-gradient-to-br from-primary/10 to-accent/10">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h3 class="text-3xl font-bold font-display text-text mb-3">Get a free mini‑pack</h3>
            <p class="text-text/60 mb-6">Try 10 hooks + 2 scripts + 1 repurpose template to prove the value.</p>
            <a href="{{ route('register') }}" class="btn btn-lg btn-primary">Claim the free mini‑pack</a>
        </div>
    </div>

    <!-- 4) Tools Directory Preview -->
    <div class="py-24 bg-gradient-to-b from-background to-card/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Powerful AI Tools</h2>
                <p class="text-xl text-text/60">Focused tools built for faceless creators</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $sampleTools = [
                        ['name' => 'Hook Generator', 'desc' => 'Retention‑first hooks with scoring', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'blue'],
                        ['name' => 'Viral Video Ideas', 'desc' => 'Idea lists with hooks + angles', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'color' => 'yellow'],
                        ['name' => 'Short Script Generator', 'desc' => 'Time‑coded scripts with b‑roll', 'icon' => 'M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z', 'color' => 'purple'],
                        ['name' => 'Scene Splitter', 'desc' => 'Script → visual scenes (JSON)', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'color' => 'pink'],
                        ['name' => 'AI Video Generator', 'desc' => 'High‑signal video prompts', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'red'],
                        ['name' => 'Repurpose: LinkedIn', 'desc' => 'Hook + insights + CTA format', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'color' => 'indigo'],
                        ['name' => 'Repurpose: X Thread', 'desc' => 'Numbered threads with CTA', 'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14', 'color' => 'green'],
                        ['name' => 'Repurpose: Newsletter', 'desc' => 'Subject + sections + CTA', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'cyan'],
                    ];
                @endphp

                @foreach($sampleTools as $tool)
                    <div
                        class="card bg-card rounded-xl p-6 border border-primary/10 hover:border-primary/50 hover:shadow-lg transition-all duration-300 group">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $tool['icon'] }}"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-text mb-2">{{ $tool['name'] }}</h4>
                        <p class="text-sm text-text/60 mb-4">{{ $tool['desc'] }}</p>
                        <a href="{{ route('tools.index') }}" class="text-primary text-sm font-medium hover:underline">Try
                            Tool →</a>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('tools.index') }}"
                    class="inline-flex items-center gap-2 btn btn-lg btn-primary shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                    View All Tools
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 5) Workflow Builder Section (MOST IMPORTANT) -->
    <div class="py-24 bg-gradient-to-br from-primary/5 to-accent/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Automations That Create Content For You</h2>
                <p class="text-xl text-text/60 max-w-2xl mx-auto">Set up workflows once, get fresh content every day
                    automatically</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="space-y-4 mb-12">
                    @php
                        $workflowSteps = [
                            ['num' => 1, 'title' => 'Generate Video Ideas', 'desc' => 'Idea lists with hooks + angles', 'color' => 'blue'],
                            ['num' => 2, 'title' => 'Create Hooks', 'desc' => 'Retention‑first openers with payoff', 'color' => 'purple'],
                            ['num' => 3, 'title' => 'Write Short Script', 'desc' => 'Time‑coded script with b‑roll', 'color' => 'pink'],
                            ['num' => 4, 'title' => 'Split Into Scenes', 'desc' => 'Auto scene breakdown for video', 'color' => 'orange'],
                            ['num' => 5, 'title' => 'Repurpose Across Platforms', 'desc' => 'LinkedIn, X, newsletter formats', 'color' => 'green'],
                        ];
                    @endphp

                    @foreach($workflowSteps as $step)
                        <div
                            class="card bg-card rounded-xl p-6 border-2 border-primary/10 hover:border-primary/50 transition-all duration-300 group relative">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold group-hover:scale-110 transition-transform">
                                    {{ $step['num'] }}
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-bold text-lg text-text mb-1">{{ $step['title'] }}</h4>
                                    <p class="text-text/60">{{ $step['desc'] }}</p>
                                </div>
                                <svg class="w-6 h-6 text-primary opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </div>
                            @if(!$loop->last)
                                <div class="absolute left-[29px] top-[70px] w-0.5 h-6 bg-primary/20">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 btn btn-lg btn-primary shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all">
                        Create Your First Workflow
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 6) How It Works -->
    <div class="py-24 bg-card">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-display text-text mb-4">How It Works</h2>
                <p class="text-xl text-text/60">Get started in 3 simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div
                        class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-text mb-3">1. Pick a Tool</h3>
                    <p class="text-text/60">Choose focused tools for hooks, ideas, scripts, scenes, and repurposing</p>
                </div>

                <div class="text-center group">
                    <div
                        class="w-20 h-20 rounded-full bg-purple-500/10 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-text mb-3">2. Generate Content</h3>
                    <p class="text-text/60">Get AI-powered results in seconds</p>
                </div>

                <div class="text-center group">
                    <div
                        class="w-20 h-20 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-text mb-3">3. Automate the Workflow</h3>
                    <p class="text-text/60">Set it and forget it - daily content on autopilot</p>
                </div>
            </div>

            <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card p-8 rounded-2xl bg-background border border-primary/10">
                    <h3 class="text-2xl font-bold text-text mb-4">How AI automation works</h3>
                    <ul class="space-y-4 text-sm text-text/70">
                        <li><span class="font-semibold text-text">1) Chain tools:</span> Ideas → Hooks → Scripts → Scenes → Repurpose.</li>
                        <li><span class="font-semibold text-text">2) Set inputs once:</span> niche, tone, length, format.</li>
                        <li><span class="font-semibold text-text">3) Run on schedule:</span> generate daily outputs and save to your library.</li>
                    </ul>
                </div>

                <div class="card p-8 rounded-2xl bg-background border border-primary/10">
                    <h3 class="text-2xl font-bold text-text mb-4">Example workflow</h3>
                    <div class="space-y-3 text-sm text-text/70">
                        <div class="flex items-center gap-3"><span class="text-primary font-semibold">Step 1</span> Generate 10 ideas</div>
                        <div class="flex items-center gap-3"><span class="text-primary font-semibold">Step 2</span> Write 5 hooks</div>
                        <div class="flex items-center gap-3"><span class="text-primary font-semibold">Step 3</span> Draft a 60‑sec script</div>
                        <div class="flex items-center gap-3"><span class="text-primary font-semibold">Step 4</span> Split into scenes</div>
                        <div class="flex items-center gap-3"><span class="text-primary font-semibold">Step 5</span> Repurpose for LinkedIn + X</div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('workflows.create') }}" class="btn btn-sm btn-primary">Build this workflow</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7) Pricing Section -->
    <div class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-text/60">Start free, upgrade when you're ready</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-card rounded-2xl p-8 border-2 border-primary/10 hover:border-primary/30 transition-all">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-text mb-2">Free</h3>
                        <div class="text-5xl font-black text-text mb-2">$0</div>
                        <p class="text-text/60">Perfect for getting started</p>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-text">5 tool runs/day</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-text">Free mini‑pack access</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-text/60">No workflows</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-text/60">0 videos/day</span>
                        </li>
                    </ul>

                    <a href="{{ route('register') }}"
                        class="block w-full py-3 px-6 text-center bg-background border border-primary/20 text-text font-semibold rounded-xl hover:bg-primary/5 transition-colors">
                        Get Started Free
                    </a>
                </div>

                <!-- Pro Plan -->
                <div
                    class="bg-gradient-to-br from-primary to-accent rounded-2xl p-8 text-white relative overflow-hidden shadow-2xl hover:shadow-primary/50 transition-all transform hover:-translate-y-1">
                    <div
                        class="absolute top-0 right-0 bg-yellow-400 text-gray-900 px-4 py-1 text-xs font-bold rounded-bl-xl">
                        POPULAR
                    </div>

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">Pro</h3>
                        <div class="text-5xl font-black mb-2">$29</div>
                        <p class="text-white/80">Per month</p>
                    </div>

                            <span>100 tool runs/day</span>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>200 runs/day</span>
                            <span>5 videos/day</span>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Workflows enabled</span>
                            <span>All Creator Packs + niches</span>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Unlimited library</span>
                            <span>Automated Workflows</span>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Priority tools</span>
                            <span>Priority Support</span>
                    </ul>

                    <a href="{{ route('register') }}" data-analytics-event="cta_upgrade_pro"
                        class="block w-full py-3 px-6 text-center bg-white text-primary font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                        Upgrade to Pro
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 8.5) Newsletter -->
    <div class="py-20 bg-background">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="card p-10 bg-surface/30 border border-white/5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h3 class="text-2xl font-bold text-text">Get weekly growth playbooks</h3>
                    <p class="text-text/60 mt-2">Tips, templates, and AI workflows to monetize faceless content.</p>
                </div>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="hidden" name="source" value="home">
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

    <!-- 8) Testimonials -->
    <div class="py-24 bg-card/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Loved by Creators</h2>
                <p class="text-xl text-text/60">See what our users are saying</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $testimonials = [
                        ['quote' => 'I made 30 shorts ideas in 10 minutes', 'name' => 'Sarah Chen', 'role' => 'Content Creator', 'avatar' => 'SC'],
                        ['quote' => 'Automation saved me hours daily', 'name' => 'Marcus Rodriguez', 'role' => 'YouTuber', 'avatar' => 'MR'],
                        ['quote' => 'Best toolkit for faceless creators', 'name' => 'Alex Kim', 'role' => 'TikTok Creator', 'avatar' => 'AK'],
                    ];
                @endphp

                @foreach($testimonials as $testimonial)
                    <div
                        class="card bg-background p-8 rounded-2xl border border-primary/10 hover:border-primary/50 hover:shadow-lg transition-all">
                        <div class="flex items-center gap-1 mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z">
                                    </path>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-xl font-medium text-text mb-6">"{{ $testimonial['quote'] }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">
                                {{ $testimonial['avatar'] }}
                            </div>
                            <div>
                                <div class="font-bold text-text">{{ $testimonial['name'] }}</div>
                                <div class="text-sm text-text/60">{{ $testimonial['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 8.2) Case Studies -->
    <div class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold font-display text-text mb-4">Case Studies</h2>
                <p class="text-text/60">Real outcomes from creators and teams</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card p-8 border border-primary/10">
                    <div class="text-sm text-primary font-semibold">YouTube Shorts</div>
                    <div class="text-2xl font-bold text-text mt-2">+210% views</div>
                    <p class="text-text/60 mt-3">Scaled from 3 to 10 videos/week using automated scripts.</p>
                </div>
                <div class="card p-8 border border-primary/10">
                    <div class="text-sm text-primary font-semibold">Agency</div>
                    <div class="text-2xl font-bold text-text mt-2">-60% costs</div>
                    <p class="text-text/60 mt-3">Reduced production time while increasing client output.</p>
                </div>
                <div class="card p-8 border border-primary/10">
                    <div class="text-sm text-primary font-semibold">TikTok Creator</div>
                    <div class="text-2xl font-bold text-text mt-2">$4.2k/mo</div>
                    <p class="text-text/60 mt-3">Turned daily ideas into consistent monetized content.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 9) Final CTA -->
    <div class="py-24 bg-gradient-to-br from-primary/10 to-accent/10 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-5xl lg:text-6xl font-bold font-display text-text mb-8">Ready to build your empire?</h2>
            <p class="text-xl text-text/60 mb-10 max-w-2xl mx-auto">Join thousands of creators using AI to scale their
                content without showing their face</p>
            <a href="{{ route('register') }}" data-analytics-event="cta_signup_final"
                class="inline-flex items-center gap-2 btn btn-lg btn-primary shadow-2xl hover:-translate-y-1 transition-all">
                Start Free Today
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </a>
            <p class="text-sm text-text/50 mt-6">No card required.</p>
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-primary/5 pointer-events-none"></div>
    </div>

</x-public-layout>