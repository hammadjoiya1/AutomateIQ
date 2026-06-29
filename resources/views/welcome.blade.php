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
    
    <!-- Vanilla Tilt for 3D wow effects -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" integrity="sha512-wC/cunGGDjXSl9OHwe00RQm5053048D51m178oIEqYqjBtv1k52rK8HnL/0Jm/E+Bv2wP9rN1e31XN/2V8B52g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
        
        @keyframes progress {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0); }
            100% { transform: translateX(100%); }
        }
        
        @keyframes dash {
            to {
                stroke-dashoffset: -20;
            }
        }
        
        .workflow-line-anim {
            animation: dash 1s linear infinite;
        }

        /* Bento Grid Spotlight Effect */
        .bento-card {
            background: rgba(var(--bg-2-rgb, 17, 24, 39), 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .bento-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .bento-spotlight {
            pointer-events: none;
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            background: radial-gradient(600px circle at var(--mouse-x) var(--mouse-y), rgba(var(--primary-rgb, 91, 33, 182), 0.1), transparent 40%);
            z-index: 0;
        }
        .bento-card:hover .bento-spotlight {
            opacity: 1;
        }
        .bento-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: radial-gradient(400px circle at var(--mouse-x) var(--mouse-y), rgba(255, 255, 255, 0.3), transparent 40%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        .bento-card:hover::before {
            opacity: 1;
        }
    </style>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="font-sans antialiased bg-background text-text overflow-x-hidden">
    
    <!-- Global Mouse Aura -->
    <div id="global-aura" class="fixed top-0 left-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-50 mix-blend-screen transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500"></div>

    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden bg-background">
        <canvas id="network-canvas" class="absolute inset-0 w-full h-full opacity-60"></canvas>
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] animate-float opacity-40 pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-accent/20 rounded-full blur-[120px] animate-float-delayed opacity-40 pointer-events-none"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.02] pointer-events-none"></div>
    </div>

    <div x-data="{ scrolled: false, open: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <!-- Navigation -->
        <nav class="navbar fixed w-full z-50 transition-all duration-300"
            :class="{ 'bg-background/80 backdrop-blur-md border-b border-border': scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="flex items-center md:hidden">
                        <button type="button" @click="open = true"
                            class="inline-flex items-center justify-center p-2 rounded-md text-text hover:text-primary hover:bg-surface/60 transition">
                            <span class="sr-only">Open menu</span>
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    <x-application-logo class="h-8 w-auto text-primary" />
                    <span class="font-display font-bold text-xl tracking-tight">AutomateIQ</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features"
                        class="text-sm font-medium text-text-muted hover:text-primary transition-colors">Features</a>
                    <a href="#pricing"
                        class="text-sm font-medium text-text-muted hover:text-primary transition-colors">Pricing</a>
                    <x-theme-switcher />
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-medium hover:text-primary transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 md:hidden" x-cloak>
        <div class="absolute inset-0 bg-black/60" @click="open = false"></div>
        <div class="absolute inset-y-0 left-0 w-80 max-w-[85vw]">
            <div x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="glass-panel h-full border-r border-white/10 p-5 overflow-y-auto">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-application-logo class="h-7 w-auto text-primary" />
                        <span class="font-bold text-lg">AutomateIQ</span>
                    </div>
                    <button type="button" @click="open = false"
                        class="p-2 rounded-md text-text hover:text-primary hover:bg-surface/60 transition">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-6 space-y-2">
                    <a href="#features" @click="open = false"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Features</a>
                    <a href="#pricing" @click="open = false"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Pricing</a>
                </div>

                <div class="mt-6 border-t border-white/10 pt-4">
                    <div class="flex items-center justify-between text-sm text-text-muted">
                        <span>Theme</span>
                        <x-theme-switcher />
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <div class="mt-4 space-y-2">
                                <a href="{{ url('/dashboard') }}" @click="open = false" class="btn btn-primary w-full">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-full">Log Out</button>
                                </form>
                            </div>
                        @else
                            <div class="mt-4 space-y-2">
                                <a href="{{ route('login') }}" @click="open = false" class="btn btn-ghost w-full">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" @click="open = false" class="btn btn-primary w-full">Get Started</a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-semibold mb-8 animate-fade-in">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                v2.0 is now live
            </div>

            <h1 class="font-display text-5xl md:text-7xl font-bold tracking-tight mb-8 animate-slide-up"
                style="animation-delay: 0.1s;">
                Master the Art of <br>
                <span class="text-gradient-primary">Faceless Automation</span>
            </h1>

            <p class="text-xl text-text-muted max-w-2xl mx-auto mb-10 leading-relaxed animate-slide-up"
                style="animation-delay: 0.2s;">
                Creator‑grade hooks, viral ideas, short scripts, scene splitters, video prompts, and repurposing—built
                for faceless growth.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-slide-up"
                style="animation-delay: 0.3s;">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg w-full sm:w-auto group magnetic-button inline-block">
                    Start Creating Now
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform inline" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#features" class="btn btn-secondary btn-lg w-full sm:w-auto">View Tools</a>
            </div>

            <!-- Hero Image/Dashboard Preview -->
            <div class="mt-20 relative max-w-5xl mx-auto animate-slide-up" style="animation-delay: 0.4s; perspective: 1200px;">
                <div class="absolute inset-0 bg-primary/20 blur-3xl -z-10 rounded-full opacity-40"></div>
                
                <!-- Mock Browser Window with initial 3D tilt -->
                <div id="scroll-3d-dashboard" class="glass-panel rounded-2xl border border-white/10 shadow-2xl overflow-hidden bg-background/80 backdrop-blur-xl transition-transform duration-75 ease-out" style="transform: rotateX(15deg) rotateY(-5deg) scale(0.95); transform-style: preserve-3d; will-change: transform;">
                    <!-- Browser Header -->
                    <div class="flex items-center gap-2 px-4 py-3 border-b border-white/5 bg-surface/50">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        </div>
                        <div class="mx-auto flex items-center justify-center bg-background border border-white/5 rounded-md px-3 py-1 text-xs text-text-muted w-1/3">
                            <svg class="w-3 h-3 mr-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                            app.automateiq.com
                        </div>
                        <div class="w-10"></div>
                    </div>

                    <!-- Workflow Mock Animation -->
                    <div class="relative h-[300px] sm:h-[450px] p-6 sm:p-10 overflow-hidden bg-grid-pattern bg-[length:20px_20px]">
                        <!-- Connecting Line (SVG SVG) -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none z-0" style="filter: drop-shadow(0 0 4px rgba(var(--primary-rgb), 0.5))">
                            <path class="workflow-line text-primary opacity-50" stroke-dasharray="10" stroke="currentColor" stroke-width="2" fill="none" d="M 200 150 C 350 150, 350 250, 500 250"></path>
                            <path class="workflow-line-anim text-primary" stroke-dasharray="10" stroke="currentColor" stroke-width="2" fill="none" d="M 200 150 C 350 150, 350 250, 500 250"></path>
                        </svg>

                        <!-- Node 1: Trigger -->
                        <div class="workflow-node absolute left-[10%] sm:left-[20%] top-[80px] sm:top-[120px] w-48 bg-surface border border-white/10 rounded-xl p-3 shadow-lg z-10 flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-accent/20 flex items-center justify-center text-accent">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span class="text-xs font-bold text-text">New Idea</span>
                            </div>
                            <div class="text-[10px] text-text-muted bg-background/50 rounded px-2 py-1 border border-white/5">Webhook received</div>
                            <div class="w-full bg-background rounded-full h-1 mt-1 overflow-hidden">
                                <div class="bg-accent h-1 w-full rounded-full animate-pulse"></div>
                            </div>
                        </div>

                        <!-- Node 2: AI Action -->
                        <div class="workflow-node absolute left-[50%] sm:left-[60%] top-[150px] sm:top-[220px] w-56 bg-surface border border-primary/30 rounded-xl p-3 shadow-xl shadow-primary/10 z-10 flex flex-col gap-2 transform transition-transform" style="animation-delay: 0.5s;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-primary/20 flex items-center justify-center text-primary">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-text">Generate Video</span>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                            </div>
                            <div class="text-[10px] text-text-muted bg-background/50 rounded px-2 py-1 border border-white/5 font-mono text-primary/70">Generating b-roll...</div>
                            <div class="flex gap-1 mt-1">
                                <div class="flex-1 bg-background rounded-full h-1 overflow-hidden">
                                    <div class="bg-primary h-1 rounded-full w-full animate-[progress_2s_ease-in-out_infinite]"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24 bg-surface/50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 scroll-animate" data-animation="fade-in-up">
                <h2 class="font-display text-3xl md:text-4xl font-bold mb-4 tilt-3d" data-scramble>Everything you need to scale</h2>
                <p class="text-text-muted text-lg">A focused toolkit for faceless creators—from hooks to scripts to scene
                    breakdowns and multi‑platform repurposing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8" data-stagger-reveal id="bento-grid">
                <!-- Feature 1 (Large Bento, Spans 8 cols) -->
                <div class="bento-card md:col-span-8 glass-article rounded-3xl p-8 relative overflow-hidden group scroll-animate" data-animation="fade-in-up" style="transform-style: preserve-3d;">
                    <div class="bento-spotlight"></div>
                    <div class="relative z-10 h-full flex flex-col justify-between" style="transform: translateZ(30px); transform-style: preserve-3d;">
                        <div style="transform-style: preserve-3d;">
                            <div class="h-14 w-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-8 group-hover:scale-110 transition-transform shadow-lg shadow-primary/20" style="transform: translateZ(20px);">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-display font-bold mb-4" style="transform: translateZ(15px);">Short‑form Script Builder</h3>
                            <p class="text-text-muted text-lg max-w-md" style="transform: translateZ(10px);">Time‑coded scripts with b‑roll and delivery notes for Shorts, Reels, and TikTok. Engineered for maximum retention.</p>
                        </div>
                        <div class="mt-8 pt-8 border-t border-white/5 flex gap-4" style="transform: translateZ(12px);">
                            <span class="text-xs font-mono text-primary bg-primary/10 px-2 py-1 rounded">JSON Output</span>
                            <span class="text-xs font-mono text-primary bg-primary/10 px-2 py-1 rounded">Hooks</span>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 (Tall Bento, Spans 4 cols) -->
                <div class="bento-card md:col-span-4 glass-article rounded-3xl p-8 relative overflow-hidden group scroll-animate" data-animation="fade-in-up" style="animation-delay: 0.1s; transform-style: preserve-3d;">
                    <div class="bento-spotlight"></div>
                    <div class="relative z-10 h-full flex flex-col justify-between" style="transform: translateZ(30px); transform-style: preserve-3d;">
                        <div class="h-14 w-14 rounded-2xl bg-accent/10 flex items-center justify-center text-accent mb-8 group-hover:scale-110 transition-transform shadow-lg shadow-accent/20" style="transform: translateZ(20px);">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-bold mb-4" style="transform: translateZ(15px);">Scene Splitter</h3>
                            <p class="text-text-muted" style="transform: translateZ(10px);">Turn scripts into visual scenes with camera, motion, and on‑screen text cues.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 (Wide Bento, Spans 12 cols) -->
                <div class="bento-card md:col-span-12 glass-article rounded-3xl p-8 sm:p-12 relative overflow-hidden group scroll-animate" data-animation="fade-in-up" style="animation-delay: 0.2s; transform-style: preserve-3d;">
                    <div class="bento-spotlight"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-16" style="transform: translateZ(30px); transform-style: preserve-3d;">
                        <div class="flex-1" style="transform-style: preserve-3d;">
                            <div class="h-14 w-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-8 group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20" style="transform: translateZ(20px);">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-display font-bold mb-4" style="transform: translateZ(15px);">Image Generator</h3>
                            <p class="text-text-muted text-lg max-w-lg" style="transform: translateZ(10px);">Create thumbnail-ready visuals and b-roll for your videos with our integrated DALL-E 3 engine. Generate variations instantly without leaving the dashboard.</p>
                        </div>
                        <div class="w-full md:w-1/3 aspect-video rounded-xl border border-white/10 bg-surface/50 overflow-hidden flex items-center justify-center group-hover:border-blue-500/30 transition-colors" style="transform: translateZ(25px);">
                            <div class="w-16 h-16 rounded-full border border-white/5 border-t-blue-500 animate-spin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Simple, transparent pricing</h2>
                <p class="text-text-muted text-lg">Choose the plan that fits your growth stage.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Free Plan -->
                <div class="card p-8 flex flex-col">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-text-muted">Starter</h3>
                        <div class="text-4xl font-bold mt-2">$0</div>
                        <div class="text-sm text-text-muted">per month</div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            5 tool runs / day
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mini‑pack access (hooks + scripts + repurpose)
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            0 videos / day
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-secondary w-full">Get Started</a>
                </div>

                <!-- Pro Plan -->
                <div class="card p-8 flex flex-col border-primary ring-2 ring-primary/20 relative">
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">
                        MOST POPULAR</div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-primary">Pro Creator</h3>
                        <div class="text-4xl font-bold mt-2">$29</div>
                        <div class="text-sm text-text-muted">per month</div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            100 tool runs / day
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            5 videos / day + video prompts
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            All packs + niches + workflows
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary w-full shadow-lg shadow-primary/25">Upgrade
                        to Pro</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="card p-8 flex flex-col">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-text-muted">Agency</h3>
                        <div class="text-4xl font-bold mt-2">$99</div>
                        <div class="text-sm text-text-muted">per month</div>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Team presets + collaboration
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            100 tool runs / day + 5 videos / day
                        </li>
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Priority Support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-secondary w-full">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface border-t border-border py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <x-application-logo class="h-6 w-auto text-primary" />
                        <span class="font-display font-bold text-lg">AutomateIQ</span>
                    </div>
                    <p class="text-text-muted text-sm max-w-sm">Empowering the next generation of content creators with
                        AI-driven automation tools.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-text-muted">
                        <li><a href="#" class="hover:text-primary transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Showcase</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-text-muted">
                        <li><a href="#" class="hover:text-primary transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-border mt-12 pt-8 text-center text-sm text-text-muted">
                &copy; {{ date('Y') }} AutomateIQ. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Interactive Canvas Background Script & Advanced Motion Effects -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ==========================================
            // 1. Interactive Fluid Grid Canvas Background
            // ==========================================
            const canvas = document.getElementById('network-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            let width, height;
            let points = [];
            const spacing = 45; // Space between grid lines
            const mouseRadius = 130;
            const spring = 0.04;
            const damping = 0.85;
            
            let mouse = { x: null, y: null, px: null, py: null, vx: 0, vy: 0 };
            
            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = width;
                canvas.height = height;
                initGrid();
            }
            
            function initGrid() {
                points = [];
                const cols = Math.ceil(width / spacing) + 1;
                const rows = Math.ceil(height / spacing) + 1;
                
                for (let c = 0; c < cols; c++) {
                    points[c] = [];
                    for (let r = 0; r < rows; r++) {
                        points[c][r] = {
                            x: c * spacing,
                            y: r * spacing,
                            ox: c * spacing,
                            oy: r * spacing,
                            vx: 0,
                            vy: 0
                        };
                    }
                }
            }
            
            window.addEventListener('resize', resize);
            
            window.addEventListener('mousemove', (e) => {
                if (mouse.x !== null) {
                    mouse.vx = e.clientX - mouse.x;
                    mouse.vy = e.clientY - mouse.y;
                }
                mouse.x = e.clientX;
                mouse.y = e.clientY;
            });
            
            window.addEventListener('mouseout', () => {
                mouse.x = null;
                mouse.y = null;
            });
            
            resize();
            
            function animateCanvas() {
                ctx.clearRect(0, 0, width, height);
                
                const cols = points.length;
                const rows = points[0] ? points[0].length : 0;
                
                // Track mouse velocity decay
                mouse.vx *= 0.9;
                mouse.vy *= 0.9;
                const mouseSpeed = Math.sqrt(mouse.vx * mouse.vx + mouse.vy * mouse.vy);
                
                // 1. Update Grid Points
                for (let c = 0; c < cols; c++) {
                    for (let r = 0; r < rows; r++) {
                        const p = points[c][r];
                        
                        if (mouse.x !== null) {
                            const dx = mouse.x - p.x;
                            const dy = mouse.y - p.y;
                            const dist = Math.sqrt(dx * dx + dy * dy);
                            
                            if (dist < mouseRadius) {
                                const force = (mouseRadius - dist) / mouseRadius;
                                // Pull/Push intensity linked to cursor velocity
                                const repel = force * (3 + mouseSpeed * 0.15); 
                                const angle = Math.atan2(dy, dx);
                                
                                p.vx -= Math.cos(angle) * repel;
                                p.vy -= Math.sin(angle) * repel;
                            }
                        }
                        
                        // Spring force returning to anchor position
                        const ax = (p.ox - p.x) * spring;
                        const ay = (p.oy - p.y) * spring;
                        
                        p.vx = (p.vx + ax) * damping;
                        p.vy = (p.vy + ay) * damping;
                        
                        p.x += p.vx;
                        p.y += p.vy;
                    }
                }
                
                // 2. Draw Membrane Lines (Vertical)
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.04)';
                ctx.lineWidth = 1;
                for (let c = 0; c < cols; c++) {
                    ctx.beginPath();
                    for (let r = 0; r < rows; r++) {
                        const p = points[c][r];
                        if (r === 0) ctx.moveTo(p.x, p.y);
                        else ctx.lineTo(p.x, p.y);
                    }
                    ctx.stroke();
                }
                
                // 3. Draw Membrane Lines (Horizontal)
                for (let r = 0; r < rows; r++) {
                    ctx.beginPath();
                    for (let c = 0; c < cols; c++) {
                        const p = points[c][r];
                        if (c === 0) ctx.moveTo(p.x, p.y);
                        else ctx.lineTo(p.x, p.y);
                    }
                    ctx.stroke();
                }
                
                requestAnimationFrame(animateCanvas);
            }
            
            animateCanvas();
        });

        // ==========================================
        // 2. Bento Grid Mouse Spotlight Effect
        // ==========================================
        document.addEventListener('mousemove', (e) => {
            document.querySelectorAll('.bento-card').forEach(card => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });

        // Initialize 3D Vanilla Tilt
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll(".bento-card"), {
                max: 12, // slightly increased for stronger 3D depth
                speed: 800,
                glare: true,
                "max-glare": 0.2,
                scale: 1.03,
                perspective: 1000,
                gyroscope: true
            });
        }

        // ==========================================
        // 3. Velocity-Deforming Global Mouse Aura
        // ==========================================
        const aura = document.getElementById('global-aura');
        if (aura) {
            let mouseX = window.innerWidth / 2;
            let mouseY = window.innerHeight / 2;
            let auraX = mouseX;
            let auraY = mouseY;
            
            let lastMouseX = mouseX;
            let lastMouseY = mouseY;
            let velocityX = 0;
            let velocityY = 0;

            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });

            function animateAura() {
                // Calculate cursor velocity
                velocityX = mouseX - lastMouseX;
                velocityY = mouseY - lastMouseY;
                
                lastMouseX = mouseX;
                lastMouseY = mouseY;

                // Damping velocity
                const speed = Math.sqrt(velocityX * velocityX + velocityY * velocityY);
                const maxSpeed = 100;
                const limitedSpeed = Math.min(speed, maxSpeed);
                
                // Calculate stretch factors
                const stretch = 1 + (limitedSpeed / maxSpeed) * 0.8;
                const squeeze = 1 - (limitedSpeed / maxSpeed) * 0.4;
                
                // Rotation angle matching mouse movement vector
                const angle = Math.atan2(velocityY, velocityX);

                // Lerping the actual position tracking
                const lerpFactor = 0.1;
                auraX += (mouseX - auraX) * lerpFactor;
                auraY += (mouseY - auraY) * lerpFactor;

                // Scale up when moving fast, stretch dynamically
                aura.style.transform = `translate3d(${auraX}px, ${auraY}px, 0) translate(-50%, -50%) rotate(${angle}rad) scale(${stretch}, ${squeeze})`;
                
                requestAnimationFrame(animateAura);
            }
            animateAura();
        }

        // ==========================================
        // 4. Elastic Magnetic Buttons
        // ==========================================
        const magneticButtons = document.querySelectorAll('.magnetic-button');
        magneticButtons.forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const h = rect.width / 2;
                const v = rect.height / 2;
                const x = e.clientX - rect.left - h;
                const y = e.clientY - rect.top - v;
                
                btn.style.transform = `translate(${x * 0.35}px, ${y * 0.35}px) scale(1.05)`;
                btn.style.transition = 'transform 0.05s ease-out';
            });
            btn.addEventListener('mouseleave', () => {
                // Spring back with overshoot look
                btn.style.transform = `translate(0px, 0px) scale(1)`;
                btn.style.transition = 'transform 0.6s cubic-bezier(0.25, 1.25, 0.5, 1.25)';
            });
        });

        // ==========================================
        // 5. Scroll-Bound 3D Dashboard Mockup Tilt
        // ==========================================
        const dashboard = document.getElementById('scroll-3d-dashboard');
        if (dashboard) {
            window.addEventListener('scroll', () => {
                const scrollProgress = Math.min(window.scrollY / 600, 1);
                
                // Interpolate from tilted to flat state
                const rotX = 15 - (scrollProgress * 15);
                const rotY = -5 + (scrollProgress * 5);
                const scale = 0.95 + (scrollProgress * 0.05);
                const translateY = (1 - scrollProgress) * 40; // parallax lift
                
                dashboard.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg) scale(${scale}) translateY(${translateY}px)`;
            });
        }

        // ==========================================
        // 6. Text Scramble Effect
        // ==========================================
        class TextScramble {
            constructor(el) {
                this.el = el;
                this.chars = '!<>-_\\/[]{}—=+*^?#________';
                this.update = this.update.bind(this);
            }
            setText(newText) {
                const oldText = this.el.innerText;
                const length = Math.max(oldText.length, newText.length);
                const promise = new Promise((resolve) => this.resolve = resolve);
                this.queue = [];
                for (let i = 0; i < length; i++) {
                    const from = oldText[i] || '';
                    const to = newText[i] || '';
                    const start = Math.floor(Math.random() * 40);
                    const end = start + Math.floor(Math.random() * 40);
                    this.queue.push({ from, to, start, end });
                }
                cancelAnimationFrame(this.frameRequest);
                this.frame = 0;
                this.update();
                return promise;
            }
            update() {
                let output = '';
                let complete = 0;
                for (let i = 0, n = this.queue.length; i < n; i++) {
                    let { from, to, start, end, char } = this.queue[i];
                    if (this.frame >= end) {
                        complete++;
                        output += to;
                    } else if (this.frame >= start) {
                        if (!char || Math.random() < 0.28) {
                            char = this.randomChar();
                            this.queue[i].char = char;
                        }
                        output += `<span class="text-primary/70">${char}</span>`;
                    } else {
                        output += from;
                    }
                }
                this.el.innerHTML = output;
                if (complete === this.queue.length) {
                    this.resolve();
                } else {
                    this.frameRequest = requestAnimationFrame(this.update);
                    this.frame++;
                }
            }
            randomChar() {
                return this.chars[Math.floor(Math.random() * this.chars.length)];
            }
        }

        const scrambles = document.querySelectorAll('[data-scramble]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.hasAttribute('data-scrambled')) {
                    const fx = new TextScramble(entry.target);
                    const originalText = entry.target.innerText;
                    fx.setText(originalText);
                    entry.target.setAttribute('data-scrambled', 'true');
                }
            });
        }, { threshold: 0.5 });
        
        scrambles.forEach(el => observer.observe(el));
    </script>
</body>
</html>