<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-theme-styles />
</head>

<body data-theme="{{ $activeTheme['slug'] ?? 'dark' }}"
    class="font-sans antialiased bg-background text-text min-h-screen flex flex-col items-center justify-center relative overflow-hidden transition-colors duration-500">
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10">
        <div
            class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-primary/20 rounded-full blur-[120px] animate-float opacity-40">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-accent/20 rounded-full blur-[120px] animate-float-delayed opacity-40">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]"></div>
    </div>

    <div class="w-full sm:max-w-md px-6 py-4">
        <div class="flex justify-center mb-8">
            <a href="/" class="flex items-center gap-2 group">
                <x-application-logo
                    class="w-12 h-12 fill-current text-primary group-hover:scale-110 transition-transform" />
                <span class="font-display font-bold text-2xl tracking-tight text-text">AutomateIQ</span>
            </a>
        </div>

        <div
            class="glass-panel p-8 rounded-2xl shadow-2xl ring-1 ring-white/10 relative overflow-hidden backdrop-blur-xl">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-sm text-text-muted">
            &copy; {{ date('Y') }} AutomateIQ.
        </div>
    </div>
</body>

</html>