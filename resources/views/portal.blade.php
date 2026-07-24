<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HR Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a0a1a;
            position: relative;
        }

        /* Background image with dark overlay */
        .bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                linear-gradient(135deg, rgba(8,8,30,0.92) 0%, rgba(15,15,45,0.7) 50%, rgba(20,20,55,0.5) 100%),
                url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1920&q=80');
            background-size: cover;
            background-position: center;
        }

        /* Content */
        .portal {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 32px 24px;
            animation: fadeIn 0.8s ease-out both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo icon */
        .logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
            animation: fadeIn 0.8s ease-out 0.1s both;
        }

        .logo svg {
            width: 32px;
            height: 32px;
            color: #fff;
        }

        /* Title */
        h1 {
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 6px;
            animation: fadeIn 0.8s ease-out 0.15s both;
        }

        .sub {
            font-size: clamp(0.85rem, 1.4vw, 1rem);
            font-weight: 400;
            color: rgba(255,255,255,0.4);
            margin-bottom: 36px;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        /* Login button */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            border: none;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            box-shadow: 0 8px 28px rgba(99, 102, 241, 0.35);
            transition: all 0.25s ease;
            animation: fadeIn 0.8s ease-out 0.25s both;
            min-width: 170px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(99, 102, 241, 0.5);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-arrow {
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .btn:hover .btn-arrow {
            transform: translateX(3px);
        }

        /* Footer */
        .footer {
            margin-top: 48px;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.15);
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        @media (max-width: 480px) {
            .btn { width: 100%; max-width: 280px; }
        }
    </style>
</head>
<body>
    <div class="bg"></div>

    <main class="portal">
        <div class="logo" aria-hidden="true">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="10" width="30" height="22" rx="3" stroke="currentColor" stroke-width="2"/>
                <path d="M14 36h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M21 32v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <circle cx="36" cy="12" r="7" stroke="currentColor" stroke-width="2"/>
                <circle cx="36" cy="12" r="2.5" fill="currentColor"/>
                <path d="M36 6.5v1.8M36 15.7v1.8M42 12h-1.8M31.8 12H30M40 8.5l-1.2 1.2M33.2 14.3l-1.2 1.2M40 15.5l-1.2-1.2M33.2 9.7l-1.2-1.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>

        <h1>HR Management System</h1>
        <p class="sub">Streamline your workforce management</p>

        <a href="{{ route('login') }}" class="btn">
            Sign In
            <span class="btn-arrow">→</span>
        </a>

        <p class="footer">&copy; {{ date('Y') }} HRMS &mdash; Human Resource Management System</p>
    </main>
</body>
</html>
