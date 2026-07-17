<header class="sticky top-0 z-30 flex shrink-0 items-center gap-4 border-b border-slate-200 bg-white px-6 py-3.5 lg:px-8">
    <div class="relative max-w-xl flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
            @include('Payroll.partials.icons', ['name' => 'search', 'class' => 'h-4 w-4'])
        </span>
        <input
            type="search"
            placeholder="Search employees or reports..."
            class="w-full rounded-lg border-0 bg-slate-100 py-2.5 pr-4 pl-10 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none"
        >
    </div>

    <div class="ml-auto flex items-center gap-1 sm:gap-2">
        <button type="button" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700" aria-label="Notifications">
            @include('Payroll.partials.icons', ['name' => 'bell', 'class' => 'h-5 w-5'])
        </button>
        <button type="button" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700" aria-label="Help">
            @include('Payroll.partials.icons', ['name' => 'help', 'class' => 'h-5 w-5'])
        </button>

        <div class="ml-2 flex items-center gap-3 border-l border-slate-200 pl-4">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-900">Admin Panel</p>
                <p class="text-xs text-slate-500">System Administrator</p>
            </div>
            <img
                src="https://i.pravatar.cc/80?img=47"
                alt="Admin avatar"
                class="h-10 w-10 rounded-full object-cover ring-2 ring-slate-100"
            >
        </div>
    </div>
</header>
