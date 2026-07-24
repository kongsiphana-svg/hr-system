<aside class="w-[260px] h-screen fixed left-0 top-0 bg-tertiary flex flex-col py-6 border-r border-outline-variant z-50">
    <a href="{{ route('employee.dashboard') }}" class="px-6 mb-8 flex items-center gap-3 no-underline">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-lg">badge</span>
        </div>
        <div>
            <h1 class="font-headline-sm text-headline-sm text-on-tertiary leading-none">HR Portal</h1>
            <p class="text-[10px] uppercase tracking-wider text-on-tertiary/50 mt-1">Employee Portal</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'employee.dashboard', 'match' => 'employee.dashboard'],
                ['label' => 'My Schedule', 'icon' => 'calendar_month', 'route' => 'employee.schedule', 'match' => 'employee.schedule*'],
                ['label' => 'My Payroll', 'icon' => 'payments', 'route' => 'employee.payroll', 'match' => 'employee.payroll*'],
                ['label' => 'My Leaves', 'icon' => 'event_busy', 'route' => 'employee.leaves', 'match' => 'employee.leave*'],
                ['label' => 'Settings', 'icon' => 'settings', 'route' => 'employee.settings', 'match' => 'employee.settings'],
            ];
        @endphp

        @foreach ($navItems as $item)
            @php
                $match = (array) $item['match'];
                $isActive = Route::has($item['route']) && request()->routeIs(...$match);
            @endphp
            <a
                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                class="flex items-center gap-3 px-4 py-3 font-body-md text-body-md transition-colors duration-200 rounded-lg
                    {{ $isActive
                        ? 'bg-on-tertiary-fixed-variant text-on-tertiary'
                        : 'text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50' }}"
            >
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-3 pt-4 mt-auto border-t border-white/10 space-y-1">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50 font-body-md text-body-md"
            >
                <span class="material-symbols-outlined">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>
