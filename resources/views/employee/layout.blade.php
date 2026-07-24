<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Portal') — HR Portal</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "surface-container-high": "#e6e8ea",
              "secondary-fixed": "#d3e4fe",
              "tertiary-fixed-dim": "#bec6e0",
              "on-tertiary-fixed-variant": "#3f465c",
              "on-secondary-fixed-variant": "#38485d",
              "on-error-container": "#93000a",
              "surface-container-highest": "#e0e3e5",
              "error-container": "#ffdad6",
              "surface-variant": "#e0e3e5",
              "surface-bright": "#f7f9fb",
              "surface-container-low": "#f2f4f6",
              "on-surface": "#191c1e",
              "on-error": "#ffffff",
              "on-tertiary": "#ffffff",
              "on-surface-variant": "#464553",
              "surface-container": "#eceef0",
              "surface": "#f7f9fb",
              "tertiary-fixed": "#dae2fd",
              "outline-variant": "#c8c4d5",
              "on-secondary-container": "#54647a",
              "on-primary": "#ffffff",
              "on-tertiary-fixed": "#131b2e",
              "inverse-surface": "#2d3133",
              "error": "#ba1a1a",
              "on-secondary": "#ffffff",
              "primary-fixed": "#e2dfff",
              "on-primary-container": "#a9a7ff",
              "surface-dim": "#d8dadc",
              "on-primary-fixed-variant": "#3b35a7",
              "tertiary": "#242c40",
              "primary-fixed-dim": "#c3c0ff",
              "surface-container-lowest": "#ffffff",
              "primary": "#1f108e",
              "tertiary-container": "#3b4257",
              "surface-tint": "#544fc0",
              "on-primary-fixed": "#0f0069",
              "secondary-container": "#d0e1fb",
              "outline": "#777584",
              "secondary-fixed-dim": "#b7c8e1",
              "background": "#f7f9fb",
              "secondary": "#505f76",
              "on-background": "#191c1e",
              "on-tertiary-container": "#a7afc8",
              "primary-container": "#3730a3",
              "on-secondary-fixed": "#0b1c30",
              "inverse-primary": "#c3c0ff",
              "inverse-on-surface": "#eff1f3",
            },
            borderRadius: {
              DEFAULT: "0.125rem",
              lg: "0.25rem",
              xl: "0.5rem",
              full: "0.75rem"
            },
            spacing: {
              "container-max": "1440px",
              "margin-mobile": "16px",
              "gutter": "16px",
              "margin-desktop": "32px",
              "sidebar-width": "260px",
              unit: "4px"
            },
            fontFamily: {
              sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"],
            },
            fontSize: {
              "headline-sm": ["20px", {lineHeight: "28px", fontWeight: "600"}],
              "body-md": ["14px", {lineHeight: "20px", fontWeight: "400"}],
              "label-md": ["12px", {lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600"}],
              "body-lg": ["16px", {lineHeight: "24px", fontWeight: "400"}],
              "headline-md": ["24px", {lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600"}],
              "body-sm": ["13px", {lineHeight: "18px", fontWeight: "400"}],
              "display-lg": ["36px", {lineHeight: "44px", letterSpacing: "-0.02em", fontWeight: "700"}],
              "label-sm": ["11px", {lineHeight: "14px", fontWeight: "500"}],
              "title-lg": ["18px", {lineHeight: "24px", fontWeight: "600"}]
            }
          },
        },
      }
    </script>
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-background min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        {{-- Employee Sidebar --}}
        @include('employee.partials.sidebar')

        {{-- Main Content Area --}}
        <div class="flex-1 ml-[260px]">
            {{-- Top Navigation --}}
            @include('employee.partials.topnav')

            <main class="p-margin-desktop max-w-container-max mx-auto pb-12 pt-20">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 font-body-md">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-5 py-4 font-body-md">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            {{ session('info') }}
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4 font-body-md">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-600">error</span>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
</body>
</html>
