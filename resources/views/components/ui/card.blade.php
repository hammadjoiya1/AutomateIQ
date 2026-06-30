@props([
    'padding'      => 'p-6',
    'hoverEffect'  => false,
    'as'           => 'div',
])

@php
    // Base: surface bg, design-token border, control-room radius (10px)
    $base = 'bg-[var(--color-surface)] border border-[var(--color-border)] relative overflow-hidden';

    // hover handled by motion-presets.js initCardHover() via [data-motion-card]
    // no CSS transitions on transform — motion lib owns that
    $classes = $base;
@endphp

<{{ $as }}
    style="border-radius: var(--radius-md)"
    {{ $hoverEffect ? 'data-motion-card' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}>

    <div class="relative z-10 {{ $padding }}">
        {{ $slot }}
    </div>
</{{ $as }}>
