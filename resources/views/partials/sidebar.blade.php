<aside class="w-[260px] h-screen fixed left-0 top-0 bg-tertiary flex flex-col py-6 border-r border-outline-variant z-50">
    <a href="{{ route('dashboard') }}" class="px-6 mb-8 flex items-center gap-3 no-underline">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-lg">corporate_fare</span>
        </div>
        <div>
            <h1 class="font-headline-sm text-headline-sm text-on-tertiary leading-none">HR Portal</h1>
            <p class="text-[10px] uppercase tracking-wider text-on-tertiary/50 mt-1">Admin Console</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard', 'match' => 'dashboard'],
                ['label' => 'Employees', 'icon' => 'groups', 'route' => 'employees.index', 'match' => 'employees.*'],
                ['label' => 'Schedule', 'icon' => 'calendar_month', 'route' => 'schedule.index', 'match' => 'schedule.*'],
                ['label' => 'Payroll', 'icon' => 'payments', 'route' => 'payroll.index', 'match' => 'payroll.*'],
                ['label' => 'Leave Requests', 'icon' => 'event_busy', 'route' => 'leave-requests.index', 'match' => 'leave-requests.*'],
                ['label' => 'Settings', 'icon' => 'settings', 'route' => 'settings.index', 'match' => 'settings.*'],
            ];
        @endphp

        @foreach ($navItems as $item)
            @php
                $isActive = Route::has($item['route']) && request()->routeIs($item['match']);
            @endphp
            <a
                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                class="flex items-center gap-3 px-4 py-3 font-body-md text-body-md transition-colors duration-200
                    {{ $isActive
                        ? 'border-l-4 border-primary bg-on-tertiary-fixed-variant text-on-tertiary'
                        : 'text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50 border-l-4 border-transparent' }}"
            >
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-3 pt-4 mt-auto border-t border-white/10 space-y-1">
        @auth
            <a
                href="{{ route('profile.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50 font-body-md text-body-md"
            >
                <span class="material-symbols-outlined">person</span>
                Profile
            </a>
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
        @else
            <a
                href="{{ route('login') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50 font-body-md text-body-md"
            >
                <span class="material-symbols-outlined">login</span>
                Login
            </a>
        @endauth
    </div>
</aside>
