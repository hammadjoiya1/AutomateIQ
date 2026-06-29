<x-public-layout>
    <style>
        /* Glassmorphism custom styling */
        .glass-article {
            background: rgba(var(--bg-2-rgb, 17, 24, 39), 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glowing-bg {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(91, 33, 182, 0.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
            transition: transform 0.1s ease-out;
        }
        @media(min-width: 640px) {
            .glowing-bg {
                width: 300px;
                height: 300px;
            }
        }

        /* Vertical TOC Active States */
        .toc-vertical-item {
            border-left: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }
        @media(min-width: 640px) {
            .toc-vertical-item {
                border-left-color: rgba(255, 255, 255, 0.05);
            }
        }
        .toc-vertical-item.active {
            color: var(--primary);
        }
        @media(min-width: 640px) {
            .toc-vertical-item.active {
                border-left-color: var(--primary);
                padding-left: 1.25rem;
            }
        }
        .toc-vertical-item.active .item-dot {
            background-color: var(--primary);
            border-color: var(--primary);
            transform: scale(1.5);
        }
    </style>

    <div class="relative min-h-screen py-6 sm:py-16">

        <div class="max-w-7xl mx-auto px-1.5 sm:px-6 lg:px-8 relative z-10">
            <!-- Locked 3-Column Grid (No screen collapse breakpoints) -->
            <div class="grid grid-cols-12 gap-1.5 sm:gap-6 lg:gap-8">
                
                <!-- LEFT COLUMN: Sticky Navigation & Share (col-span-2 on all devices) -->
                <aside class="col-span-2 relative">
                    <div class="sticky top-20 sm:top-24 flex flex-col items-center justify-start gap-4 sm:gap-8 p-1 sm:p-0 rounded-xl sm:rounded-none">
                        
                        <!-- Back Button -->
                        <a href="{{ route('blog.index') }}" 
                           class="inline-flex items-center gap-1 sm:gap-2 text-text-muted hover:text-primary transition-colors text-[10px] sm:text-sm font-semibold group">
                            <span class="transform group-hover:-translate-x-1 transition-transform">&larr;</span>
                            <span class="hidden sm:inline">Back</span>
                        </a>

                        <!-- Reading Progress Ring -->
                        <div class="flex flex-col items-center gap-2 py-4 border-y border-white/5 w-full">
                            <div class="relative w-8 h-8 sm:w-16 sm:h-16">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-white/5" stroke-width="3" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path id="progress-circle" class="text-primary transition-all duration-100" stroke-width="3" 
                                          stroke-dasharray="0, 100" stroke-linecap="round" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" style="opacity: 0;" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center font-display text-[8px] sm:text-xs font-bold text-text">
                                    <span id="progress-text">0</span>%
                                </div>
                            </div>
                            <span class="text-[7px] sm:text-[10px] uppercase tracking-wider font-bold text-text-muted text-center leading-none">Read</span>
                        </div>

                        <!-- Share Widgets -->
                        <div class="flex flex-col gap-2.5 sm:gap-4 items-center w-full">
                            <span class="hidden sm:block text-[9px] uppercase tracking-widest font-bold text-text-muted mb-1 text-center">Share</span>
                            
                            <!-- X (Twitter) -->
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                               target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-surface/50 border border-white/5 hover:border-primary/30 flex items-center justify-center text-text-muted hover:text-primary transition-all shadow-md hover:-translate-y-0.5"
                               title="Share on X">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>

                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" rel="noopener noreferrer"
                               class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-surface/50 border border-white/5 hover:border-primary/30 flex items-center justify-center text-text-muted hover:text-primary transition-all shadow-md hover:-translate-y-0.5"
                               title="Share on Facebook">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                            </a>

                            <!-- Copy Link -->
                            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link copied!');"
                                    class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-surface/50 border border-white/5 hover:border-primary/30 flex items-center justify-center text-text-muted hover:text-primary transition-all shadow-md hover:-translate-y-0.5"
                                    title="Copy Link">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- CENTER COLUMN: Content Area (col-span-8 lg:col-span-7) -->
                <main class="col-span-8 lg:col-span-7 space-y-4 sm:space-y-8">
                    <!-- Glassmorphic Article Container -->
                    <article class="glass-article rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl relative scroll-animate card-3d" data-animation="fade-in-up">
                        <!-- Custom Interactive Mouse Spotlight Layer -->
                        <div class="glowing-bg" id="card-glow"></div>

                        <!-- Parallax Featured Image Header -->
                        <div class="relative h-36 sm:h-[450px] w-full bg-surface/30 overflow-hidden" data-parallax data-speed="0.2">
                            <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent z-10 opacity-70"></div>
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/10 via-secondary/10 to-surface/20 flex items-center justify-center">
                                    <svg class="h-10 w-10 sm:h-24 sm:w-24 text-primary/30 float-3d" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Body Content Panel -->
                        <div class="p-3 sm:p-12 relative z-10 space-y-4 sm:space-y-6">
                            <!-- Category & Meta -->
                            <div class="flex items-center text-[8px] sm:text-xs uppercase tracking-widest font-bold text-text-muted space-x-2 sm:space-x-3">
                                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20">
                                    {{ $post->category->name ?? 'Update' }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                            </div>

                            <!-- Title -->
                            <h1 class="text-base sm:text-5xl font-black font-display text-text leading-tight tracking-tight tilt-3d" data-scramble>
                                {{ $post->title }}
                            </h1>

                            <!-- Rendered Markdown Content -->
                            <div class="prose prose-sm sm:prose-base lg:prose-lg dark:prose-invert max-w-none text-text leading-relaxed prose-headings:font-display prose-headings:font-black prose-a:text-primary hover:prose-a:text-primary-hover prose-pre:bg-background/80 prose-pre:border prose-pre:border-white/5 rounded-xl sm:rounded-2xl" id="article-body">
                                {!! \Illuminate\Support\Str::markdown($post->content ?? '') !!}
                            </div>
                        </div>
                    </article>

                    <!-- Comments Section -->
                    <section class="glass-article rounded-2xl sm:rounded-3xl p-3 sm:p-12 shadow-2xl scroll-animate space-y-6 sm:space-y-8" data-animation="fade-in-up">
                        <h3 class="text-sm sm:text-2xl font-black font-display text-text flex items-center gap-2">
                            Comments
                            <span class="text-[9px] sm:text-xs bg-primary/10 border border-primary/20 text-primary font-bold px-2 py-0.5 rounded-full">
                                {{ $post->comments->where('is_approved', true)->count() }}
                            </span>
                        </h3>

                        <!-- Add Comment form -->
                        <div class="bg-surface/30 p-3 sm:p-6 rounded-xl sm:rounded-2xl border border-white/5 relative overflow-hidden card-3d">
                            <h4 class="text-xs sm:text-lg font-bold text-text mb-3">Leave a Response</h4>
                            @if(session('success'))
                                <div class="mb-4 text-green-500 font-semibold bg-green-500/10 border border-green-500/20 p-3 sm:p-4 rounded-xl text-xs sm:text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-3 sm:space-y-4">
                                @csrf
                                @guest
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                        <div>
                                            <label class="block text-[9px] sm:text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Name</label>
                                            <input type="text" name="name" required
                                                   class="w-full rounded-lg sm:rounded-xl border border-white/10 bg-background text-text text-xs sm:text-sm py-2 px-3 sm:py-3 sm:px-4 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] sm:text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Email</label>
                                            <input type="email" name="email" required
                                                   class="w-full rounded-lg sm:rounded-xl border border-white/10 bg-background text-text text-xs sm:text-sm py-2 px-3 sm:py-3 sm:px-4 transition-all">
                                        </div>
                                    </div>
                                @endguest

                                <div>
                                    <label class="block text-[9px] sm:text-xs font-bold text-text-muted uppercase tracking-wider mb-1">Comment</label>
                                    <textarea name="content" rows="4" required
                                              class="w-full rounded-lg sm:rounded-xl border border-white/10 bg-background text-text text-xs sm:text-sm py-2 px-3 sm:py-3 sm:px-4 transition-all"
                                              placeholder="Thoughts?"></textarea>
                                </div>

                                <button type="submit"
                                        class="btn btn-sm sm:btn-primary hover:-translate-y-0.5 transition-transform shadow-lg shadow-primary/20">
                                    Post
                                </button>
                            </form>
                        </div>

                        <!-- Comments Listing -->
                        <div class="space-y-4 sm:space-y-6" data-stagger-reveal>
                            @forelse($post->comments->where('is_approved', true) as $comment)
                                <div class="flex gap-2 sm:gap-4 p-3 sm:p-5 rounded-xl sm:rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/5 transition-all">
                                    <div class="flex-shrink-0">
                                        <div class="h-7 w-7 sm:h-10 sm:w-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-sm sm:text-lg shadow-md">
                                            {{ substr($comment->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-text text-xs sm:text-sm">{{ $comment->name }}</span>
                                            <span class="text-[9px] sm:text-xs text-text-muted font-semibold">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-text/80 text-xs sm:text-sm leading-relaxed">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-text-muted">
                                    <p class="italic text-xs">No comments yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                    <!-- Mobile Related Articles (visible only on small screens) -->
                    <section class="block lg:hidden glass-article rounded-2xl p-3 sm:p-6 shadow-xl scroll-animate space-y-4" data-animation="fade-in-up">
                        <h4 class="text-[10px] uppercase tracking-widest font-black text-text-muted">
                            Related Articles
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse($relatedPosts as $related)
                                <a href="{{ route('blog.show', $related->slug) }}" 
                                   class="block group p-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/5 transition-all">
                                    @if($related->featured_image)
                                        <div class="aspect-[2/1] rounded-lg overflow-hidden mb-2">
                                            <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                    @endif
                                    <div class="space-y-1">
                                        <span class="text-[8px] font-bold text-primary uppercase leading-none block">{{ $related->category->name ?? 'Update' }}</span>
                                        <h5 class="font-bold text-text group-hover:text-primary transition-colors text-xs line-clamp-2 leading-tight">
                                            {{ $related->title }}
                                        </h5>
                                        <span class="text-[8px] text-text-muted font-medium block leading-none">{{ $related->created_at->format('M d, Y') }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-[10px] text-text-muted italic">None</p>
                            @endforelse
                        </div>
                    </section>
                </main>

                <!-- RIGHT COLUMN: Table of Contents & Related Posts (col-span-2 lg:col-span-3) -->
                <aside class="col-span-2 lg:col-span-3 relative">
                    <div class="sticky top-20 sm:top-24 space-y-4 sm:space-y-8">
                        
                        <!-- Dynamic Table of Contents -->
                        <div class="glass-article p-2 sm:p-6 rounded-xl sm:rounded-3xl space-y-2 sm:space-y-4" id="toc-container">
                            <h4 class="text-[8px] sm:text-xs uppercase tracking-widest font-black text-text-muted flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                <span class="hidden sm:inline">Table of Contents</span>
                                <span class="inline sm:hidden">TOC</span>
                            </h4>
                            <nav class="space-y-1 sm:space-y-2 border-l border-white/5 text-[9px] sm:text-xs" id="toc-nav">
                                <!-- Populated dynamically via Javascript -->
                                <p class="text-[8px] sm:text-xs text-text-muted pl-2 sm:pl-4 italic">None</p>
                            </nav>
                        </div>

                        <!-- Related Articles widget (Desktop only) -->
                        <div class="hidden lg:block glass-article p-6 rounded-3xl space-y-6">
                            <h4 class="text-[8px] sm:text-xs uppercase tracking-widest font-black text-text-muted">
                                <span class="hidden sm:inline">Related Articles</span>
                                <span class="inline sm:hidden">Related</span>
                            </h4>
                            <div class="space-y-2 sm:space-y-4">
                                @forelse($relatedPosts as $related)
                                    <a href="{{ route('blog.show', $related->slug) }}" 
                                       class="block group p-1.5 sm:p-3 rounded-lg sm:rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/5 transition-all">
                                        @if($related->featured_image)
                                            <div class="aspect-[2/1] rounded-lg overflow-hidden mb-1 sm:mb-3">
                                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endif
                                        <div class="space-y-0.5">
                                            <span class="text-[7px] sm:text-[10px] font-bold text-primary uppercase leading-none block">{{ $related->category->name ?? 'Update' }}</span>
                                            <h5 class="font-bold text-text group-hover:text-primary transition-colors text-[9px] sm:text-sm line-clamp-2 leading-tight">
                                                {{ $related->title }}
                                            </h5>
                                            <span class="text-[7px] sm:text-[10px] text-text-muted font-medium block leading-none">{{ $related->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-[8px] sm:text-xs text-text-muted italic">None</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- Frontend Interactive Scripts -->
    <script>
        document.addEventListener('turbo:load', () => {

            // 2. Reading Progress Circular Tracker
            const progressCircle = document.getElementById('progress-circle');
            const progressText = document.getElementById('progress-text');
            
            if (progressCircle && progressText) {
                window.addEventListener('scroll', () => {
                    const scrollTop = window.scrollY;
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrollPercent = Math.min(100, Math.max(0, Math.round((scrollTop / docHeight) * 100)));
                    
                    progressText.textContent = scrollPercent;
                    
                    if (scrollPercent === 0) {
                        progressCircle.style.opacity = '0';
                    } else {
                        progressCircle.style.opacity = '1';
                        progressCircle.style.strokeDasharray = `${scrollPercent}, 100`;
                    }
                }, { passive: true });
            }

            // 3. Dynamic Vertical Table of Contents Generation
            const articleBody = document.getElementById('article-body');
            const tocNav = document.getElementById('toc-nav');
            const tocContainer = document.getElementById('toc-container');
            
            if (articleBody && tocNav) {
                const headings = articleBody.querySelectorAll('h2, h3');
                if (headings.length > 0) {
                    tocNav.innerHTML = ''; // clear default placeholder
                    
                    headings.forEach((heading, idx) => {
                        const id = heading.id || `heading-${idx}`;
                        heading.id = id;
                        
                        const item = document.createElement('a');
                        item.href = `#${id}`;
                        item.innerHTML = `<span class="inline-block sm:hidden w-1.5 h-1.5 rounded-full bg-white/20 border border-white/10 flex-shrink-0 item-dot transition-all"></span><span class="hidden sm:inline line-clamp-1">${heading.textContent}</span>`;
                        item.className = `toc-vertical-item flex items-center justify-center sm:justify-start text-text-muted hover:text-primary transition-all py-1.5 sm:py-1.5 sm:pl-4 font-semibold ${heading.tagName === 'H3' ? 'sm:ml-3 text-[11px] opacity-75' : 'text-xs'}`;
                        
                        item.addEventListener('click', (e) => {
                            e.preventDefault();
                            if(window.lenis) window.lenis.scrollTo(`#${id}`, { offset: -100 });
                        });
                        
                        tocNav.appendChild(item);
                    });

                    // Highlight active heading on scroll
                    const tocItems = tocNav.querySelectorAll('.toc-vertical-item');
                    window.addEventListener('scroll', () => {
                        let activeIdx = 0;
                        headings.forEach((heading, idx) => {
                            const rect = heading.getBoundingClientRect();
                            if (rect.top < 150) {
                                activeIdx = idx;
                            }
                        });
                        
                        tocItems.forEach((item, idx) => {
                            if (idx === activeIdx) {
                                item.classList.add('active');
                            } else {
                                item.classList.remove('active');
                            }
                        });
                    }, { passive: true });
                } else {
                    if (tocContainer) tocContainer.style.display = 'none';
                }
            }

            // 4. Mouse Glow Highlight Movement
            const card = document.querySelector('.glass-article');
            const glow = document.getElementById('card-glow');
            if (card && glow) {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left - 75; // centering glow offset (mobile compatible)
                    const y = e.clientY - rect.top - 75;
                    glow.style.transform = `translate3d(${x}px, ${y}px, 0)`;
                });
            }
        });
    </script>
</x-public-layout>