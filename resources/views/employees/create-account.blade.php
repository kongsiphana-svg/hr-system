@extends('layouts.app')

@section('title', 'Create Employee Account')

@section('content')
<div class="p-margin-desktop max-w-container-max mx-auto pb-12 pt-8">
    <nav class="flex items-center gap-2 font-body-sm text-body-sm text-secondary mb-6">
        <a href="{{ route('admin.employees.index') }}" class="hover:text-primary transition-colors no-underline">Employees</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <a href="{{ route('admin.employees.show', $employee) }}" class="hover:text-primary transition-colors no-underline">{{ $employee->name }}</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-on-surface">Create Account</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h2 class="font-display-lg text-display-lg text-on-surface">Create User Account</h2>
            <p class="font-body-md text-body-md text-secondary mt-1">
                Create a login account for <strong>{{ $employee->name }}</strong>.
            </p>
        </div>

        @if ($existingUser)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4 font-body-md mb-6">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-600">warning</span>
                    <div>
                        <strong>A user account already exists</strong> for {{ $employee->email }}.
                        <span class="block mt-1 text-amber-700/80">User: {{ $existingUser->name }} ({{ $existingUser->role }})</span>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white border border-outline-variant rounded-xl p-6">
                <div class="px-4 py-3 bg-surface-container-low rounded-lg mb-5">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="font-label-sm text-label-sm text-secondary uppercase">Employee</p>
                            <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $employee->name }}</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-secondary uppercase">Email</p>
                            <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-secondary uppercase">Department</p>
                            <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $employee->department ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-secondary uppercase">Role</p>
                            <p class="font-body-md text-body-md text-on-surface mt-0.5">Employee</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.employees.create-account', $employee) }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block font-label-md text-label-md text-secondary mb-1.5">Login Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $employee->first_name) }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            required
                        >
                        <p class="mt-1 font-body-sm text-body-sm text-secondary">This will be the employee's login username.</p>
                        @error('name')
                            <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email_display" class="block font-label-md text-label-md text-secondary mb-1.5">Email (read-only)</label>
                        <input
                            type="email"
                            id="email_display"
                            value="{{ $employee->email }}"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-secondary/60 cursor-not-allowed"
                            disabled
                        >
                        <p class="mt-1 font-body-sm text-body-sm text-secondary">Email is synced from the employee record.</p>
                    </div>

                    <div>
                        <label for="password" class="block font-label-md text-label-md text-secondary mb-1.5">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            required
                            minlength="8"
                        >
                        @error('password')
                            <p class="mt-1 font-body-sm text-body-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-label-md text-label-md text-secondary mb-1.5">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            required
                        >
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <a href="{{ route('admin.employees.show', $employee) }}" class="px-5 py-2.5 border border-outline-variant rounded-xl text-secondary hover:bg-surface-container-low font-label-md transition-colors no-underline">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl font-label-md text-label-md hover:bg-primary/90 transition-colors">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
