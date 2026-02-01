<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('Manage Tags') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Create Tag Form -->
                <div class="col-span-1">
                    <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-text mb-4">Create New Tag</h3>
                        <form action="{{ route('admin.tags.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-text/80">Tag Name</label>
                                <input type="text" name="name" id="name"
                                    class="mt-1 block w-full rounded-md border-primary/20 bg-primary/5 text-text focus:border-primary focus:ring focus:ring-primary/50"
                                    required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-primary/90 transition-colors">
                                Create Tag
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tags List -->
                <div class="col-span-1 md:col-span-2">
                    <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-text">
                            <table class="min-w-full divide-y divide-primary/20">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                            Slug</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-text uppercase tracking-wider">
                                            Posts</th>
                                        <th class="px-6 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/10">
                                    @forelse($tags as $tag)
                                        <tr x-data="{ editing: false, name: '{{ $tag->name }}' }">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                                <span x-show="!editing">{{ $tag->name }}</span>
                                                <form x-show="editing" action="{{ route('admin.tags.update', $tag) }}"
                                                    method="POST" class="flex gap-2" @click.away="editing = false">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" x-model="name"
                                                        class="px-2 py-1 text-xs rounded border-primary/20 bg-primary/5 text-text focus:border-primary">
                                                    <button type="submit"
                                                        class="text-green-500 hover:text-green-700">✓</button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text/70">{{ $tag->slug }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text/70">
                                                {{ $tag->posts_count ?? 0 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-3">
                                                    <button @click="editing = !editing"
                                                        class="text-primary hover:text-primary/70">Edit</button>
                                                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:text-red-700">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-text/50">No tags found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $tags->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>