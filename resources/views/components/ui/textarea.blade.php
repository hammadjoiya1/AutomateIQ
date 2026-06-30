@props([
    'disabled' => false,
])

@php
    $base = 'flex w-full px-4 py-3 text-sm min-h-[100px] resize-y
             bg-[var(--color-surface)] text-[var(--color-text)]
             border border-[var(--color-border)]
             placeholder:text-[var(--color-text-muted)]
             focus:outline-none focus:border-[var(--color-accent-dim)]
             transition-colors duration-150
             disabled:cursor-not-allowed disabled:opacity-50';
@endphp

<textarea
    style="border-radius: var(--radius-sm)"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => $base]) !!}>{{ $slot }}</textarea>
