<div class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-6 mb-2">Core</div>

<a href="{{ route('admin.dashboard') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 8.25h-2.25A2.25 2.25 0 0113.5 6V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
    </svg>
    Dashboard
</a>

<a href="{{ route('admin.users.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
    </svg>
    Users
</a>

<a href="{{ route('admin.tools.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.tools.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
    </svg>
    Tools
</a>

<a href="{{ route('admin.workflows.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.workflows.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
        </path>
    </svg>
    Workflows
</a>

<div class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-8 mb-2">Content</div>

<a href="{{ route('admin.categories.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
    </svg>
    Categories
</a>

<a href="{{ route('admin.blog.generator') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.blog.generator') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
    Blog Generator
</a>

<a href="{{ route('admin.blog-posts.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.blog-posts.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
    Manage Posts
</a>

<a href="{{ route('admin.comments.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
    </svg>
    Comments
</a>

<a href="{{ route('admin.messages.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
    </svg>
    Messages
</a>

<div class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-8 mb-2">Insights</div>

<a href="{{ route('admin.tools.analytics') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.tools.analytics') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3v18h18M7 15l4-4 3 3 5-6" />
    </svg>
    Tool Analytics
</a>

<a href="{{ route('admin.profitability.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.profitability.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3v18h18M7 15l3-3 3 2 4-6" />
    </svg>
    Profitability
</a>

<a href="{{ route('admin.credits.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.credits.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v18m9-9H3" />
    </svg>
    Credit Packs
</a>

<a href="{{ route('admin.logs.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.logs.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
    Activity Logs
</a>

<a href="{{ route('admin.audit-logs.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
    </svg>
    Audit Logs
</a>

<div class="text-xs font-semibold leading-6 text-text-muted uppercase tracking-wider mt-8 mb-2">
    System</div>

<a href="{{ route('admin.themes.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.themes.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
    </svg>
    Themes
</a>

<a href="{{ route('admin.settings.index') }}"
    class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-primary/10 text-primary' : 'text-text-muted hover:text-text hover:bg-surface' }}">
    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    Settings
</a>

<div class="mt-auto pt-6">
    <a href="{{ route('dashboard') }}"
        class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-text-muted hover:text-text hover:bg-surface transition-colors">
        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
        </svg>
        Back to App
    </a>
</div>