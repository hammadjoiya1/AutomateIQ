<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('library.index') }}" class="text-text/60 hover:text-primary">Library</a>
            <span class="text-text/40">/</span>
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ $collection->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @forelse($items as $item)
                    <div class="bg-background border border-primary/20 rounded-lg p-6 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="text-sm text-text/50">{{ $item->created_at->format('M d, Y H:i') }}</div>
                            <button class="text-primary hover:text-primary/70 text-sm"
                                onclick="navigator.clipboard.writeText(this.nextElementSibling.innerText); alert('Copied!')">
                                Copy
                            </button>
                            <div class="hidden">{{ $item->content }}</div>
                        </div>
                        <div class="prose dark:prose-invert max-w-none text-text">
                            {!! nl2br(e($item->content)) !!}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-text/50">
                        No items in this collection.
                    </div>
                @endforelse

                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>