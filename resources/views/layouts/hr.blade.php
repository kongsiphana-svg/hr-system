<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HRMS Portal')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        try {
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        "colors": {
                            "surface-tint": "#544fc0",
                            "on-error": "#ffffff",
                            "on-primary": "#ffffff",
                            "on-secondary": "#ffffff",
                            "inverse-on-surface": "#eff1f3",
                            "inverse-primary": "#c3c0ff",
                            "outline-variant": "#c8c4d5",
                            "on-surface": "#191c1e",
                            "inverse-surface": "#2d3133",
                            "surface-container": "#eceef0",
                            "error": "#ba1a1a",
                            "surface-dim": "#d8dadc",
                            "error-container": "#ffdad6",
                            "tertiary": "#242c40",
                            "on-primary-fixed-variant": "#3b35a7",
                            "tertiary-fixed": "#dae2fd",
                            "on-tertiary-container": "#a7afc8",
                            "tertiary-fixed-dim": "#bec6e0",
                            "outline": "#777584",
                            "on-secondary-fixed": "#0b1c30",
                            "on-error-container": "#93000a",
                            "secondary-container": "#d0e1fb",
                            "on-tertiary-fixed-variant": "#3f465c",
                            "on-background": "#191c1e",
                            "surface-container-highest": "#e0e3e5",
                            "on-secondary-fixed-variant": "#38485d",
                            "surface-container-lowest": "#ffffff",
                            "on-primary-fixed": "#0f0069",
                            "on-secondary-container": "#54647a",
                            "secondary-fixed-dim": "#b7c8e1",
                            "on-tertiary": "#ffffff",
                            "surface": "#f7f9fb",
                            "surface-container-high": "#e6e8ea",
                            "on-tertiary-fixed": "#131b2e",
                            "surface-bright": "#f7f9fb",
                            "secondary": "#505f76",
                            "on-surface-variant": "#464553",
                            "primary-fixed": "#e2dfff",
                            "tertiary-container": "#3b4257",
                            "on-primary-container": "#a9a7ff",
                            "primary": "#1f108e",
                            "surface-variant": "#e0e3e5",
                            "background": "#f7f9fb",
                            "secondary-fixed": "#d3e4fe",
                            "primary-fixed-dim": "#c3c0ff",
                            "surface-container-low": "#f2f4f6",
                            "primary-container": "#3730a3"
                        },
                        "borderRadius": {
                            "DEFAULT": "0.125rem",
                            "lg": "0.25rem",
                            "xl": "0.5rem",
                            "full": "0.75rem"
                        },
                        "spacing": {
                            "margin-desktop": "32px",
                            "gutter": "16px",
                            "margin-mobile": "16px",
                            "unit": "4px",
                            "sidebar-width": "260px",
                            "container-max": "1440px"
                        },
                        "fontFamily": {
                            "display-lg": ["Inter"],
                            "body-lg": ["Inter"],
                            "headline-md": ["Inter"],
                            "title-lg": ["Inter"],
                            "headline-sm": ["Inter"],
                            "body-sm": ["Inter"],
                            "label-md": ["Inter"],
                            "label-sm": ["Inter"],
                            "body-md": ["Inter"]
                        },
                        "fontSize": {
                            "display-lg": ["36px", {"lineHeight": "44px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                            "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                            "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                            "title-lg": ["18px", {"lineHeight": "24px", "fontWeight": "600"}],
                            "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                            "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}],
                            "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                            "label-sm": ["11px", {"lineHeight": "14px", "fontWeight": "500"}],
                            "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
                        }
                    },
                },
            }
        } catch (_e) {}
    </script>

    @stack('styles')
</head>
<body class="font-body-md text-body-md">

    {{-- Side Navigation --}}
    @include('partials.sidebar')

    {{-- Top Navigation --}}
    @include('partials.topnav')

    {{-- Main Content Canvas --}}
    <main class="ml-sidebar-width pt-16 min-h-screen flex flex-col">
        @yield('content')
    </main>

    {{-- Micro-interaction Script --}}
    <script>
        document.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('focus', () => {
                const parent = el.closest('.group');
                if (parent) {
                    const icon = parent.querySelector('.material-symbols-outlined');
                    if (icon) icon.classList.add('text-primary');
                }
            });
            el.addEventListener('blur', () => {
                const parent = el.closest('.group');
                if (parent) {
                    const icon = parent.querySelector('.material-symbols-outlined');
                    if (icon) icon.classList.remove('text-primary');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>