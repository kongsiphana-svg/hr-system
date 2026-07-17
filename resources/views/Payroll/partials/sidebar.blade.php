@php
    $currentRoute = request()->route()?->getName();
    $payrollActive = in_array($currentRoute, ['payroll.index', 'payroll.process'], true);

    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'dashboard', 'active' => false, 'url' => '#'],
        ['label' => 'Employees', 'icon' => 'employees', 'active' => false, 'url' => '#'],
        ['label' => 'Schedule', 'icon' => 'schedule', 'active' => false, 'url' => '#'],
        ['label' => 'Payroll', 'icon' => 'payroll', 'active' => $payrollActive, 'url' => route('payroll.index')],
        ['label' => 'Leave Requests', 'icon' => 'leave', 'active' => false, 'url' => '#'],
        ['label' => 'Settings', 'icon' => 'settings', 'active' => false, 'url' => '#'],
    ];
@endphp

<aside class="flex h-full w-60 shrink-0 flex-col overflow-y-auto bg-payroll-sidebar text-white">
    <div class="px-6 pt-7 pb-8">
        <h1 class="text-lg font-semibold tracking-tight">HR Portal</h1>
        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-[0.12em] text-slate-400">Admin Console</p>
    </div>

    <nav class="flex-1 space-y-1 px-3">
        @foreach ($navItems as $item)
            <a
                href="{{ $item['url'] }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors
                    {{ $item['active']
                        ? 'bg-payroll-active font-medium text-white'
                        : 'text-slate-300 hover:bg-payroll-sidebar-hover hover:text-white' }}"
            >
                @include('Payroll.partials.icons', ['name' => $item['icon'], 'class' => 'h-5 w-5 shrink-0'])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto space-y-1 border-t border-slate-700/60 px-3 py-5">
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition-colors hover:bg-payroll-sidebar-hover hover:text-white">
            <span class="flex h-5 w-5 items-center justify-center text-base font-medium leading-none">+</span>
            <span>New Request</span>
        </a>
        <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition-colors hover:bg-payroll-sidebar-hover hover:text-white">
            @include('Payroll.partials.icons', ['name' => 'logout', 'class' => 'h-5 w-5 shrink-0'])
            <span>Logout</span>
        </a>
    </div>
</aside>
