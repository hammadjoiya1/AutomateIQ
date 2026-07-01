<x-admin-layout>
    <div class="mb-6">
        <a href="{{ route('admin.logs.index') }}" class="text-text-muted hover:text-text text-sm">← Back to Logs</a>
        <h1 class="text-3xl font-display font-bold text-text mt-2">Log Details</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card p-6 border border-border space-y-4">
            <h3 class="font-bold text-lg text-text">Run Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="text-text-muted">User:</div>
                <div class="text-text font-medium">{{ $run->user->name ?? 'Guest' }}</div>

                <div class="text-text-muted">Tool:</div>
                <div class="text-text font-medium">{{ $run->tool->name }}</div>

                <div class="text-text-muted">Status:</div>
                <div>
                    <span
                        class="px-2 py-1 rounded-full text-xs font-bold {{ $run->status === 'completed' ? 'bg-success/10 text-success' : ($run->status === 'failed' ? 'bg-danger/10 text-danger' : 'bg-accent/10 text-accent') }}">
                        {{ ucfirst($run->status) }}
                    </span>
                </div>

                <div class="text-text-muted">Model:</div>
                <div class="text-text">{{ $run->model_used ?? '—' }}</div>

                <div class="text-text-muted">Cost (Credits):</div>
                <div class="text-text">{{ $run->cost_credits ?? '—' }}</div>

                <div class="text-text-muted">Duration:</div>
                <div class="text-text">
                    {{ $run->duration_ms ? number_format($run->duration_ms / 1000, 2) . ' seconds' : '—' }}
                </div>
            </div>
        </div>

        <div class="card p-6 border border-border">
            <h3 class="font-bold text-lg text-text mb-4">Input Data</h3>
            <pre
                class="bg-bg-2 p-4 rounded-lg overflow-x-auto text-xs font-mono text-text p-4 border border-border">{{ json_encode($run->input_data, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div class="card p-6 border border-border md:col-span-2">
            <h3 class="font-bold text-lg text-text mb-4">Output Result</h3>
            <div class="bg-bg-2 p-4 rounded-lg border border-border text-text whitespace-pre-wrap">
                {{ $run->output_text }}</div>
        </div>
    </div>
</x-admin-layout>