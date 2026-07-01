<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('View Result') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-primary mb-2">Tool</h3>
                        <p class="text-text/80">{{ $run->tool->name }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-primary mb-2">Input</h3>
                        <div class="bg-surface p-4 rounded-lg border border-primary/10">
                            {{ $run->input_data['input'] ?? 'No input' }}
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-primary mb-2">Output</h3>

                        @if($run->tool->tool_type === 'video' || str_contains($run->output_text, 'VIDEO_GENERATION_STARTED') || str_contains($run->output_text, 'VIDEO_GENERATION_QUEUED'))
                            <div x-data="videoPoller({{ json_encode($run->output_text) }})"
                                class="bg-surface p-4 rounded-lg border border-primary/10">

                                <!-- VIDEO PLAYER (MP4 Output) 🎥 -->
                                <div x-show="output && output.startsWith('http') && output.includes('.mp4')"
                                    class="rounded-lg overflow-hidden border border-border shadow-lg">
                                    <video x-bind:src="output" controls class="w-full aspect-video bg-black"></video>
                                    <div class="p-3 bg-surface border-t border-border text-center">
                                        <a x-bind:href="output" download
                                            class="text-sm text-primary hover:text-primary/80 hover:underline">Download
                                            Video File</a>
                                    </div>
                                </div>

                                <!-- VIDEO GENERATOR STATUS -->
                                <div x-show="output && output.includes('VIDEO_GENERATION_STARTED')"
                                    class="text-center py-8">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4">
                                    </div>
                                    <p class="text-text font-medium">Video is generating...</p>
                                    <p class="text-text/60 text-sm mt-2">Prediction ID: <span
                                            x-text="output ? output.replace('VIDEO_GENERATION_STARTED: ', '') : ''"></span></p>
                                    <p class="text-xs text-text/40 mt-4">Refresh page in 2 minutes.</p>
                                </div>

                                <!-- VIDEO QUEUED STATUS -->
                                <div x-show="output && output.includes('VIDEO_GENERATION_QUEUED')"
                                    class="text-center py-8">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4">
                                    </div>
                                    <p class="text-text font-medium">Video job queued...</p>
                                    <p class="text-text/60 text-sm mt-2">Run ID: <span
                                            x-text="output ? output.replace('VIDEO_GENERATION_QUEUED: ', '') : ''"></span></p>
                                </div>

                                <!-- Plain Text Fallback -->
                                <div x-show="output && !output.includes('VIDEO_GENERATION_STARTED') && !(output.startsWith('http') && output.includes('.mp4'))"
                                    x-text="output" class="whitespace-pre-wrap font-mono text-sm leading-relaxed"></div>
                            </div>

                            <script>
                                document.addEventListener('alpine:init', () => {
                                    Alpine.data('videoPoller', (initialOutput) => ({
                                        output: initialOutput,
                                        
                                        init() {
                                            if (this.output.includes('VIDEO_GENERATION_QUEUED')) {
                                                const runId = this.output.replace('VIDEO_GENERATION_QUEUED: ', '').trim();
                                                this.poll(`RUN:${runId}`);
                                            } else if (this.output.includes('VIDEO_GENERATION_STARTED')) {
                                                // Robust regex extraction for Replicate ID (alphanumeric)
                                                const match = this.output.match(/([a-z0-9]{10,})/);
                                                const id = match ? match[1] : this.output.replace('VIDEO_GENERATION_STARTED: ', '').trim();
                                                console.log("Polling started for ID:", id);
                                                this.poll(id);
                                            }
                                        },

                                        async poll(id) {
                                            // Poll immediately once
                                            await this.check(id);

                                            // Then set interval
                                            const interval = setInterval(() => this.check(id, interval), 4000);
                                        },

                                        async check(id, interval = null) {
                                            try {
                                                // Add timestamp to prevent caching
                                                const response = await fetch(`/tools/status/${encodeURIComponent(id)}?t=${new Date().getTime()}`);
                                                const data = await response.json();
                                                
                                                if (data.status === 'queued') {
                                                    return;
                                                }

                                                if (data.status === 'success') {
                                                    const prediction = data.data;
                                                    console.log('Poll status:', prediction.status);

                                                    if (prediction.status === 'completed' && prediction.output) {
                                                        this.output = prediction.output;
                                                        if (interval) clearInterval(interval);
                                                    }
                                                    
                                                    if (prediction.status === 'succeeded') {
                                                        this.output = prediction.output;
                                                        if (interval) clearInterval(interval);
                                                    } else if (prediction.status === 'failed' || prediction.status === 'canceled') {
                                                        this.output = "Error: " + (prediction.error || "Video generation failed");
                                                        if (interval) clearInterval(interval);
                                                    }
                                                }
                                            } catch (e) {
                                                console.error("Polling error:", e);
                                            }
                                        }
                                    }))
                                });
                            </script>

                        @elseif(str_contains($run->tool->name, 'Splitter') || str_contains($run->output_text, '"scene_number":'))
                            <!-- VIDEO FACTORY UI 🏭 -->
                            <div class="space-y-4" x-data="{ scenes: {{ $run->output_text }}, generating: {} }">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-bold text-text-muted uppercase tracking-wider">Production Board
                                    </h4>
                                    <!-- 'Generate All' button could go here in V2 -->
                                </div>

                                <template x-for="scene in scenes" :key="scene.scene_number">
                                    <div
                                        class="bg-surface p-4 rounded-lg border border-border hover:border-primary/30 transition-colors group">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span
                                                        class="text-xs font-bold bg-primary/20 text-primary px-2 py-1 rounded">Scene
                                                        <span x-text="scene.scene_number"></span></span>
                                                    <span class="text-xs text-text-muted"
                                                        x-text="'Voiceover: ' + scene.voiceover.substring(0, 50) + '...'"></span>
                                                </div>
                                                <p class="text-sm text-text font-mono leading-relaxed"
                                                    x-text="scene.visual_prompt"></p>
                                            </div>

                                            <div class="flex flex-col gap-2 items-end">
                                                <button @click="
                                                                            generating[scene.scene_number] = 'loading';
                                                                            // Call the AI Video Generator Tool (ID hardcoded or looked up? We need the slug 'ai-video-generator')
                                                                            fetch('{{ route('tools.run', 'ai-video-generator') }}', {
                                                                                method: 'POST',
                                                                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                                body: JSON.stringify({ input: scene.visual_prompt })
                                                                            })
                                                                            .then(res => res.json())
                                                                            .then(data => {
                                                                                if(data.status === 'success') {
                                                                                     generating[scene.scene_number] = data.output; // This will be 'VIDEO_GENERATION_STARTED: ID'
                                                                                     // Polling logic would go here
                                                                                } else {
                                                                                     alert('Error: ' + data.message);
                                                                                     generating[scene.scene_number] = false;
                                                                                }
                                                                            })
                                                                            .catch(err => {
                                                                                alert('Network Error');
                                                                                generating[scene.scene_number] = false;
                                                                            })
                                                                        " class="btn btn-sm btn-primary shrink-0"
                                                    :disabled="generating[scene.scene_number]"
                                                    x-show="!generating[scene.scene_number]">
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

                                                <div x-show="generating[scene.scene_number] === 'loading'"
                                                    class="text-xs text-primary animate-pulse font-mono">
                                                    Requesting...
                                                </div>

                                                <div x-show="generating[scene.scene_number] && generating[scene.scene_number] !== 'loading'"
                                                    class="text-xs text-success font-mono text-right">
                                                    <span x-text="generating[scene.scene_number]"></span>
                                                    <br>
                                                    <a href="{{ route('tools.index') }}" target="_blank"
                                                        class="underline decoration-dotted text-[10px] text-text-muted hover:text-text">Check
                                                        History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        @else
                            <div
                                class="bg-surface p-4 rounded-lg border border-primary/10 whitespace-pre-wrap font-mono text-sm leading-relaxed">
                                {{ $run->output_text }}
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('tools.history') }}"
                            class="px-4 py-2 border border-primary/30 rounded-md text-text hover:bg-primary/5 transition-colors">
                            Back to History
                        </a>
                        <button onclick="navigator.clipboard.writeText(`{{ addslashes($run->output_text) }}`)"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Copy to Clipboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>