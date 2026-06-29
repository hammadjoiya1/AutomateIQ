@php
    $metaTitle = $attributes->get('meta-title');
    $metaDescription = $attributes->get('meta-description');
    $creditLink = route('pricing');
    foreach (['starter', 'growth', 'scale'] as $packKey) {
        $settingUrl = \App\Models\Setting::get("lemonsqueezy.topup_checkout_urls.{$packKey}", null);
        $configUrls = config('lemonsqueezy.topup_checkout_urls', []);
        $url = $settingUrl ?: $configUrls[$packKey] ?? null;
        if ($url) {
            $creditLink = route('billing.topup', $packKey);
            break;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ $activeTheme['class'] ?? 'theme-ocean-breeze' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if ($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|inter:400,500,600&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" integrity="sha512-wC/cunGGDjXSl9OHwe00RQm5053048D51m178oIEqYqjBtv1k52rK8HnL/0Jm/E+Bv2wP9rN1e31XN/2V8B52g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <x-theme-styles :activeTheme="$activeTheme" />
    <style>
        [x-cloak] {
            display: none !important;
        }
        
        /* Lenis Smooth Scrolling Styles */
        html.lenis {
            height: auto;
        }
        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }
        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }
        .lenis.lenis-stopped {
            overflow: hidden;
        }
        .lenis.lenis-scrolling iframe {
            pointer-events: none;
        }

        /* Premium scrollbar for modern browsers */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            border-radius: 4px;
            border: 2px solid rgba(0,0,0,0.1);
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Bento Grid Spotlight & 3D Tilt Effect */
        .bento-card {
            background: rgba(var(--bg-2-rgb, 17, 24, 39), 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .bento-card:hover {
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
    <!-- Lenis Smooth Scrolling CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="font-sans antialiased bg-background text-text overflow-x-hidden transition-colors duration-300">

    <!-- Global Mouse Aura -->
    <div id="global-aura" class="fixed top-0 left-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-50 mix-blend-screen transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500"></div>

    <!-- Fluid Membrane Canvas Background -->
    <canvas id="network-canvas" class="fixed inset-0 w-full h-full opacity-60 pointer-events-none z-0"></canvas>

    <div class="relative min-h-screen overflow-x-clip">
    @if(session()->has('impersonated_by'))
        <div
            class="bg-primary text-white text-center py-2 px-4 text-sm font-semibold flex items-center justify-center gap-4 relative z-50 shadow-md">
            <span>You are currently impersonating <strong>{{ Auth::user()->name }}</strong>.</span>
            <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="underline hover:text-white/80 transition-colors">Leave Impersonation</button>
            </form>
        </div>
    @endif

        <!-- Global Background Ambient Glows -->
        <div class="fixed top-1/4 left-1/10 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
        <div class="fixed bottom-1/3 right-1/10 w-[500px] h-[500px] bg-secondary/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div x-data="{ open: false }" class="sticky top-0 z-50">
        <!-- Navigation -->
        <nav class="glass-panel border-b border-primary/10 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center sm:hidden">
                            <button type="button" @click="open = true"
                                class="inline-flex items-center justify-center p-2 rounded-md text-text hover:text-primary hover:bg-surface/60 transition">
                                <span class="sr-only">Open menu</span>
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                                @if ($logo = \App\Models\Setting::get('site_logo'))
                                    <img src="{{ Storage::url($logo) }}" class="h-8 w-auto rounded-full object-cover"
                                        alt="Logo">
                                @else
                                    <img src="{{ asset('images/favicon.png') }}"
                                        class="w-10 h-10 rounded-full object-cover border border-primary/20 group-hover:scale-105 transition-transform"
                                        alt="Logo">
                                @endif
                                <span class="font-bold text-xl tracking-tight">
                                    {{ \App\Models\Setting::get('site_name', 'AutomateIQ') }}
                                </span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('tools.index')" :active="request()->routeIs('tools.index')"
                                class="text-base font-medium hover:text-primary transition-colors">
                                {{ __('Tools') }}
                            </x-nav-link>
                            <x-nav-link :href="route('workflows.index')" :active="request()->routeIs('workflows.*')"
                                class="text-base font-medium hover:text-primary transition-colors">
                                {{ __('Workflows') }}
                            </x-nav-link>
                            <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')"
                                class="text-base font-medium hover:text-primary transition-colors">
                                {{ __('Pricing') }}
                            </x-nav-link>
                            <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')"
                                class="text-base font-medium hover:text-primary transition-colors">
                                {{ __('Blog') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                        <!-- Theme Switcher -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-text/70 hover:text-primary transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                    </path>
                                </svg>
                                <span>{{ $activeTheme['name'] }}</span>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-background border border-primary/20 z-50 glass-panel py-1"
                                style="display: none;">
                                @foreach ($allThemes as $slug => $theme)
                                    <form method="POST" action="{{ route('theme.switch') }}">
                                        @csrf
                                        <input type="hidden" name="theme" value="{{ $slug }}">
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-text hover:bg-primary/10 transition-colors {{ ($activeTheme['slug'] ?? '') == $slug ? 'font-bold text-primary' : '' }}">
                                            {{ $theme['name'] }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>

                        @auth
                            <!-- Credit Badge -->
                            <a href="{{ $creditLink }}"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-primary/20 to-accent/20 text-primary border border-primary/30 font-bold hover:scale-105 transition-all shadow-lg shadow-primary/10 mr-2"
                                title="Buy more credits">
                                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-sm font-bold">{{ number_format(Auth::user()->credits) }} Credits</span>
                            </a>

                            <a href="{{ route('dashboard') }}"
                                class="text-sm font-medium text-text hover:text-primary transition-colors">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-primary/10 border border-primary/20 rounded-full font-semibold text-xs text-primary uppercase tracking-widest hover:bg-primary hover:text-white active:bg-primary/90 focus:outline-none transition ease-in-out duration-150">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-text hover:text-primary transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" data-analytics-event="cta_signup_nav"
                                    class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary/90 focus:bg-primary/90 active:bg-primary/90 focus:outline-none shadow-lg shadow-primary/30 transition ease-in-out duration-150">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 sm:hidden" x-cloak>
            <div class="absolute inset-0 bg-black/60" @click="open = false"></div>
            <div class="absolute inset-y-0 left-0 w-80 max-w-[85vw]">
                <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="glass-panel h-full border-r border-white/10 p-5 overflow-y-auto">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-application-logo class="h-7 w-auto text-primary" />
                            <span
                                class="font-bold text-lg">{{ \App\Models\Setting::get('site_name', 'AutomateIQ') }}</span>
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
                        <a href="{{ route('tools.index') }}" @click="open = false"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Tools</a>
                        <a href="{{ route('workflows.index') }}" @click="open = false"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Workflows</a>
                        <a href="{{ route('pricing') }}" @click="open = false"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Pricing</a>
                        <a href="{{ route('blog.index') }}" @click="open = false"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-text hover:bg-surface/60">Blog</a>
                    </div>

                    <div class="mt-6 border-t border-white/10 pt-4">
                        <div class="flex items-center justify-between text-sm text-text-muted">
                            <span>Theme</span>
                            <x-theme-switcher />
                        </div>

                        @auth
                            <div
                                class="mt-4 flex items-center justify-between rounded-lg border border-primary/20 bg-primary/10 px-3 py-2">
                                <span class="text-sm text-text">Credits</span>
                                <span
                                    class="text-sm font-bold text-primary">{{ number_format(Auth::user()->credits) }}</span>
                            </div>
                            <div class="mt-4 space-y-2">
                                <a href="{{ $creditLink }}" @click="open = false" class="btn btn-primary w-full">Buy
                                    Credits</a>
                                <a href="{{ route('dashboard') }}" @click="open = false"
                                    class="btn btn-ghost w-full">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-full">Log Out</button>
                                </form>
                            </div>
                        @else
                            <div class="mt-4 space-y-2">
                                <a href="{{ route('login') }}" @click="open = false" class="btn btn-ghost w-full">Log
                                    in</a>
                                <a href="{{ route('register') }}" @click="open = false" class="btn btn-primary w-full">Get
                                    Started</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <main class="flex-grow">
        <div class="px-4 sm:px-6 lg:px-8 pt-6">
            <x-flash-alerts />
        </div>
        {{ $slot }}
    </main>

    <a href="{{ route('pricing') }}" data-analytics-event="cta_pricing_sticky"
        class="pricing-sticky fixed bottom-6 left-1/2 -translate-x-1/2 z-50 inline-flex items-center gap-2 px-5 py-3 rounded-full bg-primary text-white font-semibold shadow-xl hover:bg-primary/90 transition-all">
        See Pricing
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
    </a>

    <footer class="bg-surface border-t border-primary/10 py-16 mt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-8 mb-12">
                <div class="col-span-1 md:col-span-1 space-y-6">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/favicon.png') }}"
                            class="w-8 h-8 rounded-full object-cover border border-primary/20" alt="Logo">
                        <span class="font-bold text-xl font-display text-text">AutomateIQ</span>
                    </div>
                    <p class="text-sm text-text-muted leading-relaxed max-w-xs">
                        Empowering creators to build empires without stepping in front of the camera.
                    </p>
                </div>

                <div class="md:ml-auto">
                    <h3 class="text-sm font-bold text-text tracking-wider uppercase mb-6">Product</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('tools.index') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Tools</a></li>
                        <li><a href="{{ route('pricing') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Pricing</a>
                        </li>
                        <li><a href="{{ route('blog.index') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Blog</a>
                        </li>
                    </ul>
                </div>

                <div class="md:ml-auto">
                    <h3 class="text-sm font-bold text-text tracking-wider uppercase mb-6">Support</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('faq') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">FAQ</a></li>
                        <li><a href="{{ route('contact') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Contact</a>
                        </li>
                        <li><a href="{{ route('demo') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Book Demo</a>
                        </li>
                    </ul>
                </div>

                <div class="md:ml-auto">
                    <h3 class="text-sm font-bold text-text tracking-wider uppercase mb-6">Legal</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('privacy') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Privacy</a>
                        </li>
                        <li><a href="{{ route('terms') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Terms</a>
                        </li>
                        <li><a href="{{ route('affiliate') }}"
                                class="text-sm text-text-muted hover:text-primary transition-colors">Affiliate</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-primary/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs text-text-muted">
                    {{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' AutomateIQ. All rights reserved.') }}
                </p>
                <div class="flex space-x-6">
                    @if ($twitter = \App\Models\Setting::get('social_twitter'))
                        <a href="{{ $twitter }}" class="text-text-muted hover:text-primary transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                    @endif
                    @if ($facebook = \App\Models\Setting::get('social_facebook'))
                        <a href="{{ $facebook }}" class="text-text-muted hover:text-primary transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
    </div> <!-- Close .relative.min-h-screen.overflow-x-clip wrapper -->

    <!-- Global Scripts -->
    <script>
        document.addEventListener('turbo:load', () => {
            // Only initialize Lenis once globally
            if (!window.lenis) {
                window.lenis = new Lenis({
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                    direction: 'vertical',
                    gestureDirection: 'vertical',
                    smooth: true,
                    mouseMultiplier: 1,
                    smoothTouch: false,
                    touchMultiplier: 2,
                    infinite: false,
                });

                function raf(time) {
                    window.lenis.raf(time);
                    requestAnimationFrame(raf);
                }
                requestAnimationFrame(raf);
            }
        });

        // ==========================================
        // 1. Fluid grid membrane canvas background
        // ==========================================
        document.addEventListener('turbo:load', () => {
            const canvas = document.getElementById('network-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            let width, height;
            let points = [];
            const spacing = 45; // grid spacing
            const mouseRadius = 140;
            const spring = 0.04;
            const damping = 0.85;
            
            let mouse = { x: null, y: null, vx: 0, vy: 0 };
            
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
                
                mouse.vx *= 0.9;
                mouse.vy *= 0.9;
                const mouseSpeed = Math.sqrt(mouse.vx * mouse.vx + mouse.vy * mouse.vy);
                
                for (let c = 0; c < cols; c++) {
                    for (let r = 0; r < rows; r++) {
                        const p = points[c][r];
                        
                        if (mouse.x !== null) {
                            const dx = mouse.x - p.x;
                            const dy = mouse.y - p.y;
                            const dist = Math.sqrt(dx * dx + dy * dy);
                            
                            if (dist < mouseRadius) {
                                const force = (mouseRadius - dist) / mouseRadius;
                                const repel = force * (3.5 + mouseSpeed * 0.15); 
                                const angle = Math.atan2(dy, dx);
                                
                                p.vx -= Math.cos(angle) * repel;
                                p.vy -= Math.sin(angle) * repel;
                            }
                        }
                        
                        const ax = (p.ox - p.x) * spring;
                        const ay = (p.oy - p.y) * spring;
                        
                        p.vx = (p.vx + ax) * damping;
                        p.vy = (p.vy + ay) * damping;
                        
                        p.x += p.vx;
                        p.y += p.vy;
                    }
                }
                
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
        // 2. Global Mouse Aura Script (Velocity Lerp)
        // ==========================================
        document.addEventListener('turbo:load', () => {
            const aura = document.getElementById('global-aura');
            if (!aura) return;

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
                velocityX = mouseX - lastMouseX;
                velocityY = mouseY - lastMouseY;
                lastMouseX = mouseX;
                lastMouseY = mouseY;

                const speed = Math.sqrt(velocityX * velocityX + velocityY * velocityY);
                const maxSpeed = 100;
                const limitedSpeed = Math.min(speed, maxSpeed);
                
                const stretch = 1 + (limitedSpeed / maxSpeed) * 0.8;
                const squeeze = 1 - (limitedSpeed / maxSpeed) * 0.4;
                const angle = Math.atan2(velocityY, velocityX);

                const lerpFactor = 0.1;
                auraX += (mouseX - auraX) * lerpFactor;
                auraY += (mouseY - auraY) * lerpFactor;

                aura.style.transform = `translate3d(${auraX}px, ${auraY}px, 0) translate(-50%, -50%) rotate(${angle}rad) scale(${stretch}, ${squeeze})`;
                
                requestAnimationFrame(animateAura);
            }
            animateAura();
        });

        // ==========================================
        // 3. Bento Cards Spotlight & Tilt Setup
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

        document.addEventListener('turbo:load', () => {
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(document.querySelectorAll(".bento-card"), {
                    max: 12,
                    speed: 800,
                    glare: true,
                    "max-glare": 0.2,
                    scale: 1.03,
                    perspective: 1000,
                    gyroscope: true
                });
            }
        });

        // ==========================================
        // 4. Elastic Magnetic Buttons
        // ==========================================
        document.addEventListener('turbo:load', () => {
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
                    btn.style.transform = `translate(0px, 0px) scale(1)`;
                    btn.style.transition = 'transform 0.6s cubic-bezier(0.25, 1.25, 0.5, 1.25)';
                });
            });
        });

        // ==========================================
        // 5. Scroll-Bound 3D Dashboard Mockup Tilt
        // ==========================================
        document.addEventListener('turbo:load', () => {
            const dashboard = document.getElementById('scroll-3d-dashboard');
            if (dashboard) {
                window.addEventListener('scroll', () => {
                    const scrollProgress = Math.min(window.scrollY / 600, 1);
                    const rotX = 15 - (scrollProgress * 15);
                    const rotY = -5 + (scrollProgress * 5);
                    const scale = 0.95 + (scrollProgress * 0.05);
                    const translateY = (1 - scrollProgress) * 40;
                    
                    dashboard.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg) scale(${scale}) translateY(${translateY}px)`;
                });
            }
        });

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

        document.addEventListener('turbo:load', () => {
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
        });
    </script>
</body>

</html>