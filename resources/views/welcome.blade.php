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

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="font-sans antialiased bg-background text-text overflow-x-hidden">
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-float opacity-50">
        </div>
        <div
            class="absolute bottom-0 right-1/4 w-96 h-96 bg-accent/20 rounded-full blur-[100px] animate-float-delayed opacity-50">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]"></div>
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
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg w-full sm:w-auto group">
                    Start Creating Now
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#features" class="btn btn-secondary btn-lg w-full sm:w-auto">View Tools</a>
            </div>

            <!-- Hero Image/Dashboard Preview -->
            <div class="mt-20 relative max-w-5xl mx-auto animate-slide-up" style="animation-delay: 0.4s;">
                <div class="absolute inset-0 bg-primary/20 blur-3xl -z-10 rounded-full opacity-40"></div>
                <div class="glass-panel rounded-2xl p-2 border border-white/10 shadow-2xl">
                    <img src="https://placehold.co/1200x675/1e293b/ffffff?text=Dashboard+Preview"
                        alt="Dashboard Preview"
                        class="rounded-xl w-full border border-white/5 opacity-90 block dark:hidden">
                    <img src="https://placehold.co/1200x675/0f172a/ffffff?text=Dashboard+Preview+Dark"
                        alt="Dashboard Preview"
                        class="rounded-xl w-full border border-white/5 opacity-90 hidden dark:block">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24 bg-surface/50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="font-display text-3xl md:text-4xl font-bold mb-4">Everything you need to scale</h2>
                <p class="text-text-muted text-lg">A focused toolkit for faceless creators—from hooks to scripts to scene
                    breakdowns and multi‑platform repurposing.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card p-8 card-hover group">
                    <div
                        class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Short‑form Script Builder</h3>
                    <p class="text-text-muted">Time‑coded scripts with b‑roll and delivery notes for Shorts, Reels, and TikTok.</p>
                </div>

                <!-- Feature 2 -->
                <div class="card p-8 card-hover group">
                    <div
                        class="h-12 w-12 rounded-xl bg-accent/10 flex items-center justify-center text-accent mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Scene Splitter</h3>
                    <p class="text-text-muted">Turn scripts into visual scenes with camera, motion, and on‑screen text cues.</p>
                </div>

                <!-- Feature 3 -->
                <div class="card p-8 card-hover group">
                    <div
                        class="h-12 w-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Image Generator</h3>
                    <p class="text-text-muted">Create thumbnail-ready visuals and b-roll for your videos with our
                        integrated DALL-E 3 engine.</p>
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
</body>

</html>