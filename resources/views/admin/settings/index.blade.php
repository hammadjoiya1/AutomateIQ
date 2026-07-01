<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-text">Site Settings</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6 border border-border">
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

        <div class="card p-6 border border-border">
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

        <div class="card p-6 border border-border lg:col-span-2">
            <h3 class="font-bold text-lg text-text mb-4">Credits & Billing</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Credit Price (cents)</label>
                        <input type="number" name="credits.credit_price_cents"
                            value="{{ $settings['credits.credit_price_cents'] ?? config('credits.credit_price_cents') }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1">Target Gross Margin</label>
                        <input type="number" step="0.01" min="0" max="0.95" name="credits.target_gross_margin"
                            value="{{ $settings['credits.target_gross_margin'] ?? config('credits.target_gross_margin') }}"
                            class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-text mb-2">Monthly Credits</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Free</label>
                            <input type="number" name="credits.monthly_credits.free"
                                value="{{ $settings['credits.monthly_credits.free'] ?? config('credits.monthly_credits.free') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Pro</label>
                            <input type="number" name="credits.monthly_credits.pro"
                                value="{{ $settings['credits.monthly_credits.pro'] ?? config('credits.monthly_credits.pro') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Team</label>
                            <input type="number" name="credits.monthly_credits.team"
                                value="{{ $settings['credits.monthly_credits.team'] ?? config('credits.monthly_credits.team') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-text mb-2">OpenAI Blended Cost (cents per 1K tokens)</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">gpt-4</label>
                            <input type="number" step="0.01" name="credits.openai_models.gpt-4.blended_cents_per_1k"
                                value="{{ $settings['credits.openai_models.gpt-4.blended_cents_per_1k'] ?? config('credits.openai_models.gpt-4.blended_cents_per_1k') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">gpt-4o</label>
                            <input type="number" step="0.01" name="credits.openai_models.gpt-4o.blended_cents_per_1k"
                                value="{{ $settings['credits.openai_models.gpt-4o.blended_cents_per_1k'] ?? config('credits.openai_models.gpt-4o.blended_cents_per_1k') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">gpt-4o-mini</label>
                            <input type="number" step="0.01" name="credits.openai_models.gpt-4o-mini.blended_cents_per_1k"
                                value="{{ $settings['credits.openai_models.gpt-4o-mini.blended_cents_per_1k'] ?? config('credits.openai_models.gpt-4o-mini.blended_cents_per_1k') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-text mb-2">Replicate Video Cost</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Damo cents per second</label>
                            <input type="number" step="0.01" name="credits.replicate_models.cjwbw/damo-text-to-video.cents_per_second"
                                value="{{ $settings['credits.replicate_models.cjwbw/damo-text-to-video.cents_per_second'] ?? config('credits.replicate_models.cjwbw/damo-text-to-video.cents_per_second') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Default frames</label>
                            <input type="number" name="credits.video_defaults.num_frames"
                                value="{{ $settings['credits.video_defaults.num_frames'] ?? config('credits.video_defaults.num_frames') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Default FPS</label>
                            <input type="number" name="credits.video_defaults.fps"
                                value="{{ $settings['credits.video_defaults.fps'] ?? config('credits.video_defaults.fps') }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-text mb-2">Top-up Checkout URLs</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Starter URL</label>
                            <input type="text" name="lemonsqueezy.topup_checkout_urls.starter"
                                value="{{ $settings['lemonsqueezy.topup_checkout_urls.starter'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Growth URL</label>
                            <input type="text" name="lemonsqueezy.topup_checkout_urls.growth"
                                value="{{ $settings['lemonsqueezy.topup_checkout_urls.growth'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Scale URL</label>
                            <input type="text" name="lemonsqueezy.topup_checkout_urls.scale"
                                value="{{ $settings['lemonsqueezy.topup_checkout_urls.scale'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-text mb-2">Top-up Variant Mapping</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Starter Variant ID</label>
                            <input type="text" name="lemonsqueezy.topup_variants.starter.id"
                                value="{{ $settings['lemonsqueezy.topup_variants.starter.id'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Growth Variant ID</label>
                            <input type="text" name="lemonsqueezy.topup_variants.growth.id"
                                value="{{ $settings['lemonsqueezy.topup_variants.growth.id'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Scale Variant ID</label>
                            <input type="text" name="lemonsqueezy.topup_variants.scale.id"
                                value="{{ $settings['lemonsqueezy.topup_variants.scale.id'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Starter Credits</label>
                            <input type="number" name="lemonsqueezy.topup_variants.starter.credits"
                                value="{{ $settings['lemonsqueezy.topup_variants.starter.credits'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Growth Credits</label>
                            <input type="number" name="lemonsqueezy.topup_variants.growth.credits"
                                value="{{ $settings['lemonsqueezy.topup_variants.growth.credits'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                        <div>
                            <label class="block text-xs text-text-muted mb-1">Scale Credits</label>
                            <input type="number" name="lemonsqueezy.topup_variants.scale.credits"
                                value="{{ $settings['lemonsqueezy.topup_variants.scale.credits'] ?? '' }}"
                                class="w-full bg-surface border border-border rounded-lg px-4 py-2 text-text">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary w-full justify-center">Save Credits & Billing</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>