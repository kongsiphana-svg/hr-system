<header class="sticky top-0 z-30 flex shrink-0 items-center justify-between gap-4 border-b border-gray-200 bg-white px-6 py-4 lg:px-8">
    <h2 class="text-2xl font-bold tracking-tight" style="color: #1e1b6b;">
        @yield('page-title', 'Work Schedule')
    </h2>

    <div class="flex items-center gap-4 sm:gap-6">
        <div class="relative hidden w-72 max-w-md md:block lg:w-96">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                @include('Schedule.partials.icons', ['name' => 'search', 'class' => 'h-4 w-4'])
            </span>
            <input
                type="search"
                placeholder="Search employees or shifts..."
                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pr-4 pl-10 text-sm text-slate-700 placeholder:text-slate-400 outline-none transition-all focus:border-transparent focus:ring-2"
                style="--tw-ring-color: #3b28cc;"
            >
        </div>

        <div class="flex items-center gap-3 text-slate-500">
            <button type="button" class="relative rounded-lg p-1.5 transition-colors hover:text-slate-700" aria-label="Notifications">
                @include('Schedule.partials.icons', ['name' => 'bell', 'class' => 'h-5 w-5'])
                <span class="absolute top-1 right-1 block h-2 w-2 rounded-full border-2 border-white bg-red-500"></span>
            </button>
            <button type="button" class="rounded-lg p-1.5 transition-colors hover:text-slate-700" aria-label="Help">
                @include('Schedule.partials.icons', ['name' => 'help', 'class' => 'h-5 w-5'])
            </button>
        </div>

        <div class="hidden h-8 w-px bg-gray-200 sm:block"></div>

        <div class="flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800">Alex Rivera</p>
                <p class="text-xs text-slate-500">Admin Panel</p>
            </div>
            <img
                src="https://i.pravatar.cc/80?img=12"
                alt="Admin avatar"
                class="h-10 w-10 rounded-lg object-cover border border-gray-100"
            >
        </div>
    </div>
</header>
