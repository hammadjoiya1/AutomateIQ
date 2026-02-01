<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Theme Management</h1>
        <form action="{{ route('theme.switch') }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="theme" value="dark">
            <button type="submit" class="text-xs text-text-muted hover:text-text underline">Force Dark Mode
                Check</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($themes as $theme)
            <div
                class="card p-6 border {{ $theme->is_default ? 'border-primary shadow-lg shadow-primary/10' : 'border-white/5' }} bg-surface flex flex-col items-center text-center transition-all hover:-translate-y-1">
                <div
                    class="w-16 h-16 rounded-full flex items-center justify-center text-2xl mb-4 bg-bg-2 border border-border">
                    @if($theme->key == 'light') ☀️
                    @elseif($theme->key == 'dark') 🌑
                    @elseif($theme->key == 'neon-cyber') 👾
                    @elseif($theme->key == 'luxury-gold') ⚜️
                    @endif
                </div>
                <h3 class="font-bold text-lg text-text">{{ $theme->name }}</h3>
                <p class="text-xs text-text-muted mb-4">{{ $theme->key }}</p>

                @if($theme->is_default)
                    <span class="badge bg-green-500/10 text-green-500 border-green-500/20 px-4 py-1.5 mb-2">Default Theme</span>
                    <button disabled class="btn btn-sm btn-ghost opacity-50 cursor-not-allowed">Active</button>
                @else
                    <form action="{{ route('admin.themes.activate', $theme) }}" method="POST" class="w-full mt-auto">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary w-full justify-center">Set as Default</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</x-admin-layout>