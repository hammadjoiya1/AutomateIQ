@php
    $trialActive = Auth::check() && Auth::user()->trial_ends_at && now()->lt(Auth::user()->trial_ends_at);
    $isPro = Auth::check() && (in_array(Auth::user()->plan, ['pro', 'team']) || $trialActive);
    $layout = Auth::check() ? 'app-layout' : 'public-layout';
@endphp
<x-dynamic-component :component="$layout" :meta-title="$tool->name . ' — AI Tool'" :meta-description="\Illuminate\Support\Str::limit($tool->description, 160)">
    <div class="py-12 lg:py-20 animate-fade-in" x-data="toolRunner(@js($tool->input_schema ?? []), '{{ $tool->tool_type }}', @js($presets ?? []), {{ $isFavorite ? 'true' : 'false' }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('tools.index') }}"
                            class="text-text-muted hover:text-primary transition-colors">Tools</a></li>
                    <li><svg class="w-4 h-4 text-text-muted/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg></li>
                    <li class="text-text font-medium">{{ $tool->name }}</li>
                </ol>
            </nav>

            <div id="upgrade-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 p-6">
                <div class="card max-w-lg w-full p-6">
                    <h3 class="text-xl font-bold text-text mb-2">Upgrade to continue</h3>
                    <p class="text-text/70 mb-6" id="upgrade-modal-message">You’ve hit your plan limit.</p>
                    <div class="flex gap-3">
                        @auth
                            <a href="{{ route('billing.checkout', 'pro') }}" class="btn btn-primary">Upgrade to Pro</a>
                            <a href="{{ route('pricing') }}" class="btn btn-ghost">Compare plans</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">Start Free</a>
                            <a href="{{ route('pricing') }}" class="btn btn-ghost">See pricing</a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Input Form -->
                <div class="lg:col-span-1">
                    <div class="card p-6 lg:sticky lg:top-24">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="h-12 w-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-sm border border-primary/20">
                                <span class="font-display font-bold text-xl">{{ substr($tool->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-display font-bold text-text leading-tight">{{ $tool->name }}
                                </h1>
                                <span
                                    class="text-xs font-mono text-primary bg-primary/10 px-2 py-0.5 rounded-full">v2.0</span>
                            </div>
                            @auth
                                <button type="button" @click="toggleFavorite" class="ml-auto btn btn-sm btn-ghost">
                                    <span x-text="isFavorite ? '★ Favorited' : '☆ Favorite'"></span>
                                </button>
                            @endauth
                        </div>

                        <p class="text-text-muted mb-4 text-sm leading-relaxed">{{ $tool->description }}</p>

                        <div class="inline-flex items-center gap-2 text-xs text-text-muted mb-6">
                            <span class="px-2 py-1 rounded-full bg-primary/10 text-primary font-semibold">Estimated</span>
                            <span x-text="getCreditCost() + ' credits per run'">{{ number_format($estimatedCredits ?? 0) }} credits per run</span>
                        </div>

                        @if($tool->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($tool->tags as $tag)
                                    <span class="text-xs px-2 py-1 rounded-full bg-surface border border-border text-text-muted">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <form @submit.prevent="generate" class="space-y-6">
                            <div>
                                <label for="input" class="label">
                                    Topic / Prompt <span class="text-red-500">*</span>
                                </label>
                                <textarea id="input" x-model="form.input" rows="5" class="input resize-none py-3"
                                    placeholder="Describe specifically what you want to generate example: 'A 60-second script about AI automation benefits'..."
                                    required></textarea>
                                <p class="text-xs text-text-muted mt-2 text-right" x-text="form.input.length + ' / 2000 chars'"></p>
                            </div>

                            <template x-if="fields.length">
                                <div class="space-y-4">
                                    <template x-for="field in fields" :key="field.name">
                                        <div>
                                            <label class="label" x-text="field.label || field.name"></label>
                                            <template x-if="field.type === 'textarea'">
                                                <textarea class="input resize-none py-3"
                                                    rows="4"
                                                    :required="field.required"
                                                    :placeholder="field.placeholder || ''"
                                                    x-model="form[field.name]"></textarea>
                                            </template>
                                            <template x-if="field.type === 'number'">
                                                <input type="number" class="input"
                                                    :required="field.required"
                                                    :placeholder="field.placeholder || ''"
                                                    x-model="form[field.name]">
                                            </template>
                                            <template x-if="field.type === 'select'">
                                                <select class="input" :required="field.required"
                                                    x-model="form[field.name]">
                                                    <option value="">Select...</option>
                                                    <template x-for="option in getOptions(field)" :key="option">
                                                        <option :value="option" x-text="getOptionLabel(field.name, option)"></option>
                                                    </template>
                                                </select>
                                            </template>
                                            <template x-if="!field.type || field.type === 'text'">
                                                <input type="text" class="input"
                                                    :required="field.required"
                                                    :placeholder="field.placeholder || ''"
                                                    x-model="form[field.name]">
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Dynamic Settings Placeholder -->
                            @if($tool->tool_type !== 'video')
                                <div x-data="{ expanded: false }" class="border-t border-border pt-4">
                                    <button type="button" @click="expanded = !expanded"
                                        class="flex items-center justify-between w-full text-sm font-medium text-text-muted hover:text-text transition-colors">
                                        <span>Advanced Settings</span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div x-show="expanded" x-collapse class="mt-4 space-y-4">
                                        <div>
                                            <label class="label">Tone</label>
                                            <select class="input" x-model="form.tone">
                                                <option>Professional</option>
                                                <option>Casual</option>
                                                <option>Humorous</option>
                                                <option>Friendly</option>
                                                <option>Direct</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="label">Length</label>
                                            <select class="input" x-model="form.length">
                                                <option>Short</option>
                                                <option>Medium</option>
                                                <option>Long</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="label">Format</label>
                                            <select class="input" x-model="form.format">
                                                <option>Paragraph</option>
                                                <option>Bullet Points</option>
                                                <option>Numbered Steps</option>
                                                <option>JSON</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @auth
                                <div class="border-t border-border pt-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-semibold text-text">Presets</h3>
                                        <button type="button" class="btn btn-xs btn-ghost" @click="showPresetForm = !showPresetForm">
                                            <span x-text="showPresetForm ? 'Hide' : 'Save preset'"></span>
                                        </button>
                                    </div>

                                    <template x-if="presets.length">
                                        <div class="space-y-2">
                                            <template x-for="preset in presets" :key="preset.id">
                                                <div class="flex items-center justify-between gap-2 bg-surface border border-border rounded-lg px-3 py-2">
                                                    <button type="button" class="text-sm text-text font-medium text-left flex-1"
                                                        @click="applyPreset(preset)" x-text="preset.name"></button>
                                                    <button type="button" class="text-xs text-text-muted hover:text-text"
                                                        @click="deletePreset(preset)">Remove</button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="!presets.length">
                                        <p class="text-xs text-text-muted">No presets yet.</p>
                                    </template>

                                    <div x-show="showPresetForm" x-collapse class="mt-4 space-y-3">
                                        <input type="text" class="input" placeholder="Preset name" x-model="presetForm.name">
                                        <select class="input" x-model="presetForm.visibility">
                                            <option value="private">Private</option>
                                            <option value="team">Team</option>
                                            <option value="public">Public</option>
                                        </select>
                                        <button type="button" class="btn btn-sm btn-primary w-full" @click="savePreset">Save preset</button>
                                    </div>
                                </div>
                            @endauth

                            <div class="pt-2">
                                <button type="submit"
                                    :disabled="loading || !form.input"
                                    class="w-full px-8 py-4 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl hover:-translate-y-1 flex items-center justify-center gap-2">
                                    <span class="flex items-center justify-center gap-2" x-show="!loading">
                                        Generate Content
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </span>
                                    <span x-show="loading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </div>

                            @guest
                                <div class="bg-surface/50 rounded-lg p-3 text-center border border-border">
                                    <p class="text-xs text-text-muted">
                                        <a href="{{ route('login') }}"
                                            class="underline hover:text-primary transition-colors">Login</a> to save results
                                        to your library.
                                    </p>
                                </div>
                            @endguest
                        </form>
                    </div>
                </div>

                <!-- Right: Output -->
                <div class="lg:col-span-2">
                    <div
                        class="glass-panel rounded-2xl min-h-[600px] flex flex-col relative overflow-hidden ring-1 ring-white/10 group">
                        <!-- Toolbar -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-white/5">
                            <div class="flex items-center gap-2">
                                <h2 class="font-display font-semibold text-text">Output</h2>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] bg-green-500/10 text-green-500 border border-green-500/20"
                                    x-show="output">Generated</span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="copyToClipboard" x-show="output"
                                    class="btn btn-sm btn-ghost gap-1.5 transition-all" title="Copy to Clipboard">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Copy
                                </button>
                                @auth
                                    <button type="button" class="btn btn-sm btn-secondary gap-1.5 shadow-sm"
                                        x-show="output" @click="saveToLibrary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                        Save
                                    </button>
                                @endauth
                            </div>
                        </div>

                        <!-- Content Window -->
                        <div
                            class="flex-grow p-6 md:p-8 font-mono text-sm leading-relaxed text-text/90 relative bg-surface/30">
                            <!-- Empty State -->
                            <template x-if="!output && !loading">
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center text-text-muted/40 p-8 text-center select-none">
                                    <div
                                        class="w-20 h-20 rounded-full bg-surface/50 border border-border/50 flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-text mb-2">Ready to Create</h3>
                                    <p class="max-w-sm">Enter your prompt on the left and hit generate. Results will
                                        stream here instantly.</p>
                                </div>
                            </template>

                            <!-- Loading Skeleton -->
                            <template x-if="loading">
                                <div class="space-y-4 animate-pulse max-w-2xl mx-auto mt-10">
                                    <div class="h-4 bg-text-muted/10 rounded w-3/4"></div>
                                    <div class="h-4 bg-text-muted/10 rounded w-full"></div>
                                    <div class="h-4 bg-text-muted/10 rounded w-5/6"></div>
                                    <div class="h-4 bg-text-muted/10 rounded w-2/3"></div>
                                </div>
                            </template>

                            <!-- Video Output -->
                            <template x-if="output && isVideoUrl(output)">
                                <div class="animate-fade-in">
                                    <video class="w-full rounded-xl border border-white/10 bg-black/40" controls playsinline>
                                        <source :src="output" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <a class="inline-flex mt-3 text-xs text-primary hover:underline" :href="output" target="_blank" rel="noopener">Open video in new tab</a>
                                </div>
                            </template>

                            <!-- Actual Output -->
                            <div x-show="output && !isVideoUrl(output) && !isJson(output) && !output.includes('VIDEO_GENERATION_STARTED') && !output.includes('VIDEO_GENERATION_QUEUED')"
                                x-text="output" class="whitespace-pre-wrap animate-fade-in focus:outline-none"
                                tabindex="0"></div>

                            <template x-if="saveMessage">
                                <div class="mt-4 text-sm text-primary" x-text="saveMessage"></div>
                            </template>

                            <div x-show="output && output.includes('VIDEO_GENERATION_QUEUED')"
                                class="text-center py-8 bg-surface/50 rounded-lg border border-primary/20">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4">
                                </div>
                                <p class="text-text font-medium">Video job queued...</p>
                                <p class="text-text/60 text-sm mt-2">Run ID: <span
                                        x-text="output.replace('VIDEO_GENERATION_QUEUED: ', '')"></span></p>
                                <template x-if="videoStatus">
                                    <p class="text-xs text-text/50 mt-4" x-text="'Status: ' + videoStatus"></p>
                                </template>
                                <div x-show="videoStatus === 'processing' || videoProgress > 0" class="mt-4 max-w-sm mx-auto">
                                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-2 bg-primary transition-all duration-500" :style="'width: ' + Math.max(5, videoProgress) + '%'" aria-hidden="true"></div>
                                    </div>
                                    <div class="text-xs text-text/50 mt-2" x-text="videoProgress > 0 ? videoProgress + '%' : 'Warming up AI model...'"></div>
                                </div>
                            </div>

                            <!-- VIDEO GENERATOR STATUS -->
                            <div x-show="output && output.includes('VIDEO_GENERATION_STARTED')"
                                class="text-center py-8 bg-surface/50 rounded-lg border border-primary/20">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4">
                                </div>
                                <p class="text-text font-medium">Video is generating...</p>
                                <p class="text-text/60 text-sm mt-2">Prediction ID: <span
                                        x-text="output.replace('VIDEO_GENERATION_STARTED: ', '')"></span></p>
                                <template x-if="videoStatus">
                                    <p class="text-xs text-text/50 mt-4" x-text="'Status: ' + videoStatus"></p>
                                </template>
                                <div x-show="videoStatus === 'processing' || videoProgress > 0" class="mt-4 max-w-sm mx-auto">
                                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-2 bg-primary transition-all duration-500" :style="'width: ' + Math.max(5, videoProgress) + '%'" aria-hidden="true"></div>
                                    </div>
                                    <div class="text-xs text-text/50 mt-2" x-text="videoProgress > 0 ? videoProgress + '%' : 'Warming up AI model...'"></div>
                                </div>
                                <div x-show="videoLogs" class="mt-4 text-left text-xs text-text/50 max-w-lg mx-auto whitespace-pre-wrap bg-white/5 border border-white/10 rounded-lg p-3" x-text="videoLogs"></div>
                            </div>

                            <!-- PRODUCTION BOARD (JSON Output) 🏭 -->
                            <template x-if="output && isJson(output)">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-sm font-bold text-text-muted uppercase tracking-wider">
                                            Production Board</h4>
                                    </div>
                                    <template x-for="scene in JSON.parse(output)" :key="scene.scene_number">
                                        <div
                                            class="bg-surface p-4 rounded-lg border border-white/5 hover:border-primary/30 transition-colors">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span
                                                            class="text-xs font-bold bg-primary/20 text-primary px-2 py-1 rounded">Scene
                                                            <span x-text="scene.scene_number"></span></span>
                                                        <span class="text-xs text-text-muted"
                                                            x-text="'Voiceover: ' + (scene.voiceover ? scene.voiceover.substring(0, 50) + '...' : '...')"></span>
                                                    </div>
                                                    <p class="text-sm text-text font-mono leading-relaxed"
                                                        x-text="scene.visual_prompt"></p>
                                                </div>
                                                <button class="btn btn-sm btn-primary shrink-0"
                                                    @click="window.open('{{ route('tools.show', 'ai-video-generator') }}?prompt=' + encodeURIComponent(scene.visual_prompt), '_blank')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Generate Clip
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            @auth
                                @if(!$isPro)
                                    <div class="mt-6 p-4 rounded-xl border border-primary/20 bg-primary/5">
                                        <div class="text-sm text-text mb-3">Unlock higher limits and pro workflows.</div>
                                        <a href="{{ route('billing.checkout', 'pro') }}" class="btn btn-sm btn-primary" data-analytics-event="cta_upgrade_results">
                                            Upgrade to Pro
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <!-- Status Bar -->
                        <div
                            class="px-6 py-3 border-t border-white/5 bg-background/50 backdrop-blur text-xs text-text-muted flex justify-between items-center">
                            <span>Status: <span x-text="loading ? 'Processing...' : 'Ready'"
                                    :class="loading ? 'text-primary' : 'text-text-muted'">Ready</span></span>
                            <span x-text="form.input.length + ' chars'">0 chars</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showUpgradeModal(message) {
                const modal = document.getElementById('upgrade-modal');
                const msg = document.getElementById('upgrade-modal-message');
                if (msg && message) msg.textContent = message;
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    }, { once: true });
                }
            }

            function toolRunner(schema = [], toolType = 'generator', initialPresets = [], initialFavorite = false) {
                const initialInput = new URLSearchParams(window.location.search).get('prompt') || '';
                const form = {
                    input: initialInput,
                    tone: 'Professional',
                    length: 'Medium',
                    format: 'Paragraph',
                };

                (schema || []).forEach((field) => {
                    if (!field || !field.name) return;
                    if (field.name === 'input') return;
                    form[field.name] = field.default ?? '';
                });

                return {
                    fields: schema || [],
                    toolType,
                    presets: initialPresets || [],
                    isFavorite: initialFavorite,
                    showPresetForm: false,
                    presetForm: {
                        name: '',
                        visibility: 'private',
                    },
                    form,
                    output: '',
                    loading: false,
                    saveMessage: '',
                    videoStatus: '',
                    videoProgress: 0,
                    videoLogs: '',

                    getOptions(field) {
                        if (!field || !field.options) return [];
                        if (Array.isArray(field.options)) return field.options;
                        return String(field.options)
                            .split(',')
                            .map(option => option.trim())
                            .filter(Boolean);
                    },

                    getCreditCost() {
                        if (this.toolType === 'video') {
                            const q = this.form.quality || 'hd';
                            if (q === 'standard') return 2;
                            if (q === 'premium') return 25;
                            return 20; // hd
                        }
                        return {{ $estimatedCredits ?? 0 }};
                    },

                    getOptionLabel(fieldName, option) {
                        if (fieldName === 'quality') {
                            if (option === 'standard') return 'Standard (2 credits)';
                            if (option === 'hd') return 'HD (20 credits)';
                            if (option === 'premium') return 'Premium (25 credits)';
                        }
                        return option.charAt(0).toUpperCase() + option.slice(1);
                    },

                    async generate() {
                        if (window.trackEvent) {
                            window.trackEvent('first_run_attempt', { tool: '{{ $tool->slug }}' });
                        }
                        if (!this.form.input) return;
                        this.loading = true;
                        this.output = ''; // Clear previous
                        this.videoStatus = '';
                        this.videoProgress = 0;
                        this.videoLogs = '';

                        try {
                            if (this.toolType === 'video') {
                                const response = await fetch("{{ route('tools.run', $tool->slug) }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(this.form)
                                });

                                if (response.status === 403) {
                                    const data = await response.json().catch(() => ({}));
                                    this.output = "Error: " + (data.message || "Upgrade required.");
                                    showUpgradeModal(data.message || 'You have reached your plan limit.');
                                    return;
                                }

                                const data = await response.json();
                                if (data.status === 'success') {
                                    this.output = data.output;
                                    if (this.output.includes('VIDEO_GENERATION_QUEUED')) {
                                        const runId = this.output.replace('VIDEO_GENERATION_QUEUED: ', '');
                                        this.videoStatus = 'queued';
                                        this.pollStatus(`RUN:${runId}`);
                                    }
                                    if (this.output.includes('VIDEO_GENERATION_STARTED')) {
                                        this.videoStatus = 'starting';
                                        this.pollStatus(this.output.replace('VIDEO_GENERATION_STARTED: ', ''));
                                    }
                                } else {
                                    this.output = "Error: " + (data.message || "Something went wrong.");
                                }
                                return;
                            }

                            const response = await fetch("{{ route('tools.run', $tool->slug) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(this.form)
                            });

                            if (!response.ok) {
                                const data = await response.json().catch(() => ({}));
                                this.output = "Error: " + (data.message || "Upgrade required.");
                                showUpgradeModal(data.message || 'You have reached your plan limit.');
                                return;
                            }

                            const data = await response.json();
                            if (data.status === 'success') {
                                this.output = data.output;
                            } else {
                                this.output = "Error: " + (data.message || "Something went wrong.");
                            }
                        } catch (e) {
                            this.output = "Error: Network error or server failed. " + (e.message || e);
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    },

                    applyPreset(preset) {
                        if (!preset || !preset.input_data) return;
                        Object.keys(preset.input_data).forEach((key) => {
                            this.form[key] = preset.input_data[key];
                        });
                    },

                    async savePreset() {
                        if (!this.presetForm.name) return;
                        try {
                            const response = await fetch("{{ route('tools.presets.store', $tool->slug) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    name: this.presetForm.name,
                                    visibility: this.presetForm.visibility,
                                    input_data: this.form,
                                })
                            });

                            const data = await response.json();
                            if (data.status === 'success') {
                                this.presets.unshift(data.preset);
                                this.presetForm.name = '';
                                this.showPresetForm = false;
                            } else {
                                this.saveMessage = data.message || 'Unable to save preset.';
                            }
                        } catch (e) {
                            this.saveMessage = 'Unable to save preset.';
                        }
                    },

                    async deletePreset(preset) {
                        if (!preset || !preset.id) return;
                        try {
                            const response = await fetch(`/tools/presets/${preset.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            const data = await response.json();
                            if (data.status === 'success') {
                                this.presets = this.presets.filter(item => item.id !== preset.id);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    async toggleFavorite() {
                        try {
                            const response = await fetch("{{ route('tools.favorite', $tool->slug) }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            const data = await response.json();
                            this.isFavorite = data.status === 'added';
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    async pollStatus(id) {
                        const interval = setInterval(async () => {
                            try {
                                const response = await fetch(`/tools/status/${encodeURIComponent(id)}`);
                                const data = await response.json();

                                if (data.status === 'queued') {
                                    this.videoStatus = 'queued';
                                    return;
                                }

                                if (data.status === 'success') {
                                    const prediction = data.data;

                                    if (prediction?.status) {
                                        this.videoStatus = prediction.status;
                                    }

                                    if (prediction?.logs) {
                                        this.videoLogs = prediction.logs;
                                        
                                        // 1. Try to find the LAST percentage in logs
                                        const matches = [...prediction.logs.matchAll(/(\d{1,3})%/g)];
                                        if (matches.length > 0) {
                                            const lastMatch = matches[matches.length - 1];
                                            const percent = Math.min(100, parseInt(lastMatch[1], 10));
                                            if (!Number.isNaN(percent)) this.videoProgress = percent;
                                        } else {
                                            // 2. Try to find step outputs e.g. "15/50" or "15 / 50"
                                            const stepMatches = [...prediction.logs.matchAll(/(\d+)\s*\/\s*(\d+)/g)];
                                            if (stepMatches.length > 0) {
                                                const lastStep = stepMatches[stepMatches.length - 1];
                                                const currentStep = parseInt(lastStep[1], 10);
                                                const totalSteps = parseInt(lastStep[2], 10);
                                                if (totalSteps > 0 && currentStep <= totalSteps) {
                                                    const percent = Math.round((currentStep / totalSteps) * 100);
                                                    this.videoProgress = percent;
                                                }
                                            }
                                        }
                                    }

                                    if (typeof prediction?.progress === 'number') {
                                        const percent = Math.round(Math.min(1, Math.max(0, prediction.progress)) * 100);
                                        this.videoProgress = percent;
                                    }

                                    if (prediction.status === 'completed' && prediction.output) {
                                        this.output = prediction.output;
                                        clearInterval(interval);
                                        return;
                                    }

                                    if (prediction.status === 'succeeded') {
                                        this.output = prediction.output; // URL is here
                                        clearInterval(interval);
                                    } else if (prediction.status === 'failed' || prediction.status === 'canceled') {
                                        this.output = "Error: Video generation failed. " + (prediction.error || "Unknown error");
                                        clearInterval(interval);
                                    }
                                    // If starting/processing, keep polling
                                }
                            } catch (e) {
                                console.error("Polling error", e);
                            }
                        }, 4000); // Check every 4 seconds
                    },

                    async copyToClipboard() {
                        if (!this.output) return;
                        this.saveMessage = '';
                        const text = this.output;

                        try {
                            if (navigator.clipboard && window.isSecureContext) {
                                await navigator.clipboard.writeText(text);
                            } else {
                                const textarea = document.createElement('textarea');
                                textarea.value = text;
                                textarea.style.position = 'fixed';
                                textarea.style.opacity = '0';
                                document.body.appendChild(textarea);
                                textarea.focus();
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);
                            }
                            this.saveMessage = 'Copied to clipboard.';
                        } catch (e) {
                            console.error(e);
                            this.saveMessage = 'Copy failed.';
                        }
                    },

                    async saveToLibrary() {
                        if (!this.output) return;
                        this.saveMessage = '';
                        try {
                            const response = await fetch("{{ route('library.items.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    content: this.output,
                                    tool_name: "{{ $tool->name }}",
                                    input: this.form.input,
                                })
                            });

                            const data = await response.json();
                            if (data.status === 'success') {
                                this.saveMessage = 'Saved to your library.';
                            } else {
                                this.saveMessage = data.message || 'Unable to save.';
                            }
                        } catch (e) {
                            this.saveMessage = 'Unable to save.';
                        }
                    },

                    isJson(str) {
                        if (typeof str !== 'string') return false;
                        if (!str.trim().startsWith('[')) return false; // Optimization for our specific array use case
                        try {
                            const result = JSON.parse(str);
                            const type = Object.prototype.toString.call(result);
                            return type === '[object Array]';
                        } catch (e) {
                            return false;
                        }
                    },

                    isVideoUrl(str) {
                        if (typeof str !== 'string') return false;
                        const value = str.trim();
                        return /^https?:\/\//i.test(value) && /\.mp4($|\?)/i.test(value);
                    }
                }
            }
        </script>
    </div>
</x-dynamic-component>