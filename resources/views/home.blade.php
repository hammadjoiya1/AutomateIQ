<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AutomateIQ') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|space-grotesk:400,500,600,700|space-mono:400,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-theme-styles />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vanilla Tilt -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

    <!-- Lenis Smooth Scroll -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.34/dist/lenis.min.js"></script>

    <script>
        window.addEventListener('error', function(e) {
            if (e.message && e.message.indexOf('ResizeObserver') !== -1) return;
            if (e.filename && e.filename.indexOf('chrome-extension://') !== -1) return;
            if (e.error && e.error.stack && e.error.stack.indexOf('chrome-extension') !== -1) return;
            var div = document.createElement('div');
            div.style.cssText = 'position:fixed;top:0;left:0;right:0;background:var(--danger);color:white;padding:15px;z-index:99999;font-family:monospace;font-size:12px;white-space:pre-wrap;box-shadow:0 4px 12px rgba(0,0,0,0.5);';
            div.textContent = 'JS Error: ' + e.message + '\nFile: ' + e.filename + '\nLine: ' + e.lineno + ':' + e.colno + '\nStack: ' + (e.error ? e.error.stack : 'N/A');
            document.body.appendChild(div);
        });
    </script>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="welcome-dark-bg font-sans antialiased bg-background text-text overflow-x-hidden transition-colors duration-300">

    <!-- Ambient Background Glow Orbs -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>
    <div class="ambient-glow ambient-glow-3"></div>

    <!-- Fine Grid overlay for satin background finish -->
    <div class="fixed inset-0 -z-10 bg-grid-pattern opacity-[0.02] pointer-events-none"></div>

    <!-- Global Mouse Aura — lime accent -->
    <div id="global-aura"
        class="fixed top-0 left-0 w-96 h-96 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-40 mix-blend-screen transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500"
        style="background: rgba(var(--primary-rgb), 0.12)">
    </div>

    <!-- Interactive Physics Canvas Grid Background -->
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden opacity-30">
        <canvas id="physics-canvas" class="w-full h-full"></canvas>
    </div>

    <!-- Navigation Header — Floating Capsule -->
    <div x-data="{ open: false }">
        <nav class="capsule-nav fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-5xl transition-all duration-300">
            <div class="capsule-nav-inner flex items-center gap-2 px-2 py-2">

                <!-- Logo Pill -->
                <div class="capsule-nav-logo-pill relative flex-shrink-0" style="z-index: 1;">
                    <a href="{{ route('home') }}" class="relative flex items-center gap-2 group" style="padding: 10px 20px 8px 16px; z-index: 10;">
                        <x-application-logo class="h-5 w-auto text-white drop-shadow-sm" />
                        <span class="font-display font-bold text-sm text-white tracking-tight whitespace-nowrap" style="transform: translateY(-0.5px);">AutomateIQ</span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-1 flex-1 px-2">
                    <a href="{{ route('tools.index') }}" class="capsule-nav-link">Tools</a>
                    <a href="{{ route('workflows.index') }}" class="capsule-nav-link">Workflows</a>
                    <a href="{{ route('pricing') }}" class="capsule-nav-link">Pricing</a>
                    <a href="{{ route('blog.index') }}" class="capsule-nav-link">Blog</a>
                </div>

                <!-- Spacer on mobile -->
                <div class="flex-1 md:hidden"></div>

                <!-- Auth + Theme -->
                <div class="hidden md:flex items-center gap-2 flex-shrink-0">
                    <x-theme-switcher />
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="capsule-nav-link">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="capsule-nav-link">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="capsule-cta-btn">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile hamburger -->
                <button type="button" @click="open = true"
                    class="flex items-center md:hidden text-white/70 hover:text-white p-2 rounded-lg hover:bg-white/10 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <!-- Mobile Menu Dropdown -->
            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-200 origin-top"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150 origin-top"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                class="absolute top-[calc(100%+0.5rem)] left-0 right-0 z-50 md:hidden overflow-hidden rounded-2xl bg-surface border border-border shadow-2xl shadow-black/50">
                <div class="p-6 flex flex-col max-h-[75vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                            <x-application-logo class="h-6 w-auto text-primary" />
                            <span class="font-bold text-base text-text">AutomateIQ</span>
                        </a>
                        <button type="button" @click="open = false" class="p-2 text-text-muted hover:text-text rounded-md transition bg-surface-raised">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-2 mb-8">
                        <a href="{{ route('tools.index') }}" @click="open = false" class="block px-4 py-3 text-base font-semibold text-text-muted hover:text-text hover:bg-surface-raised rounded-xl transition">Tools</a>
                        <a href="{{ route('workflows.index') }}" @click="open = false" class="block px-4 py-3 text-base font-semibold text-text-muted hover:text-text hover:bg-surface-raised rounded-xl transition">Workflows</a>
                        <a href="{{ route('pricing') }}" @click="open = false" class="block px-4 py-3 text-base font-semibold text-text-muted hover:text-text hover:bg-surface-raised rounded-xl transition">Pricing</a>
                        <a href="{{ route('blog.index') }}" @click="open = false" class="block px-4 py-3 text-base font-semibold text-text-muted hover:text-text hover:bg-surface-raised rounded-xl transition">Blog</a>
                    </div>
                    <div class="border-t border-border pt-6">
                        <div class="flex items-center justify-between text-sm text-text-muted mb-6 px-2">
                            <span class="font-medium">Theme</span>
                            <x-theme-switcher />
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <x-ui.badge variant="accent" class="w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl mb-4">
                                    <span class="text-text font-medium">Credits</span>
                                    <span class="font-bold text-primary text-base">{{ number_format(Auth::user()->credits) }}</span>
                                </x-ui.badge>
                                <x-ui.button variant="secondary" size="lg" class="w-full" href="{{ url('/dashboard') }}" @click="open = false">Dashboard</x-ui.button>
                            @else
                                <x-ui.button variant="ghost" size="lg" class="w-full mb-3 bg-surface-raised hover:bg-surface-raised/80" href="{{ route('login') }}" @click="open = false">Log in</x-ui.button>
                                @if (Route::has('register'))
                                    <x-ui.button variant="primary" size="lg" class="w-full shadow-lg shadow-primary/20" href="{{ route('register') }}" @click="open = false">Get Started</x-ui.button>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div>
    </div>
    <!-- Hero Section -->
    <section class="relative pt-36 pb-24 lg:pt-52 lg:pb-36 overflow-hidden">
        <!-- Drifting Blobs Background -->
        <canvas id="drifting-blobs-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-60"></canvas>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Eyebrow label / v2.0 Badge -->
            <div class="fu inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold mb-8"
                style="animation-delay: 0s; background: rgba(var(--primary-rgb), 0.06); border-color: rgba(var(--primary-rgb), 0.2); color: var(--color-text-muted);">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-accent);"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2" style="background: var(--color-accent);"></span>
                </span>
                ⚡ AutomateIQ v2.0 is now live
            </div>

            <!-- Headline Stacked Lines -->
            <div class="flex flex-col items-center justify-center mb-8 font-display">
                <h1 class="fu text-5xl md:text-7xl font-extrabold tracking-tight leading-none uppercase" style="animation-delay: 0.15s; margin-bottom: 0.12em;">Build.</h1>
                <h1 class="fu text-5xl md:text-7xl font-extrabold tracking-tight leading-none uppercase text-[var(--color-accent)]" style="animation-delay: 0.28s; margin-bottom: 0.12em;">Automate.</h1>
                <h1 class="fu text-5xl md:text-7xl font-extrabold tracking-tight leading-none uppercase text-white" style="animation-delay: 0.41s; margin-bottom: 0.12em;">Scale.</h1>
            </div>

            <!-- Subtext -->
            <p class="fu text-lg md:text-xl text-text-muted max-w-2xl mx-auto mb-12 leading-relaxed" style="animation-delay: 0.56s;">
                Hooks, scripts, scenes, and repurposing — one system instead of six loud ones.
            </p>

            <!-- CTA Buttons -->
            <div class="fu flex flex-col sm:flex-row gap-4 justify-center items-center" style="animation-delay: 0.7s;">
                @guest
                    <a href="{{ route('register') }}" class="btn-glow magnetic-btn">
                        Start Free Trial
                        <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#process" class="btn-ghost-strat magnetic-btn">See How It Works</a>
                @else
                    <a href="{{ route('dashboard') ?? '/dashboard' }}" class="btn-glow magnetic-btn">
                        Go to Dashboard
                        <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('dashboard') ?? '/dashboard' }}" class="btn-ghost-strat magnetic-btn">Create New Workflow</a>
                @endguest
            </div>

            <!-- Stats Row -->
            <div class="fu max-w-5xl mx-auto mt-16" style="animation-delay: 0.85s;">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-left">
                    <div class="stat-card">
                        <div class="stat-number count-up text-white" data-prefix="+" data-suffix="x" data-value="12">+12x</div>
                        <div class="stat-label">Throughput Increase</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number count-up text-white" data-prefix="-" data-suffix="%" data-value="85">-85%</div>
                        <div class="stat-label">Operational Cost</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number count-up text-white" data-prefix="< " data-suffix="s" data-value="1.2">&lt; 1.2s</div>
                        <div class="stat-label">API Response Time</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number count-up text-white" data-suffix="%" data-value="99.99">99.99%</div>
                        <div class="stat-label">Uptime SLA Guarantee</div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Mockup -->
            <div class="scroll-reveal mt-20 relative max-w-5xl mx-auto" style="transition-delay: 0.4s;">
                <div class="absolute inset-0 bg-surface/[0.03] blur-3xl -z-10 rounded-full opacity-40"></div>

                <div class="dashboard-mockup-3d terminal-window relative rounded-2xl border border-border shadow-2xl overflow-hidden bg-[var(--color-bg)] aspect-[16/10] sm:aspect-[16/9] text-left">
                    <!-- Terminal Header -->
                    <div class="terminal-header flex items-center justify-between px-4 py-3 border-b border-border bg-surface/[0.02]">
                        <div class="terminal-dots flex gap-1.5">
                            <div class="terminal-dot terminal-dot-red w-2.5 h-2.5 rounded-full"></div>
                            <div class="terminal-dot terminal-dot-yellow w-2.5 h-2.5 rounded-full"></div>
                            <div class="terminal-dot terminal-dot-green w-2.5 h-2.5 rounded-full"></div>
                        </div>
                        <div class="font-mono text-[10px] text-white/40 tracking-wider">aiq: terminal-session</div>
                        <div class="w-10"></div>
                    </div>

                    <!-- Terminal Body -->
                    <div class="terminal-body-sim flex-1 p-6 font-mono text-xs text-text-muted overflow-y-auto leading-relaxed select-none">
                        <!-- Simulated CLI typewriter output goes here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 relative overflow-hidden section-scroll-fade">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Testimonial content -->
                <div class="hero-text-reveal">
                    <div class="section-badge mb-6">💬 Testimonials</div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">What our clients say</h2>
 
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <svg viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <p class="text-lg text-text-muted leading-relaxed italic mb-6">
                            "AutomateIQ integrated directly into our core tech stack. We've automated our entire lead pipeline and visual asset generation, reducing manual data entry to absolute zero."
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-surface/10 flex items-center justify-center font-bold text-white">
                                JT</div>
                            <div>
                                <div class="text-sm font-semibold text-white">Jordan Taylor</div>
                                <div class="text-xs text-white/40">VP of Operations, StratCorp</div>
                            </div>
                        </div>
                    </div>
                </div>
 
                <!-- Testimonial Stats -->
                <div class="hero-text-reveal space-y-6">
                    <div class="strat-card">
                        <div class="text-5xl font-black text-white count-up" data-suffix="%" data-value="99.9">99.9%</div>
                        <div class="text-sm text-text-muted mt-2 font-medium">SLA Compliance &amp; Pipeline Guarantee</div>
                    </div>
                    <div class="strat-card">
                        <div class="text-5xl font-black text-white count-up" data-prefix="+" data-value="45">+45M
                        </div>
                        <div class="text-sm text-text-muted mt-2 font-medium">API Tasks Processed Seamlessly
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Creator Tools -->
    <section id="features" class="py-24 relative overflow-hidden section-scroll-fade">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 hero-text-reveal">
                <div class="section-badge mb-4">🛠️ AI WORKSPACE</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Featured Creator Tools</h2>
                <p class="text-text-muted text-lg">Power your production with high-appeal vertical video generators, caption builders, and repurposing scripts.</p>
            </div>

            <div id="features-cards-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6 scroll-reveal-stagger">
                <!-- YouTube Hook Generator -->
                <a href="{{ route('tools.show', 'youtube-hook-generator') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l-4 3v-6l4 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">YouTube Hook Generator</h3>
                        <p class="text-sm text-text-muted flex-grow">Generates attention-grabbing opening hooks for videos.</p>
                    </x-ui.card>
                </a>

                <!-- Scene Splitter (Video Factory) -->
                <a href="{{ route('tools.show', 'scene-splitter-video-factory') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-4.879-4.879L19 9.12M12 12a3 3 0 11-6 0 3 3 0 016 0zm0 0a3 3 0 116 0 3 3 0 01-6 0zm-3-3L12 12m-3 3L12 12" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">Scene Splitter (Video Factory)</h3>
                        <p class="text-sm text-text-muted flex-grow">Automatically segments scripts/videos into separate scenes.</p>
                    </x-ui.card>
                </a>

                <!-- Caption Generator -->
                <a href="{{ route('tools.show', 'caption-generator') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">Caption Generator</h3>
                        <p class="text-sm text-text-muted flex-grow">Drafts engaging captions for Instagram, TikTok, and YouTube Shorts.</p>
                    </x-ui.card>
                </a>

                <!-- Repurpose: Newsletter -->
                <a href="{{ route('tools.show', 'repurpose-newsletter') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">Repurpose: Newsletter</h3>
                        <p class="text-sm text-text-muted flex-grow">Builds full newsletters from articles, transcripts, or notes.</p>
                    </x-ui.card>
                </a>

                <!-- Blog Outline Generator -->
                <a href="{{ route('tools.show', 'blog-outline-generator') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">Blog Outline Generator</h3>
                        <p class="text-sm text-text-muted flex-grow">Outlines articles with structure, headings, and SEO directions.</p>
                    </x-ui.card>
                </a>

                <!-- AI Video Generator -->
                <a href="{{ route('tools.show', 'ai-video-generator') }}" class="group block h-full cursor-pointer">
                    <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                        <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5l-9 9M13.5 4.5l-3 3M16.5 7.5l-3 3M6 20h.01M9 17h.01M12 14h.01M3 6l3-3m0 0l3 3M6 3v9" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">AI Video Generator</h3>
                        <p class="text-sm text-text-muted flex-grow">Orchestrates AI-driven video production.</p>
                    </x-ui.card>
                </a>
            </div>

            <div class="text-center mt-16 hero-text-reveal">
                <a href="{{ route('tools.index') }}" class="btn-ghost-strat group inline-flex items-center gap-2">
                    View all 17 tools
                    <span class="inline-block transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 relative overflow-hidden bg-surface/[0.01] section-scroll-fade">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 hero-text-reveal">
                <div class="section-badge mb-4">🔧 Deployment</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Simple 3‑Step Setup</h2>
                <p class="text-text-muted text-lg">Go from concept to production-ready automation in minutes.</p>
            </div>

            <div class="timeline-container scroll-reveal-stagger">
                <div class="timeline-line"></div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">1</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                             <h3 class="text-2xl font-bold text-white mb-3">Ingest &amp; Analyze</h3>
                             <p class="text-text-muted text-sm leading-relaxed">Connect your podcast, YouTube channel, or raw footage. Our AI analyzes the content to find the most engaging hooks and moments.</p>
                        </div>
                        <div
                            class="bg-surface/5 border border-border rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            Content Ingestion Pipeline
                        </div>
                    </div>
                </div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">2</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div
                            class="order-2 md:order-1 bg-surface/5 border border-border rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            AI Script Generator
                        </div>
                        <div class="order-1 md:order-2">
                             <h3 class="text-2xl font-bold text-white mb-3">Generate Scripts &amp; Scenes</h3>
                             <p class="text-text-muted text-sm leading-relaxed">Automatically generate optimized scripts, viral hooks, and scene breakdowns tailored for TikTok, Reels, and Shorts.</p>
                        </div>
                    </div>
                </div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">3</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                             <h3 class="text-2xl font-bold text-white mb-3">Schedule &amp; Distribute</h3>
                             <p class="text-text-muted text-sm leading-relaxed">Review the repurposed assets and automatically schedule them across your social platforms from one unified dashboard.</p>
                        </div>
                        <div
                            class="bg-surface/5 border border-border rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            Multi-Platform Scheduler
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Pipeline & SLA Cost Estimator Section -->
    <section id="estimator" class="py-24 relative overflow-hidden bg-surface/[0.01] border-y border-border section-scroll-fade">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 hero-text-reveal">
                <div class="section-badge mb-4">🧮 Project Sizing</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Pipeline Cost Estimator</h2>
                <p class="text-text-muted text-lg font-medium">Estimate your customized multi-step AI workflow scaling budgets instantly.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center bg-[var(--color-bg)] border border-border rounded-2xl p-8 md:p-12 relative overflow-hidden">
                <!-- Grid decorative pattern -->
                <div class="absolute inset-0 bg-grid-pattern opacity-[0.02] pointer-events-none"></div>

                <!-- Left Column: Sliders & Controls -->
                <div class="space-y-8 relative z-10 text-left">
                    <!-- Volume Slider -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-sm font-semibold text-white/90">Hours of Video Processed</label>
                            <span id="estimator-volume-label" class="text-sm font-mono font-bold text-primary">30 Hours</span>
                        </div>
                        <input id="estimator-volume" type="range" min="10" max="100" step="10" value="30" class="premium-slider w-full">
                        <div class="flex justify-between text-[10px] text-white/30 font-mono mt-2">
                            <span>10 Hours</span>
                            <span>50 Hours</span>
                            <span>100 Hours</span>
                        </div>
                    </div>

                    <!-- Complexity Options -->
                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-3">Repurposing Strategy</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" data-estimator-complexity="simple" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Short-Form
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Reels/TikTok</span>
                            </button>
                            <button type="button" data-estimator-complexity="advanced" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition active border-primary bg-primary/10 text-white">
                                Full Episode
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">YT + Shorts</span>
                            </button>
                            <button type="button" data-estimator-complexity="enterprise" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Content Engine
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Full Distro</span>
                            </button>
                        </div>
                    </div>

                    <!-- Support Level Options -->
                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-3">Rendering Support Level</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" data-estimator-support="standard" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Standard
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">720p Render</span>
                            </button>
                            <button type="button" data-estimator-support="business" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition active border-primary bg-primary/10 text-white">
                                Priority
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">4K Render</span>
                            </button>
                            <button type="button" data-estimator-support="enterprise" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Dedicated
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Account Manager</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visual Breakdown Output -->
                <div class="flex flex-col justify-between h-full border border-border bg-surface/[0.01] rounded-xl p-8 text-center space-y-6 relative z-10">
                    <div>
                        <span id="estimator-cluster-output" class="px-3 py-1 border border-border bg-surface/5 rounded-full text-[10px] font-mono font-semibold text-text-muted">Dedicated VPC Instance</span>
                        <div class="text-[10px] text-white/40 uppercase tracking-widest font-mono mt-6">Estimated Cost</div>
                        <div id="estimator-cost-output" class="text-5xl font-extrabold text-white mt-2 font-display">$289</div>
                        <div class="text-xs text-white/40 mt-1">per month (billed annually)</div>
                    </div>

                    <div class="border-t border-border pt-6 text-left space-y-3 font-mono text-[11px]">
                        <div class="flex justify-between">
                            <span class="text-white/40">Workflow Runs Charge:</span>
                            <span id="estimator-runs-output" class="text-text-muted font-bold">$190/mo</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/40">SLA Support Coverage:</span>
                            <span id="estimator-support-output" class="text-text-muted font-bold">$99/mo</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn-glow w-full text-center justify-center">Provision This Pipeline</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 relative overflow-hidden section-scroll-fade">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 hero-text-reveal">
                <div class="section-badge mb-4">💎 Flexible Tiers</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Simple, transparent pricing</h2>
                <p class="text-text-muted text-lg font-medium">Scale pricing transparently based on your execution volumes.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-stretch scroll-reveal-stagger">
                <!-- Free Plan -->
                <div class="hero-text-reveal pricing-card-strat spotlight-card flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-text-muted font-display">Starter</h3>
                        <div class="text-5xl font-extrabold text-white mt-4">$0</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                5 hours of video processing
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Standard 720p exports
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Basic script generation
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Get
                        Started</a>
                </div>

                <!-- Pro Plan -->
                <div
                    class="hero-text-reveal pricing-card-strat pricing-highlight spotlight-card flex flex-col justify-between border-primary/40 relative shadow-[0_0_40px_rgba(var(--primary-rgb),0.1)]">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-black text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest">
                        Popular
                    </div>
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white font-display">Creator Pro</h3>
                        </div>
                        <div class="text-5xl font-extrabold text-white mt-4">$29</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-white font-medium font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                30 hours of video processing
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white font-medium font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                4K exports & Viral hook gen
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white font-medium font-sans">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Multi-platform scheduling
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-glow w-full text-center mt-8 justify-center">Start Pro Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="hero-text-reveal pricing-card-strat spotlight-card flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-text-muted font-display">Agency / Enterprise</h3>
                        <div class="text-5xl font-extrabold text-white mt-4">$99</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Unlimited video processing
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Priority rendering queues
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium font-sans">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Custom branding & API access
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}"
                        class="btn-ghost-strat w-full text-center mt-8 justify-center">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden bg-surface/[0.01] section-scroll-fade">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 hero-text-reveal">
            <div class="cta-icon-glow">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                    </path>
                </svg>
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Ready to Dominate Your Content Strategy?</h2>
            <p class="text-text-muted text-lg mb-10 max-w-2xl mx-auto">Connect your channels and start automatically repurposing your long-form videos into viral clips today.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="btn-glow magnetic-btn">
                    Start Repurposing Today
                    <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-strat py-16 relative section-scroll-fade">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 items-start">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <x-application-logo class="h-6 w-auto text-white" />
                        <span class="font-display font-bold text-lg text-white tracking-tight">AutomateIQ</span>
                    </div>
                    <p class="text-white/40 text-sm max-w-sm mb-6 leading-relaxed">
                        {{ \App\Models\Setting::get('site_description', 'Empowering digital creators with structured scripts and seamless AI-driven shorts production.') }}
                    </p>
                    <div class="flex gap-3">
                        @if ($twitter = \App\Models\Setting::get('social_twitter'))
                            <a href="{{ $twitter }}"
                                class="footer-social-icon text-text-muted hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                        @endif
                        @if ($facebook = \App\Models\Setting::get('social_facebook'))
                            <a href="{{ $facebook }}"
                                class="footer-social-icon text-text-muted hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="footer-link-group">
                    <h4 class="font-mono text-white/90">Product</h4>
                    <a href="#features">Features</a>
                    <a href="#process">Process</a>
                    <a href="#pricing">Pricing</a>
                    <a href="{{ route('blog.index') }}">Blog</a>
                </div>
                <div class="footer-link-group">
                    <h4 class="font-mono text-white/90">Company</h4>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('contact') }}">Contact</a>
                    <a href="{{ route('faq') }}">FAQ</a>
                    <a href="{{ route('demo') }}">Book Demo</a>
                </div>
            </div>
            <div
                class="border-t border-border mt-16 pt-8 text-center text-sm text-white/40 flex flex-col sm:flex-row justify-between gap-4 font-medium font-sans">
                <div>
                    {{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' AutomateIQ. All rights reserved.') }}
                </div>
                <div class="flex gap-6 justify-center">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="{{ route('affiliate') }}" class="hover:text-white transition-colors">Affiliate</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Canvas Spotlight & Advanced Motion Effects -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hero Background Canvas animation
            (function() {
                const canvas = document.getElementById('hero-bg-canvas');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                let w, h;
                const dpr = Math.min(window.devicePixelRatio || 1, 2);

                function resize() {
                    const rect = canvas.getBoundingClientRect();
                    w = rect.width;
                    h = rect.height;
                    canvas.width = w * dpr;
                    canvas.height = h * dpr;
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                }
                resize();
                window.addEventListener('resize', resize);

                // Helper to fetch theme RGB string
                function getThemeColorRgb(cssVar) {
                    const hex = getComputedStyle(document.documentElement).getPropertyValue(cssVar).trim();
                    if (!hex) return '127,127,127';
                    const clean = hex.replace('#', '');
                    if (clean.length === 3) {
                        const r = parseInt(clean[0] + clean[0], 16);
                        const g = parseInt(clean[1] + clean[1], 16);
                        const b = parseInt(clean[2] + clean[2], 16);
                        return `${r},${g},${b}`;
                    } else if (clean.length === 6) {
                        const r = parseInt(clean.slice(0, 2), 16);
                        const g = parseInt(clean.slice(2, 4), 16);
                        const b = parseInt(clean.slice(4, 6), 16);
                        return `${r},${g},${b}`;
                    }
                    return '127,127,127';
                }

                let t = 0;
                function draw() {
                    t += 0.006;
                    ctx.clearRect(0, 0, w, h);

                    // Read background from themes.css (dynamic light/dark)
                    const bgColor = getComputedStyle(document.documentElement).getPropertyValue('--color-bg').trim() || '#1A1816';
                    ctx.fillStyle = bgColor;
                    ctx.fillRect(0, 0, w, h);

                    // Scroll-based reactivity
                    const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
                    const pageScrollProgress = maxScroll > 0 ? Math.min(Math.max(window.scrollY / maxScroll, 0), 1) : 0;
                    
                    const driftAmt = 0.12 + pageScrollProgress * 0.25;
                    const radVal = Math.min(w, h) * (0.5 + pageScrollProgress * 0.3);
                    const radVal2 = Math.min(w, h) * (0.45 + pageScrollProgress * 0.3);

                    const accentRgb = getThemeColorRgb('--color-accent');
                    const signalRgb = getThemeColorRgb('--color-signal');

                    // First blob (accent)
                    const bx = w * (0.3 + Math.sin(t) * driftAmt);
                    const by = h * (0.35 + Math.cos(t * 0.8) * driftAmt);
                    const g = ctx.createRadialGradient(bx, by, 0, bx, by, radVal);
                    g.addColorStop(0, `rgba(${accentRgb}, 0.16)`);
                    g.addColorStop(1, `rgba(${accentRgb}, 0)`);
                    ctx.fillStyle = g;
                    ctx.fillRect(0, 0, w, h);

                    // Second blob (signal)
                    const bx2 = w * (0.72 - Math.cos(t * 0.7) * driftAmt);
                    const by2 = h * (0.6 - Math.sin(t * 0.9) * driftAmt);
                    const g2 = ctx.createRadialGradient(bx2, by2, 0, bx2, by2, radVal2);
                    g2.addColorStop(0, `rgba(${signalRgb}, 0.09)`);
                    g2.addColorStop(1, `rgba(${signalRgb}, 0)`);
                    ctx.fillStyle = g2;
                    ctx.fillRect(0, 0, w, h);

                    requestAnimationFrame(draw);
                }
                draw();
            })();

            // Feature/Tool Cards Scroll Mask Reveal
            (function() {
                const grid = document.getElementById('features-cards-grid');
                if (!grid) return;

                function updateMask() {
                    const rect = grid.getBoundingClientRect();
                    const viewHeight = window.innerHeight;
                    
                    // Math to calculate progress of grid transit through viewport
                    const entryPoint = viewHeight;
                    const exitPoint = 100;
                    
                    const totalDist = entryPoint - exitPoint;
                    const currentDist = entryPoint - rect.top;
                    
                    let scrollProgress = totalDist > 0 ? currentDist / totalDist : 0;
                    scrollProgress = Math.min(Math.max(scrollProgress, 0), 1);
                    
                    const revealPct = scrollProgress * 130;
                    grid.style.maskImage = `linear-gradient(to right, black ${revealPct}%, transparent ${revealPct + 15}%)`;
                    grid.style.webkitMaskImage = `linear-gradient(to right, black ${revealPct}%, transparent ${revealPct + 15}%)`;
                }

                window.addEventListener('scroll', updateMask, { passive: true });
                window.addEventListener('resize', updateMask);
                updateMask();
            })();

            // Bento Grid Mouse Spotlight Effect
            document.addEventListener('mousemove', (e) => {
                document.querySelectorAll('.strat-card, .stat-card').forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                });
            });



            // Primary CTA — Magnetic Pull
            (function() {
                const magneticBtns = document.querySelectorAll('.magnetic-btn');
                
                magneticBtns.forEach(btn => {
                    let currentX = 0;
                    let currentY = 0;
                    let targetX = 0;
                    let targetY = 0;
                    
                    window.addEventListener('mousemove', (e) => {
                        const rect = btn.getBoundingClientRect();
                        const btnCenterX = rect.left + window.scrollX + rect.width / 2;
                        const btnCenterY = rect.top + window.scrollY + rect.height / 2;
                        
                        const mouseX = e.pageX;
                        const mouseY = e.pageY;
                        
                        const dist = Math.hypot(mouseX - btnCenterX, mouseY - btnCenterY);
                        
                        if (dist < 90) {
                            const diffX = mouseX - btnCenterX;
                            const diffY = mouseY - btnCenterY;
                            targetX = diffX * 0.4;
                            targetY = diffY * 0.4;
                        } else {
                            targetX = 0;
                            targetY = 0;
                        }
                    });
                    
                    btn.addEventListener('mouseleave', () => {
                        targetX = 0;
                        targetY = 0;
                    });
                    
                    function updatePhysics() {
                        currentX += (targetX - currentX) * 0.18;
                        currentY += (targetY - currentY) * 0.18;
                        
                        btn.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;
                        requestAnimationFrame(updatePhysics);
                    }
                    updatePhysics();
                });
            })();



            // Velocity-Deforming Global Mouse Aura (Spring Physics Lerp)
            const aura = document.getElementById('global-aura');
            if (aura) {
                let mouseX = window.innerWidth / 2;
                let mouseY = window.innerHeight / 2;
                let auraX = mouseX;
                let auraY = mouseY;

                // Spring Physics Variables
                let vx = 0;
                let vy = 0;
                const stiffness = 0.08;
                const damping = 0.65;

                // Deformation Springs (Stretch/Squash elasticity)
                let currentScaleX = 1;
                let currentScaleY = 1;
                let scaleVx = 0;
                let scaleVy = 0;

                window.addEventListener('mousemove', (e) => {
                    mouseX = e.clientX;
                    mouseY = e.clientY;
                });

                function animateAura() {
                    const ax = (mouseX - auraX) * stiffness;
                    const ay = (mouseY - auraY) * stiffness;

                    vx = (vx + ax) * damping;
                    vy = (vy + ay) * damping;

                    auraX += vx;
                    auraY += vy;

                    const angle = Math.atan2(vy, vx);
                    const speed = Math.sqrt(vx * vx + vy * vy);

                    // Physical deformation target values
                    const targetScaleX = 1 + Math.min(speed * 0.04, 1.0);
                    const targetScaleY = 1 - Math.min(speed * 0.02, 0.4);

                    // Spring physics for scale decay
                    const scaleStiffness = 0.12;
                    const scaleDamping = 0.68;

                    scaleVx = (scaleVx + (targetScaleX - currentScaleX) * scaleStiffness) * scaleDamping;
                    scaleVy = (scaleVy + (targetScaleY - currentScaleY) * scaleStiffness) * scaleDamping;

                    currentScaleX += scaleVx;
                    currentScaleY += scaleVy;

                    aura.style.transform = `translate3d(${auraX}px, ${auraY}px, 0) translate(-50%, -50%) rotate(${angle}rad) scale(${currentScaleX}, ${currentScaleY})`;

                    requestAnimationFrame(animateAura);
                }
                animateAura();
            }
        });
    </script>
</body>

</html>