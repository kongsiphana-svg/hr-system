@php
    $currentRoute = request()->route()?->getName();
    $scheduleActive = in_array($currentRoute, ['schedule.index', 'schedule.calendar', 'schedule.create'], true);

    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'active' => false, 'url' => '#'],
        ['label' => 'Employees', 'icon' => 'employees', 'active' => false, 'url' => '#'],
        ['label' => 'Schedule', 'icon' => 'schedule', 'active' => $scheduleActive, 'url' => route('schedule.index')],
        ['label' => 'Payroll', 'icon' => 'payroll', 'active' => false, 'url' => '#'],
        ['label' => 'Leave Requests', 'icon' => 'leave', 'active' => false, 'url' => '#'],
        ['label' => 'Settings', 'icon' => 'settings', 'active' => false, 'url' => '#'],
    ];
@endphp

<aside class="flex h-full w-64 shrink-0 flex-col overflow-y-auto bg-schedule-sidebar text-white" style="background-color: #1e293b;">
    <div class="px-6 pt-7 pb-6">
        <h1 class="text-xl font-bold tracking-tight">HR Portal</h1>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Admin Console</p>
    </div>

    <nav class="flex-1 space-y-1 px-4">
        @foreach ($navItems as $item)
            <a
                href="{{ $item['url'] }}"
                class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm transition-colors
                    {{ $item['active']
                        ? 'bg-schedule-active font-medium text-white'
                        : 'text-slate-400 hover:bg-schedule-sidebar-hover hover:text-white' }}"
                style="{{ $item['active'] ? 'background-color: #334155;' : '' }}"
            >
                @include('Schedule.partials.icons', ['name' => $item['icon'], 'class' => 'h-5 w-5 shrink-0'])
                <span class="font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto space-y-3 border-t border-slate-700/60 px-4 py-5">
        <a
            href="{{ route('schedule.create') }}"
            class="flex w-full items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-medium text-white transition-colors"
            style="background-color: #3121b1;"
            onmouseover="this.style.backgroundColor='#28189c'"
            onmouseout="this.style.backgroundColor='#3121b1'"
        >
            @include('Schedule.partials.icons', ['name' => 'plus', 'class' => 'h-4 w-4'])
            New Request
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-400 transition-colors hover:text-white">
            @include('Schedule.partials.icons', ['name' => 'logout', 'class' => 'h-5 w-5 shrink-0'])
            <span>Logout</span>
        </a>
    </div>
</aside>
