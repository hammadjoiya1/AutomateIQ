<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AutomateIQ') }} - Admin</title>
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
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="h-full font-sans antialiased bg-background text-text transition-colors duration-500">
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div
            class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary/10 rounded-full blur-[120px] opacity-40">
        </div>
        <div
            class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-accent/10 rounded-full blur-[120px] opacity-40">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.02]"></div>
    </div>

    <div class="min-h-full flex" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-bg/80 backdrop-blur-sm"></div>

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
                        class="flex grow flex-col gap-y-5 overflow-y-auto glass-panel border-r border-border px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <x-application-logo class="h-8 w-auto text-primary" />
                                <span class="font-display font-bold text-xl text-text">Admin Panel</span>
                            </div>
                            <button type="button" @click="sidebarOpen = false"
                                class="p-2 rounded-md text-text hover:text-primary hover:bg-surface/60 transition">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            @include('layouts.partials.admin-sidebar-items')
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col p-4">
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto card h-full rounded-2xl px-6 pb-4 border border-border shadow-2xl">
                <div class="flex h-16 shrink-0 items-center mt-2 gap-2">
                    <x-application-logo class="h-8 w-auto text-primary" />
                    <span class="font-display font-bold text-xl tracking-tight text-gradient-primary">Admin Panel</span>
                </div>
                <nav class="flex flex-1 flex-col mt-4 space-y-1">
                    @include('layouts.partials.admin-sidebar-items')
                </nav>
                <div class="mt-auto border-t border-border pt-4">
                    <x-theme-switcher />
                </div>
            </div>
        </div>

        <div class="lg:pl-72 flex flex-col w-full min-h-screen">
            <!-- Top bar (similar to App Layout but for Admin) -->
            <div
                class="sticky top-4 z-40 flex h-16 shrink-0 items-center gap-x-4 glass-panel mx-4 sm:mx-6 lg:mx-8 rounded-xl px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 border border-border">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-text lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex-1 text-sm font-medium text-text-muted">
                    Admin / <span class="text-text">Control Panel</span>
                </div>
                <!-- Simple Admin Profile Dropdown -->
                <div class="flex items-center gap-4">
                    <div class="text-sm text-text font-semibold hidden md:block">{{ Auth::user()->name }}</div>
                    <div
                        class="h-8 w-8 rounded-full bg-danger/20 text-danger flex items-center justify-center font-bold text-xs">
                        AD</div>
                </div>
            </div>

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