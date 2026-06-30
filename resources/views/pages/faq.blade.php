<x-public-layout meta-title="FAQ - AutomateIQ">
    <div class="py-24 sm:py-32 relative">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <x-ui.badge variant="accent" class="mb-4 text-center">❓ FAQ</x-ui.badge>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text font-display">Frequently Asked Questions</h2>
                <p class="mt-4 text-lg leading-7 text-text-muted max-w-2xl mx-auto">
                    Questions about getting started with automated content creation? We have answers.
                </p>
            </div>
            
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 scroll-reveal-stagger">
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="scroll-reveal flex flex-col">
                    <dt class="text-lg font-bold leading-7 text-text mb-3">Do I need to show my face?</dt>
                    <dd class="text-base leading-relaxed text-text-muted flex-grow">
                        No! Our entire platform is built for "faceless" content creators. Tools focus on hooks, short scripts, scene splitting, video prompts, and repurposing across platforms.
                    </dd>
                </x-ui.card>
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="scroll-reveal flex flex-col">
                    <dt class="text-lg font-bold leading-7 text-text mb-3">Is the content original?</dt>
                    <dd class="text-base leading-relaxed text-text-muted flex-grow">
                        Yes, every generation is unique based on your specific inputs and our advanced AI prompts.
                    </dd>
                </x-ui.card>
                <x-ui.card padding="p-6 md:p-8" :hoverEffect="true" class="scroll-reveal flex flex-col">
                    <dt class="text-lg font-bold leading-7 text-text mb-3">Can I cancel anytime?</dt>
                    <dd class="text-base leading-relaxed text-text-muted flex-grow">
                        Absolutely. There are no contracts. You can downgrade to the free plan whenever you like.
                    </dd>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-public-layout>