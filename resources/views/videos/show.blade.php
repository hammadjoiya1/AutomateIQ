<x-public-layout :meta-title="$project->title ?? 'Video Project'">
    <div class="py-12 lg:py-20 animate-fade-in" id="project-container">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold font-display text-text">{{ $project->title ?? 'Video Project' }}</h1>
                    <p class="text-sm text-text-muted mt-1">Status and rendered results for this video generation project.</p>
                </div>
                <a href="{{ route('videos.index') }}" class="btn px-4 py-2 bg-surface hover:bg-surface-2 border border-border text-text rounded-lg text-sm transition-colors">
                    ← Back to Videos
                </a>
            </div>

            <div class="card p-6">
                {{-- Status Banner --}}
                @if($project->status === 'completed')
                    <div class="bg-success/10 border border-success/30 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-bold text-success">Video Complete!</p>
                                <p class="text-sm text-muted-text">Your video is ready to watch and download.</p>
                            </div>
                        </div>
                    </div>
                @elseif($project->status === 'failed')
                    <div class="bg-danger/10 border border-danger/30 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-bold text-danger">Generation Failed</p>
                                <p class="text-sm text-muted-text">Something went wrong. Please try again.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Video Player Area --}}
                @if($project->video_url)
                    <div class="card p-6 border-primary/20 bg-primary/5 mb-8">
                        <h3 class="font-bold text-xl font-display text-text mb-4">
                            {{ $project->scenes->count() > 0 ? 'Full Movie' : 'Generated Video' }}
                        </h3>
                        <div class="aspect-video bg-black rounded-lg overflow-hidden mb-6 shadow-lg">
                            <video controls class="w-full h-full">
                                <source src="{{ $project->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ $project->video_url }}" download class="btn-primary w-full md:w-auto justify-center px-6 py-3 flex items-center gap-2 shadow-lg shadow-primary/25 hover:shadow-primary/40">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download {{ $project->scenes->count() > 0 ? 'Full Movie' : 'Video' }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Individual Scenes List --}}
                @if($project->scenes->count() > 0)
                    <div class="space-y-8 mb-8">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xl font-display text-text">Scene Breakdown ({{ $project->scenes->count() }})</h3>
                            <span class="badge bg-surface text-muted-text border-border">Individual Clips</span>
                        </div>

                        @foreach($project->scenes->sortBy('sequence_order') as $scene)
                            <div class="card p-4 border border-border/50 bg-bg-2/30">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-sm">
                                            {{ $scene->sequence_order }}
                                        </span>
                                        <p class="font-medium text-text text-sm line-clamp-1" title="{{ $scene->script_text }}">
                                            {{ Str::limit($scene->script_text, 80) }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($scene->status === 'completed')
                                            <span class="badge bg-success/10 text-success border-success/20 text-xs">Ready</span>
                                        @elseif($scene->status === 'failed')
                                            <span class="badge bg-danger/10 text-danger border-danger/20 text-xs">Failed</span>
                                        @else
                                            <span class="badge bg-accent/10 text-accent border-accent/20 text-xs animate-pulse">Generating...</span>
                                        @endif
                                    </div>
                                </div>

                                @if($scene->status === 'completed' && $scene->video_url)
                                    <div class="aspect-video bg-black rounded-lg overflow-hidden relative group">
                                        <video controls class="w-full h-full" preload="metadata">
                                            <source src="{{ $scene->video_url }}" type="video/mp4">
                                        </video>
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ $scene->video_url }}" download class="btn btn-sm bg-black/50 text-white backdrop-blur hover:bg-black/70">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                @elseif($scene->status === 'failed')
                                    <div class="aspect-video bg-danger/5 rounded-lg flex items-center justify-center border border-danger/10">
                                        <div class="text-center p-4">
                                            <svg class="w-8 h-8 text-danger mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <p class="text-xs text-danger font-medium">Generation Failed</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="aspect-video bg-surface rounded-lg flex flex-col items-center justify-center border border-dashed border-border">
                                        <svg class="w-8 h-8 text-primary animate-spin mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <p class="text-xs text-muted-text font-medium animate-pulse">Rendering Scene...</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Project Details --}}
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-muted-text uppercase tracking-wide mb-1">Prompt</h4>
                        <p class="text-text">{{ $project->prompt }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-muted-text uppercase tracking-wide mb-1">Visual Style</h4>
                            <p class="text-text capitalize">{{ $project->visual_style }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-muted-text uppercase tracking-wide mb-1">Model</h4>
                            <p class="text-text capitalize">{{ $project->model_provider }}</p>
                        </div>
                    </div>
                    @if($project->script_content)
                        <div>
                            <h4 class="text-sm font-bold text-muted-text uppercase tracking-wide mb-1">Generated Script</h4>
                            <div class="bg-bg-2 rounded-lg p-4 text-sm text-text whitespace-pre-wrap">{{ $project->script_content }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-public-layout>