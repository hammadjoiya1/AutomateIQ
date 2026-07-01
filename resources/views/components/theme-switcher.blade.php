@php
    $currentTheme = $activeTheme['slug'] ?? session('theme', config('themes.default', 'light'));
    $nextTheme = ($currentTheme === 'light') ? 'dark' : 'light';
@endphp

<div class="inline-flex items-center relative" x-data="{ showAlert: false }">
    <input type="hidden" name="theme" value="{{ $nextTheme }}">
    
    <!-- Beautiful Theme Toggle Switch -->
    <button type="button" @click="showAlert = true; setTimeout(() => showAlert = false, 3000)"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $currentTheme === 'dark' ? 'bg-primary' : 'bg-surface' }}"
        title="Switch to {{ $nextTheme }} mode">
        <span class="sr-only">Toggle theme</span>
        <span 
            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-surface shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center {{ $currentTheme === 'dark' ? 'translate-x-5' : 'translate-x-0' }}">
            @if($currentTheme === 'light')
                <!-- Sun Icon -->
                <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464-4.95a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414zm2.12 8.484a1 1 0 11-1.414 1.414 1 1 0 011.414-1.414zm-8.484 2.12a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414zm-2.12-8.484a1 1 0 111.414-1.414 1 1 0 01-1.414 1.414zM10 14a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            @else
                <!-- Moon Icon -->
                <svg class="w-3 h-3 text-indigo-900" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
            @endif
        </span>
    </button>

    <!-- In-built Alert Toast -->
    <div x-show="showAlert" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="fixed bottom-6 right-6 w-72 p-4 bg-surface border border-border rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-[100] flex items-start gap-3">
        <div class="text-primary mt-0.5 bg-primary/10 p-2 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h4 class="text-base font-bold text-text">Under Process</h4>
            <p class="text-sm text-text-muted mt-1 leading-relaxed">Light mode is currently disabled while we upgrade the site architecture.</p>
        </div>
    </div>
</div>