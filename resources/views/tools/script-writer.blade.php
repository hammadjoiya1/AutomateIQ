<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold font-display text-text mb-2">✍️ AI Script Writer</h1>
                <p class="text-text-secondary">Generate professional video scripts for faceless content in seconds</p>
            </div>

            {{-- Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-success/10 border border-success/30 text-success rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-danger/10 border border-danger/30 text-danger rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form --}}
            <div class="card p-8">
                <form action="{{ route('tools.script-writer.generate') }}" method="POST" x-data="{ generating: false }" @submit="generating = true">
                    @csrf

                    {{-- Topic --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-text mb-2">What's your video about?</label>
                        <textarea name="topic" rows="3" required
                            class="w-full px-4 py-3 bg-input-bg border border-border rounded-xl text-input-text placeholder-placeholder focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="e.g., How to make passive income with AI tools">{{ old('topic') }}</textarea>
                        @error('topic')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tone --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-text mb-2">Tone</label>
                        <select name="tone" required
                            class="w-full px-4 py-3 bg-input-bg border border-border rounded-xl text-input-text focus:ring-2 focus:ring-primary">
                            <option value="professional" {{ old('tone') == 'professional' ? 'selected' : '' }}>Professional
                            </option>
                            <option value="casual" {{ old('tone') == 'casual' ? 'selected' : '' }}>Casual</option>
                            <option value="energetic" {{ old('tone') == 'energetic' ? 'selected' : '' }}>Energetic
                            </option>
                            <option value="humorous" {{ old('tone') == 'humorous' ? 'selected' : '' }}>Humorous</option>
                        </select>
                    </div>

                    {{-- Length --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-text mb-2">Script Length</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="length" value="short" {{ old('length', 'medium') == 'short' ? 'checked' : '' }}
                                    class="sr-only peer" required>
                                <div
                                    class="p-4 border-2 border-border rounded-xl text-center transition-all peer-checked:border-primary peer-checked:bg-primary/10">
                                    <div class="font-semibold">Short</div>
                                    <div class="text-sm text-text-secondary">~30s</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="length" value="medium"
                                    {{ old('length', 'medium') == 'medium' ? 'checked' : '' }} class="sr-only peer">
                                <div
                                    class="p-4 border-2 border-border rounded-xl text-center transition-all peer-checked:border-primary peer-checked:bg-primary/10">
                                    <div class="font-semibold">Medium</div>
                                    <div class="text-sm text-text-secondary">~60s</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="length" value="long" {{ old('length') == 'long' ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div
                                    class="p-4 border-2 border-border rounded-xl text-center transition-all peer-checked:border-primary peer-checked:bg-primary/10">
                                    <div class="font-semibold">Long</div>
                                    <div class="text-sm text-text-secondary">~3min</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Optional Fields --}}
                    <div x-data="{ showAdvanced: false }" class="mb-8">
                        <button type="button" @click="showAdvanced = !showAdvanced"
                            class="text-primary hover:text-primary-hover font-semibold text-sm mb-4">
                            <span x-show="!showAdvanced">+ Advanced Options</span>
                            <span x-show="showAdvanced">- Hide Advanced Options</span>
                        </button>

                        <div x-show="showAdvanced" x-cloak class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-text mb-2">Target Audience (optional)</label>
                                <input type="text" name="target_audience" value="{{ old('target_audience') }}"
                                    class="w-full px-4 py-3 bg-input-bg border border-border rounded-xl text-input-text placeholder-placeholder focus:ring-2 focus:ring-primary"
                                    placeholder="e.g., Entrepreneurs, Students, General audience">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-text mb-2">Key Points to Include
                                    (optional)</label>
                                <textarea name="key_points" rows="2"
                                    class="w-full px-4 py-3 bg-input-bg border border-border rounded-xl text-input-text placeholder-placeholder focus:ring-2 focus:ring-primary"
                                    placeholder="List specific points you want to cover...">{{ old('key_points') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="generating" data-analytics-event="first_run_attempt"
                        class="w-full px-8 py-4 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <span x-show="!generating">✨ Generate Script</span>
                        <span x-show="generating" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Generating your script...
                        </span>
                    </button>

                    <p class="mt-4 text-sm text-text-secondary text-center">
                        View your <a href="{{ route('scripts.index') }}" class="text-primary hover:underline">script
                            history</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
