<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('Contact Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    @if($messages->isEmpty())
                        <div class="text-center py-10 text-text/50">
                            No messages found.
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($messages as $message)
                                <div class="bg-primary/5 p-4 rounded-lg border border-primary/10">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="font-bold text-lg">{{ $message->name }}</span>
                                            <span class="text-sm text-text/60 ml-2">&lt;{{ $message->email }}&gt;</span>
                                        </div>
                                        <span
                                            class="text-xs text-text/50">{{ $message->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <p class="text-text/80 whitespace-pre-wrap">{{ $message->message }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>