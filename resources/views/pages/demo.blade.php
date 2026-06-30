<x-public-layout meta-title="Book a Demo — AutomateIQ" meta-description="Schedule a quick demo to see AutomateIQ in action.">
    <div class="py-24 sm:py-32 relative">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <div class="scroll-reveal mb-16">
                <div class="section-badge mb-4 inline-block">📅 Live Demo</div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text">Book a Demo</h1>
                <p class="mt-4 text-lg text-text/60 max-w-xl mx-auto">See how teams use AutomateIQ to scale content and workflows. 15‑minute walkthrough, no obligation.</p>
            </div>
            
            <div class="strat-card scroll-reveal max-w-md mx-auto p-10 text-center">
                <div class="cta-icon-glow mx-auto">
                    <svg class="w-8 h-8 text-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-text/60 text-sm mb-8 leading-relaxed">Pick a time that works for you. We'll walk you through the platform, answer your questions, and help you get set up.</p>
                <a href="{{ env('DEMO_BOOKING_URL', 'https://calendly.com/your-demo') }}" target="_blank" rel="noopener"
                    class="btn-glow w-full justify-center" data-analytics-event="cta_book_demo">
                    Open Calendar
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
                <p class="text-xs text-text/40 mt-4">No credit card required.</p>
            </div>
        </div>
    </div>
</x-public-layout>
