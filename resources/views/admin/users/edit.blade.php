<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-background border border-primary/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-text/80">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                autofocus
                                class="mt-1 block w-full rounded-md border-primary/20 bg-primary/5 text-text focus:border-primary focus:ring focus:ring-primary/50">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <label for="email" class="block text-sm font-medium text-text/80">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                required
                                class="mt-1 block w-full rounded-md border-primary/20 bg-primary/5 text-text focus:border-primary focus:ring focus:ring-primary/50">
                            @error('email')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <label for="role" class="block text-sm font-medium text-text/80">Role</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full rounded-md border-primary/20 bg-primary/5 text-text focus:border-primary focus:ring focus:ring-primary/50">
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plan (if applicable) -->
                        <div class="mt-4">
                            <label for="plan" class="block text-sm font-medium text-text/80">Plan</label>
                            <select name="plan" id="plan"
                                class="mt-1 block w-full rounded-md border-primary/20 bg-primary/5 text-text focus:border-primary focus:ring focus:ring-primary/50">
                                <option value="free" {{ old('plan', $user->plan) === 'free' ? 'selected' : '' }}>Free
                                </option>
                                <option value="pro" {{ old('plan', $user->plan) === 'pro' ? 'selected' : '' }}>Pro
                                </option>
                                <option value="enterprise" {{ old('plan', $user->plan) === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                            @error('plan')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm text-text/60 hover:text-text mr-4">Cancel</a>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>