<x-admin-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-display font-bold text-text">Credit Packs</h1>
            <p class="text-text/60">Configure one-time top-up packs and checkout links.</p>
        </div>
    </div>

    <form action="{{ route('admin.credits.update') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach(['starter' => 'Starter', 'growth' => 'Growth', 'scale' => 'Scale'] as $key => $label)
                <div class="card p-6 border border-border bg-surface/50">
                    <h3 class="text-lg font-semibold text-text">{{ $label }} Pack</h3>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Credits</label>
                            <input type="number" name="lemonsqueezy.topup_variants.{{ $key }}.credits"
                                value="{{ $settings["lemonsqueezy.topup_variants.{$key}.credits"] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Variant ID</label>
                            <input type="text" name="lemonsqueezy.topup_variants.{{ $key }}.id"
                                value="{{ $settings["lemonsqueezy.topup_variants.{$key}.id"] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Checkout URL</label>
                            <input type="text" name="lemonsqueezy.topup_checkout_urls.{{ $key }}"
                                value="{{ $settings["lemonsqueezy.topup_checkout_urls.{$key}"] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Display Price (optional)</label>
                            <input type="text" name="credits.topup_display_prices.{{ $key }}"
                                value="{{ $settings["credits.topup_display_prices.{$key}"] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-2">
            <button type="submit" class="btn btn-primary w-full justify-center">Save Credit Packs</button>
        </div>
    </form>
</x-admin-layout>
