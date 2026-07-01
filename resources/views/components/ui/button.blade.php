@props([
    'variant' => 'primary', // primary | secondary | ghost | danger
    'size'    => 'md',      // sm | md | lg
    'href'    => null,
])

@php
    // One primary (red accent) per screen. Everything else is secondary or ghost.
    $base = 'inline-flex items-center justify-center gap-2 font-medium whitespace-nowrap
             focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2
             focus-visible:ring-offset-[var(--color-bg)] focus-visible:ring-[var(--color-accent-dim)]
             disabled:pointer-events-none disabled:opacity-50
             select-none cursor-pointer';

    // Radius: var(--radius-sm) = 6px — no pills, equipment has edges
    $variants = [
        'primary'   => 'bg-[var(--color-accent)] text-white border border-transparent
                        hover:bg-[var(--primary-hover)] active:bg-[var(--primary-dark)]',
        'secondary' => 'bg-transparent text-[var(--color-text-muted)] border border-[var(--color-border)]
                        hover:border-[var(--color-text-muted)] hover:text-[var(--color-text)]',
        'ghost'     => 'bg-transparent text-[var(--color-text-muted)] border border-transparent
                        hover:text-[var(--color-text)] hover:bg-[var(--color-surface)]',
        'danger'    => 'bg-[var(--color-accent)]/10 text-[var(--color-accent)]
                        border border-[var(--color-accent-dim)]
                        hover:bg-[var(--color-accent)] hover:text-white',
    ];

    $sizes = [
        'sm' => 'h-8  px-3 text-xs',
        'md' => 'h-10 px-5 text-sm',
        'lg' => 'h-12 px-7 text-base',
    ];

    $classes = trim(preg_replace('/\s+/', ' ',
        $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md'])
    ));
@endphp

@if ($href)
    <a href="{{ $href }}"
       style="border-radius: var(--radius-sm)"
       data-motion-press
       {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button
       style="border-radius: var(--radius-sm)"
       data-motion-press
       {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
