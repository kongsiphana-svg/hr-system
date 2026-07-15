<aside class="w-[260px] h-screen fixed left-0 top-0 bg-tertiary flex flex-col py-6 border-r border-outline-variant z-50">
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-lg">corporate_fare</span>
        </div>
        <h1 class="font-headline-sm text-headline-sm text-on-tertiary">HR Portal</h1>
    </div>


    <nav class="flex-1 space-y-1">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard'],
                ['label' => 'Employees', 'icon' => 'groups', 'route' => 'employees.index'],
                ['label' => 'Schedule', 'icon' => 'calendar_month', 'route' => 'schedule'],
                ['label' => 'Payroll', 'icon' => 'payments', 'route' => 'payroll'],
                ['label' => 'Leave Requests', 'icon' => 'event_busy', 'route' => 'leave-requests'],
                ['label' => 'Settings', 'icon' => 'settings', 'route' => 'settings'],
            ];
        @endphp

        @foreach ($navItems as $item)
            @php $isActive = Route::has($item['route']) && request()->routeIs($item['route'] . '*'); @endphp
            <a
                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                class="flex items-center gap-3 px-4 py-3 font-body-md text-body-md transition-colors duration-200
                    {{ $isActive
                        ? 'border-l-4 border-primary bg-on-tertiary-fixed-variant text-on-tertiary'
                        : 'text-on-tertiary/70 hover:bg-on-tertiary-fixed-variant/50' }}"
            >
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>