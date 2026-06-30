<x-public-layout meta-title="Affiliate Program — AutomateIQ" meta-description="Earn commissions by referring creators to AutomateIQ.">
    <div class="py-24 sm:py-32 relative">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <x-ui.badge variant="accent" class="mb-4 text-center">🤝 Partner With Us</x-ui.badge>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text font-display">Affiliate Program</h1>
                <p class="mt-4 text-lg text-text-muted">Earn recurring commissions by referring creators and teams.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 scroll-reveal-stagger">
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="text-center scroll-reveal">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-text mb-2">30% Recurring</div>
                    <p class="text-text-muted text-sm leading-relaxed">Earn monthly revenue from every referral for as long as they're subscribed.</p>
                </x-ui.card>
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="text-center scroll-reveal">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-text mb-2">Marketing Assets</div>
                    <p class="text-text-muted text-sm leading-relaxed">Use proven creatives, banners, and landing pages we provide.</p>
                </x-ui.card>
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="text-center scroll-reveal">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-text mb-2">Fast Payouts</div>
                    <p class="text-text-muted text-sm leading-relaxed">Monthly payouts via your preferred method. No minimum threshold.</p>
                </x-ui.card>
            </div>

            <div class="mt-16 text-center scroll-reveal">
                <x-ui.button variant="primary" size="lg" href="{{ route('contact') }}" data-analytics-event="cta_affiliate_apply">
                    Apply Now
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </x-ui.button>
            </div>
        </div>
    </div>
</x-public-layout>
