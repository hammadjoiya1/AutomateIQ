<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">Workflow Runs</h2>
                    <p class="text-text-muted">{{ $workflow->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('workflows.index') }}" class="btn btn-ghost">Back to Workflows</a>
                    <form method="POST" action="{{ route('workflows.run', $workflow) }}">
                        @csrf
                        <input type="hidden" name="topic" value="">
                        <button type="submit" class="btn btn-primary">Run Now</button>
                    </form>
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="p-6">
                    @if($runs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-primary/10">
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">Run ID</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">Input</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">Created</th>
                                        <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @foreach($runs as $run)
                                        <tr class="group hover:bg-primary/5 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text">#{{ $run->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                                    {{ $run->status === 'completed' ? 'bg-success/10 text-success border border-success/20' : '' }}
                                                    {{ $run->status === 'failed' ? 'bg-danger/10 text-danger border border-danger/20' : '' }}
                                                    {{ $run->status === 'running' ? 'bg-warning/10 text-warning border border-warning/20' : '' }}
                                                    {{ in_array($run->status, ['queued', 'pending']) ? 'bg-text-muted/10 text-text-muted border border-text-muted/20' : '' }}
                                                ">
                                                    {{ ucfirst($run->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-text/70 max-w-xs truncate">
                                                {{ data_get($run->input_data, 'input', '—') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-muted">
                                                {{ $run->created_at->format('M d, Y • H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('workflows.runs.show', $run) }}" class="btn btn-sm btn-secondary">View Results</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $runs->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="text-text-muted">No runs yet.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
