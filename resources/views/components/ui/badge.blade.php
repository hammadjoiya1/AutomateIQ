@props([
    'variant' => 'default', // default | signal | accent | warn | muted
])

@php
    // Radius: radius-sm (6px). No pills.
    // accent (red) = live/active. signal (green) = completed/success.
    $base = 'inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium border';

    $variants = [
        'default' => 'bg-[var(--color-surface-raised)] text-[var(--color-text-muted)] border-[var(--color-border)]',
        'signal'  => 'bg-[var(--color-signal-dim)]    text-[var(--color-signal)]      border-[var(--color-signal)]/30',
        'accent'  => 'bg-[var(--color-accent-dim)]    text-[var(--color-accent)]       border-[var(--color-accent)]/30',
        'warn'    => 'bg-[var(--color-warn)]/10        text-[var(--color-warn)]         border-[var(--color-warn)]/30',
        'muted'   => 'bg-transparent                  text-[var(--color-text-muted)]   border-[var(--color-border)]',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span style="border-radius: var(--radius-sm)" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
