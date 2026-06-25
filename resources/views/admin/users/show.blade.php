<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-3">←</a>
            <h1 class="text-3xl font-display font-bold text-text">{{ $user->name }}</h1>
        </div>

        <div class="flex gap-2">
            @if($user->id !== Auth::id())
                @if($user->is_banned)
                    <form action="{{ route('admin.users.unban', $user) }}" method="POST"
                        x-data @submit.prevent="$dispatch('confirm', { message: 'Unban this user?', form: $el })">
                        @csrf
                        <button type="submit" class="btn btn-success">Unban User</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST"
                        x-data @submit.prevent="$dispatch('confirm', { message: 'Ban this user?', form: $el })">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ban User</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile & Edit -->
        <div class="card p-6 border border-white/5 space-y-6">
            <div class="text-center">
                <div
                    class="w-24 h-24 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-4xl mx-auto mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <p class="text-text-muted">{{ $user->email }}</p>
                <p class="text-xs text-text-muted mt-1">Joined {{ $user->created_at->format('M d, Y') }}</p>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST"
                class="space-y-4 pt-4 border-t border-border">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Role</label>
                    <select name="role" class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Plan</label>
                    <select name="plan" class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        <option value="free" {{ $user->plan === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="pro" {{ $user->plan === 'pro' ? 'selected' : '' }}>Pro</option>
                        <option value="team" {{ $user->plan === 'team' ? 'selected' : '' }}>Team</option>
                        <option value="enterprise" {{ $user->plan === 'enterprise' ? 'selected' : '' }}>Enterprise
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Subscription Credits</label>
                    <input type="number" name="subscription_credits" value="{{ $user->subscription_credits }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Top-up Credits</label>
                    <input type="number" name="topup_credits" value="{{ $user->topup_credits }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div class="text-xs text-text-muted">
                    Total credits: {{ number_format($user->credits) }}
                </div>

                <button type="submit" class="btn btn-primary w-full justify-center">Update User</button>
            </form>
        </div>

        <!-- Activity Stats -->
        <div class="col-span-2 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="card p-4 border border-white/5 bg-surface/50 text-center">
                    <div class="text-2xl font-bold text-text">{{ $stats['total_runs'] }}</div>
                    <div class="text-xs text-text-muted">Total Tool Runs</div>
                </div>
                <div class="card p-4 border border-white/5 bg-surface/50 text-center">
                    <div class="text-sm font-bold text-text">{{ $stats['last_login'] }}</div>
                    <div class="text-xs text-text-muted">Last Activity</div>
                </div>
            </div>

            <div class="card p-6 border border-white/5">
                <h3 class="font-bold text-lg text-text mb-4">Recent Tool Runs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-text-muted uppercase">
                            <tr>
                                <th class="px-4 py-2">Tool</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @forelse($user->toolRuns()->latest()->take(5)->get() as $run)
                                <tr>
                                    <td class="px-4 py-3">{{ $run->tool->name }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $run->status === 'completed' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }}">
                                            {{ ucfirst($run->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-text-muted">{{ $run->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-muted">No activity yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>