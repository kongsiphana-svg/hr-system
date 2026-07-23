<header class="fixed top-0 right-0 left-sidebar-width h-16 bg-surface border-b border-surface-container-highest flex items-center justify-between px-8 z-40">

    <div class="flex items-center gap-4">
        <form action="{{ route('employees.search') }}" method="GET" class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" data-icon="search">search</span>
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="bg-surface-container-low border-none rounded-full py-2 pl-10 pr-4 w-64 text-label-md focus:ring-2 focus:ring-surface-tint transition-all outline-none"
                placeholder="Search employee records...">
        </form>
    </div>

    <div class="flex items-center gap-3">
        <button type="button" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors relative">
            <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
            @if(($unreadNotifications ?? 0) > 0)
                <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
            @endif
        </button>

        <button type="button" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
        </button>

        <div class="h-6 w-px bg-surface-container-highest mx-2"></div>

        <div class="flex items-center gap-2">
            <span class="text-on-surface font-label-md">{{ strtoupper(app()->getLocale()) }}</span>
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]" data-icon="expand_more">expand_more</span>
        </div>
    </div>
</header>