@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-muted bg-surface border border-border cursor-not-allowed leading-5 rounded-md dark:text-surface-raised dark:bg-text dark:border-text-muted">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-text bg-surface border border-border leading-5 rounded-md hover:text-text focus:outline-none focus:ring ring-gray-300 focus:border-accent active:bg-surface-raised active:text-text transition ease-in-out duration-150 dark:bg-text dark:border-text-muted dark:text-surface-raised dark:focus:border-accent dark:active:bg-text dark:active:text-surface-raised hover:bg-surface-raised dark:hover:bg-text dark:hover:text-surface-raised">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-text bg-surface border border-border leading-5 rounded-md hover:text-text focus:outline-none focus:ring ring-gray-300 focus:border-accent active:bg-surface-raised active:text-text transition ease-in-out duration-150 dark:bg-text dark:border-text-muted dark:text-surface-raised dark:focus:border-accent dark:active:bg-text dark:active:text-surface-raised hover:bg-surface-raised dark:hover:bg-text dark:hover:text-surface-raised">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-muted bg-surface border border-border cursor-not-allowed leading-5 rounded-md dark:text-surface-raised dark:bg-text dark:border-text-muted">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
