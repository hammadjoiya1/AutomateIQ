<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('Content Library') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Create Collection Form -->
            <div class="mb-8">
                <form method="POST" action="{{ route('library.store') }}" class="flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="New Collection Name" required
                        class="rounded-md border-primary/30 bg-background text-text focus:border-primary focus:ring focus:ring-primary/20 shadow-sm w-full md:w-1/3">
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary/90">
                        Create Folder
                    </button>
                </form>
            </div>

            <!-- Collections Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($collections as $collection)
                    <a href="{{ route('library.show', $collection->id) }}"
                        class="block p-6 bg-background border border-primary/20 rounded-lg shadow-sm hover:border-primary transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-full bg-primary/10 text-primary">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-text">{{ $collection->name }}</h3>
                                <p class="text-text/60 text-sm">{{ $collection->items_count }} items</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($collections->isEmpty())
                <div class="text-center py-20 text-text/50">
                    <p>No collections yet. Create one to start saving content.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>