<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ isset($brandVoice) ? __('Edit Brand Voice') : __('Create Brand Voice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    <form method="POST" action="{{ isset($brandVoice) ? route('brand-voices.update', $brandVoice) : route('brand-voices.store') }}">
                        @csrf
                        @if(isset($brandVoice))
                            @method('PUT')
                        @endif

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-bold text-text mb-2">Voice Name</label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $brandVoice->name ?? '') }}"
                                class="w-full rounded-md border-primary/30 bg-background text-text focus:border-primary focus:ring focus:ring-primary/20 shadow-sm"
                                placeholder="e.g. Professional Coach, Sarcastic Gamer">
                            @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="prompt" class="block text-sm font-bold text-text mb-2">Voice Instructions (The "DNA")</label>
                            <p class="text-xs text-text/60 mb-2">Describe exactly how the AI should write. Be specific.</p>
                            <textarea name="prompt" id="prompt" rows="6" required
                                class="w-full rounded-md border-primary/30 bg-background text-text focus:border-primary focus:ring focus:ring-primary/20 shadow-sm font-mono text-sm leading-relaxed"
                                placeholder="Example: Use short, punchy sentences. Be extremely enthusiastic. Use emojis like 🚀 and 🔥. Avoid big words. sound like a 22-year-old startup founder.">{{ old('prompt', $brandVoice->prompt ?? '') }}</textarea>
                            @error('prompt') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-8">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="is_default" value="0">
                                <input type="checkbox" name="is_default" value="1"
                                    {{ old('is_default', $brandVoice->is_default ?? false) ? 'checked' : '' }}
                                    class="rounded border-primary/30 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-background">
                                <span class="ml-2 text-sm text-text">Set as Active Default Voice</span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('brand-voices.index') }}" class="px-4 py-2 border border-primary/30 rounded-md text-text hover:bg-primary/5 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all">
                                {{ isset($brandVoice) ? 'Update Voice' : 'Create Voice' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
