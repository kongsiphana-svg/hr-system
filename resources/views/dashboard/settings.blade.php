@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="p-margin-desktop max-w-container-max mx-auto pb-12 pt-8">
    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface">Settings</h2>
        <p class="font-body-md text-body-md text-secondary mt-1">Manage portal preferences and account access.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ auth()->check() ? route('profile.index') : route('login') }}" class="bg-white border border-outline-variant rounded-xl p-6 hover:border-primary/40 transition-colors no-underline block">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary">manage_accounts</span>
                <h3 class="font-title-lg text-title-lg text-on-surface">Account Profile</h3>
            </div>
            <p class="font-body-sm text-body-sm text-secondary">{{ auth()->check() ? 'Update your name, email, and password.' : 'Sign in to manage your account profile.' }}</p>
        </a>

        <a href="{{ route('employees.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 hover:border-primary/40 transition-colors no-underline block">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary">groups</span>
                <h3 class="font-title-lg text-title-lg text-on-surface">Employee Directory</h3>
            </div>
            <p class="font-body-sm text-body-sm text-secondary">Manage workforce records and statuses.</p>
        </a>

        <a href="{{ route('payroll.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 hover:border-primary/40 transition-colors no-underline block">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary">payments</span>
                <h3 class="font-title-lg text-title-lg text-on-surface">Payroll</h3>
            </div>
            <p class="font-body-sm text-body-sm text-secondary">Review and process compensation cycles.</p>
        </a>

        <a href="{{ route('leave-requests.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 hover:border-primary/40 transition-colors no-underline block">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary">event_busy</span>
                <h3 class="font-title-lg text-title-lg text-on-surface">Leave Requests</h3>
            </div>
            <p class="font-body-sm text-body-sm text-secondary">Approve or decline employee absences.</p>
        </a>
    </div>
</div>
@endsection
