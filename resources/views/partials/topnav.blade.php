@php
    $searchConfig = match (true) {
        request()->routeIs('payroll.*') => [
            'action' => route('payroll.index'),
            'name' => 'search',
            'placeholder' => 'Search payroll records...',
            'value' => request('search'),
        ],
        request()->routeIs('schedule.*') => [
            'action' => route('schedule.index'),
            'name' => 'search',
            'placeholder' => 'Search schedules...',
            'value' => request('search'),
        ],
        request()->routeIs('leave-requests.*') => [
            'action' => route('leave-requests.index'),
            'name' => 'search',
            'placeholder' => 'Search leave requests...',
            'value' => request('search'),
        ],
        default => [
            'action' => route('employees.index'),
            'name' => 'search',
            'placeholder' => 'Search employees...',
            'value' => request('search'),
        ],
    };
@endphp

<header class="fixed top-0 right-0 left-[260px] h-16 bg-surface flex justify-between items-center px-margin-desktop border-b border-surface-container-high z-40">
    <div class="flex items-center gap-4 flex-1">
        <form action="{{ $searchConfig['action'] }}" method="GET" class="relative w-full max-w-md">
            @if (request()->routeIs('employees.*'))
                @if (request('department'))
                    <input type="hidden" name="department" value="{{ request('department') }}">
                @endif
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            @endif
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
            <input
                class="w-full bg-surface-container-low border border-outline-variant rounded-lg py-2 pl-10 pr-4 text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                id="topbar-search"
                name="{{ $searchConfig['name'] }}"
                placeholder="{{ $searchConfig['placeholder'] }}"
                type="text"
                value="{{ $searchConfig['value'] }}"
            >
        </form>
    </div>
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-4">
            <button class="p-2 text-secondary hover:bg-surface-container-low rounded-full transition-colors" type="button" title="Notifications">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <a href="{{ route('settings.index') }}" class="p-2 text-secondary hover:bg-surface-container-low rounded-full transition-colors" title="Settings">
                <span class="material-symbols-outlined">settings</span>
            </a>
        </div>
        <div class="h-8 w-[1px] bg-outline-variant"></div>
        @auth
            <a href="{{ route('profile.index') }}" class="flex items-center gap-2 text-secondary hover:text-primary transition-colors no-underline">
                <span class="material-symbols-outlined text-[20px]">account_circle</span>
                <span class="font-label-md text-label-md">{{ Auth::user()->name }}</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-2 text-secondary hover:text-primary transition-colors no-underline">
                <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                <span class="font-label-md text-label-md">Admin Panel</span>
            </a>
        @endauth
    </div>
</header>
