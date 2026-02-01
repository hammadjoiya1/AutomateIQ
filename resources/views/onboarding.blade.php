<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="card p-8 border border-white/5 bg-surface/50">
            <h1 class="text-3xl font-bold text-text mb-4">Get your first win in 5 minutes</h1>
            <p class="text-text/60 mb-8">Complete these steps to see immediate value and unlock your workflow.</p>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-background/50 border border-border">
                    <div class="font-semibold text-text">1) Run your first AI tool</div>
                    <p class="text-sm text-text/60 mt-2">Generate hooks, viral ideas, or short scripts in seconds.</p>
                    <a href="{{ route('tools.index') }}" class="text-primary text-sm">Open Tools</a>
                </div>
                <div class="p-4 rounded-xl bg-background/50 border border-border">
                    <div class="font-semibold text-text">2) Save to Library</div>
                    <p class="text-sm text-text/60 mt-2">Keep and reuse your best outputs.</p>
                    <a href="{{ route('library.index') }}" class="text-primary text-sm">Open Library</a>
                </div>
                <div class="p-4 rounded-xl bg-background/50 border border-border">
                    <div class="font-semibold text-text">3) Automate with a workflow</div>
                    <p class="text-sm text-text/60 mt-2">Schedule your content pipeline.</p>
                    <a href="{{ route('workflows.create') }}" class="text-primary text-sm">Create Workflow</a>
                </div>
            </div>

            <form method="POST" action="{{ route('onboarding.complete') }}" class="mt-8">
                @csrf
                <button class="btn btn-primary">Mark Onboarding Complete</button>
            </form>
        </div>
    </div>
</x-app-layout>
