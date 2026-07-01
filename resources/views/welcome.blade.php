<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AutomateIQ') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-theme-styles />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Vanilla Tilt -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

    <script>
        window.addEventListener('error', function(e) {
            if (e.message && e.message.indexOf('ResizeObserver') !== -1) return;
            var div = document.createElement('div');
            div.style.cssText = 'position:fixed;top:0;left:0;right:0;background:var(--danger);color:white;padding:15px;z-index:99999;font-family:monospace;font-size:12px;white-space:pre-wrap;box-shadow:0 4px 12px rgba(0,0,0,0.5);';
            div.textContent = 'JS Error: ' + e.message + '\nFile: ' + e.filename + '\nLine: ' + e.lineno + ':' + e.colno + '\nStack: ' + (e.error ? e.error.stack : 'N/A');
            document.body.appendChild(div);
        });
    </script>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}" class="welcome-dark-bg font-sans antialiased bg-background text-text overflow-x-hidden transition-colors duration-300">
    
    <!-- Ambient Background Glow Orbs -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>
    <div class="ambient-glow ambient-glow-3"></div>

    <!-- Fine Grid overlay for satin background finish -->
    <div class="fixed inset-0 -z-10 bg-grid-pattern opacity-[0.02] pointer-events-none"></div>

    <!-- Global Mouse Aura -->
    <div id="global-aura" class="fixed top-0 left-0 w-96 h-96 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-30 transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500" style="background: var(--color-accent)"></div>

    <!-- Interactive Physics Canvas Grid Background -->
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden opacity-30">
        <canvas id="physics-canvas" class="w-full h-full"></canvas>
    </div>

    <!-- Navigation Header -->
    <!-- Navigation Header — Floating Capsule -->
    <div x-data="{ open: false }">
        <nav class="capsule-nav fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-5xl transition-all duration-300">
            <div class="capsule-nav-inner flex items-center gap-2 px-2 py-2">

                <!-- Logo Pill with GradientBlinds WebGL effect -->
                <div class="capsule-nav-logo-pill relative overflow-hidden flex-shrink-0" style="z-index: 1;">
                    <div id="nav-blinds-mount" class="absolute inset-0 rounded-[inherit] overflow-hidden pointer-events-none" style="z-index: 0;"></div>
                    <a href="{{ route('home') }}" class="relative flex items-center gap-2 px-4 py-2 group" style="z-index: 10;">
                        <x-application-logo class="h-5 w-auto text-white drop-shadow-sm" />
                        <span class="font-display font-bold text-sm text-white tracking-tight whitespace-nowrap">AutomateIQ</span>
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
        </nav>

        <!-- Mobile Drawer -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 md:hidden" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="open = false"></div>
            <div class="absolute inset-y-0 left-0 w-80 max-w-[85vw] z-50">
                <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="h-full bg-[var(--color-bg)] border-r border-border p-6 flex flex-col justify-between overflow-y-auto">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-application-logo class="h-7 w-auto text-primary" />
                                <span class="font-bold text-lg text-text">AutomateIQ</span>
                            </div>
                            <button type="button" @click="open = false" class="p-2 text-text/60 hover:text-text rounded-md transition">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-8 space-y-1">
                            <a href="{{ route('tools.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-text-muted hover:text-white hover:bg-white/5 rounded-lg transition">Tools</a>
                            <a href="{{ route('workflows.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-text-muted hover:text-white hover:bg-white/5 rounded-lg transition">Workflows</a>
                            <a href="{{ route('pricing') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-text-muted hover:text-white hover:bg-white/5 rounded-lg transition">Pricing</a>
                            <a href="{{ route('blog.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-text-muted hover:text-white hover:bg-white/5 rounded-lg transition">Blog</a>
                        </div>
                    </div>
                    <div class="border-t border-border pt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm text-text-muted">
                            <span>Theme</span>
                            <x-theme-switcher />
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" @click="open = false" class="block text-center py-3 bg-surface text-black rounded-full font-bold text-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" @click="open = false" class="block text-center py-3 text-text-muted hover:text-white font-medium text-sm">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" @click="open = false" class="block text-center py-3 bg-surface text-black rounded-full font-bold text-sm">Get Started</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative pt-36 pb-24 lg:pt-52 lg:pb-36 overflow-hidden">
        <!-- Hero Background: GradientBlinds -->
        <div id="hero-blinds-mount" class="absolute inset-0 w-full h-full pointer-events-none z-0 overflow-hidden" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Live badge -->
            <div class="hero-text-reveal inline-flex items-center gap-2 px-3 py-1 border text-xs font-mono font-semibold mb-8" style="border-radius: var(--radius-sm); background: var(--color-accent-dim); border-color: var(--color-accent); color: var(--color-accent)">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-accent)"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2" style="background: var(--color-accent)"></span>
                </span>
                ⚡ AutomateIQ v2.0 is now live
            </div>

            <!-- Title -->
            <h1 class="hero-text-reveal text-gradient-hero text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-none" style="transition-delay: 0.1s;">
                Automate Your Growth, <br>
                <span>Scale Your Workflows</span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-text-reveal text-lg md:text-xl max-w-2xl mx-auto mb-12 leading-relaxed" style="color: var(--color-text-muted); transition-delay: 0.2s;">
                The all‑in‑one intelligent automation platform to streamline operations, orchestrate processes, and scale your business without the overhead.
            </p>

            <!-- CTA Actions -->
            <div class="hero-text-reveal flex flex-col sm:flex-row gap-4 justify-center items-center" style="transition-delay: 0.3s;">
                <x-ui.button variant="primary" size="lg" href="{{ route('register') }}" class="magnetic-btn group">
                    Start Free Trial
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </x-ui.button>
                <x-ui.button variant="secondary" size="lg" href="#features" class="magnetic-btn">Book Demo</x-ui.button>
            </div>

            <!-- Dashboard Mockup (Perspective + Scroll-flattening) -->
            <div class="scroll-reveal mt-20 relative max-w-5xl mx-auto" style="transition-delay: 0.4s;">
                <div class="absolute inset-0 bg-primary/20 blur-3xl -z-10 rounded-full opacity-40"></div>
                
                <div
                    class="dashboard-mockup-3d terminal-window relative rounded-2xl border border-border shadow-2xl overflow-hidden bg-[var(--color-bg)] aspect-[16/10] sm:aspect-[16/9] text-left">
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

    <!-- Signal Divider — the signature waveform, full-bleed, always visible -->
    <section class="relative py-10 border-y" style="background: var(--color-surface); border-color: var(--color-border)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-6">
            <span class="shrink-0 text-xs font-mono uppercase tracking-widest" style="color: var(--color-text-muted)">Signal</span>
            <div class="waveform flex-1" style="height: 32px; gap: 4px; justify-content: space-between;">
                @for ($i = 0; $i < 64; $i++)
                    <div class="waveform-bar"></div>
                @endfor
            </div>
            <span class="shrink-0 text-xs font-mono uppercase tracking-widest flex items-center gap-2" style="color: var(--color-text-muted)">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-signal)"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: var(--color-signal)"></span>
                </span>
                idle
            </span>
        </div>
    </section>

    <!-- Trusted By Marquee -->
    <section class="py-10 border-y border-border bg-surface/[0.01] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 text-center">
            <p class="text-sm font-semibold text-white/40 uppercase tracking-widest">Trusted by leading enterprises</p>
        </div>
        <div class="relative flex overflow-x-hidden group">
            <div class="animate-marquee whitespace-nowrap flex items-center gap-16 px-8">
                <!-- Logos -->
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">CreativOS</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">NexusMedia</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">Automata</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">StratStudio</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">Visionary</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">ScaleContent</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">FlowState</span>
            </div>
            <div class="absolute top-0 animate-marquee2 whitespace-nowrap flex items-center gap-16 px-8">
                <!-- Duplicated for infinite effect -->
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">CreativOS</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">NexusMedia</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">Automata</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">StratStudio</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">Visionary</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">ScaleContent</span>
                <span class="text-xl font-bold text-white/20 hover:text-white/40 transition">FlowState</span>
            </div>
            <!-- Gradient Masks -->
            <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-background to-transparent pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-background to-transparent pointer-events-none"></div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 scroll-reveal-stagger">
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up" data-mono data-prefix="+" data-suffix="x" data-value="12">+12x</div>
                    <div class="stat-label">Throughput Increase</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up" data-mono data-prefix="-" data-suffix="%" data-value="85">-85%</div>
                    <div class="stat-label">Operational Cost</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up" data-mono data-prefix="&lt; " data-suffix="s" data-value="1.2">&lt; 1.2s</div>
                    <div class="stat-label">API Response Time</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up" data-mono data-suffix="%" data-value="99.99">99.99%</div>
                    <div class="stat-label">Uptime SLA Guarantee</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Testimonial content -->
                <div class="hero-text-reveal">
                    <div class="section-badge mb-6">💬 Testimonials</div>
                    <h2 class="font-display text-3xl md:text-4xl font-bold mb-8" style="color: var(--color-text); font-stretch: expanded;">What our clients say</h2>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-lg leading-relaxed italic mb-6" style="color: var(--color-text-muted)">
                            "AutomateIQ integrated directly into our core tech stack. We've automated our entire lead pipeline and visual asset generation, reducing manual data entry to absolute zero."
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center font-bold font-mono border" style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border); color: var(--color-text)">JT</div>
                            <div>
                                <div class="text-sm font-semibold" style="color: var(--color-text)">Jordan Taylor</div>
                                <div class="text-xs" style="color: var(--color-text-muted)">VP of Operations, StratCorp</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Stats -->
                <div class="hero-text-reveal space-y-6">
                    <div class="strat-card spotlight-card">
                        <div class="text-5xl font-black count-up" data-mono data-suffix="%" data-value="99.9" style="color: var(--color-text); font-family: var(--font-mono)">99.9%</div>
                        <div class="text-sm mt-2 font-medium" style="color: var(--color-text-muted)">SLA Compliance &amp; Pipeline Guarantee</div>
                    </div>
                    <div class="strat-card spotlight-card">
                        <div class="text-5xl font-black count-up" data-mono data-prefix="+" data-value="45" style="color: var(--color-text); font-family: var(--font-mono)">+45M</div>
                        <div class="text-sm mt-2 font-medium" style="color: var(--color-text-muted)">API Tasks Processed Seamlessly</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid (Bento Box) -->
    <section id="features" class="py-24 relative overflow-hidden" style="background: var(--color-bg)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 hero-text-reveal">
                <div class="section-badge mb-4">⚡ SaaS Architecture</div>
                <h2 class="font-display text-4xl md:text-5xl font-bold mb-4 tracking-tight" style="color: var(--color-text); font-stretch: expanded;">Built for High‑Scale Operations</h2>
                <p class="text-lg" style="color: var(--color-text-muted)">A unified workflow engine built to replace manual scripts with event-driven automation.</p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 auto-rows-[240px] hero-text-reveal">

                <!-- Large Feature (Col span 2, Row span 2) — Script Builder with live mockup -->
                <x-ui.card padding="p-8" hoverEffect="true" class="col-span-1 md:col-span-2 md:row-span-2 flex flex-col justify-between group">
                    <div class="mb-4">
                        <div class="w-12 h-12 flex items-center justify-center mb-6 border group-hover:scale-110 transition-transform duration-300" data-card-icon style="border-radius: var(--radius-sm); background: var(--color-accent-dim); border-color: var(--color-accent)">
                            <svg class="w-6 h-6" style="color: var(--color-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2" style="color: var(--color-text)">AI Workflow Orchestrator</h3>
                        <p class="text-base" style="color: var(--color-text-muted)">Design and automate multi-step processes with logical triggers, loops, and conditional branches.</p>
                    </div>
                    <div class="relative h-40 w-full overflow-hidden mt-4 border" style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border)">
                        <div class="absolute inset-x-0 bottom-0 h-12 z-10" style="background: linear-gradient(to top, var(--color-surface-raised), transparent)"></div>
                        <div class="p-4 space-y-3 font-mono text-xs" style="color: var(--color-text-muted)">
                            <div class="flex gap-2"><span style="color: var(--color-accent)">[Trigger]</span> <span>Webhook: New User Sign Up...</span></div>
                            <div class="flex gap-2"><span style="color: var(--color-accent)">[Action]</span> <span>AI Module: Generate Welcome Asset</span></div>
                            <div class="flex gap-2"><span style="color: var(--color-accent)">[Branch]</span> <span>If Status is Active: Stream Content</span></div>
                            <div class="flex gap-2"><span style="color: var(--color-signal)">[Finish]</span> <span>API: Post to Webhook Endpoint</span></div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Medium Feature (Col span 2, Row span 1) — Scene Splitter -->
                <x-ui.card padding="p-8" hoverEffect="true" class="col-span-1 md:col-span-2 flex flex-col justify-center group relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-48 h-48 blur-3xl pointer-events-none" style="background: radial-gradient(circle, var(--color-accent-dim) 0%, transparent 70%)"></div>
                    <div class="relative z-10">
                        <div class="w-10 h-10 flex items-center justify-center mb-4 border" data-card-icon style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border)">
                            <svg class="w-5 h-5" style="color: var(--color-text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2" style="color: var(--color-text)">Real‑Time Processing</h3>
                        <p class="text-sm max-w-sm" style="color: var(--color-text-muted)">Connect custom data feeds and stream execution steps through advanced logic arrays with ultra‑low latency.</p>
                    </div>
                </x-ui.card>

                <!-- Small Feature (Col span 1, Row span 1) — AI Image Gen -->
                <x-ui.card padding="p-6" hoverEffect="true" class="col-span-1 flex flex-col group">
                    <div class="w-10 h-10 flex items-center justify-center mb-4 border" data-card-icon style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border)">
                        <svg class="w-5 h-5" style="color: var(--color-text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-1" style="color: var(--color-text)">Asset API</h3>
                    <p class="text-sm" style="color: var(--color-text-muted)">Programmatically build design assets instantly.</p>
                </x-ui.card>

                <!-- Small Feature (Col span 1, Row span 1) — High Conversion -->
                <x-ui.card padding="p-6" hoverEffect="true" class="col-span-1 flex flex-col group">
                    <div class="w-10 h-10 flex items-center justify-center mb-4 border" data-card-icon style="border-radius: var(--radius-sm); background: var(--color-signal-dim); border-color: var(--color-signal)">
                        <svg class="w-5 h-5" style="color: var(--color-signal)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold mb-1" style="color: var(--color-text)">Pipeline Scaling</h3>
                    <p class="text-sm" style="color: var(--color-text-muted)">Scale queues up to thousands of operations/sec.</p>
                </x-ui.card>

            </div>
        </div>
    </section>

    <!-- Process Section — Signal Chain (asymmetric: nodes left, live monitor offset right) -->
    <section id="process" class="py-24 relative overflow-hidden" style="background: var(--color-surface)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mb-16 hero-text-reveal">
                <div class="section-badge mb-4">🔧 Deployment</div>
                <h2 class="font-display text-3xl md:text-5xl font-bold mb-4" style="color: var(--color-text); font-stretch: expanded;">Simple 3‑Step Setup</h2>
                <p class="text-lg" style="color: var(--color-text-muted)">Go from concept to production-ready automation in minutes.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                <!-- Workflow nodes with self-drawing connectors — narrower left column -->
                <div class="workflow-section lg:col-span-7">

                    <!-- Node 01 -->
                    <div class="workflow-node hero-text-reveal">
                        <div class="flex gap-5 items-start">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="workflow-node-dot">
                                    <span class="font-mono text-xs font-bold" style="color: var(--color-accent)">01</span>
                                </div>
                                <div class="workflow-connector-wrap">
                                    <div class="workflow-connector-bg"></div>
                                    <div class="workflow-connector-fill"></div>
                                </div>
                            </div>
                            <div class="pb-12 flex-1">
                                <h3 class="text-xl font-bold mb-2" style="color: var(--color-text)">Define Your Trigger</h3>
                                <p class="text-sm leading-relaxed mb-4" style="color: var(--color-text-muted)">Select from webhooks, API events, or database changes to initiate your automated pipelines.</p>
                                <div class="border p-3 text-xs font-mono" style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border)">
                                    <span style="color: var(--color-text-muted)">trigger</span><span style="color: var(--color-border)"> → </span><span style="color: var(--color-accent)">webhook_received</span><span style="color: var(--color-border)"> · </span><span style="color: var(--color-signal)">event payload matched</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Node 02 — offset right to break the column's straight edge -->
                    <div class="workflow-node hero-text-reveal lg:ml-10">
                        <div class="flex gap-5 items-start">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="workflow-node-dot">
                                    <span class="font-mono text-xs font-bold" style="color: var(--color-accent)">02</span>
                                </div>
                                <div class="workflow-connector-wrap">
                                    <div class="workflow-connector-bg"></div>
                                    <div class="workflow-connector-fill"></div>
                                </div>
                            </div>
                            <div class="pb-12 flex-1">
                                <h3 class="text-xl font-bold mb-2" style="color: var(--color-text)">Design the Workflow</h3>
                                <p class="text-sm leading-relaxed mb-4" style="color: var(--color-text-muted)">Map steps, connect AI modules, and set up logic branches using our intuitive console.</p>
                                <div class="border p-3 space-y-1.5" style="border-radius: var(--radius-sm); background: var(--color-surface-raised); border-color: var(--color-border)">
                                    <div class="flex gap-3 text-xs font-mono"><span style="color: var(--color-accent)">[Node 1]</span><span style="color: var(--color-text-muted)">Extract content metadata</span></div>
                                    <div class="flex gap-3 text-xs font-mono"><span style="color: var(--color-accent)">[Node 2]</span><span style="color: var(--color-text-muted)">Generate design layout parameters</span></div>
                                    <div class="flex gap-3 text-xs font-mono"><span style="color: var(--color-accent)">[Node 3]</span><span style="color: var(--color-text-muted)">Run visual rendering engine API</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Node 03 — signal/live state, back to the left edge, no connector after -->
                    <div class="workflow-node hero-text-reveal">
                        <div class="flex gap-5 items-start">
                            <div class="shrink-0">
                                <div class="workflow-node-dot" style="background: var(--color-signal-dim); border-color: var(--color-signal)">
                                    <span class="font-mono text-xs font-bold" style="color: var(--color-signal)">03</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2" style="color: var(--color-text)">Monitor &amp; Orchestrate</h3>
                                <p class="text-sm leading-relaxed mb-4" style="color: var(--color-text-muted)">Deploy with one click and monitor task executions, logs, and data processing rates in real time.</p>
                                <div class="flex items-center gap-2 text-xs font-mono" style="color: var(--color-signal)">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-signal)"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2" style="background: var(--color-signal)"></span>
                                    </span>
                                    48 pipelines active — <span data-mono>240.8K</span> tasks executed this week
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Live monitor panel — offset right, sticky, breaks the centered template -->
                <div class="lg:col-span-5 hero-text-reveal lg:sticky lg:top-32 lg:mt-16">
                    <div class="border p-5" style="border-radius: var(--radius-lg); background: var(--color-surface-raised); border-color: var(--color-border)">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-mono uppercase tracking-widest" style="color: var(--color-text-muted)">Live Monitor</span>
                            <span class="flex items-center gap-1.5 text-xs font-mono" style="color: var(--color-signal)">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-signal)"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: var(--color-signal)"></span>
                                </span>
                                live
                            </span>
                        </div>
                        <div class="waveform mb-5" style="height: 48px; gap: 3px;" data-waveform="active">
                            @for ($i = 0; $i < 28; $i++)
                                <div class="waveform-bar"></div>
                            @endfor
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="border p-3" style="border-radius: var(--radius-sm); border-color: var(--color-border)">
                                <div class="text-2xl font-bold font-mono" data-mono style="color: var(--color-text)">240.8K</div>
                                <div class="text-xs" style="color: var(--color-text-muted)">Total executions</div>
                            </div>
                            <div class="border p-3" style="border-radius: var(--radius-sm); border-color: var(--color-border)">
                                <div class="text-2xl font-bold font-mono" data-mono style="color: var(--color-text)">48</div>
                                <div class="text-xs" style="color: var(--color-text-muted)">Active pipelines</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

     <!-- AI Pipeline & SLA Cost Estimator Section -->
    <section id="estimator" class="py-24 relative overflow-hidden bg-surface/[0.01] border-y border-border">
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
                            <label class="text-sm font-semibold text-white/90">Monthly Executions</label>
                            <span id="estimator-volume-label" class="text-sm font-mono font-bold text-primary">100,000 Runs</span>
                        </div>
                        <input id="estimator-volume" type="range" min="10000" max="1000000" step="10000" value="100000" class="premium-slider w-full">
                        <div class="flex justify-between text-[10px] text-white/30 font-mono mt-2">
                            <span>10K Runs</span>
                            <span>500K Runs</span>
                            <span>1M Runs</span>
                        </div>
                    </div>

                    <!-- Complexity Options -->
                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-3">Pipeline Complexity</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" data-estimator-complexity="simple" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Simple
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">1-3 Actions</span>
                            </button>
                            <button type="button" data-estimator-complexity="advanced" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition active border-primary bg-primary/10 text-white">
                                Advanced
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Multi-Agent</span>
                            </button>
                            <button type="button" data-estimator-complexity="enterprise" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Enterprise
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Complex SLA</span>
                            </button>
                        </div>
                    </div>

                    <!-- Support Level Options -->
                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-3">SLA Support Level</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" data-estimator-support="standard" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Standard
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">Community</span>
                            </button>
                            <button type="button" data-estimator-support="business" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition active border-primary bg-primary/10 text-white">
                                Business
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">8x5 Priority</span>
                            </button>
                            <button type="button" data-estimator-support="enterprise" class="px-4 py-2.5 border rounded-xl text-xs font-semibold transition border-border text-text-muted">
                                Dedicated
                                <span class="block text-[9px] font-normal text-white/40 mt-0.5">24/7 Phone</span>
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
    <section id="pricing" class="py-24 relative overflow-hidden">
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
                        <div class="text-xs text-white/40 mt-1">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                100 operations / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Core workflow editor
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Get Started</a>
                </div>

                <!-- Pro Plan -->
                <div class="hero-text-reveal pricing-card-strat spotlight-card featured flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white font-display">Scale SaaS</h3>
                            <span class="text-[10px] bg-surface/10 px-2.5 py-1 rounded-full text-white font-bold uppercase tracking-wider font-mono">Popular</span>
                        </div>
                        <div class="text-5xl font-extrabold text-white mt-4">$29</div>
                        <div class="text-xs text-white/40 mt-1 font-medium">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text-muted font-semibold">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                10,000 operations / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-semibold">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Advanced AI API keys
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-semibold">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Priority multi-step queues
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-glow w-full text-center mt-8 justify-center">Start Pro Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="hero-text-reveal pricing-card-strat spotlight-card flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-text-muted font-display">Enterprise</h3>
                        <div class="text-5xl font-extrabold text-white mt-4">$99</div>
                        <div class="text-xs text-white/40 mt-1 font-medium">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Unlimited monthly operations
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Dedicated high-speed queues
                            </li>
                            <li class="flex items-center gap-3 text-sm text-text-muted font-medium">
                                <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Custom SLA &amp; SSO compliance
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden bg-surface/[0.01]">
        <div class="absolute inset-0 bg-primary/10 blur-[100px] -z-10 rounded-full opacity-30 transform -translate-y-1/2"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 hero-text-reveal">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 rounded-2xl flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(var(--primary-rgb),0.3)]">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Ready to Scale Your SaaS Operations?</h2>
            <p class="text-text-muted text-base md:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
                Connect with our Solutions Architects for a custom walkthrough, custom SLA compliance terms, and enterprise pipeline sizing.
            </p>
            <x-ui.button variant="primary" size="lg" href="#" class="group">
                Book Solutions Call
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-strat py-16 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 items-start">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <x-application-logo class="h-6 w-auto text-white" />
                        <span class="font-display font-bold text-lg text-white tracking-tight">AutomateIQ</span>
                    </div>
                    <p class="text-white/40 text-sm max-w-sm mb-6 leading-relaxed">
                        {{ \App\Models\Setting::get('site_description', 'Enterprise-grade workflow automation, real-time AI pipelines, and cloud-scaling SaaS orchestrations.') }}
                    </p>
                    <div class="flex gap-3">
                        @if ($twitter = \App\Models\Setting::get('social_twitter'))
                            <a href="{{ $twitter }}" class="footer-social-icon text-text-muted hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                        @endif
                        @if ($facebook = \App\Models\Setting::get('social_facebook'))
                            <a href="{{ $facebook }}" class="footer-social-icon text-text-muted hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
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
            <div class="border-t border-border mt-16 pt-8 text-center text-sm text-white/40 flex flex-col sm:flex-row justify-between gap-4 font-medium font-sans">
                <div>{{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' AutomateIQ. All rights reserved.') }}</div>
                <div class="flex gap-6 justify-center">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="{{ route('affiliate') }}" class="hover:text-white transition-colors">Affiliate</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Velocity-deforming global mouse aura (spring physics lerp)
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