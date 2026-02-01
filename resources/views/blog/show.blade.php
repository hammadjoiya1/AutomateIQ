<x-public-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-background border border-primary/20 rounded-xl overflow-hidden shadow-sm">
                <!-- Header -->
                <div
                    class="relative h-64 sm:h-96 w-full bg-primary/10 flex items-center justify-center text-primary/30">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    @endif
                </div>

                <div class="p-8 sm:p-12">
                    <!-- Meta -->
                    <div class="flex items-center text-sm text-text/60 mb-6 space-x-4">
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                        <span>&bull;</span>
                        <span
                            class="px-3 py-1 rounded-full bg-primary/10 text-primary font-medium">{{ $post->category->name ?? 'Update' }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-5xl font-extrabold text-text mb-8 leading-tight">
                        {{ $post->title }}
                    </h1>

                    <!-- Content -->
                    <div class="prose dark:prose-invert prose-lg max-w-none text-text">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Back Link -->
                    <div class="mt-12 pt-8 border-t border-primary/20">
                        <a href="{{ route('blog.index') }}"
                            class="text-primary hover:text-primary/70 font-medium flex items-center gap-2">
                            &larr; Back to Articles
                        </a>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-background border-t border-primary/20 p-8 sm:p-12">
                    <h3 class="text-2xl font-bold text-text mb-8">Comments
                        ({{ $post->comments->where('is_approved', true)->count() }})</h3>

                    <!-- Comment Form -->
                    <div class="mb-12 bg-primary/5 p-6 rounded-lg border border-primary/10">
                        <h4 class="text-lg font-medium text-text mb-4">Leave a Comment</h4>
                        @if(session('success'))
                            <div class="mb-4 text-green-500 font-medium">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            @guest
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-text/80 mb-1">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="w-full rounded-md border-primary/20 bg-background text-text focus:border-primary focus:ring focus:ring-primary/50"
                                            required>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-text/80 mb-1">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="w-full rounded-md border-primary/20 bg-background text-text focus:border-primary focus:ring focus:ring-primary/50"
                                            required>
                                    </div>
                                </div>
                            @endguest

                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-text/80 mb-1">Comment</label>
                                <textarea name="content" id="content" rows="4"
                                    class="w-full rounded-md border-primary/20 bg-background text-text focus:border-primary focus:ring focus:ring-primary/50"
                                    required></textarea>
                            </div>

                            <button type="submit"
                                class="px-6 py-2 bg-primary text-white font-medium rounded-md hover:bg-primary/90 transition-colors">
                                Submit Comment
                            </button>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-8">
                        @forelse($post->comments->where('is_approved', true) as $comment)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-lg">
                                        {{ substr($comment->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h5 class="font-bold text-text">{{ $comment->name }}</h5>
                                        <span
                                            class="text-xs text-text/50">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-text/80 leading-relaxed">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-text/50 italic">No comments yet. Be the first to share your thoughts!</p>
                        @endforelse
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-public-layout>