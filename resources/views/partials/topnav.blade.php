<header class="fixed top-0 right-0 left-[260px] h-16 bg-surface flex justify-between items-center px-margin-desktop border-b border-surface-container-high z-40">
    <div class="flex items-center gap-4 flex-1">
        <form action="{{ route('employees.index') }}" method="GET" class="relative w-full max-w-md">
            @if (request('department'))
                <input type="hidden" name="department" value="{{ request('department') }}">
            @endif
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
            <input
                class="w-full bg-surface-container-low border border-outline-variant rounded-lg py-2 pl-10 pr-4 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                id="topbar-search"
                name="search"
                placeholder="Search employees..."
                type="text"
                value="{{ request('search') }}"
            >
        </form>
    </div>
    <div class="flex items-center gap-6">
<<<<<<< HEAD
        
=======
        <div class="flex items-center gap-4">
            <button class="p-2 text-secondary hover:bg-surface-container-low rounded-full transition-colors" type="button" title="Notifications (not yet configured)">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-2 text-secondary hover:bg-surface-container-low rounded-full transition-colors" type="button" title="Help">
                <span class="material-symbols-outlined">help</span>
            </button>
        </div>
>>>>>>> origin/feat/fe-employee-visal
        <div class="h-8 w-[1px] bg-outline-variant"></div>
        <div class="flex items-center gap-2 text-secondary">
            <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
            <span class="font-label-md text-label-md">Admin Panel</span>
        </div>
    </div>
</header>