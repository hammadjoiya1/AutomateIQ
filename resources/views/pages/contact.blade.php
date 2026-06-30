<x-public-layout meta-title="Contact Us - AutomateIQ">
    <div class="py-24 sm:py-32 relative">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-16">
                <x-ui.badge variant="accent" class="mb-4 text-center">📧 Get In Touch</x-ui.badge>
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text font-display">Contact Support</h2>
                <p class="mt-4 text-lg leading-8 text-text-muted">Have questions? We're here to help.</p>
            </div>
            
            <x-ui.card padding="p-8 md:p-12" :hoverEffect="false" class="scroll-reveal mx-auto max-w-xl">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-semibold leading-6 text-text">Name</label>
                            <div class="mt-2.5">
                                <x-ui.input type="text" name="name" id="name" autocomplete="given-name" required />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-semibold leading-6 text-text">Email</label>
                            <div class="mt-2.5">
                                <x-ui.input type="email" name="email" id="email" autocomplete="email" required />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="block text-sm font-semibold leading-6 text-text">Message</label>
                            <div class="mt-2.5">
                                <x-ui.textarea name="message" id="message" rows="5" required></x-ui.textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <x-ui.button variant="primary" size="lg" type="submit" class="w-full">
                            Send message
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-public-layout>