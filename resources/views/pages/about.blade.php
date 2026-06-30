<x-public-layout meta-title="About Us - AutomateIQ">
    <div class="py-24 sm:py-32 relative">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <x-ui.badge variant="accent" class="mb-4 text-center">👋 About Us</x-ui.badge>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text font-display">Our Mission</h2>
            </div>
            
            <x-ui.card padding="p-8 md:p-12" :hoverEffect="true" class="text-center md:text-left scroll-reveal">
                <p class="text-xl leading-relaxed text-text mb-8 font-medium">
                    We are dedicated to helping content creators automate their workflow and scale their presence without showing their face.
                </p>
                <p class="text-text-muted leading-relaxed text-lg mb-6">
                    Founded in 2024, AutomateIQ provides a suite of tools designed specifically for the unique needs of "faceless" channels on YouTube, TikTok, and Instagram. Our mission is to democratize content creation through automation.
                </p>
                <p class="text-text-muted leading-relaxed text-lg">
                    By combining script generation, scene splitting, and automated video workflows, we empower individuals and agencies to pump out high-quality, engaging content at an unprecedented scale.
                </p>
                
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <x-ui.button variant="primary" href="{{ route('pricing') }}">See Our Plans</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('contact') }}">Contact Us</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-public-layout>