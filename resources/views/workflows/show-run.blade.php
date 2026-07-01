<x-app-layout>
    <div class="py-12" x-data="workflowRunStatus('{{ route('workflows.runs.status', $run) }}', '{{ $run->status }}')">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">
                        Workflow Results
                    </h2>
                    <p class="text-text-muted">{{ $workflow->name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-xs text-text/60">Run status</div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                        :class="statusBadge">
                        <span class="w-2 h-2 rounded-full mr-2" :class="statusDot"></span>
                        <span x-text="status"></span>
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 banner banner-success animate-fade-in-up">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-success/20 text-success">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <template x-if="results && results.length">
                        <div class="space-y-4">
                            <template x-for="item in results" :key="item.step">
                                <div class="card p-5 border border-primary/10 bg-card/60">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-xs text-text-muted">Step <span x-text="item.step"></span></div>
                                            <div class="text-lg font-semibold text-text" x-text="item.tool"></div>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full"
                                            :class="item.status === 'success' ? 'bg-success/10 text-success border border-success/20' : 'bg-danger/10 text-danger border border-danger/20'"
                                            x-text="item.status === 'success' ? 'Success' : 'Failed'"></span>
                                    </div>
                                    <template x-if="item.output">
                                        <pre class="mt-4 whitespace-pre-wrap text-sm text-text/80 bg-surface/60 border border-border rounded-lg p-4" x-text="item.output"></pre>
                                    </template>
                                    <template x-if="item.error">
                                        <div class="mt-4 text-sm text-danger" x-text="item.error"></div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!results || !results.length">
                        <div class="card p-8 border border-primary/10 bg-card/60 text-center">
                            <div class="text-text/60">Results will appear here when the workflow finishes.</div>
                            <div class="mt-4 text-xs text-text/40">This page auto-refreshes status.</div>
                        </div>
                    </template>
                </div>

                <div class="space-y-6">
                    <div class="card p-5 border border-primary/10 bg-card/60">
                        <div class="text-sm font-semibold text-text mb-3">Run Details</div>
                        <div class="text-xs text-text/60">Run ID</div>
                        <div class="text-sm text-text mb-4">{{ $run->id }}</div>
                        <div class="text-xs text-text/60">Started</div>
                        <div class="text-sm text-text mb-4">{{ $run->created_at->format('M d, Y • H:i') }}</div>
                        <div class="text-xs text-text/60">Input</div>
                        <div class="text-sm text-text">{{ data_get($run->input_data, 'input', '—') }}</div>
                    </div>

                    <div class="card p-5 border border-primary/10 bg-card/60">
                        <div class="text-sm font-semibold text-text mb-3">Actions</div>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('workflows.index') }}" class="btn btn-ghost">Back to Workflows</a>
                            <form method="POST" action="{{ route('workflows.run', $workflow) }}">
                                @csrf
                                <input type="hidden" name="topic" value="{{ data_get($run->input_data, 'input', '') }}">
                                <button type="submit" class="btn btn-primary">Run Again</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function workflowRunStatus(statusUrl, initialStatus) {
            return {
                status: initialStatus || 'queued',
                results: @json($run->results ?? []),
                timer: null,
                get statusBadge() {
                    if (this.status === 'completed') return 'bg-success/10 text-success border border-success/20';
                    if (this.status === 'failed') return 'bg-danger/10 text-danger border border-danger/20';
                    if (this.status === 'running') return 'bg-warning/10 text-warning border border-warning/20';
                    return 'bg-text-muted/10 text-text-muted border border-text-muted/20';
                },
                get statusDot() {
                    if (this.status === 'completed') return 'bg-success';
                    if (this.status === 'failed') return 'bg-danger';
                    if (this.status === 'running') return 'bg-warning';
                    return 'bg-text-muted';
                },
                init() {
                    if (this.status === 'queued' || this.status === 'running') {
                        this.timer = setInterval(async () => {
                            try {
                                const res = await fetch(statusUrl);
                                const data = await res.json();
                                this.status = data.status || this.status;
                                this.results = data.results || this.results;
                                if (this.status === 'completed' || this.status === 'failed') {
                                    clearInterval(this.timer);
                                }
                            } catch (e) {
                                console.error(e);
                            }
                        }, 3000);
                    }
                }
            }
        }
    </script>
</x-app-layout>
