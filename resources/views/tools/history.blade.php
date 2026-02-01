<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('Generation History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="glass-panel overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-text">
                            @if($runs->isEmpty())
                                <div class="text-center py-10 text-text/50">
                                    No history yet. Start generating content!
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-primary/20">
                                        <thead class="bg-primary/5">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                                    Date</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                                    Tool</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                                    Input</th>
                                                <th class="px-6 py-3 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-transparent divide-y divide-primary/10">
                                            @foreach($runs as $run)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-text/70">
                                                        {{ $run->created_at->format('M d, H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                                        {{ $run->tool->name }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-text/70 max-w-xs truncate">
                                                        {{ $run->input_data['input'] ?? 'Data' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('tools.show-run', $run) }}"
                                                            class="text-primary hover:text-primary/70 font-bold hover:underline">
                                                            View Result
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $runs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>