@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="p-margin-desktop max-w-3xl mx-auto pb-12 pt-8">
    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface">Account Profile</h2>
        <p class="font-body-md text-body-md text-secondary mt-1">Manage your credentials and information.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 font-body-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-outline-variant rounded-xl p-6 md:p-8">
        <div class="flex justify-center mb-8">
            <div class="w-24 h-24 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[48px]">person</span>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block font-label-md text-label-md text-secondary mb-2" for="name">Username</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                >
                @error('name')
                    <p class="mt-1 font-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-secondary mb-2" for="email">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                >
                @error('email')
                    <p class="mt-1 font-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-outline-variant">

            <div>
                <label class="block font-label-md text-label-md text-secondary mb-2" for="password">New Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Leave blank to keep current"
                    class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                >
                @error('password')
                    <p class="mt-1 font-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-label-md text-label-md text-secondary mb-2" for="password_confirmation">Confirm Password</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="w-full rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                >
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('settings.index') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-secondary font-label-md hover:bg-surface-container-low">Cancel</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label-md hover:bg-primary-container">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
