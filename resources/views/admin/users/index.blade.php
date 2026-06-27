<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">User Management</h1>
        <div class="flex gap-2">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                    class="bg-surface border border-border rounded-lg px-4 py-2 text-text focus:border-primary focus:ring-1 focus:ring-primary">
                <button type="submit" class="btn btn-secondary px-4">Search</button>
            </form>
        </div>
    </div>

    <div class="card overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-text-muted uppercase bg-surface/50 border-b border-border">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Plan</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/50">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-text">{{ $user->name }}</div>
                                        <div class="text-xs text-text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-500/10 text-purple-500' : 'bg-gray-500/10 text-gray-500' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_banned)
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-500">Banned</span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-text-muted">{{ ucfirst($user->plan) }}</td>
                            <td class="px-6 py-4 text-text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-ghost">View</a>
                                    @if($user->id !== Auth::id())
                                        @if($user->is_banned)
                                            <form action="{{ route('admin.users.unban', $user) }}" method="POST"
                                                x-data @submit.prevent="$dispatch('confirm', { message: 'Unban this user?', form: $el })">
                                                @csrf
                                                <button type="submit"
                                                    class="text-green-500 hover:text-green-400 text-xs font-bold">Unban</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.ban', $user) }}" method="POST"
                                                x-data @submit.prevent="$dispatch('confirm', { message: 'Ban this user?', form: $el })">
                                                @csrf
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-400 text-xs font-bold">Ban</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            x-data @submit.prevent="$dispatch('confirm', { message: 'Delete this user permanently?', form: $el })" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-400 text-xs font-bold">Delete</button>
                                        </form>
                                        @if($user->role !== 'admin')
                                            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST"
                                                x-data @submit.prevent="$dispatch('confirm', { message: 'Log in and impersonate this user account?', form: $el })" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-primary hover:opacity-80 text-xs font-bold">Impersonate</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>