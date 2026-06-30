@props([
    'disabled' => false,
    'icon'     => null,
])

@php
    // Focus: accent-dim border (NOT full accent — too loud per spec)
    $base = 'flex w-full border px-4 py-2.5 text-sm
             bg-[var(--color-surface)] text-[var(--color-text)]
             border-[var(--color-border)]
             placeholder:text-[var(--color-text-muted)]
             focus:outline-none focus:border-[var(--color-accent-dim)]
             transition-colors duration-150
             disabled:cursor-not-allowed disabled:opacity-50';

    if ($icon) {
        $base .= ' pl-10';
    }
@endphp

<div class="relative w-full">
    @if ($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[var(--color-text-muted)]">
            <x-dynamic-component :component="$icon" class="w-4 h-4" />
        </div>
    @endif

    <input
        {{ $disabled ? 'disabled' : '' }}
        style="border-radius: var(--radius-sm)"
        {!! $attributes->merge(['class' => $base]) !!}>
</div>
