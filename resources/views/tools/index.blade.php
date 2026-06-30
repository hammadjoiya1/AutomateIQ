<x-app-layout>
    <div class="py-8 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header section with centered search -->
            <div class="text-center max-w-3xl mx-auto mb-12 px-4">
                <x-ui.badge variant="accent" class="mb-6">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    {{ $tools->total() }} AI Tools Available
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
                    @if(request()->favorite)
                        <input type="hidden" name="favorite" value="{{ request()->favorite }}">
                    @endif
                </form>
            </div>

            <!-- Categories Filter -->
            <div class="mb-10 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                <div class="flex gap-2 justify-start md:justify-center min-w-max">
                    <a href="{{ route('tools.index', ['search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                        class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ !request()->category ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                        All Tools
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('tools.index', ['category' => $category->slug, 'search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                            class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ request()->category === $category->slug ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ route('tools.index', ['favorite' => request()->favorite ? null : 1, 'search' => request()->search, 'category' => request()->category, 'tag' => request()->tag]) }}"
                            class="px-4 py-2 rounded-control-sm text-sm font-semibold transition-all {{ request()->favorite ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            Favorites
                        </a>
                    @endauth
                </div>
            </div>

            @if($tags->isNotEmpty())
                <div class="mb-10 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                    <div class="flex gap-2 justify-start md:justify-center min-w-max">
                        <a href="{{ route('tools.index', ['search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                            class="px-3 py-1.5 rounded-control-sm text-xs font-semibold transition-all {{ !request()->tag ? 'bg-surface border border-border text-text' : 'bg-surface/50 border border-border text-text-muted hover:text-text' }}">
                            All Tags
                        </a>
                        @foreach($tags as $tag)
                            <a href="{{ route('tools.index', ['tag' => $tag->slug, 'search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                                class="px-3 py-1.5 rounded-control-sm text-xs font-semibold transition-all {{ request()->tag === $tag->slug ? 'bg-primary/20 text-primary border border-primary/30' : 'bg-surface border border-border text-text-muted hover:text-text' }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tools Grid -->
            @if($tools->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
                    @foreach($tools as $tool)
                        <a href="{{ route('tools.show', $tool->slug) }}" class="group block h-full">
                            <x-ui.card padding="p-6" class="h-full relative overflow-hidden transition-all duration-300 group-hover:translate-y-[-4px] group-hover:border-primary/30" :hoverEffect="true">
                                <!-- Hover Glow Effect -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>

                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-start justify-between mb-6">
                                        <div data-card-icon
                                            class="h-12 w-12 rounded-control-sm bg-surface border border-border flex items-center justify-center text-primary group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm">
                                            <!-- Dynamic Icon Logic (Placeholder) -->
                                            <span class="font-bold text-lg">{{ substr($tool->name, 0, 1) }}</span>
                                        </div>
                                        @if($tool->is_featured)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-[10px] uppercase tracking-wider font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                                Featured
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-xl font-bold text-text mb-2 group-hover:text-primary transition-colors">
                                        {{ $tool->name }}
                                    </h3>
                                    <p class="text-sm text-text-muted line-clamp-3 mb-6 flex-grow">{{ $tool->description }}</p>

                                    <div class="pt-4 border-t border-border flex items-center justify-between text-sm">
                                        <span
                                            class="text-text-muted group-hover:text-text transition-colors font-semibold">{{ $tool->category->name ?? 'Utility' }}</span>
                                        @if($tool->tags->isNotEmpty())
                                            <span class="text-xs text-text-muted">{{ $tool->tags->pluck('name')->implode(' • ') }}</span>
                                        @endif
                                        <div
                                            class="flex items-center text-primary font-bold group-hover:translate-x-1 transition-transform duration-300">
                                            Try Now <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12 px-4">
                    {{ $tools->links() }}
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
</x-app-layout>