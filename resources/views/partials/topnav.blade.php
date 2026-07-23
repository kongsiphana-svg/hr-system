<header class="fixed top-0 right-0 left-sidebar-width h-16 bg-surface border-b border-surface-container-highest flex items-center justify-between px-8 z-40">


    <div class="flex items-center gap-3">
        <button type="button" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors relative">
            <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
            @if(($unreadNotifications ?? 0) > 0)
                <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
            @endif
        </button>

        <button type="button" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
        </button>

        <div class="h-6 w-px bg-surface-container-highest mx-2"></div>

        <div class="flex items-center gap-2">
            <span class="text-on-surface font-label-md">{{ strtoupper(app()->getLocale()) }}</span>
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]" data-icon="expand_more">expand_more</span>
        </div>
    </div>
</header>