<x-public-layout meta-title="AI Tools — AutomateIQ" meta-description="Creator-grade hooks, viral ideas, short scripts, scene splitters, video prompts, and repurposing tools for faceless growth.">
    <div class="py-12 lg:py-20 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header section with centered search -->
            <div class="text-center max-w-3xl mx-auto mb-16 px-4">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-semibold mb-6">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    {{ $tools->total() }} AI Tools Available
                </div>
                <h1 class="text-4xl md:text-5xl font-display font-bold text-text tracking-tight mb-6">
                    Automate your <span class="text-gradient-primary">creative workflow</span>
                </h1>
                <p class="text-xl text-text-muted mb-10 leading-relaxed">
                    Focused tools for faceless creators: hooks, ideas, short scripts, scene splitters, video prompts, and repurposing.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3 text-xs font-medium text-text-muted">
                    <span class="px-3 py-1 rounded-full border border-primary/20 bg-primary/5 text-primary">Creator Packs</span>
                    <span class="px-3 py-1 rounded-full border border-border bg-surface">Fitness</span>
                    <span class="px-3 py-1 rounded-full border border-border bg-surface">Finance</span>
                    <span class="px-3 py-1 rounded-full border border-border bg-surface">SaaS</span>
                    <span class="px-3 py-1 rounded-full border border-border bg-surface">Free mini‑pack</span>
                </div>

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
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-surface/50 border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all shadow-lg shadow-black/5 text-text placeholder:text-text-muted/50"
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

            <div class="mb-14 px-4">
                <div class="card p-6 bg-surface/40 border border-primary/10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-text">Creator Packs (ready‑to‑use outputs)</h2>
                            <p class="text-sm text-text-muted">Bundle your tools into sellable packs with templates + examples.</p>
                        </div>
                        <a href="{{ route('pricing') }}" class="btn btn-sm btn-secondary">See pack pricing</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Hook Pack</div>
                            <div class="text-xs text-text-muted mt-1">YouTube Hook Generator</div>
                        </div>
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Script Pack</div>
                            <div class="text-xs text-text-muted mt-1">Script Generator (Short)</div>
                        </div>
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Scene Splitter Pack</div>
                            <div class="text-xs text-text-muted mt-1">Scene Splitter</div>
                        </div>
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Repurpose Pack</div>
                            <div class="text-xs text-text-muted mt-1">X / LinkedIn / Newsletter</div>
                        </div>
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Idea Calendar</div>
                            <div class="text-xs text-text-muted mt-1">Viral Video Ideas Generator</div>
                        </div>
                        <div class="rounded-xl border border-border bg-background/60 p-4">
                            <div class="text-sm font-semibold text-text">Video Prompt Pack</div>
                            <div class="text-xs text-text-muted mt-1">AI Video Generator</div>
                        </div>
                    </div>
                    <div class="mt-6 text-xs text-text-muted">Includes niche versions for Fitness, Finance, and SaaS. Free mini‑pack available.</div>
                </div>
            </div>

            <!-- Categories Filter -->
            <div class="mb-12 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                <div class="flex gap-3 justify-start md:justify-center min-w-max">
                    <a href="{{ route('tools.index', ['search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all {{ !request()->category ? 'bg-primary text-white shadow-lg shadow-primary/25 ring-2 ring-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                        All Tools
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('tools.index', ['category' => $category->slug, 'search' => request()->search, 'tag' => request()->tag, 'favorite' => request()->favorite]) }}"
                            class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->category === $category->slug ? 'bg-primary text-white shadow-lg shadow-primary/25 ring-2 ring-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ route('tools.index', ['favorite' => request()->favorite ? null : 1, 'search' => request()->search, 'category' => request()->category, 'tag' => request()->tag]) }}"
                            class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->favorite ? 'bg-primary text-white shadow-lg shadow-primary/25 ring-2 ring-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text hover:bg-surface/80' }}">
                            Favorites
                        </a>
                    @endauth
                </div>
            </div>

            @if($tags->isNotEmpty())
                <div class="mb-10 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide">
                    <div class="flex gap-3 justify-start md:justify-center min-w-max">
                        <a href="{{ route('tools.index', ['search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                            class="px-4 py-2 rounded-full text-xs font-medium transition-all {{ !request()->tag ? 'bg-surface border border-border text-text' : 'bg-surface/50 border border-border text-text-muted hover:text-text' }}">
                            All Tags
                        </a>
                        @foreach($tags as $tag)
                            <a href="{{ route('tools.index', ['tag' => $tag->slug, 'search' => request()->search, 'category' => request()->category, 'favorite' => request()->favorite]) }}"
                                class="px-4 py-2 rounded-full text-xs font-medium transition-all {{ request()->tag === $tag->slug ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-surface border border-border text-text-muted hover:text-text' }}">
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
                            <div
                                class="card h-full p-6 transition-all duration-300 group-hover:translate-y-[-4px] group-hover:shadow-2xl group-hover:shadow-primary/10 group-hover:border-primary/20 relative overflow-hidden">
                                <!-- Hover Glow Effect -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>

                                <div class="relative z-10 flex flex-col h-full">
                                    <div class="flex items-start justify-between mb-6">
                                        <div
                                            class="h-12 w-12 rounded-xl bg-surface border border-white/10 flex items-center justify-center text-primary group-hover:scale-110 group-hover:text-white group-hover:bg-primary transition-all duration-300 shadow-sm">
                                            <!-- Dynamic Icon Logic (Placeholder) -->
                                            <span class="font-bold text-lg">{{ substr($tool->name, 0, 1) }}</span>
                                        </div>
                                        @if($tool->is_featured)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-500 border border-amber-500/20">
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
                                            class="text-text-muted group-hover:text-text transition-colors">{{ $tool->category->name ?? 'Utility' }}</span>
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
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12 px-4">
                    {{ $tools->links() }}
                </div>
            @else
                <div class="text-center py-24 glass-panel rounded-3xl mx-4 border-dashed">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface mb-6">
                        <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-text mb-2">No tools found</h3>
                    <p class="text-text-muted mb-8">We couldn't find any tools matching your search criteria.</p>
                    <a href="{{ route('tools.index') }}" class="btn btn-secondary">Clear All Filters</a>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>