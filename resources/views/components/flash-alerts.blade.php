@php
    $alerts = [
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ];
@endphp

@if($errors->any() || collect($alerts)->filter()->isNotEmpty())
    <div {{ $attributes->merge(['class' => 'space-y-3 mb-6']) }}>
        @if($errors->any())
            <div x-data="{ show: true }" x-show="show"
                class="rounded-xl border border-danger/30 bg-danger/10 text-danger px-4 py-3 flex items-start justify-between gap-4">
                <div>
                    <div class="font-semibold">There were some problems with your submission:</div>
                    <ul class="mt-2 text-sm list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" @click="show = false" class="text-danger/80 hover:text-danger">
                    <span class="sr-only">Dismiss</span>
                    ✕
                </button>
            </div>
        @endif

        @foreach($alerts as $type => $message)
            @if($message)
                @php
                    $styles = match ($type) {
                        'success' => 'border-success/30 bg-success/10 text-success',
                        'error' => 'border-danger/30 bg-danger/10 text-danger',
                        'warning' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-500',
                        'info' => 'border-primary/30 bg-primary/10 text-primary',
                        default => 'border-border bg-surface text-text',
                    };
                @endphp
                <div x-data="{ show: true }" x-show="show"
                    class="rounded-xl border px-4 py-3 flex items-start justify-between gap-4 {{ $styles }}">
                    <div class="text-sm font-medium">{{ $message }}</div>
                    <button type="button" @click="show = false" class="opacity-70 hover:opacity-100">
                        <span class="sr-only">Dismiss</span>
                        ✕
                    </button>
                </div>
            @endif
        @endforeach
    </div>
@endif
