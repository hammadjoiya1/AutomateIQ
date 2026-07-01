@php
    $layout = (request()->layout === 'dashboard' && Auth::check()) ? 'app-layout' : 'public-layout';
@endphp
<x-dynamic-component :component="$layout">
    <div class="py-8 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header section with centered search -->
            <div class="text-center max-w-3xl mx-auto mb-12 px-4">
                <x-ui.badge variant="accent" class="mb-6">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    {{ $tools->count() }} AI Tools Available
                </x-ui.badge>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-text tracking-tight mb-4">
                    Automate your <span class="text-primary">creative workflow</span>
                </h1>
                <p class="text-lg text-text-muted mb-8 leading-relaxed">
                    Focused tools for faceless creators: hooks, ideas, short scripts, scene splitters, video prompts, and repurposing.
                </p>

                <!-- Modern Search Bar -->
                <form method="GET" action="{{ route('tools.index') }}" class="relative max-w-xl mx-auto group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-text-muted group-focus-within:text-primary transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request()->search }}"
                        class="w-full pl-12 pr-4 py-4 rounded-control-sm bg-input border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all shadow-sm text-text placeholder:text-muted focus:outline-none"
                        placeholder="Search for tools like 'Script Writer'...">
                    @if(request()->category)
                        <input type="hidden" name="category" value="{{ request()->category }}">
                    @endif
                    @if(request()->tag)
                        <input type="hidden" name="tag" value="{{ request()->tag }}">
                    @endif
                    @if(request()->layout)
                        <input type="hidden" name="layout" value="{{ request()->layout }}">
                    @endif
                </form>
            </div>

            <!-- Categories Filter -->
            <div class="mb-10 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                <div class="flex gap-2 justify-start md:justify-center min-w-max">
                    <a href="{{ route('tools.index', ['layout' => request()->layout, 'search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                        class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ !request()->category ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                        All Tools
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('tools.index', ['layout' => request()->layout, 'category' => $category->slug, 'search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                            class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ request()->category === $category->slug ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ route('tools.index', ['layout' => request()->layout, 'favorite' => request()->favorite ? null : 1, 'search' => request()->search, 'category' => request()->category, 'tag' => request()->tag]) }}"
                            class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ request()->favorite ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            Favorites
                        </a>
                    @endauth
                </div>
            </div>

            @if($tags->isNotEmpty())
                <div class="mb-10 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                    <div class="flex gap-2 justify-start md:justify-center min-w-max">
                        <a href="{{ route('tools.index', ['layout' => request()->layout, 'search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                            class="px-3 py-1.5 rounded-control-sm text-xs font-semibold transition-all {{ !request()->tag ? 'bg-surface border border-border text-text' : 'bg-surface/50 border border-border text-text-muted hover:text-text' }}">
                            All Tags
                        </a>
                        @foreach($tags as $tag)
                            <a href="{{ route('tools.index', ['layout' => request()->layout, 'tag' => $tag->slug, 'search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                                class="px-3 py-1.5 rounded-control-sm text-xs font-semibold transition-all {{ request()->tag === $tag->slug ? 'bg-primary/20 text-primary border border-primary/30' : 'bg-surface border border-border text-text-muted hover:text-text' }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Grouped Tools Categories -->
            @php
                $videoSlugs = ['youtube-hook-generator', 'viral-video-ideas-generator', 'script-generator-short', 'ai-video-generator', 'scene-splitter-video-factory'];
                $socialSlugs = ['caption-generator', 'hashtag-generator', 'tweet-thread-generator', 'repurpose-twitter-thread', 'repurpose-linkedin-post', 'repurpose-newsletter'];
                $writingSlugs = ['blog-outline-generator', 'seo-title-generator', 'product-description-generator', 'motivational-quote-generator', 'story-generator'];
                $utilitySlugs = ['prompt-builder-tool'];

                $videoTools = $tools->filter(fn($t) => in_array($t->slug, $videoSlugs));
                $socialTools = $tools->filter(fn($t) => in_array($t->slug, $socialSlugs));
                $writingTools = $tools->filter(fn($t) => in_array($t->slug, $writingSlugs));
                $utilityTools = $tools->filter(fn($t) => in_array($t->slug, $utilitySlugs));

                $categoriesList = [
                    ['title' => 'Video & Script Creation', 'tools' => $videoTools],
                    ['title' => 'Social Media & Repurposing', 'tools' => $socialTools],
                    ['title' => 'Writing & Content Marketing', 'tools' => $writingTools],
                    ['title' => 'Utility', 'tools' => $utilityTools]
                ];

                $toolIcons = [
                    'youtube-hook-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l-4 3v-6l4 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3z" />
                        </svg>
                    ',
                    'viral-video-ideas-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    ',
                    'script-generator-short' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    ',
                    'ai-video-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5l-9 9M13.5 4.5l-3 3M16.5 7.5l-3 3M6 20h.01M9 17h.01M12 14h.01M3 6l3-3m0 0l3 3M6 3v9" />
                        </svg>
                    ',
                    'scene-splitter-video-factory' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-4.879-4.879L19 9.12M12 12a3 3 0 11-6 0 3 3 0 016 0zm0 0a3 3 0 116 0 3 3 0 01-6 0zm-3-3L12 12m-3 3L12 12" />
                        </svg>
                    ',
                    'caption-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    ',
                    'hashtag-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                    ',
                    'tweet-thread-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5" />
                        </svg>
                    ',
                    'repurpose-twitter-thread' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v16h16V4H4zm4 4h8M8 12h8m-8 4h5" />
                        </svg>
                    ',
                    'repurpose-linkedin-post' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    ',
                    'repurpose-newsletter' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    ',
                    'blog-outline-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    ',
                    'seo-title-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    ',
                    'product-description-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.5 9.5a2.25 2.25 0 003.182 0l5.178-5.178a2.25 2.25 0 000-3.182l-9.5-9.5A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    ',
                    'motivational-quote-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    ',
                    'story-generator' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    ',
                    'prompt-builder-tool' => '
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    '
                ];

                $hasAnyTools = false;
                foreach($categoriesList as $cat) {
                    if($cat['tools']->isNotEmpty()) $hasAnyTools = true;
                }
            @endphp

            @if($hasAnyTools)
                <div class="space-y-16">
                    @foreach($categoriesList as $cat)
                        @if($cat['tools']->isNotEmpty())
                            <div class="category-scroll-fade">
                                <!-- Category Title Header in monospaced uppercase -->
                                <h3 class="font-mono text-xs uppercase tracking-widest text-[var(--color-accent)] mb-8 px-4 font-bold">
                                    // {{ $cat['title'] }}
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 mb-12">
                                    @foreach($cat['tools']->values() as $index => $tool)
                                        <a href="{{ route('tools.show', ['slug' => $tool->slug, 'layout' => request()->layout]) }}" 
                                           class="group block h-full cursor-pointer category-card-reveal"
                                           style="animation-delay: {{ $index * 60 }}ms;">
                                            <x-ui.card padding="p-6" class="strat-card spotlight-card border border-border flex flex-col h-full" :hoverEffect="true">
                                                <div class="h-12 w-12 rounded-xl bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm mb-6">
                                                    {!! $toolIcons[$tool->slug] ?? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="1.5"/></svg>' !!}
                                                </div>
                                                <h4 class="text-lg font-semibold text-text mb-2 group-hover:text-primary transition-colors">
                                                    {{ $tool->name }}
                                                </h4>
                                                <p class="text-sm text-text-muted flex-grow">
                                                    {{ $tool->description }}
                                                </p>
                                            </x-ui.card>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <x-ui.card padding="py-24 px-4" class="text-center mx-4 border-dashed" :hoverEffect="false">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface mb-6">
                        <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-2">No tools found</h3>
                    <p class="text-text-muted mb-8">We couldn't find any tools matching your search criteria.</p>
                    <x-ui.button variant="secondary" href="{{ route('tools.index') }}">Clear All Filters</x-ui.button>
                </x-ui.card>
            @endif
        </div>
    </div>
</x-dynamic-component>