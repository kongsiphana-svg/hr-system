<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HR Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        :root {
            --hr-indigo: #3730a3;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        .main-page {
            min-height: 100vh;
            background-color: var(--hr-indigo);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .main-page__icon {
            width: 84px;
            height: 78px;
            margin-bottom: 1.25rem;
            opacity: 0;
            transform: translateY(12px);
            animation: rise-in 0.6s ease forwards;
        }

        .main-page__icon img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .main-page__title {
            color: #ffffff;
            font-size: clamp(2rem, 5vw, 3.75rem);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            line-height: 1.15;
            max-width: 843px;
            margin: 0 0 2rem;
            opacity: 0;
            transform: translateY(12px);
            animation: rise-in 0.6s ease 0.12s forwards;
        }

        .main-page__actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 22px;
            opacity: 0;
            transform: translateY(12px);
            animation: rise-in 0.6s ease 0.24s forwards;
        }

        .main-page__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 191px;
            height: 50px;
            padding: 13px 17px;
            border-radius: 8px;
            background: #ffffff;
            color: var(--hr-indigo);
            font-size: 16px;
            line-height: 24px;
            font-weight: 400;
            text-decoration: none;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .main-page__btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            background-color: #f8fafc;
        }

        @keyframes rise-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .main-page__btn {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <main class="main-page" data-node-id="18:2">
        <div class="main-page__icon" data-node-id="18:11">
            <img
                src="{{ asset('images/hr-monitor-icon.svg') }}"
                alt=""
                width="84"
                height="78"
            >
        </div>

        <h1 class="main-page__title" data-node-id="18:3">
            HR Management System
        </h1>

        <div class="main-page__actions" data-node-id="18:4">
            <a href="{{ route('register') }}" class="main-page__btn" data-node-id="18:5">
                Create Account
            </a>
            <a href="{{ route('login') }}" class="main-page__btn" data-node-id="18:8">
                Log In
            </a>
        </div>
    </main>
</body>
</html>
