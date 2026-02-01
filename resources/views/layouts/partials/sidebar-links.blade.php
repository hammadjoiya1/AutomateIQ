@if(Auth::check() && Auth::user()->isAdmin() && Route::has('admin.dashboard'))
    <li>
        <a href="{{ route('admin.dashboard') }}"
            class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors">
            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6M9 16h6M9 8h6" />
            </svg>
            Admin Panel
        </a>
    </li>
@endif

<li class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-6 mb-2">Overview</li>
<li>
    <a href="{{ route('dashboard') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-gray-50 text-primary' : 'text-gray-700 hover:text-primary hover:bg-gray-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
        </svg>
        Dashboard
    </a>
</li>
<li class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-6 mb-2">Build</li>
<li>
    <a href="{{ route('tools.index') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('tools.*') ? 'bg-gray-50 text-primary' : 'text-gray-700 hover:text-primary hover:bg-gray-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('tools.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
        </svg>
        AI Tools
    </a>
</li>
<li>
    <a href="{{ route('workflows.index') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('workflows.*') ? 'bg-gray-50 text-primary' : 'text-gray-700 hover:text-primary hover:bg-gray-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('workflows.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Workflows
    </a>
</li>
<li class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-6 mb-2">History</li>
<li>
    <a href="{{ route('tools.history') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('tools.history') ? 'bg-gray-50 text-primary' : 'text-gray-700 hover:text-primary hover:bg-gray-50' }}">
        <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('tools.history') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
        </svg>
        Generations
    </a>
</li>
<li>
    <a href="{{ route('brand-voices.index') }}"
        class="{{ request()->routeIs('brand-voices.*') ? 'bg-primary/10 text-primary' : 'text-text/70 hover:text-text hover:bg-white/5' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all">
        <svg class="{{ request()->routeIs('brand-voices.*') ? 'text-primary' : 'text-text/40 group-hover:text-text' }} h-6 w-6 shrink-0 transition-colors"
            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
        </svg>
        Brand Voices <span
            class="ml-auto text-xs font-bold text-accent bg-accent/10 px-2 py-0.5 rounded-full">Pro</span>
    </a>
</li>
<li class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-6 mb-2">Library</li>
<li>
    <a href="{{ route('library.index') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('library.*') ? 'bg-gray-50 text-primary' : 'text-gray-700 hover:text-primary hover:bg-gray-50' }}">
        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
        </svg>
        Library
    </a>
</li>