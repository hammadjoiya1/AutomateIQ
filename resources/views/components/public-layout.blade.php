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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@125,700;125,800;125,900&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-theme-styles :activeTheme="$activeTheme" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vanilla Tilt -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js" integrity="sha512-wC/cunGGDjXSl9OHUH0RuqSyW4YNLlsPwhcLxwWW1CR4OeC2E1xpcdZz2DeQkEmums41laI+eGMw95IJ15SS3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Lenis Smooth Scroll -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.34/dist/lenis.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="font-sans antialiased bg-background text-text overflow-x-hidden transition-colors duration-300 welcome-dark-bg">

    <!-- Ambient Background Glow Orbs -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>
    <div class="ambient-glow ambient-glow-3"></div>

    <!-- Fine Grid overlay for satin background finish -->
    <div class="fixed inset-0 -z-10 bg-grid-pattern opacity-[0.02] pointer-events-none"></div>

    <!-- Global Mouse Aura -->
    <div id="global-aura" class="fixed top-0 left-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none z-[-1] opacity-50 mix-blend-screen transform -translate-x-1/2 -translate-y-1/2 transition-opacity duration-500"></div>

    <div class="relative min-h-screen flex flex-col overflow-x-clip">
    @if(session()->has('impersonated_by'))
        <div class="bg-primary text-white text-center py-2 px-4 text-sm font-semibold flex items-center justify-center gap-4 relative z-50 shadow-md">
            <span>You are currently impersonating <strong>{{ Auth::user()->name }}</strong>.</span>
            <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="underline hover:text-white/80 transition-colors">Leave Impersonation</button>
            </form>
        </div>
    @endif

        <!-- Navigation Header -->
        <div x-data="{ scrolled: false, open: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
            <!-- Full-Width StratStudio Navigation -->
            <nav class="floating-nav fixed top-0 left-0 right-0 w-full z-50 px-6 py-4 md:px-12 transition-all duration-300">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                            <x-application-logo class="h-6 w-auto text-primary group-hover:scale-110 transition-transform" />
                            <span class="font-display font-bold text-lg text-text tracking-tight group-hover:opacity-80 transition-opacity">
                                {{ \App\Models\Setting::get('site_name', 'AutomateIQ') }}
                            </span>
                        </a>
                    </div>
                    
                    <div class="hidden md:flex items-center gap-8">
                        <a href="{{ route('tools.index') }}" class="nav-link-strat {{ request()->routeIs('tools.*') ? 'active' : '' }}">Tools</a>
                        <a href="{{ route('workflows.index') }}" class="nav-link-strat {{ request()->routeIs('workflows.*') ? 'active' : '' }}">Workflows</a>
                        <a href="{{ route('pricing') }}" class="nav-link-strat {{ request()->routeIs('pricing') ? 'active' : '' }}">Pricing</a>
                        <a href="{{ route('blog.index') }}" class="nav-link-strat {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
                        
                        <x-theme-switcher :activeTheme="$activeTheme" />
                        
                        @if (Route::has('login'))
                            <div class="flex items-center gap-4">
                                @auth
                                    <x-ui.badge variant="accent" class="gap-1.5 py-1.5 px-3">
                                        ⚡ {{ number_format(Auth::user()->credits) }} Credits
                                    </x-ui.badge>
                                    <x-ui.button variant="secondary" size="sm" href="{{ url('/dashboard') }}">Dashboard</x-ui.button>
                                @else
                                    <x-ui.button variant="ghost" size="sm" href="{{ route('login') }}">Log in</x-ui.button>
                                    @if (Route::has('register'))
                                        <x-ui.button variant="primary" size="sm" href="{{ route('register') }}">Get Started</x-ui.button>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center md:hidden">
                        <button type="button" @click="open = true" class="text-text p-2 rounded-md hover:bg-white/5 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile Menu Overlay -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 md:hidden" x-cloak>
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="open = false"></div>
                <div class="absolute inset-y-0 left-0 w-80 max-w-[85vw] z-50">
                    <div x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                        class="h-full bg-surface border-r border-white/10 p-6 flex flex-col justify-between overflow-y-auto">
                        <div>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                                    <x-application-logo class="h-7 w-auto text-primary" />
                                    <span class="font-bold text-lg text-text">AutomateIQ</span>
                                </a>
                                <button type="button" @click="open = false" class="p-2 text-text-muted hover:text-text rounded-md transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-8 space-y-4">
                                <a href="{{ route('tools.index') }}" @click="open = false" class="block py-2 text-base font-medium text-text-muted hover:text-text transition">Tools</a>
                                <a href="{{ route('workflows.index') }}" @click="open = false" class="block py-2 text-base font-medium text-text-muted hover:text-text transition">Workflows</a>
                                <a href="{{ route('pricing') }}" @click="open = false" class="block py-2 text-base font-medium text-text-muted hover:text-text transition">Pricing</a>
                                <a href="{{ route('blog.index') }}" @click="open = false" class="block py-2 text-base font-medium text-text-muted hover:text-text transition">Blog</a>
                            </div>
                        </div>
                        <div class="border-t border-white/10 pt-6">
                            <div class="flex items-center justify-between text-sm text-text-muted mb-6">
                                <span>Theme</span>
                                <x-theme-switcher :activeTheme="$activeTheme" />
                            </div>
                            @if (Route::has('login'))
                                @auth
                                    <x-ui.badge variant="accent" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-lg mb-4">
                                        <span class="text-text">Credits</span>
                                        <span class="font-bold text-primary">{{ number_format(Auth::user()->credits) }}</span>
                                    </x-ui.badge>
                                    <x-ui.button variant="primary" size="lg" class="w-full mb-3" href="{{ $creditLink }}" @click="open = false">Buy Credits</x-ui.button>
                                    <x-ui.button variant="secondary" size="lg" class="w-full" href="{{ url('/dashboard') }}" @click="open = false">Dashboard</x-ui.button>
                                @else
                                    <x-ui.button variant="ghost" size="lg" class="w-full mb-3" href="{{ route('login') }}" @click="open = false">Log in</x-ui.button>
                                    @if (Route::has('register'))
                                        <x-ui.button variant="primary" size="lg" class="w-full" href="{{ route('register') }}" @click="open = false">Get Started</x-ui.button>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-grow pt-24">
            <div class="px-4 sm:px-6 lg:px-8">
                <x-flash-alerts />
            </div>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="footer-strat py-16 relative mt-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- CTA Band above footer links -->
                @guest
                <div class="footer-cta-band mb-16 flex flex-col md:flex-row items-center justify-between gap-8 scroll-reveal">
                    <div>
                        <h3 class="text-2xl font-bold text-text">Ready to automate your channel growth?</h3>
                        <p class="text-text-muted text-sm mt-2 max-w-xl">Create scripts, hooks, and automated workflows in minutes. Scale your content without showing your face.</p>
                    </div>
                    <div class="flex gap-4">
                        <x-ui.button variant="primary" href="{{ route('register') }}">Get Started Free</x-ui.button>
                        <x-ui.button variant="ghost" href="{{ route('demo') }}">Book a Demo</x-ui.button>
                    </div>
                </div>
                @endguest

                <div class="grid md:grid-cols-4 gap-12 items-start">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <x-application-logo class="h-6 w-auto text-primary" />
                            <span class="font-display font-bold text-lg text-text tracking-tight">{{ \App\Models\Setting::get('site_name', 'AutomateIQ') }}</span>
                        </div>
                        <p class="text-text-muted text-sm max-w-sm mb-6 leading-relaxed">
                            {{ \App\Models\Setting::get('site_description', 'Empowering digital creators with structured scripts and seamless AI-driven shorts production.') }}
                        </p>
                        <div class="flex gap-3">
                            @if ($twitter = \App\Models\Setting::get('social_twitter'))
                                <a href="{{ $twitter }}" class="footer-social-icon text-text-muted hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            @endif
                            @if ($facebook = \App\Models\Setting::get('social_facebook'))
                                <a href="{{ $facebook }}" class="footer-social-icon text-text-muted hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="footer-link-group">
                        <h4 class="font-mono">Product</h4>
                        <a href="{{ route('tools.index') }}">Tools</a>
                        <a href="{{ route('workflows.index') }}">Workflows</a>
                        <a href="{{ route('pricing') }}">Pricing</a>
                        <a href="{{ route('blog.index') }}">Blog</a>
                    </div>
                    <div class="footer-link-group">
                        <h4 class="font-mono">Company</h4>
                        <a href="{{ route('about') }}">About Us</a>
                        <a href="{{ route('contact') }}">Contact</a>
                        <a href="{{ route('faq') }}">FAQ</a>
                        <a href="{{ route('demo') }}">Book Demo</a>
                    </div>
                </div>
                <div class="border-t border-white/5 mt-16 pt-8 text-center text-sm text-text-muted flex flex-col sm:flex-row justify-between gap-4 font-medium font-sans">
                    <div>{{ \App\Models\Setting::get('footer_text', '© ' . date('Y') . ' AutomateIQ. All rights reserved.') }}</div>
                    <div class="flex gap-6 justify-center">
                        <a href="{{ route('privacy') }}" class="hover:text-primary transition-colors">Privacy Policy</a>
                        <a href="{{ route('terms') }}" class="hover:text-primary transition-colors">Terms of Service</a>
                        <a href="{{ route('affiliate') }}" class="hover:text-primary transition-colors">Affiliate</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Global Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Global Mouse Aura Script (Velocity Spring Lerp)
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

            // Bento Cards Spotlight & Tilt Setup
            document.addEventListener('mousemove', (e) => {
                document.querySelectorAll('.bento-card, .strat-card').forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                });
            });

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
        });
    </script>
</body>
</html>