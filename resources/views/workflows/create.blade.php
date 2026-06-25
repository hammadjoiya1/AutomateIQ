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
    <div class="py-12" x-data="workflowBuilder(@js($initialSteps))">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card animate-fade-in-up">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">
                                {{ $isEdit ? 'Edit Workflow' : 'Create New Workflow' }}
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

                    <form method="POST"
                        action="{{ $isEdit ? route('workflows.update', $workflow) : route('workflows.store') }}">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="floating-label group relative">
                                <input type="text" name="name" id="name" required
                                    class="w-full rounded-xl border border-primary/20 bg-background text-text focus:border-primary focus:ring-0 px-4 py-3 transition-colors peer placeholder-transparent"
                                    placeholder=" " value="{{ old('name', $workflow->name ?? '') }}">
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
    ]" :value="old('schedule', $workflow->schedule ?? '')"
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

                        <!-- Steps Builder (n8n‑style) -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            <!-- Canvas -->
                            <div class="lg:col-span-2">
                                <div class="relative rounded-2xl border border-primary/10 bg-surface/40 p-6 min-h-[420px] overflow-hidden">
                                    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.25),transparent_40%),radial-gradient(circle_at_80%_30%,rgba(56,189,248,0.25),transparent_40%)]"></div>

                                    <div class="relative space-y-4">
                                        <template x-for="(step, index) in steps" :key="index">
                                            <div class="relative">
                                                <div class="absolute left-4 -top-4 h-4 w-0.5 bg-primary/30" x-show="index !== 0"></div>
                                                <div
                                                    class="group rounded-2xl border border-primary/20 bg-background/70 p-4 shadow-sm hover:shadow-lg transition-all"
                                                    :class="selectedIndex === index ? 'ring-2 ring-primary/50 border-primary/40' : ''"
                                                    draggable="true"
                                                    @dragstart="startDrag(index)"
                                                    @dragover.prevent
                                                    @drop="dropOn(index)"
                                                    @click="selectStep(index)">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-primary/15 text-primary text-xs font-bold" x-text="index + 1"></span>
                                                            <div>
                                                                <div class="text-sm font-semibold text-text" x-text="getToolLabel(step.tool_id) || 'Choose a tool'"
                                                                ></div>
                                                                <div class="text-xs text-text-muted" x-text="step.input || 'Click to configure'"
                                                                ></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <button type="button" class="text-xs text-text-muted hover:text-primary" title="Move">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16M4 14h16"></path>
                                                                </svg>
                                                            </button>
                                                            <button type="button" @click.stop="removeStep(index)" class="text-xs text-danger hover:text-danger">
                                                                Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Node Settings Panel -->
                            <div class="card p-5 bg-card/60 border border-primary/10 rounded-2xl">
                                <div class="text-sm font-semibold text-text mb-4">Node Settings</div>
                                <template x-if="steps[selectedIndex]">
                                    <div class="space-y-4">
                                        <div class="relative">
                                            <label class="block text-xs font-bold text-primary uppercase tracking-wide mb-2 ml-1">Tool</label>
                                            <select class="w-full rounded-xl border border-primary/20 bg-background text-text px-4 py-3"
                                                x-model="steps[selectedIndex].tool_id">
                                                <option value="">Choose a tool...</option>
                                                @foreach($tools as $tool)
                                                    <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-primary uppercase tracking-wide mb-2 ml-1">Configuration</label>
                                            <input type="text" class="w-full rounded-xl border-primary/20 bg-surface text-text focus:border-primary focus:ring-0 py-3.5 px-4"
                                                placeholder="Optional: Static input parameters"
                                                x-model="steps[selectedIndex].input">
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!steps[selectedIndex]">
                                    <div class="text-sm text-text-muted">Select a node to edit its settings.</div>
                                </template>
                            </div>
                        </div>

                        <template x-for="(step, index) in steps" :key="'hidden-' + index">
                            <div class="hidden">
                                <input type="hidden" :name="'steps['+index+'][tool_id]'" x-model="step.tool_id">
                                <input type="hidden" :name="'steps['+index+'][input]'" x-model="step.input">
                            </div>
                        </template>

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
                        {{ $isEdit ? 'Update Workflow' : 'Create Workflow' }}
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
                dragIndex: null,
                addStep() {
                    this.steps.push({ tool_id: '', input: '' });
                    this.selectedIndex = this.steps.length - 1;
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
                startDrag(index) {
                    this.dragIndex = index;
                },
                dropOn(index) {
                    if (this.dragIndex === null || this.dragIndex === index) return;
                    const moved = this.steps.splice(this.dragIndex, 1)[0];
                    this.steps.splice(index, 0, moved);
                    this.selectedIndex = index;
                    this.dragIndex = null;
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
                }
            }
        }
    </script>
</x-app-layout>