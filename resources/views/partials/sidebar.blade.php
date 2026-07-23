<aside class="fixed left-0 top-0 h-screen w-sidebar-width bg-tertiary dark:bg-tertiary-container flex flex-col h-full overflow-y-auto border-r border-outline-variant z-50 sidebar-scroll">
    <div class="px-6 py-8">
        <h1 class="font-headline-sm text-headline-sm font-bold text-on-tertiary tracking-tight">HRMS Portal</h1>
        <p class="text-on-tertiary-container font-label-sm mt-1 uppercase tracking-widest">Employee Center</p>
    </div>

    <nav class="flex-1 flex flex-col gap-1 mt-4">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-6 py-3 relative group transition-colors
           {{ request()->routeIs('dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-tertiary-container hover:bg-tertiary-fixed-variant' }}">
            @if(request()->routeIs('dashboard'))
                <div class="sidebar-active-indicator"></div>
            @endif
            <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? '' : 'group-hover:text-on-tertiary' }} transition-colors" data-icon="dashboard">dashboard</span>
            <span class="font-body-md {{ request()->routeIs('dashboard') ? 'font-semibold' : 'group-hover:text-on-tertiary' }} transition-colors">Dashboard</span>
        </a>

        {{-- My Schedule --}}
        <a href="{{ route('schedule.index') }}"
           class="flex items-center gap-3 px-6 py-3 relative group transition-colors
           {{ request()->routeIs('schedule.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-tertiary-container hover:bg-tertiary-fixed-variant' }}">
            @if(request()->routeIs('schedule.*'))
                <div class="sidebar-active-indicator"></div>
            @endif
            <span class="material-symbols-outlined {{ request()->routeIs('schedule.*') ? '' : 'group-hover:text-on-tertiary' }} transition-colors" data-icon="calendar_month">calendar_month</span>
            <span class="font-body-md {{ request()->routeIs('schedule.*') ? 'font-semibold' : 'group-hover:text-on-tertiary' }} transition-colors">My Schedule</span>
        </a>

        {{-- Leave Requests --}}
        <a href="{{ route('leave.index') }}"
           class="flex items-center gap-3 px-6 py-3 relative group transition-colors
           {{ request()->routeIs('leave.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-tertiary-container hover:bg-tertiary-fixed-variant' }}">
            @if(request()->routeIs('leave.*'))
                <div class="sidebar-active-indicator"></div>
            @endif
            <span class="material-symbols-outlined {{ request()->routeIs('leave.*') ? '' : 'group-hover:text-on-tertiary' }} transition-colors" data-icon="event_busy">event_busy</span>
            <span class="font-body-md {{ request()->routeIs('leave.*') ? 'font-semibold' : 'group-hover:text-on-tertiary' }} transition-colors">Leave Requests</span>
        </a>

        {{-- My Profile --}}
        <a href="{{ route('employee-profile.show') }}"
           class="flex items-center gap-3 px-6 py-3 relative group transition-colors
           {{ request()->routeIs('employee-profile.*') ? 'bg-primary-container text-on-primary-container' : 'text-on-tertiary-container hover:bg-tertiary-fixed-variant' }}">
            @if(request()->routeIs('employee-profile.*'))
                <div class="sidebar-active-indicator"></div>
            @endif
            <span class="material-symbols-outlined {{ request()->routeIs('employee-profile.*') ? '' : 'group-hover:text-on-tertiary' }} transition-colors" data-icon="person">person</span>
            <span class="font-body-md {{ request()->routeIs('employee-profile.*') ? 'font-semibold' : 'group-hover:text-on-tertiary' }} transition-colors">My Profile</span>
        </a>

    </nav>

    <div class="mt-auto p-6 border-t border-tertiary-container">
        @php
            $sidebarProfile = session('created_employee_profile', []);
            $sidebarName    = $sidebarProfile['name'] ?? auth()->user()->name ?? 'Guest User';
            $sidebarTitle   = $sidebarProfile['title'] ?? 'Employee';
            $sidebarAvatar  = $sidebarProfile['avatar_url'] ?? null;
        @endphp
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg overflow-hidden bg-surface-container-highest flex-shrink-0 flex items-center justify-center">
                @if ($sidebarAvatar)
                    <img class="w-full h-full object-cover" src="{{ $sidebarAvatar }}" alt="{{ $sidebarName }}">
                @else
                    <span class="material-symbols-outlined text-on-tertiary-container">account_circle</span>
                @endif
            </div>
            <div class="overflow-hidden">
                <p class="text-on-tertiary font-label-md truncate">{{ $sidebarName }}</p>
                <p class="text-on-tertiary-container text-label-sm truncate">{{ $sidebarTitle }}</p>
            </div>
        </div>
    </div>
</aside>