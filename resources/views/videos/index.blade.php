<x-public-layout :meta-title="'My AI Videos — AutomateIQ'">
    <div class="py-12 lg:py-20 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold font-display text-text">My AI Videos</h1>
                    <p class="text-sm text-text-muted mt-1 font-medium">History and downloads of your stitched video projects.</p>
                </div>
                <a href="{{ route('videos.create') }}" class="btn px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-lg text-sm font-semibold transition-colors">
                    + Create New Video
                </a>
            </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($projects->isEmpty())
                <div class="card p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-muted-text mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-text mb-2">No Videos Yet</h3>
                    <p class="text-muted-text mb-6">Get started by creating your first AI-powered video.</p>
                    <a href="{{ route('videos.create') }}" class="btn-primary px-6 py-3">
                        Create Your First Video
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                                <a href="{{ route('videos.show', $project) }}" class="card card-hover overflow-hidden group">
                                    <div class="aspect-video bg-bg-2 relative">
                                        @if($project->thumbnail_url)
                                            <img src="{{ $project->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-muted-text" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2">
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-gray-500',
                                                    'scripting' => 'bg-yellow-500',
                                                    'generating' => 'bg-blue-500 animate-pulse',
                                                    'completed' => 'bg-green-500',
                                                    'failed' => 'bg-red-500',
                                                ];
                                            @endphp
                        <span
                                                class="px-2 py-1 text-xs text-white rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-500' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-text truncate">
                                            {{ $project->title ?? Str::limit($project->prompt, 40) }}</h3>
                                        <p class="text-xs text-muted-text mt-1">
                                            {{ $project->visual_style }} • {{ ucfirst($project->model_provider) }}
                                        </p>
                                        <p class="text-xs text-muted-text mt-1">
                                            {{ $project->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-public-layout>