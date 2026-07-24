@extends('layouts.guest')

@section('title', 'Admin Create Account')

@section('content')
<style>
    :root {
        --auth-bg: #f3f4f6;
        --auth-primary: #3b3db1;
        --auth-primary-hover: #2f3192;
        --auth-label: #9ca3af;
        --auth-border: #e5e7eb;
        --auth-text: #111827;
    }

    body {
        background: var(--auth-bg) !important;
        min-height: 100vh;
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 16px;
        position: relative;
    }

    .auth-back {
        position: absolute;
        top: 28px;
        left: 28px;
        color: var(--auth-primary);
        font-size: 1.35rem;
        text-decoration: none;
        line-height: 1;
        transition: opacity 0.15s ease;
    }

    .auth-back:hover {
        opacity: 0.7;
        color: var(--auth-primary);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .auth-badge {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 12px;
        background: var(--auth-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 8px 20px rgba(59, 61, 177, 0.25);
    }

    .auth-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--auth-text);
        letter-spacing: -0.02em;
    }

    .auth-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 28px 28px 24px;
    }

    .auth-label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--auth-label);
    }

    .auth-field {
        margin-bottom: 18px;
    }

    .auth-input,
    .auth-input-wrap {
        width: 100%;
        border: 1px solid var(--auth-border);
        border-radius: 10px;
        background: #fff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .auth-input {
        display: block;
        padding: 12px 14px;
        font-size: 0.95rem;
        color: var(--auth-text);
        outline: none;
    }

    .auth-input:focus,
    .auth-input-wrap:focus-within {
        border-color: #c7d2fe;
        box-shadow: 0 0 0 3px rgba(59, 61, 177, 0.12);
    }

    .auth-input-wrap {
        display: flex;
        align-items: center;
        padding: 0 12px;
        gap: 8px;
    }

    .auth-input-wrap .auth-input {
        border: 0;
        box-shadow: none;
        padding-left: 0;
        padding-right: 0;
        background: transparent;
    }

    .auth-input-wrap .auth-input:focus {
        box-shadow: none;
    }

    .auth-icon {
        color: #9ca3af;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .auth-toggle {
        border: 0;
        background: transparent;
        color: #9ca3af;
        padding: 0;
        cursor: pointer;
        line-height: 1;
    }

    .auth-toggle:hover {
        color: #6b7280;
    }

    .auth-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 0.8rem;
    }

    .auth-submit {
        width: 100%;
        border: 0;
        border-radius: 10px;
        background: var(--auth-primary);
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 13px 16px;
        margin-top: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.15s ease;
    }

    .auth-submit:hover {
        background: var(--auth-primary-hover);
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 22px 0 18px;
        color: #9ca3af;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    .auth-google {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        height: 48px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        color: #111827;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 4px;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
    }

    .auth-google:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #111827;
        transform: translateY(-1px);
    }

    .auth-footer {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 18px;
    }

    .auth-footer a {
        color: var(--auth-primary);
        font-weight: 700;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="auth-page">
    <a href="{{ route('login') }}" class="auth-back" aria-label="Back to login">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="auth-header">
        <div class="auth-badge" aria-hidden="true">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <h1 class="auth-title">Create Employee Account</h1>
    </div>

    <div class="auth-card">
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="auth-field">
                <label class="auth-label" for="name">Username</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="auth-input"
                    autocomplete="username"
                >
                @error('name')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="auth-input"
                    autocomplete="email"
                >
                @error('email')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password">Password</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-lock auth-icon" aria-hidden="true"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="auth-input"
                        autocomplete="new-password"
                    >
                    <button type="button" class="auth-toggle" data-toggle-password="password" aria-label="Show password">
                        <i class="fa-solid fa-eye" data-eye></i>
                    </button>
                </div>
                @error('password')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-lock auth-icon" aria-hidden="true"></i>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                        class="auth-input"
                        autocomplete="new-password"
                    >
                    <button type="button" class="auth-toggle" data-toggle-password="password_confirmation" aria-label="Show confirm password">
                        <i class="fa-solid fa-eye" data-eye></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-submit">
                Create Account
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </button>

            <div class="auth-divider">OR CONTINUE WITH</div>

            <a href="{{ route('auth.google') }}" class="auth-google">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285f4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34a853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#fbbc05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#ea4335"/>
                </svg>
                Continue with Google
            </a>

            <div class="auth-footer">
                Already have an account?
                <a href="{{ route('login') }}">Sign In</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.getAttribute('data-toggle-password'));
            const icon = button.querySelector('[data-eye]');
            if (!input || !icon) return;

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !show);
            icon.classList.toggle('fa-eye-slash', show);
            button.setAttribute('aria-label', show ? 'Hide value' : 'Show value');
        });
    });
</script>
@endpush
