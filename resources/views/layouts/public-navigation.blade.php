<nav x-data="{ open: false }" class="sticky top-4 z-50 mx-4 sm:mx-6 lg:mx-8 glass-panel rounded-xl mt-4">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('tools.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-primary" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('tools.index')" :active="request()->routeIs('tools.*')"
                        class="text-text hover:text-primary transition-colors duration-300">
                        {{ __('Tools') }}
                    </x-nav-link>
                    <!-- Add Blog later -->
                </div>
            </div>

            <!-- Settings / Auth -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <x-theme-switcher />

                @auth
                    @php
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
                    <a href="{{ $creditLink }}"
                        class="hidden sm:flex items-center gap-2 mr-4 px-3 py-1.5 rounded-full bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 hover:border-primary/40 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 group btn-shine"
                        title="Buy more credits">
                        <div
                            class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="font-bold text-sm counter-animated">{{ number_format(Auth::user()->credits) }} Credits</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-premium text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-text hover:text-primary transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="btn-premium text-sm text-white hover:text-white">Get
                        Started</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-text hover:text-primary focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}"
        class="hidden sm:hidden glass-panel mt-2 rounded-xl border border-border overflow-hidden">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('tools.index')" :active="request()->routeIs('tools.*')"
                class="rounded-lg hover:bg-white/5 text-text">
                {{ __('Tools') }}
            </x-responsive-nav-link>
        </div>

        <!-- Mobile Auth/Settings -->
        <div class="pt-4 pb-4 border-t border-border px-4 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-text-muted">Theme</span>
                <x-theme-switcher />
            </div>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="block w-full text-center py-2 rounded-lg bg-primary/20 text-primary font-medium">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block w-full text-center py-2 text-text hover:text-primary">Log in</a>
                <a href="{{ route('register') }}"
                    class="block w-full text-center py-2 rounded-lg bg-primary text-white font-medium shadow-lg shadow-primary/30">Get
                    Started</a>
            @endauth
        </div>
    </div>
</nav>