<x-public-layout meta-title="Book a Demo — AutomateIQ" meta-description="Schedule a quick demo to see AutomateIQ in action.">
    <div class="py-24">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-text mb-4">Book a Demo</h1>
            <p class="text-text/60 mb-10">See how teams use AutomateIQ to scale content and workflows.</p>
            <a href="{{ env('DEMO_BOOKING_URL', 'https://calendly.com/your-demo') }}" target="_blank" rel="noopener"
                class="btn btn-lg btn-primary" data-analytics-event="cta_book_demo">
                Open Calendar
            </a>
            <p class="text-xs text-text/50 mt-4">No obligation. 15‑minute walkthrough.</p>
        </div>
    </div>
</x-public-layout>
