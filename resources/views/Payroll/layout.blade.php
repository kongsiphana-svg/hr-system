<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Payroll Management') — HR Portal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            payroll: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        colors: {
                            'payroll-sidebar': '#1e293b',
                            'payroll-sidebar-hover': '#334155',
                            'payroll-active': '#334155',
                            'payroll-bg': '#f8fafc',
                        },
                    },
                },
            };
        </script>
    @endif

    @stack('styles')
</head>
<body class="font-payroll antialiased bg-payroll-bg text-slate-900">
    {{--
        Backend connection map (frontend-only placeholders):
        - POST   /api/payroll/process          { pay_period }
        - GET    /api/payroll?pay_period=YYYY-MM
        - GET    /api/payroll/{id}/payslip
        - GET    /payroll                      list / management
        - GET    /payroll/process              process UI
    --}}
    <div
        id="payroll-app"
        class="flex min-h-screen"
        data-csrf="{{ csrf_token() }}"
        data-process-url="{{ url('/api/payroll/process') }}"
        data-list-url="{{ url('/api/payroll') }}"
        data-payslip-url-template="{{ url('/api/payroll') }}/:id/payslip"
    >
        @include('Payroll.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('Payroll.partials.topbar')

            <main class="flex-1 overflow-auto px-6 py-8 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
