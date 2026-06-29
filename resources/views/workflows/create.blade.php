<x-app-layout>
    @php
        $toolOptions = $tools->map(function ($tool) {
            return [
                'id' => $tool->id,
                'name' => $tool->name,
                'slug' => $tool->slug,
            ];
        })->values();

        $initialSteps = old('steps', isset($steps) ? $steps->toArray() : [['tool_id' => '', 'input' => '']]);
        $isEdit = isset($workflow);
    @endphp

    <style>
        /* Hide scrollbar for the canvas */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .node-connector {
            background-image: radial-gradient(circle, var(--color-primary) 2px, transparent 2px);
            background-size: 10px 10px;
            background-position: center;
        }
    </style>

    <div class="py-12" x-data="workflowBuilder(@js($initialSteps))">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="card animate-fade-in-up shadow-xl shadow-primary/5">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">
                                {{ $isEdit ? 'Edit Workflow Canvas' : 'Create Visual Workflow' }}
                            </h2>
                            <p class="text-text-muted">Design your automation pipeline by chaining tools together.</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>

                    <form method="POST" action="{{ $isEdit ? route('workflows.update', $workflow) : route('workflows.store') }}">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 bg-surface/30 p-6 rounded-2xl border border-white/5">
                            <div class="floating-label group relative">
                                <input type="text" name="name" id="name" required
                                    class="w-full rounded-xl border border-primary/20 bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 px-4 py-3 transition-colors peer placeholder-transparent"
                                    placeholder=" " value="{{ old('name', $workflow->name ?? '') }}">
                                <label for="name"
                                    class="absolute left-4 -top-3 text-xs font-bold text-primary px-2 bg-background transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:font-normal peer-placeholder-shown:text-text-muted peer-focus:-top-3 peer-focus:text-xs peer-focus:font-bold peer-focus:text-primary pointer-events-none rounded-md">
                                    Workflow Name
                                </label>
                            </div>

                            <div class="floating-label group">
                                <x-custom-select name="schedule" label="Schedule (Cron)" :options="[
                                    '' => 'Manual Run Only',
                                    'daily' => 'Daily (Midnight)',
                                    'weekly' => 'Weekly',
                                    'hourly' => 'Hourly',
                                ]" :value="old('schedule', $workflow->schedule ?? '')" class="w-full" />
                            </div>
                        </div>

                        <!-- Templates -->
                        <div class="card p-5 mb-8 bg-surface/50 border border-primary/10 rounded-2xl flex flex-col md:flex-row md:items-center md:justify-between gap-4 group hover:bg-surface transition-colors cursor-pointer" @click="applyTemplate('faceless_creator')">
                            <div>
                                <div class="text-sm font-bold text-primary flex items-center gap-2 mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Quick-start template
                                </div>
                                <div class="text-sm text-text">Idea &rarr; Hook &rarr; Script &rarr; Scenes &rarr; Repurpose</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary border border-white/10 group-hover:border-primary/50 transition-colors">Use this pipeline</button>
                        </div>

                        <!-- Node Canvas -->
                        <div class="mb-8">
                            <div class="relative rounded-3xl border border-white/10 bg-[#0f172a] shadow-inner overflow-hidden" style="min-height: 400px;">
                                <!-- Grid Background -->
                                <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.2) 1px, transparent 1px); background-size: 20px 20px;"></div>
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.3),transparent_60%)]"></div>

                                <!-- Horizontal Pipeline Scroll Area -->
                                <div class="relative w-full h-full overflow-x-auto no-scrollbar py-20 px-10 flex items-center" id="canvas-container">
                                    <div class="flex items-center" x-ref="pipelineContainer">
                                        <template x-for="(step, index) in steps" :key="index">
                                            <div class="flex items-center">
                                                <!-- Node Card -->
                                                <div 
                                                    class="relative w-72 rounded-2xl border-2 transition-all cursor-pointer shadow-xl z-10 flex flex-col"
                                                    :class="selectedIndex === index ? 'border-primary bg-surface/90 shadow-primary/20 scale-105' : 'border-white/10 bg-surface/70 hover:border-white/30 hover:bg-surface/80'"
                                                    @click="selectStep(index)">
                                                    
                                                    <!-- Node Header -->
                                                    <div class="p-4 border-b border-white/5 flex items-center justify-between" :class="selectedIndex === index ? 'bg-primary/10' : 'bg-black/20'">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-sm"
                                                                :class="selectedIndex === index ? 'bg-primary text-white' : 'bg-white/10 text-text-muted'">
                                                                <span x-text="index + 1"></span>
                                                            </div>
                                                            <div class="font-bold text-sm text-text truncate" x-text="getToolLabel(step.tool_id) || 'Unconfigured Node'"></div>
                                                        </div>
                                                        <button type="button" @click.stop="removeStep(index)" class="text-text-muted hover:text-red-500 transition-colors p-1" title="Delete Node">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Node Body -->
                                                    <div class="p-4 flex-grow">
                                                        <div class="text-xs text-text-muted uppercase tracking-wider font-bold mb-2">Input Settings</div>
                                                        <div class="text-sm text-text truncate" x-text="step.input || 'No custom parameters...'"></div>
                                                        
                                                        <div class="mt-4 flex items-center justify-between text-xs font-semibold">
                                                            <span class="text-primary/70 flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                                Tool
                                                            </span>
                                                            <span class="text-text-muted" x-show="!step.tool_id">Required</span>
                                                            <span class="text-green-500" x-show="step.tool_id">Configured</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Connection Points -->
                                                    <div class="absolute top-1/2 -left-3 w-6 h-6 -mt-3 rounded-full border-4 border-surface bg-background z-20 flex items-center justify-center" x-show="index !== 0">
                                                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                                                    </div>
                                                    <div class="absolute top-1/2 -right-3 w-6 h-6 -mt-3 rounded-full border-4 border-surface bg-background z-20 flex items-center justify-center">
                                                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                                                    </div>
                                                </div>

                                                <!-- Connector Line (if not last) -->
                                                <div class="w-16 h-12 flex items-center justify-center relative z-0 node-connector" x-show="index !== steps.length - 1">
                                                    <!-- Optional animated dot on the line -->
                                                    <div class="absolute w-2 h-2 bg-primary rounded-full animate-ping opacity-50"></div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Add Node Button -->
                                        <div class="ml-6 flex items-center">
                                            <button type="button" @click="addStep" class="w-16 h-16 rounded-full border-2 border-dashed border-primary/50 flex items-center justify-center text-primary hover:bg-primary/10 hover:scale-110 transition-all shadow-lg shadow-primary/10 group relative">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                <span class="absolute -bottom-8 w-24 text-center text-xs font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity">Add Node</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3 text-xs text-text-muted">
                                Scroll horizontally to view your entire pipeline. Click a node to configure it below.
                            </div>
                        </div>

                        <!-- Settings Panel (Bottom) -->
                        <div class="card p-6 bg-surface/50 border border-primary/10 rounded-3xl mb-8 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                            
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-text flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Node Configuration
                                </h3>
                                <div class="text-sm font-bold text-primary bg-primary/10 px-3 py-1 rounded-full" x-show="steps[selectedIndex]">
                                    Node <span x-text="selectedIndex + 1"></span>
                                </div>
                            </div>

                            <template x-if="steps[selectedIndex]">
                                <div class="grid md:grid-cols-2 gap-8">
                                    <div>
                                        <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-3">Assigned Tool</label>
                                        <div class="relative">
                                            <select class="w-full appearance-none rounded-xl border border-white/10 bg-background text-text px-4 py-3.5 focus:border-primary focus:ring-1 focus:ring-primary/50 transition-all font-semibold"
                                                x-model="steps[selectedIndex].tool_id">
                                                <option value="">Select an AI Tool to execute...</option>
                                                @foreach($tools as $tool)
                                                    <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-text-muted">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-text-muted uppercase tracking-wide mb-3">Input Parameters</label>
                                        <input type="text" class="w-full rounded-xl border border-white/10 bg-background text-text focus:border-primary focus:ring-1 focus:ring-primary/50 py-3.5 px-4 transition-all"
                                            placeholder="e.g. Generate 5 ideas about fitness"
                                            x-model="steps[selectedIndex].input">
                                        <p class="text-xs text-text-muted mt-2">Static input used when this node executes.</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!steps[selectedIndex]">
                                <div class="text-center py-8 text-text-muted">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Please select a node on the canvas to configure it.
                                </div>
                            </template>
                        </div>

                        <!-- Hidden Inputs to submit form -->
                        <template x-for="(step, index) in steps" :key="'hidden-' + index">
                            <div class="hidden">
                                <input type="hidden" :name="'steps['+index+'][tool_id]'" x-model="step.tool_id">
                                <input type="hidden" :name="'steps['+index+'][input]'" x-model="step.input">
                            </div>
                        </template>

                        <div class="border-t border-white/10 pt-8 flex justify-end gap-4">
                            <a href="{{ route('workflows.index') }}"
                                class="btn btn-ghost hover:bg-surface text-text-muted transition-colors">Cancel</a>
                            <button type="submit"
                                class="btn btn-primary px-8 py-3 rounded-xl shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $isEdit ? 'Save Workflow' : 'Deploy Pipeline' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function workflowBuilder(initialSteps = [{ tool_id: '', input: '' }]) {
            return {
                steps: (initialSteps && initialSteps.length)
                    ? initialSteps.map(step => ({
                        tool_id: step.tool_id ?? '',
                        input: step.input ?? ''
                    }))
                    : [{ tool_id: '', input: '' }],
                tools: @json($toolOptions),
                selectedIndex: 0,
                
                addStep() {
                    this.steps.push({ tool_id: '', input: '' });
                    this.selectedIndex = this.steps.length - 1;
                    
                    // Auto-scroll to end of pipeline
                    setTimeout(() => {
                        const container = document.getElementById('canvas-container');
                        if (container) {
                            container.scrollLeft = container.scrollWidth;
                        }
                    }, 50);
                },
                removeStep(index) {
                    this.steps.splice(index, 1);
                    if (this.selectedIndex >= this.steps.length) {
                        this.selectedIndex = Math.max(0, this.steps.length - 1);
                    }
                },
                selectStep(index) {
                    this.selectedIndex = index;
                },
                getToolLabel(id) {
                    const tool = this.tools.find(t => String(t.id) === String(id));
                    return tool ? tool.name : '';
                },
                findToolId(keywords) {
                    const lower = (value) => (value || '').toLowerCase();
                    const match = this.tools.find(tool => {
                        const hay = `${lower(tool.name)} ${lower(tool.slug)}`;
                        return keywords.some(keyword => hay.includes(keyword));
                    });
                    return match ? match.id : '';
                },
                applyTemplate(template) {
                    if (template !== 'faceless_creator') return;

                    const idea = this.findToolId(['idea', 'viral']);
                    const hook = this.findToolId(['hook']);
                    const script = this.findToolId(['script']);
                    const scene = this.findToolId(['scene', 'split']);
                    const repurpose = this.findToolId(['repurpose', 'thread', 'linkedin', 'newsletter']);

                    this.steps = [
                        { tool_id: idea, input: 'Generate 10 video ideas for my niche.' },
                        { tool_id: hook, input: 'Write 5 hooks for the best idea.' },
                        { tool_id: script, input: 'Create a 60‑second script with b‑roll cues.' },
                        { tool_id: scene, input: 'Split the script into 3–5 second scenes.' },
                        { tool_id: repurpose, input: 'Repurpose into a LinkedIn post and X thread.' },
                    ].filter(step => step.tool_id);
                    
                    this.selectedIndex = 0;
                }
            }
        }
    </script>
</x-app-layout>