<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Tools Management</h1>
        <a href="{{ route('admin.tools.create') }}" class="btn btn-primary px-4">
            + Create New Tool
        </a>
    </div>

    <div class="card overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">Icon</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Featured</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4">
                                <img src="{{ $tool->icon }}" class="w-8 h-8 rounded bg-surface object-cover" alt="Icon">
                            </td>
                            <td class="px-6 py-4 font-medium text-text">{{ $tool->name }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $tool->category->name ?? 'Uncategorized' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold {{ $tool->status ? 'bg-green-500/10 text-green-500' : 'bg-gray-500/10 text-gray-500' }}">
                                    {{ $tool->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($tool->is_featured)
                                    <span class="text-yellow-500">★</span>
                                @else
                                    <span class="text-text-muted opacity-20">★</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tools.edit', $tool) }}" class="btn btn-sm btn-ghost">Edit</a>
                                    <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST"
                                        x-data @submit.prevent="$dispatch('confirm', { message: 'Delete this tool?', form: $el })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-400 text-xs font-bold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-text-muted">No tools found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">
            {{ $tools->links() }}
        </div>
    </div>
</x-admin-layout>