<x-public-layout meta-title="Enterprise Demo — AutomateIQ" meta-description="Schedule a comprehensive enterprise demo.">
    <div class="py-24 sm:py-32 relative">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center scroll-reveal mb-12">
                <div class="section-badge mb-4 inline-block">🏢 Enterprise Solutions</div>
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-text font-display">Book an Enterprise Demo</h1>
                <p class="mt-4 text-lg text-text-muted max-w-2xl mx-auto leading-relaxed">Discover how our high-volume rendering pipelines and dedicated SLAs can transform your agency's content strategy.</p>
            </div>
            
            <div class="strat-card scroll-reveal p-8 md:p-12 border border-border/50 bg-surface/30 backdrop-blur-md rounded-2xl relative">
                @if(session('success'))
                    <div class="bg-success/10 border border-success/30 text-success px-4 py-3 rounded-lg mb-6 text-center">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('demo.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-text mb-2">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-background border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all placeholder:text-text-muted/50" placeholder="Jane Doe" value="{{ old('name') }}">
                            @error('name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-text mb-2">Work Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" required class="w-full bg-background border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all placeholder:text-text-muted/50" placeholder="jane@company.com" value="{{ old('email') }}">
                            @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company" class="block text-sm font-medium text-text mb-2">Company / Agency Name <span class="text-danger">*</span></label>
                            <input type="text" name="company" id="company" required class="w-full bg-background border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all placeholder:text-text-muted/50" placeholder="Acme Media" value="{{ old('company') }}">
                            @error('company')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="volume" class="block text-sm font-medium text-text mb-2">Estimated Monthly Video Volume <span class="text-danger">*</span></label>
                            <select name="volume" id="volume" required class="w-full bg-background border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all appearance-none">
                                <option value="" disabled selected>Select an option</option>
                                <option value="1-50">1 - 50 videos / month</option>
                                <option value="51-200">51 - 200 videos / month</option>
                                <option value="200-500">200 - 500 videos / month</option>
                                <option value="500+">500+ videos / month</option>
                            </select>
                            @error('volume')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-text mb-2">What are your main goals with AutomateIQ? (Optional)</label>
                        <textarea name="message" id="message" rows="4" class="w-full bg-background border border-border rounded-lg px-4 py-3 text-text focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all placeholder:text-text-muted/50" placeholder="Tell us a bit about your current workflow...">{{ old('message') }}</textarea>
                        @error('message')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 text-center">
                        <button type="submit" class="btn-glow w-full md:w-auto px-12 justify-center text-lg">
                            Request Enterprise Demo
                        </button>
                        <p class="text-xs text-text-muted mt-4">We respect your privacy. By submitting this form, you agree to our <a href="{{ route('privacy') }}" class="text-primary hover:underline">Privacy Policy</a>.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-public-layout>
