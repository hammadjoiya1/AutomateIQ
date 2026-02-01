<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ __('Brand Voices') }}
            </h2>
            <a href="{{ route('brand-voices.create') }}"
                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all hover:-translate-y-0.5">
                + Create New Voice
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($voices->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-text/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-text">No brand voices yet</h3>
                            <p class="mt-1 text-sm text-text/60">Define your unique style and AI will write like you.</p>
                            <div class="mt-6">
                                <a href="{{ route('brand-voices.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90">
                                    Create Your First Voice
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($voices as $voice)
                                <div
                                    class="bg-surface rounded-xl p-6 border {{ $voice->is_default ? 'border-primary shadow-primary/20 shadow-lg' : 'border-primary/10' }} relative group transition-all hover:-translate-y-1">
                                    @if($voice->is_default)
                                        <div
                                            class="absolute top-4 right-4 text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-full uppercase tracking-wider">
                                            Active
                                        </div>
                                    @endif

                                    <h3 class="text-xl font-bold text-text mb-2">{{ $voice->name }}</h3>
                                    <p class="text-text/60 text-sm mb-4 line-clamp-3 italic">"{{ $voice->prompt }}"</p>

                                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-primary/10">
                                        <a href="{{ route('brand-voices.edit', $voice) }}"
                                            class="text-sm font-medium text-primary hover:text-primary/70">Edit</a>

                                        @if(!$voice->is_default)
                                            <form action="{{ route('brand-voices.update', $voice) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $voice->name }}">
                                                <input type="hidden" name="prompt" value="{{ $voice->prompt }}">
                                                <input type="hidden" name="is_default" value="1">
                                                <button type="submit" class="text-sm font-medium text-text/50 hover:text-text ml-2"
                                                    title="Set as Active">Make Active</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('brand-voices.destroy', $voice) }}" method="POST" class="ml-auto"
                                            onsubmit="return confirm('Delete this voice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>