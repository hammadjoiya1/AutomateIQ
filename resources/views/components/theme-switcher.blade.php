@php
    $allThemes = config('themes.themes');
    $currentTheme = session('theme', config('themes.default'));
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-text hover:text-primary transition-colors rounded-lg hover:bg-white/50 border border-primary/10">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
            </path>
        </svg>
        <span class="hidden sm:inline">Theme</span>
    </button>

    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 p-2"
        style="display: none;">

        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">Choose Theme</div>

        @foreach($allThemes as $slug => $theme)
            <form action="{{ route('theme.switch') }}" method="POST" class="mb-1">
                @csrf
                <input type="hidden" name="theme" value="{{ $slug }}">
                <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $currentTheme === $slug ? 'bg-primary text-white font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center"
                            style="background: {{ $theme['colors']['primary'] }}">
                            @if($currentTheme === $slug)
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                        <span>{{ $theme['name'] }}</span>
                    </div>
                </button>
            </form>
        @endforeach
    </div>
</div>