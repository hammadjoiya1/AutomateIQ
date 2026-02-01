<x-public-layout>
    <div class="bg-background py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-text sm:text-4xl">Contact Support</h2>
                <p class="mt-2 text-lg leading-8 text-text/70">Have questions? We're here to help.</p>
            </div>
            <form action="{{ route('contact.store') }}" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
                @csrf
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-semibold leading-6 text-text">Name</label>
                        <div class="mt-2.5">
                            <input type="text" name="name" id="name" autocomplete="given-name"
                                class="block w-full rounded-md border-primary/30 bg-background px-3.5 py-2 text-text shadow-sm focus:border-primary focus:ring-primary sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-semibold leading-6 text-text">Email</label>
                        <div class="mt-2.5">
                            <input type="email" name="email" id="email" autocomplete="email"
                                class="block w-full rounded-md border-primary/30 bg-background px-3.5 py-2 text-text shadow-sm focus:border-primary focus:ring-primary sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-semibold leading-6 text-text">Message</label>
                        <div class="mt-2.5">
                            <textarea name="message" id="message" rows="4"
                                class="block w-full rounded-md border-primary/30 bg-background px-3.5 py-2 text-text shadow-sm focus:border-primary focus:ring-primary sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit"
                        class="block w-full rounded-md bg-primary px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">Send
                        message</button>
                </div>
            </form>
        </div>
    </div>
</x-public-layout>