<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Work Schedule') — HR Portal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/schedule.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            schedule: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        colors: {
                            'schedule-sidebar': '#1e293b',
                            'schedule-sidebar-hover': '#334155',
                            'schedule-active': '#334155',
                            'schedule-bg': '#f8fafc',
                            'schedule-accent': '#2a1ab9',
                            'schedule-accent-hover': '#1e118c',
                        },
                    },
                },
            };
        </script>
        <script src="{{ asset('js/schedule.js') }}" defer></script>
    @endif

    @stack('styles')
</head>
<body class="font-schedule antialiased bg-schedule-bg text-slate-900" style="font-family: Inter, ui-sans-serif, system-ui, sans-serif;">
    {{--
        Backend connection map (frontend-only placeholders):
        - GET    /schedule                     list / work schedule
        - GET    /schedule/calendar            weekly calendar grid
        - GET    /schedule/create              add new shift form
        - POST   /api/schedule                 create shift
        - GET    /api/schedule?week=YYYY-MM-DD list shifts for week
    --}}
    <div
        id="schedule-app"
        class="flex h-screen overflow-hidden"
        data-csrf="{{ csrf_token() }}"
        data-create-url="{{ url('/api/schedule') }}"
        data-list-url="{{ url('/api/schedule') }}"
    >
        @include('Schedule.partials.sidebar')

        <div class="flex min-h-0 min-w-0 flex-1 flex-col">
            @include('Schedule.partials.topbar')

            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
