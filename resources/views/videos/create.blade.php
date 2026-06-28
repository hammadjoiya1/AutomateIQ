<x-public-layout :meta-title="'Create AI Video — AutomateIQ'">
    <div class="py-12 lg:py-20 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Breadcrumbs -->
            <div class="mb-8">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs">
                        <li class="inline-flex items-center">
                            <a href="{{ route('tools.index') }}" class="text-text-muted hover:text-text">Tools</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-text-muted mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="text-text-muted font-semibold">AI Video Generator</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-extrabold font-display text-text">AI Video Generator</h1>
                <p class="text-sm text-text-muted mt-1">Generate high-fidelity AI video clips or full stitched scenes from scripts.</p>
            </div>

            <div class="card p-8 shadow-2xl border-primary/5">
                @if(session('error'))
                    <!-- Inbuilt Website Popup Alert -->
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-90 translate-y-4"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-90 translate-y-4"
                         class="fixed inset-0 z-[100] flex items-center justify-center px-4 pointer-events-none">
                        
                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm pointer-events-auto" @click="show = false"></div>
                        
                        <!-- Modal Content -->
                        <div class="bg-surface border border-red-500/30 rounded-2xl shadow-2xl p-6 max-w-md w-full relative z-10 pointer-events-auto flex flex-col items-center text-center">
                            <!-- Icon -->
                            <div class="w-16 h-16 bg-red-500/10 text-red-500 rounded-full flex items-center justify-center mb-4 ring-8 ring-red-500/5">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            
                            <!-- Text -->
                            <h3 class="text-xl font-bold font-display text-text mb-2">Insufficient Credits</h3>
                            <p class="text-muted-text mb-6">{{ session('error') }}</p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3 w-full">
                                <button type="button" @click="show = false" class="btn-secondary flex-1 py-3 rounded-xl font-medium">
                                    Close
                                </button>
                                <a href="{{ route('pricing') }}" class="btn-primary flex-1 py-3 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Get Credits
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('videos.store') }}" method="POST" x-data="{ selectedStyle: 'realistic', quality: 'hd', mode: 'simple' }">
                    @csrf
                    <input type="hidden" name="mode" :value="mode">

                    @if(!$hasToken)
                        {{-- ... (token warning) ... --}}
                    @endif

                    {{-- Mode Selection Tabs --}}
                    <div class="flex justify-center mb-8">
                        <div class="bg-bg-2 p-1 rounded-xl inline-flex">
                            <button type="button" @click="mode = 'simple'" 
                                :class="mode === 'simple' ? 'bg-surface shadow-sm text-primary font-bold' : 'text-text-muted hover:text-text'"
                                class="px-6 py-2 rounded-lg transition-all text-sm">
                                Simple Prompt
                            </button>
                            <button type="button" @click="mode = 'script'" 
                                :class="mode === 'script' ? 'bg-surface shadow-sm text-primary font-bold' : 'text-text-muted hover:text-text'"
                                class="px-6 py-2 rounded-lg transition-all text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Full Script
                            </button>
                        </div>
                    </div>

                    {{-- Step 1: Prompt & Quality --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                        <div class="lg:col-span-2 space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold font-display text-text" x-text="mode === 'simple' ? 'Step 1: Describe Your Vision' : 'Step 1: Paste Your Script'">Step 1: Describe Your Vision</h3>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                                    <span class="text-xs text-muted-text bg-bg-2 px-2 py-1 rounded border border-border">AI Assistant Ready</span>
                                </div>
                            </div>
                            
                            {{-- Simple Prompt Input --}}
                            <div x-show="mode === 'simple'" class="relative group" x-transition>
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-primary to-accent rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                                <textarea
                                    name="prompt"
                                    rows="6"
                                    class="relative w-full rounded-xl border border-border bg-surface/50 text-text p-5 focus:ring-0 focus:border-primary/50 transition-all placeholder:text-muted-text/50 resize-none z-10"
                                    placeholder="Describe your video in detail... Example: A futuristic drone flying through a neon-lit cyberpunk city at night as rain falls gently..."
                                    :required="mode === 'simple'"
                                    minlength="10"
                                >{{ old('prompt') }}</textarea>
                                <button type="button" class="absolute bottom-3 right-3 text-xs bg-bg-2 hover:bg-primary hover:text-white border border-border text-muted-text px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5 z-20 shadow-sm">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Enhance Prompt
                                </button>
                            </div>

                            {{-- Full Script Input --}}
                            <div x-show="mode === 'script'" class="relative group" style="display: none;" x-transition>
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                                <textarea
                                    name="script"
                                    rows="12"
                                    class="relative w-full rounded-xl border border-border bg-surface/50 text-text p-5 focus:ring-0 focus:border-primary/50 transition-all placeholder:text-muted-text/50 resize-none z-10 font-mono text-sm leading-relaxed"
                                    placeholder="Paste your full script here. We will break it down into scenes automatically.

Example:
Scene 1: The sun rises over the mountains.
Scene 2: A cowboy rides his horse into the valley.
Scene 3: Close up of the cowboy's face, determined."
                                    :required="mode === 'script'"
                                    minlength="20"
                                >{{ old('script') }}</textarea>
                                <div class="absolute bottom-3 right-3 z-20 text-xs text-muted-text bg-surface/80 px-2 py-1 rounded">
                                    Each line will happen sequentially
                                </div>
                            </div>

                            @error('prompt')
                                <p class="text-danger text-sm mt-1 animate-pulse">{{ $message }}</p>
                            @enderror
                            @error('script')
                                <p class="text-danger text-sm mt-1 animate-pulse">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Quality Selector — 3 Tiers --}}
                        <div class="bg-bg-2/30 p-5 rounded-2xl border border-border/50 h-fit">
                            <h3 class="text-lg font-bold font-display text-text mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Quality & Budget
                            </h3>
                            <input type="hidden" name="quality" :value="quality">
                            
                            <div class="space-y-3">
                                @php $tiers = config('credits.video_tiers', []); @endphp

                                {{-- Standard Option --}}
                                <div 
                                    @click="quality = 'standard'"
                                    class="cursor-pointer border-2 rounded-xl p-4 transition-all duration-300 relative overflow-hidden group"
                                    :class="quality === 'standard' ? 'border-green-500 bg-surface shadow-lg shadow-green-500/10' : 'border-transparent bg-surface/50 hover:bg-surface hover:border-border'"
                                >
                                    <div class="flex justify-between items-start mb-2 relative z-10">
                                        <div>
                                            <span class="font-bold text-text block text-lg">Standard</span>
                                            <span class="text-xs text-muted-text">LTX Video</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block font-bold text-green-500 text-lg">{{ $tiers['standard']['credits'] ?? 2 }} credits</span>
                                            <span class="text-[10px] text-muted-text uppercase tracking-wide">Per Video</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-bg-2 h-1.5 rounded-full overflow-hidden mb-2">
                                        <div class="bg-green-500/60 h-full w-[35%]"></div>
                                    </div>
                                    <p class="text-xs text-muted-text">{{ $tiers['standard']['description'] ?? 'Fast drafts & testing.' }}</p>
                                    <div x-show="quality === 'standard'" class="absolute -top-1 -right-1" x-transition>
                                        <div class="bg-green-500 text-white p-1 rounded-bl-lg shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- HD Option (Default) --}}
                                <div 
                                    @click="quality = 'hd'"
                                    class="cursor-pointer border-2 rounded-xl p-4 transition-all duration-300 relative overflow-hidden group"
                                    :class="quality === 'hd' ? 'border-primary bg-surface shadow-lg shadow-primary/10' : 'border-transparent bg-surface/50 hover:bg-surface hover:border-border'"
                                >
                                    <div class="flex justify-between items-start mb-2 relative z-10">
                                        <div>
                                            <span class="font-bold text-text block text-lg">HD</span>
                                            <span class="text-xs text-muted-text">MiniMax Hailuo</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block font-bold text-text text-lg">{{ $tiers['hd']['credits'] ?? 20 }} credits</span>
                                            <span class="text-[10px] text-muted-text uppercase tracking-wide">Per Video</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-bg-2 h-1.5 rounded-full overflow-hidden mb-2">
                                        <div class="bg-gradient-to-r from-primary to-accent h-full w-[70%]"></div>
                                    </div>
                                    <p class="text-xs text-muted-text">{{ $tiers['hd']['description'] ?? '1080p cinematic quality.' }}</p>
                                    <span class="absolute top-2 left-2 bg-primary/20 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full">POPULAR</span>
                                    <div x-show="quality === 'hd'" class="absolute -top-1 -right-1" x-transition>
                                        <div class="bg-primary text-white p-1 rounded-bl-lg shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Premium Option --}}
                                <div 
                                    @click="quality = 'premium'"
                                    class="cursor-pointer border-2 rounded-xl p-4 transition-all duration-300 relative overflow-hidden group"
                                    :class="quality === 'premium' ? 'border-amber-500 bg-surface shadow-lg shadow-amber-500/10' : 'border-transparent bg-surface/50 hover:bg-surface hover:border-border'"
                                >
                                    <div class="flex justify-between items-start mb-2 relative z-10">
                                        <div>
                                            <span class="font-bold text-text block text-lg">Premium</span>
                                            <span class="text-xs text-muted-text">HunyuanVideo</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block font-bold text-amber-500 text-lg">{{ $tiers['premium']['credits'] ?? 25 }} credits</span>
                                            <span class="text-[10px] text-muted-text uppercase tracking-wide">Per Video</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-bg-2 h-1.5 rounded-full overflow-hidden mb-2">
                                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-full w-[95%]"></div>
                                    </div>
                                    <p class="text-xs text-muted-text">{{ $tiers['premium']['description'] ?? 'Highest fidelity. Hollywood-grade.' }}</p>
                                    <div x-show="quality === 'premium'" class="absolute -top-1 -right-1" x-transition>
                                        <div class="bg-amber-500 text-white p-1 rounded-bl-lg shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Visual Style --}}
                    <div class="mb-12">
                        <h3 class="text-xl font-bold font-display text-text mb-4">Step 2: Choose Your Aesthetic</h3>
                        <input type="hidden" name="visual_style" :value="selectedStyle">
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4">
                            @php
                                $styles = [
                                    ['id' => 'realistic', 'name' => 'Realistic V2', 'grad' => 'from-gray-700 to-gray-900', 'icon' => 'photo'],
                                    ['id' => 'cinematic', 'name' => 'Cinematic', 'grad' => 'from-amber-700 to-purple-900', 'new' => true],
                                    ['id' => 'cyberpunk', 'name' => 'Cyberpunk', 'grad' => 'from-pink-600 to-blue-600'],
                                    ['id' => 'anime', 'name' => 'Anime', 'grad' => 'from-indigo-400 to-cyan-400'],
                                    ['id' => 'pixar', 'name' => '3D Animation', 'grad' => 'from-blue-400 to-indigo-500'],
                                    ['id' => 'watercolor', 'name' => 'Watercolor', 'grad' => 'from-emerald-300 to-teal-200'],
                                    ['id' => 'comic', 'name' => 'Comic Book', 'grad' => 'from-yellow-400 to-orange-500'],
                                    ['id' => 'horror', 'name' => 'Dark Horror', 'grad' => 'from-gray-900 to-black border border-red-900'],
                                    ['id' => 'vintage', 'name' => 'Vintage', 'grad' => 'from-amber-200 to-yellow-700'],
                                    ['id' => 'pixel', 'name' => 'Pixel Art', 'grad' => 'from-green-400 to-green-600'],
                                    ['id' => 'painting', 'name' => 'Oil Painting', 'grad' => 'from-amber-700 to-orange-900'],
                                    ['id' => 'french', 'name' => 'French Art', 'grad' => 'from-indigo-300 to-purple-400'],
                                ];
                            @endphp
                            @foreach ($styles as $style)
                                <div
                                    @click="selectedStyle = '{{ $style['id'] }}'"
                                    :class="selectedStyle === '{{ $style['id'] }}' ? 'ring-2 ring-primary scale-105 shadow-xl shadow-primary/20' : 'hover:scale-105 hover:shadow-lg opacity-80 hover:opacity-100'"
                                    class="relative cursor-pointer aspect-[3/4] rounded-2xl overflow-hidden transition-all duration-300 group"
                                >
                                    {{-- Gradient Background --}}
                                    <div class="absolute inset-0 bg-gradient-to-br {{ $style['grad'] }} transition-transform duration-500 group-hover:scale-110"></div>
                                    
                                    {{-- Overlay --}}
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                                    
                                    {{-- Selection Indicator --}}
                                    <div class="absolute top-2 right-2 transition-all duration-300" :class="selectedStyle === '{{ $style['id'] }}' ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-2'">
                                        <div class="bg-white text-primary rounded-full p-1 shadow-lg">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                    
                                    {{-- Label --}}
                                    <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/80 to-transparent">
                                        <p class="text-white font-bold text-sm tracking-wide">{{ $style['name'] }}</p>
                                    </div>

                                    @if($style['new'] ?? false)
                                        <span class="absolute top-2 left-2 bg-accent text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg">NEW</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-between items-center pt-8 border-t border-border">
                        <a href="{{ route('videos.index') }}" class="btn-secondary px-6 py-3 rounded-xl flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Gallery
                        </a>
                        <button type="submit" class="btn btn-primary px-10 py-4 text-lg font-bold rounded-xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all hover:-translate-y-1 flex items-center gap-2">
                            <span>Start Generation</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-public-layout>
