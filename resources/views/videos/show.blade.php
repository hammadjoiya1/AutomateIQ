@if($project->status === 'generating' || $project->status === 'scripting')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creation in progress</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #050505; color: white; margin: 0; overflow: hidden; font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; background-color: rgba(167, 139, 250, 0.2); filter: blur(120px); border-radius: 50%; pointer-events: none;"></div>

    <div style="position: relative; z-index: 10; width: 100%; max-width: 48rem; padding: 0 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
        
        {{-- Spinner --}}
        <div style="margin-bottom: 2rem; width: 3rem; height: 3rem; position: relative;">
            <svg style="animation: spin 1s linear infinite; width: 100%; height: 100%; color: #333;" viewBox="0 0 24 24" fill="none">
                <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                <path d="M12 2a10 10 0 0 1 10 10" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"></path>
            </svg>
        </div>

        {{-- Title --}}
        <h1 style="font-size: 3rem; margin-bottom: 1rem; color: white; font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif; font-style: italic; letter-spacing: -1px;">Creation in progress</h1>

        {{-- Progress Text --}}
        <h2 style="font-size: 1.875rem; font-weight: 800; color: white; margin-bottom: 0.5rem; letter-spacing: 0.025em;" id="hero-progress-percent">0%</h2>
        <p style="color: #9ca3af; margin-bottom: 3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.875rem;" id="hero-progress-text">Initializing...</p>

        {{-- Steps Cards --}}
        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; width: 100%; margin-bottom: 3rem;">
            <div id="step-script" style="background-color: rgba(167, 139, 250, 0.1); border: 1px solid rgba(167, 139, 250, 0.5); border-radius: 1rem; padding: 2rem 1rem; box-shadow: 0 0 15px rgba(167, 139, 250, 0.2); transition: all 0.5s;">
                <h3 style="color: white; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Script</h3>
            </div>
            <div id="step-assets" style="background-color: #111; border: 1px solid #333; border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;">
                <h3 style="color: #6b7280; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Assets</h3>
            </div>
            <div id="step-editing" style="background-color: #111; border: 1px solid #333; border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;">
                <h3 style="color: #6b7280; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;">Editing</h3>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div style="width: 100%; max-width: 42rem; background-color: #111; border-radius: 9999px; height: 0.75rem; overflow: hidden; border: 1px solid #333; position: relative;">
            <div id="hero-progress-bar" style="height: 100%; border-radius: 9999px; transition: width 0.3s ease-out; background-image: linear-gradient(to right, #9333ea, #ec4899, #fcd34d); width: 5%;">
            </div>
        </div>
    </div>
@else
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6">
                {{-- Status Banner --}}
                @if($project->status === 'scripting')
                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-yellow-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <div>
                                <p class="font-bold text-yellow-500">Generating Script...</p>
                                <p class="text-sm text-muted-text">AI is writing your video script. This may take a moment.
                                </p>
                            </div>
                        </div>
                    </div>
                {{-- Status Banner with Progress Bar --}}
                @elseif($project->status === 'generating')
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-6 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-6 h-6 text-blue-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 0 002-2V8a2 0 00-2-2H5a2 0 00-2 2v8a2 0 002 2z"/>
                            </svg>
                            <div>
                                <h3 class="font-bold text-blue-500">Generating Video...</h3>
                                <p class="text-sm text-muted-text">Your video is being rendered.</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-bg-2 rounded-full h-4 overflow-hidden relative">
                            <div id="progress-bar" class="bg-blue-500 h-full rounded-full transition-all duration-300" style="width: 5%">
                                <div class="absolute inset-0 bg-white/20 animate-shimmer" style="background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent); background-size: 200% 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-muted-text font-mono">
                            <span id="progress-text">Initializing...</span>
                            <span id="progress-percent">0%</span>
                        </div>
                    </div>
                @elseif($project->status === 'completed')
                    <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-bold text-green-500">Video Complete!</p>
                                <p class="text-sm text-muted-text">Your video is ready to watch and download.</p>
                            </div>
                        </div>
                    </div>
                @elseif($project->status === 'failed')
                    <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-bold text-red-500">Generation Failed</p>
                                <p class="text-sm text-muted-text">Something went wrong. Please try again.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Video Player Area --}}
                {{-- Final/Stitched Video --}}
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
                    {{-- Multi-Scene View --}}
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
                                            <span class="badge bg-green-500/10 text-green-500 border-green-500/20 text-xs">Ready</span>
                                        @elseif($scene->status === 'failed')
                                            <span class="badge bg-red-500/10 text-red-500 border-red-500/20 text-xs">Failed</span>
                                        @else
                                            <span class="badge bg-blue-500/10 text-blue-500 border-blue-500/20 text-xs animate-pulse">Generating...</span>
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
                                    <div class="aspect-video bg-red-500/5 rounded-lg flex items-center justify-center border border-red-500/10">
                                        <div class="text-center p-4">
                                            <svg class="w-8 h-8 text-red-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <p class="text-xs text-red-400 font-medium">Generation Failed</p>
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
                            <div class="bg-bg-2 rounded-lg p-4 text-sm text-text whitespace-pre-wrap">
                                {{ $project->script_content }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    @endif

    @if($project->status === 'generating' || $project->status === 'scripting')
        <script>
            // IMMEDIATE DEBUG EXECUTION
            const debugText = document.getElementById('hero-progress-text');
            if (debugText) {
                debugText.innerText = "JS STARTED";
                debugText.style.color = "red";
            }

            function initPolling() {
                const pollInterval = setInterval(checkStatus, 5000); // Poll every 5 seconds
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const progressPercent = document.getElementById('progress-percent');
                
                // Persistent simulated progress state
                const storageKey = 'progress_project_{{ $project->id }}';
                let simulatedProgress = 5;
                try {
                    simulatedProgress = parseFloat(localStorage.getItem(storageKey)) || 5;
                } catch (e) {}
                
                const maxSimulated = 90;
                const duration = 120; // Approx 2 minutes per scene
                const increment = (maxSimulated - 5) / (duration / 5); // Increment per 5s

                function checkStatus() {
                    try {
                        // Update simulated progress locally first
                        if (simulatedProgress < maxSimulated) {
                            simulatedProgress += increment;
                            try {
                                localStorage.setItem(storageKey, simulatedProgress);
                            } catch (e) {} // Ignore localStorage errors
                        }
                        
                        // Immediately update UI with simulated progress so it doesn't freeze while waiting for the server
                        updateUI(Math.floor(simulatedProgress), "Processing...");

                        fetch('{{ route('videos.check-status', $project) }}')
                            .then(response => response.json())
                            .then(data => {
                            let displayPercent = Math.floor(simulatedProgress);
                            let displayStatus = "Processing...";

                            // If we have multiple scenes, calculate real mathematical progress
                            if (data.total_scenes && data.total_scenes > 0) {
                                const chunkPerScene = 100 / data.total_scenes;
                                const baseProgress = data.completed_scenes * chunkPerScene;
                                
                                // Add the simulated progress of the *current* scene
                                const currentSceneProgress = (simulatedProgress / 100) * chunkPerScene;
                                
                                displayPercent = Math.floor(baseProgress + currentSceneProgress);
                                displayStatus = `Rendering Scene ${data.completed_scenes + 1} of ${data.total_scenes}...`;
                                
                                if (data.completed_scenes >= data.total_scenes) {
                                    displayPercent = 95;
                                    displayStatus = "Stitching final video...";
                                }
                            } else {
                                displayStatus = data.status === 'generating' ? "Generating frames..." : "Processing...";
                            }
                            
                            // Prevent progress from going backwards
                            const highestProgressKey = 'highest_progress_{{ $project->id }}';
                            let highestProgress = parseInt(localStorage.getItem(highestProgressKey)) || 0;
                            if (displayPercent > highestProgress) {
                                highestProgress = displayPercent;
                                localStorage.setItem(highestProgressKey, highestProgress);
                            } else {
                                displayPercent = highestProgress;
                            }

                            // Dynamic DOM Replacement on scene completion
                            // Dynamic DOM Replacement on scene completion
                            if (window.lastCompletedScenes === undefined) {
                                window.lastCompletedScenes = data.completed_scenes;
                            } else if (data.completed_scenes !== undefined && data.completed_scenes > window.lastCompletedScenes) {
                                window.lastCompletedScenes = data.completed_scenes;
                                
                                // A scene finished! Reset simulation for next scene
                                simulatedProgress = 5;
                                localStorage.setItem(storageKey, simulatedProgress);

                            }

                            // Update UI
                            updateUI(displayPercent, displayStatus);

                            if (data.status === 'completed') {
                                updateUI(100, "Finalizing...");
                                localStorage.removeItem(storageKey);
                                localStorage.removeItem(highestProgressKey);
                                clearInterval(pollInterval);
                                
                                // Final dynamic load for the completed stitched video
                                window.location.reload();
                            } else if (data.status === 'failed') {
                                clearInterval(pollInterval);
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error polling status:', error);
                            const statusText = document.getElementById('hero-progress-text');
                            if (statusText) statusText.innerText = 'Network error... Retrying...';
                        });
                    } catch (e) {
                        console.error('JS Error in checkStatus:', e);
                        const statusText = document.getElementById('hero-progress-text');
                        if (statusText) statusText.innerText = 'JS Error: ' + e.message;
                    }
                }
                
                function updateUI(percent, text) {
                    const bar = document.getElementById('progress-bar');
                    const percentText = document.getElementById('progress-percent');
                    const statusText = document.getElementById('progress-text');
                    
                    if (bar) bar.style.width = percent + '%';
                    if (percentText) percentText.innerText = percent + '%';
                    if (statusText && text) statusText.innerText = text;

                    // Hero UI Elements
                    const heroBar = document.getElementById('hero-progress-bar');
                    const heroPercentText = document.getElementById('hero-progress-percent');
                    const heroStatusText = document.getElementById('hero-progress-text');
                    
                    if (heroBar) heroBar.style.width = percent + '%';
                    if (heroPercentText) heroPercentText.innerText = percent + '%';
                    if (heroStatusText && text) heroStatusText.innerText = text;

                    // Hero Steps UI
                    const stepScript = document.getElementById('step-script');
                    const stepAssets = document.getElementById('step-assets');
                    const stepEditing = document.getElementById('step-editing');

                    if (stepScript && stepAssets && stepEditing) {
                        const activeStyle = "background-color: rgba(167, 139, 250, 0.1); border: 1px solid rgba(167, 139, 250, 0.5); border-radius: 1rem; padding: 2rem 1rem; box-shadow: 0 0 15px rgba(167, 139, 250, 0.2); transition: all 0.5s;";
                        const activeTextStyle = "color: white; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;";
                        
                        const inactiveStyle = "background-color: #111; border: 1px solid #333; border-radius: 1rem; padding: 2rem 1rem; transition: all 0.5s;";
                        const inactiveTextStyle = "color: #6b7280; font-weight: 700; font-size: 1.125rem; letter-spacing: 0.025em; margin: 0;";

                        if (percent < 30) {
                            stepScript.style.cssText = activeStyle;
                            stepScript.querySelector('h3').style.cssText = activeTextStyle;
                            stepAssets.style.cssText = inactiveStyle;
                            stepAssets.querySelector('h3').style.cssText = inactiveTextStyle;
                            stepEditing.style.cssText = inactiveStyle;
                            stepEditing.querySelector('h3').style.cssText = inactiveTextStyle;
                        } else if (percent < 90) {
                            stepScript.style.cssText = inactiveStyle;
                            stepScript.querySelector('h3').style.cssText = inactiveTextStyle;
                            
                            stepAssets.style.cssText = "background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.5); border-radius: 1rem; padding: 2rem 1rem; box-shadow: 0 0 15px rgba(245, 158, 11, 0.2); transition: all 0.5s;";
                            stepAssets.querySelector('h3').style.cssText = activeTextStyle;

                            stepEditing.style.cssText = inactiveStyle;
                            stepEditing.querySelector('h3').style.cssText = inactiveTextStyle;
                        } else {
                            stepScript.style.cssText = inactiveStyle;
                            stepScript.querySelector('h3').style.cssText = inactiveTextStyle;
                            stepAssets.style.cssText = inactiveStyle;
                            stepAssets.querySelector('h3').style.cssText = inactiveTextStyle;

                            stepEditing.style.cssText = "background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 1rem; padding: 2rem 1rem; box-shadow: 0 0 15px rgba(16, 185, 129, 0.2); transition: all 0.5s;";
                            stepEditing.querySelector('h3').style.cssText = activeTextStyle;
                        }
                    }
                }

                // Run once immediately
                checkStatus();
            }

            // Execute immediately since the script is at the bottom of the body
            // This prevents the UI from freezing if external scripts (like Vite's app.js) take too long to load or time out.
            initPolling();
        </script>
    @endif
@if($project->status === 'generating' || $project->status === 'scripting')
</body>
</html>
@else
</x-public-layout>
@endif