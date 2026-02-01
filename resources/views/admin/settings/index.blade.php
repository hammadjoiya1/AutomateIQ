<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Site Settings</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6 border border-white/5">
            <h3 class="font-bold text-lg text-text mb-4">General Configuration</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Site Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'AutomateIQ' }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Contact Email</label>
                    <input type="email" name="contact_email"
                        value="{{ $settings['contact_email'] ?? 'support@example.com' }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Footer Text</label>
                    <input type="text" name="footer_text"
                        value="{{ $settings['footer_text'] ?? '© 2026 AutomateIQ. All rights reserved.' }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full justify-center">Save General Settings</button>
                </div>
            </form>
        </div>

        <div class="card p-6 border border-white/5">
            <h3 class="font-bold text-lg text-text mb-4">SEO Defaults</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Meta Title</label>
                    <input type="text" name="meta_title"
                        value="{{ $settings['meta_title'] ?? 'AutomateIQ - AI SaaS Platform' }}"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="4"
                        class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">{{ $settings['meta_description'] ?? 'The ultimate faceless AI automation toolkit.' }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-secondary w-full justify-center">Save SEO Settings</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>