<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('AI Blog Generator') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loading: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Form Area -->
                <div class="lg:col-span-1">
                    <div class="card p-6 bg-surface/50 border border-white/5 rounded-3xl sticky top-24">
                        <h3 class="text-lg font-bold text-text mb-6">Generation Parameters</h3>
                        
                        <form method="POST" action="{{ route('admin.blog.generate') }}" @submit="loading = true">
                            @csrf
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">Topic / Title</label>
                                    <input type="text" name="topic" required
                                        class="w-full rounded-xl border border-white/10 bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3 px-4 transition-all"
                                        placeholder="e.g. 10 ways to automate your agency" value="{{ old('topic') }}">
                                    @error('topic')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">Target Keywords</label>
                                    <input type="text" name="keywords"
                                        class="w-full rounded-xl border border-white/10 bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3 px-4 transition-all"
                                        placeholder="e.g. automation, agency scaling, ai tools" value="{{ old('keywords') }}">
                                    <p class="text-xs text-text-muted mt-1">Comma-separated for SEO.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-2">Tone of Voice</label>
                                    <select name="tone" class="w-full appearance-none rounded-xl border border-white/10 bg-background text-text px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all font-semibold">
                                        <option value="Professional" {{ old('tone') == 'Professional' ? 'selected' : '' }}>Professional</option>
                                        <option value="Conversational" {{ old('tone') == 'Conversational' ? 'selected' : '' }}>Conversational</option>
                                        <option value="Authoritative" {{ old('tone') == 'Authoritative' ? 'selected' : '' }}>Authoritative</option>
                                        <option value="Humorous" {{ old('tone') == 'Humorous' ? 'selected' : '' }}>Humorous</option>
                                        <option value="Inspirational" {{ old('tone') == 'Inspirational' ? 'selected' : '' }}>Inspirational</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-full mt-4 shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2" :disabled="loading">
                                    <span x-show="!loading">Generate Post</span>
                                    <span x-show="loading" class="flex items-center gap-2">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Writing with AI...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="lg:col-span-2 space-y-6">
                    @if(session('generated_content'))
                        <!-- Visual Preview -->
                        <div class="card p-8 border border-white/5 bg-surface/50 rounded-3xl overflow-hidden relative">
                            <div class="absolute top-0 right-0 bg-primary/10 text-primary font-bold text-xs px-4 py-2 rounded-bl-xl border-b border-l border-primary/20">
                                Live Preview
                            </div>
                            <div class="prose prose-invert prose-lg max-w-none text-text">
                                <pre class="whitespace-pre-wrap font-sans text-sm text-text-muted bg-transparent p-0 m-0 leading-relaxed">{{ session('generated_content') }}</pre>
                            </div>
                        </div>

                        <!-- Actions Area -->
                        <div class="card p-6 border border-white/5 bg-surface/50 rounded-3xl" x-data="{ copySuccess: false }">
                            <h3 class="text-lg font-bold text-text mb-4">Post Actions</h3>
                            <div class="flex gap-4">
                                <!-- Copy Button -->
                                <button @click="
                                        navigator.clipboard.writeText(`{{ addslashes(session('generated_content')) }}`);
                                        copySuccess = true;
                                        setTimeout(() => copySuccess = false, 2000);
                                    " 
                                    class="btn bg-background border border-white/10 hover:bg-white/5 transition-colors flex items-center gap-2 text-text">
                                    <span x-show="!copySuccess">Copy Text</span>
                                    <span x-show="copySuccess" class="text-green-500">Copied!</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>

                                <!-- Publish Form -->
                                <form method="POST" action="{{ route('admin.blog.store') }}">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ session('topic') }}">
                                    <input type="hidden" name="content" value="{{ session('generated_content') }}">
                                    <button type="submit" class="btn btn-primary shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Publish to Blog
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card p-12 border border-white/5 bg-surface/30 rounded-3xl flex flex-col items-center justify-center text-center h-full min-h-[400px]">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-6 animate-float">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-text mb-2">No Content Generated</h3>
                            <p class="text-text-muted max-w-md">Enter your topic and parameters on the left to generate a fully formatted, SEO-optimized blog post with AI.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
