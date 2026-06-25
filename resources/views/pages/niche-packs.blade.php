<x-public-layout meta-title="Niche Packs — AutomateIQ" meta-description="Pre‑tuned packs for Fitness, Finance, and SaaS creators.">
    <div class="bg-background py-20 sm:py-28">
        <div class="mx-auto max-w-5xl px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl sm:text-5xl font-display font-bold text-text">Niche Packs</h1>
                <p class="mt-4 text-lg text-text/70">Pre‑tuned presets for hooks, scripts, and scene lists—built for your audience.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-xl font-bold text-text mb-2">Fitness</h3>
                    <p class="text-sm text-text/60 mb-4">High‑energy hooks, transformation scripts, workout scene lists.</p>
                    <ul class="space-y-2 text-sm text-text/70">
                        <li>• High‑Energy Hook Preset</li>
                        <li>• Transformation Script Preset</li>
                        <li>• Workout Scene List Preset</li>
                    </ul>
                </div>

                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-xl font-bold text-text mb-2">Finance</h3>
                    <p class="text-sm text-text/60 mb-4">Clarity hooks, myth‑busting scripts, visual breakdown prompts.</p>
                    <ul class="space-y-2 text-sm text-text/70">
                        <li>• Clarity Hook Preset</li>
                        <li>• Myth‑Busting Script Preset</li>
                        <li>• Visual Breakdown Scene Preset</li>
                    </ul>
                </div>

                <div class="card p-6 rounded-2xl bg-card border border-primary/10">
                    <h3 class="text-xl font-bold text-text mb-2">SaaS</h3>
                    <p class="text-sm text-text/60 mb-4">Problem/solution hooks, demo scripts, feature scene prompts.</p>
                    <ul class="space-y-2 text-sm text-text/70">
                        <li>• Problem/Solution Hook Preset</li>
                        <li>• Demo Script Preset</li>
                        <li>• Feature Scene Prompt Preset</li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="rounded-2xl p-6 border border-border bg-surface/40">
                    <h4 class="text-base font-semibold text-text">Included Tools</h4>
                    <p class="mt-2 text-sm text-text/60">Each pack ships as presets inside these tools:</p>
                    <ul class="mt-3 space-y-2 text-sm text-text/70">
                        <li>• YouTube Hook Generator</li>
                        <li>• Script Generator (Short)</li>
                        <li>• Scene Splitter (Video Factory)</li>
                    </ul>
                </div>
                <div class="rounded-2xl p-6 border border-border bg-surface/40">
                    <h4 class="text-base font-semibold text-text">How it works</h4>
                    <p class="mt-2 text-sm text-text/60">Sign up, open a tool, then select a preset from the Presets panel to apply the pack.</p>
                    <p class="mt-2 text-xs text-text/50">Packs are public presets and can be customized.</p>
                </div>
                <div class="rounded-2xl p-6 border border-border bg-surface/40">
                    <h4 class="text-base font-semibold text-text">Get started</h4>
                    <p class="mt-2 text-sm text-text/60">Pick a niche and start generating content in seconds.</p>
                    <div class="mt-4 flex flex-col gap-3">
                        @auth
                            <a href="{{ route('tools.index') }}" class="btn btn-primary">Open tools</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">Create free account</a>
                        @endauth
                        <a href="{{ route('pricing') }}" class="btn btn-ghost">See pricing</a>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-wrap gap-3 justify-center">
                <a href="{{ route('tools.show', 'youtube-hook-generator') }}" class="btn btn-secondary">Hook Presets</a>
                <a href="{{ route('tools.show', 'script-generator-short') }}" class="btn btn-secondary">Script Presets</a>
                <a href="{{ route('tools.show', 'scene-splitter-video-factory') }}" class="btn btn-secondary">Scene Presets</a>
            </div>
        </div>
    </div>
</x-public-layout>
