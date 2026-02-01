<x-app-layout>
    @php
        $toolOptions = $tools->map(function ($tool) {
            return [
                'id' => $tool->id,
                'name' => $tool->name,
                'slug' => $tool->slug,
            ];
        })->values();
    @endphp
    <div class="py-12" x-data="workflowBuilder()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card animate-fade-in-up">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">Create New Workflow
                            </h2>
                            <p class="text-text-muted">Automate your content creation by defining a series of steps.</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('workflows.store') }}">
                        @csrf

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="floating-label group relative">
                                <input type="text" name="name" id="name" required
                                    class="w-full rounded-xl border border-primary/20 bg-background text-text focus:border-primary focus:ring-0 px-4 py-3 transition-colors peer placeholder-transparent"
                                    placeholder=" ">
                                <label for="name"
                                    class="absolute left-4 -top-3 text-xs font-bold text-primary px-2 bg-background transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:font-normal peer-placeholder-shown:text-text-muted peer-focus:-top-3 peer-focus:text-xs peer-focus:font-bold peer-focus:text-primary pointer-events-none"
                                    style="z-index: 10;">Workflow
                                    Name</label>
                            </div>

                            <div class="floating-label group">
                                <x-custom-select name="schedule" label="Schedule (Cron)" :options="[
        '' => 'Manual Run Only',
        'daily' => 'Daily (Midnight)',
        'weekly' => 'Weekly',
        'hourly' => 'Hourly',
    ]"
                                    class="w-full" />
                            </div>
                        </div>

                        <div class="relative py-4 mb-8">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-primary/10"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span
                                    class="px-4 bg-background text-sm text-text-muted font-medium uppercase tracking-wider">Workflow
                                    Steps</span>
                            </div>
                        </div>

                        <div class="card p-5 mb-8 bg-surface/50 border border-primary/10">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-text">Quick‑start template</div>
                                    <div class="text-xs text-text-muted">Idea → Hook → Script → Scenes → Repurpose</div>
                                </div>
                                <button type="button" @click="applyTemplate('faceless_creator')"
                                    class="btn btn-sm btn-primary">Use this workflow</button>
                            </div>
                        </div>

                        <!-- Steps Builder -->
                        <div class="space-y-6 mb-8">
                            <template x-for="(step, index) in steps" :key="index">
                                <div
                                    class="card card-hover border-2 border-primary/10 p-6 relative group transition-all duration-300 hover:border-primary/30">
                                    <div class="absolute -left-3 top-6 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shadow-lg ring-4 ring-background z-10"
                                        x-text="index + 1"></div>

                                    <div class="absolute top-4 right-4 transition-opacity">
                                        <button type="button" @click="removeStep(index)"
                                            class="p-2 rounded-lg text-danger hover:bg-danger/10 transition-colors"
                                            title="Remove Step">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-4">
                                        <div class="relative z-20">
                                            <!-- Tool Select -->
                                            <div class="relative mb-1">
                                                <!-- Alpine/Blade component bridging: 
                                                     We need to recreate the select logic because x-model inside blade components can be tricky.
                                                     However, for simplicity, we will use the custom select but manually bind x-model to the hidden input
                                                -->
                                                <div x-data="{
                                                    open: false,
                                                    selected: step.tool_id,
                                                    label: '',
                                                    options: [
                                                        {value: '', label: 'Choose a tool...'},
                                                        @foreach($tools as $tool)
                                                            {value: '{{ $tool->id }}', label: '{{ $tool->name }}'}, 
                                                        @endforeach
                                                    ],
                                                    init() {
                                                        const found = this.options.find(o => o.value == this.selected);
                                                        this.label = found ? found.label : '';
                                                        
                                                        // Watch for external changes (like initial load)
                                                        this.$watch('step.tool_id', (val) => {
                                                            this.selected = val;
                                                            const found = this.options.find(o => o.value == val);
                                                            this.label = found ? found.label : '';
                                                        });
                                                    },
                                                    select(option) {
                                                        this.selected = option.value;
                                                        this.label = option.label;
                                                        this.open = false;
                                                        step.tool_id = this.selected; // Update parent alpine model
                                                    }
                                                }" class="relative" @click.outside="open = false">

                                                    <label
                                                        class="block text-xs font-bold text-primary uppercase tracking-wide mb-2 ml-1">Select
                                                        Tool</label>

                                                    <button type="button" @click="open = !open"
                                                        class="w-full relative flex items-center justify-between w-full rounded-xl border border-primary/20 bg-background text-text px-4 py-3 transition-all duration-300 hover:border-primary/50 focus:outline-none focus:border-primary focus:ring-0"
                                                        :class="{'border-primary bg-background shadow-lg': open}">

                                                        <span class="block truncate"
                                                            x-text="label || 'Choose a tool...'"
                                                            :class="{'text-text': label, 'text-text-muted': !label}"></span>

                                                        <span
                                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-primary">
                                                            <svg class="h-5 w-5 transition-transform duration-300"
                                                                :class="{'rotate-180': open}"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                        x-transition:leave="transition ease-in duration-150"
                                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                                        class="absolute z-50 mt-2 w-full rounded-xl bg-background/95 backdrop-blur-xl shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-primary/10 max-h-60 overflow-auto py-1 custom-scrollbar">

                                                        <ul class="py-1">
                                                            <template x-for="option in options" :key="option.value">
                                                                <li @click="select(option)"
                                                                    class="cursor-pointer select-none relative py-3 pl-4 pr-4 hover:bg-primary/10 transition-colors group"
                                                                    :class="{'bg-primary/5 text-primary font-semibold': selected == option.value, 'text-text': selected != option.value}">
                                                                    <div class="flex items-center">
                                                                        <span class="block truncate"
                                                                            :class="{'font-semibold': selected == option.value, 'font-normal': selected != option.value}"
                                                                            x-text="option.label"></span>
                                                                    </div>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" :name="'steps['+index+'][tool_id]'"
                                                x-model="step.tool_id">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-primary uppercase tracking-wide mb-2 ml-1">Configuration</label>
                                            <input type="text" :name="'steps['+index+'][input]'" x-model="step.input"
                                                class="w-full rounded-xl border-primary/20 bg-surface text-text focus:border-primary focus:ring-0 py-3.5 px-4 transition-all placeholder-text-muted/50"
                                                placeholder="Optional: Static input parameters">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </template>
                </div>

                <div class="flex justify-center mb-10">
                    <button type="button" @click="addStep"
                        class="group relative inline-flex items-center px-6 py-2 border-2 border-dashed border-primary/30 text-sm font-bold rounded-xl text-primary hover:border-primary hover:bg-primary/5 focus:outline-none transition-all duration-300">
                        <span
                            class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center mr-2 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </span>
                        Add Next Step
                    </button>
                </div>

                <div class="border-t border-primary/10 pt-6 flex justify-end gap-4 overflow-hidden">
                    <a href="{{ route('workflows.index') }}"
                        class="btn btn-ghost hover:bg-surface text-text-muted">Cancel</a>
                    <button type="submit"
                        class="btn btn-primary btn-shine shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Create Workflow
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        function workflowBuilder() {
            return {
                steps: [
                    { tool_id: '', input: '' } // Initial step
                ],
                tools: @json($toolOptions),
                addStep() {
                    this.steps.push({ tool_id: '', input: '' });
                },
                removeStep(index) {
                    this.steps.splice(index, 1);
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
                }
            }
        }
    </script>
</x-app-layout>