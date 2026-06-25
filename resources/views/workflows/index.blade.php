<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="font-display font-bold text-3xl text-text leading-tight mb-2">
                        {{ __('My Workflows') }}
                    </h2>
                    <p class="text-text-muted">Manage and automate your content creation pipelines</p>
                </div>
                <a href="{{ route('workflows.create') }}"
                    class="btn btn-primary btn-shine hover-glow shadow-lg transition-transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Workflow
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 banner banner-success animate-fade-in-up">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-success/20 text-success">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="card overflow-hidden animate-fade-in-up delay-100">
                <div class="p-6">
                    @if($workflows->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-primary/10">
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">
                                            Name</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">
                                            Schedule</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-primary uppercase tracking-wider">
                                            Created</th>
                                        <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5">
                                    @foreach($workflows as $workflow)
                                        <tr class="group hover:bg-primary/5 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-semibold text-text group-hover:text-primary transition-colors">
                                                    {{ $workflow->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-muted">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $workflow->schedule ?? 'Manual Trigger' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $workflow->active ? 'bg-success/10 text-success border border-success/20' : 'bg-text-muted/10 text-text-muted border border-text-muted/20' }}">
                                                    {{ $workflow->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-muted">
                                                {{ $workflow->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div
                                                    class="flex items-center justify-end gap-3 transition-opacity duration-200">
                                                    <form action="{{ route('workflows.run', $workflow) }}" method="POST"
                                                        class="flex items-center gap-2">
                                                        @csrf
                                                        <input type="text" name="topic" placeholder="Enter topic..."
                                                            class="text-xs rounded-lg border-primary/20 bg-background/50 text-text px-3 py-1.5 w-40 focus:ring-primary focus:border-primary transition-all focus:w-48 placeholder-text-muted/50"
                                                            required>
                                                        <button type="submit"
                                                            class="btn btn-sm btn-primary whitespace-nowrap shadow-md hover:shadow-lg">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                            Run
                                                        </button>
                                                    </form>
                                                    <div class="h-4 w-px bg-primary/20"></div>
                                                    <a href="{{ route('workflows.edit', $workflow) }}"
                                                        class="btn btn-sm btn-secondary hover:text-primary">Edit</a>
                                                    @if(!empty($hasWorkflowRuns))
                                                        <a href="{{ route('workflows.runs.index', $workflow) }}"
                                                            class="btn btn-sm btn-ghost">Runs</a>
                                                        @if($workflow->runs()->exists())
                                                            <a href="{{ route('workflows.runs.show', $workflow->runs()->latest()->first()) }}"
                                                                class="btn btn-sm btn-ghost">View Results</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $workflows->links() }}
                        </div>
                    @else
                        <div class="text-center py-16 empty-state">
                            <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="h-10 w-10 text-primary/40" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-text mb-2">No workflows yet</h3>
                            <p class="text-text-muted max-w-sm mx-auto mb-8">Get started by creating a new automation
                                workflow to streamline your content creation.</p>
                            <a href="{{ route('workflows.create') }}" class="btn btn-primary btn-shine hover-glow">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Create First Workflow
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>