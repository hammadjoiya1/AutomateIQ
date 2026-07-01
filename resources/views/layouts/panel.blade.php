@php
    $variant = $variant ?? 'user';
    $isAdmin = $variant === 'admin';
    $sidebarPartial = $isAdmin
        ? 'layouts.partials.admin-sidebar-items'
        : 'layouts.partials.sidebar-links';
    $brandTitle = $isAdmin ? 'Admin Panel' : 'AutomateIQ';
    $brandHref = $isAdmin && Route::has('admin.dashboard') ? route('admin.dashboard') : route('home');
    $topbarLabel = $isAdmin ? 'Admin / Control Panel' : 'Dashboard / Overview';
    $creditLink = route('pricing');
    foreach (['starter', 'growth', 'scale'] as $packKey) {
        $settingUrl = \App\Models\Setting::get("lemonsqueezy.topup_checkout_urls.{$packKey}", null);
        $configUrls = config('lemonsqueezy.topup_checkout_urls', []);
        $url = $settingUrl ?: ($configUrls[$packKey] ?? null);
        if ($url) {
            $creditLink = route('billing.topup', $packKey);
            break;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutomateIQ') }}{{ $isAdmin ? ' - Admin' : '' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|space-grotesk:400,500,600,700|space-mono:400,700&display=swap"
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
    class="h-full font-sans antialiased text-text transition-colors duration-500 bg-background">
    @if(session()->has('impersonated_by'))
        <div class="bg-primary text-white text-center py-2 px-4 text-sm font-semibold flex items-center justify-center gap-4 relative z-50 shadow-md">
            <span>You are currently impersonating <strong>{{ Auth::user()->name }}</strong>.</span>
            <form action="{{ route('impersonate.leave') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="underline hover:text-white/80 transition-colors">Leave Impersonation</button>
            </form>
        </div>
    @endif
    <!-- Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/10 rounded-full blur-[120px] opacity-40">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-accent/10 rounded-full blur-[120px] opacity-40">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.02]"></div>
    </div>

    <div class="min-h-full flex" x-data="{ sidebarOpen: false }">
        <!-- Off-canvas menu for mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div
                        class="flex grow flex-col gap-y-5 glass-panel border-r border-white/5 px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center justify-between gap-2">
                            <a href="{{ $brandHref }}" class="flex items-center gap-2">
                                <x-application-logo class="h-8 w-auto text-primary" />
                                <span class="font-display font-bold text-xl text-text">{{ $brandTitle }}</span>
                            </a>
                            <button type="button" @click="sidebarOpen = false"
                                class="p-2 rounded-md text-text hover:text-primary hover:bg-surface/60 transition">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col overflow-y-auto">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        @include($sidebarPartial)
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col p-4">
            <div
                class="flex grow flex-col gap-y-5 card h-full rounded-2xl px-6 pb-4 border border-white/5 shadow-2xl">
                <div class="flex h-16 shrink-0 items-center mt-2 gap-2">
                    <a href="{{ $brandHref }}" class="flex items-center gap-2 group">
                        <x-application-logo
                            class="h-8 w-auto text-primary group-hover:scale-110 transition-transform" />
                        <span
                            class="font-display font-bold text-2xl tracking-tight text-gradient-primary group-hover:opacity-80 transition-opacity">{{ $brandTitle }}</span>
                    </a>
                </div>
                <nav class="flex flex-1 flex-col mt-4 overflow-y-auto">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                @include($sidebarPartial)
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="mt-auto border-t border-border pt-4">
                    <x-theme-switcher />
                </div>
            </div>
        </div>

        <div class="lg:pl-72 flex flex-col w-full min-h-screen">
            <!-- Top bar -->
            <div
                class="sticky top-4 z-40 flex h-16 shrink-0 items-center gap-x-4 glass-panel mx-4 sm:mx-6 lg:mx-8 rounded-xl px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 border border-white/5">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-text lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex-1 text-sm font-medium text-text-muted">
                    {{ $topbarLabel }}
                </div>

                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    @if(!$isAdmin)
                        <!-- Credit Badge -->
                        <a href="{{ $creditLink }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-primary/20 to-accent/20 text-primary border border-primary/30 font-bold hover:scale-105 transition-all shadow-lg shadow-primary/10 mr-2"
                            title="Buy more credits">
                            <svg class="w-4 h-4 animate-pulse" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="text-sm font-bold">{{ number_format(Auth::user()->credits) }} Credits</span>
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="-m-1.5 flex items-center p-1.5 hover:bg-surface rounded-lg transition-colors">
                                <span class="sr-only">Open user menu</span>
                                <div
                                    class="h-8 w-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-sm ring-2 ring-transparent group-hover:ring-primary/20 transition-all">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold text-text">{{ Auth::user()->name }}</span>
                                    <svg class="ml-2 h-5 w-5 text-text-muted" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-xl glass-panel py-2 shadow-lg ring-1 ring-white/5 focus:outline-none"
                                role="menu" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">

                                <div class="px-4 py-2 border-b border-border/50 mb-1">
                                    <p class="text-sm font-semibold text-text truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-text-muted truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-text hover:bg-primary/10 transition-colors">Profile
                                    Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-500/10 transition-colors">Sign
                                        out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-text font-semibold hidden md:block">{{ Auth::user()->name }}</div>
                        <div
                            class="h-8 w-8 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center font-bold text-xs">
                            AD</div>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <x-flash-alerts />
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <x-confirm-dialog />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('scripts')
</body>
</html>
