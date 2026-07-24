<header class="fixed top-0 right-0 left-[260px] h-16 bg-surface flex justify-between items-center px-margin-desktop border-b border-surface-container-high z-40">
    <div class="flex items-center gap-4 flex-1">
        <h2 class="font-title-lg text-title-lg text-on-surface">@yield('page-title', 'Employee Portal')</h2>
    </div>
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.settings') }}" class="p-2 text-secondary hover:bg-surface-container-low rounded-full transition-colors" title="Settings">
                <span class="material-symbols-outlined">settings</span>
            </a>
        </div>
        <div class="h-8 w-[1px] bg-outline-variant"></div>
        @auth
            <div class="flex items-center gap-2 text-secondary">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-label-md font-bold">
                    {{ Auth::user()->initials() }}
                </div>
                <span class="font-label-md text-label-md">{{ Auth::user()->name }}</span>
            </div>
        @endauth
    </div>
</header>
