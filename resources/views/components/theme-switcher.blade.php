@php
    $currentTheme = $activeTheme['slug'] ?? session('theme', config('themes.default', 'light'));
    $nextTheme = ($currentTheme === 'light') ? 'dark' : 'light';
@endphp

<form action="{{ route('theme.switch') }}" method="POST" class="inline-flex items-center">
    @csrf
    <input type="hidden" name="theme" value="{{ $nextTheme }}">
    
    <!-- Beautiful Theme Toggle Switch -->
    <button type="submit" 
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $currentTheme === 'dark' ? 'bg-primary' : 'bg-gray-200' }}"
        title="Switch to {{ $nextTheme }} mode">
        <span class="sr-only">Toggle theme</span>
        <span 
            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out flex items-center justify-center {{ $currentTheme === 'dark' ? 'translate-x-5' : 'translate-x-0' }}">
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
</form>