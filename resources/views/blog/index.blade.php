<x-public-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12 scroll-animate" data-animation="fade-in-up">
                <h1 class="text-4xl font-extrabold text-text tracking-tight sm:text-5xl font-display tilt-3d" data-scramble>
                    Latest Insights
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-text/70">
                    Tips, tutorials, and strategies for faceless content creation.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-stagger-reveal>
                @forelse($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="block group scroll-animate card-3d" data-animation="fade-in-up">
                        <div
                            class="glass-article rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                            <!-- Image Placeholder -->
                            <div class="h-48 bg-primary/10 flex items-center justify-center text-primary/30">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                @endif
                            </div>
                            <div class="p-6">
                                <div class="flex items-center text-xs text-text/50 mb-2 space-x-2">
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $post->category->name ?? 'Update' }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-text group-hover:text-primary transition-colors mb-2">
                                    {{ $post->title }}</h3>
                                <p class="text-text/70 text-sm line-clamp-3">
                                    {{ Str::limit(strip_tags($post->content), 100) }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-20 text-text/50">
                        <p>No blog posts yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-public-layout>