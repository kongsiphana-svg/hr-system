@extends('layouts.hr')

@section('title', 'Profile Created Successfully')

@section('content')
<div class="flex-1 flex items-center justify-center p-margin-desktop overflow-y-auto relative">

    {{-- Atmospheric Background Element --}}
    <div class="absolute inset-0 success-bg-pattern pointer-events-none"></div>

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-12 gap-8 relative z-10">

        {{-- Success Card --}}
        <div class="md:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl p-12 text-center flex flex-col items-center justify-center">

            {{-- Success Icon Wrapper --}}
            <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-8 animate-bounce transition-transform duration-700">
                <span class="material-symbols-outlined text-emerald-600 text-[64px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>

            <h1 class="font-display-lg text-display-lg text-primary mb-4">Profile Successfully Created!</h1>
            <p class="font-body-lg text-body-lg text-secondary mb-10 max-w-lg">
                Welcome to the team, <span class="font-semibold text-on-surface">{{ $employee['name'] ?? (auth()->user()->name ?? 'Alex Johnson') }}</span>. Your profile is now live and you can start managing your schedule and leave requests.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                <a href="{{ route('dashboard') }}"
                   class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-primary-container transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined">dashboard</span>
                    Go to Dashboard
                </a>
                <a href="{{ route('employee-profile.show') }}"
                   class="bg-surface-container-lowest border border-outline text-on-surface px-8 py-3 rounded-lg font-label-md text-label-md flex items-center justify-center gap-2 hover:bg-surface-container-low transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined">visibility</span>
                    View My Profile
                </a>
            </div>
        </div>

        {{-- Side Information / Next Steps --}}
        <div class="md:col-span-4 flex flex-col gap-6">
            {{-- Next Steps Bento Card --}}
        </div>
    </div>

    {{-- Decorative Illustration Element (Subtle) --}}
    <div class="absolute bottom-0 right-0 w-1/3 h-1/2 opacity-20 pointer-events-none">
        <div class="w-full h-full relative">
            <div class="absolute bottom-10 right-10 w-64 h-64 bg-primary-container rounded-full blur-[100px]"></div>
            <div class="absolute bottom-20 right-40 w-48 h-48 bg-secondary-container rounded-full blur-[80px]"></div>
        </div>
    </div>
</div>

{{-- Notification Toast (Micro-interaction) --}}
<div class="fixed bottom-8 right-8 bg-inverse-surface text-inverse-on-surface px-6 py-3 rounded-lg shadow-xl transition-all duration-500 flex items-center gap-3 translate-y-20 opacity-0" id="toast">
    <span class="material-symbols-outlined text-emerald-400">task_alt</span>
    <span class="font-label-md text-label-md">Your credentials have been encrypted and saved.</span>
</div>
@endsection

@push('styles')
    <style>
        .success-bg-pattern {
            background-image: radial-gradient(#3730a3 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.05;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Micro-interaction for toast entrance
        window.addEventListener('load', () => {
            setTimeout(() => {
                const toast = document.getElementById('toast');
                toast.classList.remove('translate-y-20', 'opacity-0');
            }, 800);

            setTimeout(() => {
                const toast = document.getElementById('toast');
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 5000);
        });

        // Simple hover effects
        document.querySelectorAll('button, a.rounded-lg').forEach(btn => {
            btn.addEventListener('mouseenter', () => btn.classList.add('shadow-md'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('shadow-md'));
        });
    </script>
@endpush