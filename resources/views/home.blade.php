<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AutomateIQ') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|inter:400,500,600&display=swap"
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
            var div = document.createElement('div');
            div.style.cssText = 'position:fixed;top:0;left:0;right:0;background:#ef4444;color:white;padding:15px;z-index:99999;font-family:monospace;font-size:12px;white-space:pre-wrap;box-shadow:0 4px 12px rgba(0,0,0,0.5);';
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

    <!-- Global Mouse Aura -->
    <div id="global-aura"
        class="fixed top-0 left-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-50 mix-blend-screen transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500">
    </div>

    <!-- Navigation Header — Floating Capsule -->
    <div x-data="{ open: false }">
        <nav class="capsule-nav fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-5xl transition-all duration-300">
            <div class="capsule-nav-inner flex items-center gap-2 px-2 py-2">

                <!-- Logo Pill with GradientBlinds WebGL effect -->
                <div class="capsule-nav-logo-pill relative overflow-hidden flex-shrink-0">
                    <div id="nav-blinds-mount" class="absolute inset-0 rounded-[inherit] overflow-hidden"></div>
                    <a href="{{ route('home') }}" class="relative z-10 flex items-center gap-2 px-4 py-2 group">
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
                    class="h-full bg-[#0a0a0f] border-r border-white/10 p-6 flex flex-col justify-between overflow-y-auto">
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
                            <a href="{{ route('tools.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Tools</a>
                            <a href="{{ route('workflows.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Workflows</a>
                            <a href="{{ route('pricing') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Pricing</a>
                            <a href="{{ route('blog.index') }}" @click="open = false" class="block py-2 px-3 text-base font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Blog</a>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm text-white/60">
                            <span>Theme</span>
                            <x-theme-switcher />
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" @click="open = false" class="block text-center py-3 bg-white text-black rounded-full font-bold text-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" @click="open = false" class="block text-center py-3 text-white/60 hover:text-white font-medium text-sm">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" @click="open = false" class="block text-center py-3 bg-white text-black rounded-full font-bold text-sm">Get Started</a>
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
            <!-- v2.0 Badge -->
            <div
                class="hero-text-reveal inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-white/70 text-xs font-semibold mb-8">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                ⚡ AutomateIQ v2.0 is now live
            </div>

            <!-- Title -->
            <h1 class="hero-text-reveal text-gradient-hero text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-none"
                style="transition-delay: 0.1s;">
                Automate Your Growth, <br>
                <span>Scale Your Workflows</span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-text-reveal text-lg md:text-xl text-white/50 max-w-2xl mx-auto mb-12 leading-relaxed"
                style="transition-delay: 0.2s;">
                The all‑in‑one intelligent automation platform to streamline operations, orchestrate processes, 
                and scale your business without the overhead.
            </p>

            <!-- CTA Actions -->
            <div class="hero-text-reveal flex flex-col sm:flex-row gap-4 justify-center items-center"
                style="transition-delay: 0.3s;">
                <a href="{{ route('register') }}" class="btn-glow magnetic-btn">
                    Start Free Trial
                    <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#features" class="btn-ghost-strat magnetic-btn">Book Demo</a>
            </div>

            <!-- Dashboard Mockup (Perspective + Scroll-flattening) -->
            <div class="scroll-reveal mt-20 relative max-w-5xl mx-auto" style="transition-delay: 0.4s;">
                <div class="absolute inset-0 bg-white/[0.03] blur-3xl -z-10 rounded-full opacity-40"></div>

                <div
                    class="dashboard-mockup-3d relative rounded-2xl border border-white/[0.06] shadow-2xl overflow-hidden bg-[#0a0a0a] aspect-[16/10] sm:aspect-[16/9]">
                    <!-- Browser Header -->
                    <div class="flex items-center gap-2 px-4 py-3 border-b border-white/5 bg-white/5">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        </div>
                        <div
                            class="mx-auto flex items-center justify-center bg-[#050505] border border-white/[0.06] rounded-md px-3 py-1 text-xs text-white/40 w-1/3">
                            <svg class="w-3 h-3 mr-2 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                            </svg>
                            app.automateiq.com
                        </div>
                        <div class="w-10"></div>
                    </div>

                    <!-- Inner Mock Dashboard -->
                    <div
                        class="p-6 h-full bg-[#0a0a0a] text-left relative overflow-hidden flex flex-col justify-between">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-white/40 font-mono">System Status</span>
                                <h4 class="text-lg font-bold text-white mt-1">SaaS Workflow Console</h4>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-white/80">
                                    ⚡ 48 Active Pipelines</div>
                                <div
                                    class="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-white/80">
                                    Enterprise Plan</div>
                            </div>
                        </div>

                        <!-- Mock Graph Content -->
                        <div class="grid grid-cols-3 gap-4 mt-6 flex-1">
                            <div
                                class="col-span-2 bg-[#050505] border border-white/[0.06] rounded-xl p-4 flex flex-col justify-between relative overflow-hidden">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <div class="text-[10px] text-white/40 uppercase">Tasks Automated</div>
                                        <div class="text-2xl font-extrabold text-white mt-1">240.8K <span
                                                class="text-xs text-green-500 font-semibold">+24.6%</span></div>
                                    </div>
                                    <div
                                        class="text-[10px] text-white/40 bg-white/5 border border-white/10 rounded px-2 py-1">
                                        Active Console</div>
                                </div>
                                <!-- Mock Line Chart SVG -->
                                <svg class="w-full h-32 text-primary" viewBox="0 0 300 100" fill="none">
                                    <path d="M0,90 Q50,70 100,50 T200,30 T300,10" stroke="currentColor" stroke-width="3"
                                        fill="none" stroke-linecap="round" />
                                    <path d="M0,90 Q50,70 100,50 T200,30 T300,10 L300,100 L0,100 Z" fill="url(#grad)"
                                        opacity="0.1" />
                                    <defs>
                                        <linearGradient id="grad" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="currentColor" />
                                            <stop offset="100%" stop-color="transparent" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div
                                class="bg-[#050505] border border-white/[0.06] rounded-xl p-4 flex flex-col justify-between">
                                <div>
                                    <div class="text-[10px] text-white/40 uppercase">Avg Run Time</div>
                                    <div class="text-2xl font-extrabold text-white mt-1">1.24s <span
                                            class="text-xs text-green-500 font-semibold">-28.5%</span></div>
                                </div>
                                <!-- Mock Bar Chart -->
                                <div class="flex items-end justify-between gap-1 h-20">
                                    <div class="bg-white/10 w-full h-8 rounded-sm"></div>
                                    <div class="bg-white/10 w-full h-12 rounded-sm"></div>
                                    <div class="bg-white/10 w-full h-16 rounded-sm"></div>
                                    <div class="bg-primary w-full h-24 rounded-sm"></div>
                                    <div class="bg-white/10 w-full h-14 rounded-sm"></div>
                                    <div class="bg-white/10 w-full h-20 rounded-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 scroll-reveal-stagger">
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up text-white" data-prefix="+" data-suffix="x" data-value="12">+12x
                    </div>
                    <div class="stat-label">Throughput Increase</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up text-white" data-prefix="-" data-suffix="%" data-value="85">-85%
                    </div>
                    <div class="stat-label">Operational Cost</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up text-white" data-prefix="< " data-suffix="s" data-value="1.2">&lt;
                        1.2s</div>
                    <div class="stat-label">API Response Time</div>
                </div>
                <div class="hero-text-reveal stat-card">
                    <div class="stat-number count-up text-white" data-suffix="%" data-value="99.99">99.99%</div>
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
                        <p class="text-lg text-white/80 leading-relaxed italic mb-6">
                            "AutomateIQ integrated directly into our core tech stack. We've automated our entire lead pipeline and visual asset generation, reducing manual data entry to absolute zero."
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center font-bold text-white">
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
                        <div class="text-sm text-white/50 mt-2 font-medium">SLA Compliance &amp; Pipeline Guarantee</div>
                    </div>
                    <div class="strat-card">
                        <div class="text-5xl font-black text-white count-up" data-prefix="+" data-value="45">+45M
                        </div>
                        <div class="text-sm text-white/50 mt-2 font-medium">API Tasks Processed Seamlessly
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 hero-text-reveal">
                <div class="section-badge mb-4">⚡ SaaS Architecture</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Built for High‑Scale Operations</h2>
                <p class="text-white/50 text-lg">A unified workflow engine built to replace manual scripts with event-driven automation.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 scroll-reveal-stagger">
                <div class="strat-card hero-text-reveal strat-card-shimmer">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">AI Workflow Orchestrator</h3>
                    <p class="text-white/50 text-sm">Design and automate multi-step processes with logical triggers, loops, and conditional branches.</p>
                </div>
                <div class="strat-card hero-text-reveal">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Real‑Time Processing</h3>
                    <p class="text-white/50 text-sm">Connect custom data feeds and stream execution steps through advanced logic arrays with ultra‑low latency.</p>
                </div>
                <div class="strat-card hero-text-reveal">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Asset Generation API</h3>
                    <p class="text-white/50 text-sm">Programmatically generate high-converting visual assets, data visualizations, and structured media outputs.</p>
                </div>
                <div class="strat-card hero-text-reveal">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Pipeline Scaling</h3>
                    <p class="text-white/50 text-sm">High-throughput task queues built to ingest thousands of operations per second with automatic retries.</p>
                </div>
                <div class="strat-card hero-text-reveal">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Enterprise Security</h3>
                    <p class="text-white/50 text-sm">End-to-end data encryption, role-based access control, and complete compliance logging audit trials.</p>
                </div>
                <div class="strat-card hero-text-reveal">
                    <div class="strat-card-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Pre-built Connectors</h3>
                    <p class="text-white/50 text-sm">Integrate seamlessly with Slack, Salesforce, Google Workspace, AWS, and 500+ databases.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 relative overflow-hidden bg-white/[0.01]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 hero-text-reveal">
                <div class="section-badge mb-4">🔧 Deployment</div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Simple 3‑Step Setup</h2>
                <p class="text-white/50 text-lg">Go from concept to production-ready automation in minutes.</p>
            </div>

            <div class="timeline-container scroll-reveal-stagger">
                <div class="timeline-line"></div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">1</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                             <h3 class="text-2xl font-bold text-white mb-3">Define Your Trigger</h3>
                             <p class="text-white/50 text-sm leading-relaxed">Select from webhooks, API events, or database changes to initiate your automated pipelines.</p>
                        </div>
                        <div
                            class="bg-white/5 border border-white/5 rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            Visual Event Trigger Editor
                        </div>
                    </div>
                </div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">2</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div
                            class="order-2 md:order-1 bg-white/5 border border-white/5 rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            Interactive Canvas Designer
                        </div>
                        <div class="order-1 md:order-2">
                             <h3 class="text-2xl font-bold text-white mb-3">Design the Workflow</h3>
                             <p class="text-white/50 text-sm leading-relaxed">Map steps, connect AI modules, and set up logic branches using our intuitive console.</p>
                        </div>
                    </div>
                </div>

                <div class="hero-text-reveal timeline-step">
                    <div class="timeline-number">3</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                             <h3 class="text-2xl font-bold text-white mb-3">Monitor &amp; Orchestrate</h3>
                             <p class="text-white/50 text-sm leading-relaxed">Deploy with one click and monitor task executions, logs, and data processing rates in real time.</p>
                        </div>
                        <div
                            class="bg-white/5 border border-white/5 rounded-xl p-4 aspect-video flex items-center justify-center font-bold text-white/20">
                            Real-Time Execution Console
                        </div>
                    </div>
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
                <p class="text-white/50 text-lg font-medium">Scale pricing transparently based on your execution volumes.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-stretch scroll-reveal-stagger">
                <!-- Free Plan -->
                <div class="hero-text-reveal pricing-card-strat flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white/60 font-display">Starter</h3>
                        <div class="text-5xl font-extrabold text-white mt-4">$0</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-white/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                100 operations / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Core workflow editor
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-ghost-strat w-full text-center mt-8 justify-center">Get
                        Started</a>
                </div>

                <!-- Pro Plan -->
                <div class="hero-text-reveal pricing-card-strat featured flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white font-display">Scale SaaS</h3>
                            <span
                                class="text-[10px] bg-white/10 px-2.5 py-1 rounded-full text-white font-bold uppercase tracking-wider font-mono">Popular</span>
                        </div>
                        <div class="text-5xl font-extrabold text-white mt-4">$29</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-white/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                10,000 operations / month
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Advanced AI API keys
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white/80 font-semibold font-sans">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Priority multi-step queues
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="btn-glow w-full text-center mt-8 justify-center">Start Pro Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="hero-text-reveal pricing-card-strat flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white/60 font-display">Enterprise</h3>
                        <div class="text-5xl font-extrabold text-white mt-4">$99</div>
                        <div class="text-xs text-white/40 mt-1 font-medium font-sans">per month</div>
                        <ul class="space-y-4 mt-8">
                            <li class="flex items-center gap-3 text-sm text-white/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Unlimited monthly operations
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Dedicated high-speed queues
                            </li>
                            <li class="flex items-center gap-3 text-sm text-white/60 font-medium font-sans">
                                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Custom SLA &amp; SSO compliance
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
    <section class="py-24 relative overflow-hidden bg-white/[0.01]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 hero-text-reveal">
            <div class="cta-icon-glow">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                    </path>
                </svg>
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">Ready to Scale Your SaaS Operations?</h2>
            <p class="text-white/50 text-base md:text-lg max-w-xl mx-auto mb-10 leading-relaxed">
                Connect with our Solutions Architects for a custom walkthrough, custom SLA compliance terms, and enterprise pipeline sizing.
            </p>
            <a href="#" class="btn-glow">
                Book Solutions Call
                <svg class="w-4 h-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </a>
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
                        {{ \App\Models\Setting::get('site_description', 'Empowering digital creators with structured scripts and seamless AI-driven shorts production.') }}
                    </p>
                    <div class="flex gap-3">
                        @if ($twitter = \App\Models\Setting::get('social_twitter'))
                            <a href="{{ $twitter }}"
                                class="footer-social-icon text-white/50 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                        @endif
                        @if ($facebook = \App\Models\Setting::get('social_facebook'))
                            <a href="{{ $facebook }}"
                                class="footer-social-icon text-white/50 hover:text-white transition-colors">
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
                class="border-t border-white/5 mt-16 pt-8 text-center text-sm text-white/40 flex flex-col sm:flex-row justify-between gap-4 font-medium font-sans">
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

            // Initialize 3D Vanilla Tilt on cards
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(document.querySelectorAll(".strat-card:not(.no-tilt)"), {
                    max: 8,
                    speed: 800,
                    glare: true,
                    "max-glare": 0.15,
                    scale: 1.02,
                    perspective: 1200,
                    gyroscope: true
                });
            }

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