@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-margin-desktop max-w-container-max mx-auto pb-12 pt-8">
    <div class="mb-8">
        <h2 class="font-display-lg text-display-lg text-on-surface">Dashboard</h2>
        <p class="font-body-md text-body-md text-secondary mt-1">Overview of workforce activity across the HR portal.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('employees.index') }}" class="bg-white border border-outline-variant rounded-xl p-5 hover:border-primary/40 transition-colors no-underline">
            <div class="flex items-center gap-3 mb-3">
                <span class="material-symbols-outlined text-primary">groups</span>
                <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Employees</p>
            </div>
            <p class="font-headline-md text-headline-md text-on-surface">{{ $metrics['total_employees'] }}</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Total directory records</p>
        </a>

        <a href="{{ route('leave-requests.index') }}" class="bg-white border border-outline-variant rounded-xl p-5 hover:border-primary/40 transition-colors no-underline">
            <div class="flex items-center gap-3 mb-3">
                <span class="material-symbols-outlined text-amber-600">hourglass_top</span>
                <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pending Leaves</p>
            </div>
            <p class="font-headline-md text-headline-md text-on-surface">{{ $metrics['pending_leaves'] }}</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Awaiting review</p>
        </a>

        <a href="{{ route('payroll.index') }}" class="bg-white border border-outline-variant rounded-xl p-5 hover:border-primary/40 transition-colors no-underline">
            <div class="flex items-center gap-3 mb-3">
                <span class="material-symbols-outlined text-emerald-600">payments</span>
                <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Payroll</p>
            </div>
            <p class="font-headline-md text-headline-md text-on-surface">Open</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Review compensation</p>
        </a>

        <a href="{{ route('schedule.index') }}" class="bg-white border border-outline-variant rounded-xl p-5 hover:border-primary/40 transition-colors no-underline">
            <div class="flex items-center gap-3 mb-3">
                <span class="material-symbols-outlined text-indigo-600">calendar_month</span>
                <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Schedule</p>
            </div>
            <p class="font-headline-md text-headline-md text-on-surface">Open</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Shifts & calendar</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
                <h3 class="font-title-lg text-title-lg text-on-surface">Recent Leave Activity</h3>
                <a href="{{ route('leave-requests.index') }}" class="font-label-md text-label-md text-primary hover:underline">View all</a>
            </div>
            <div class="divide-y divide-surface-container-high">
                @forelse($recentLeaves as $leave)
                    <div class="px-5 py-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $leave->user->name ?? 'Employee' }}</p>
                            <p class="font-body-sm text-body-sm text-secondary">
                                {{ $leave->start_date }} to {{ $leave->end_date }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-secondary-container text-secondary font-label-sm text-label-sm">
                            {{ $leave->leave_type }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center font-body-md text-secondary">
                        No leave activity yet.
                        <div class="mt-3">
                            <a href="{{ route('leave-requests.index') }}" class="text-primary font-label-md">Open leave requests</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant">
                <h3 class="font-title-lg text-title-lg text-on-surface">Quick Links</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('employees.create') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                    <span class="material-symbols-outlined text-primary">person_add</span>
                    <span class="font-body-md text-body-md">Add Employee</span>
                </a>
                <a href="{{ route('payroll.process') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                    <span class="material-symbols-outlined text-primary">request_quote</span>
                    <span class="font-body-md text-body-md">Process Payroll</span>
                </a>
                <a href="{{ route('schedule.create') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                    <span class="material-symbols-outlined text-primary">add_circle</span>
                    <span class="font-body-md text-body-md">Add Shift</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                    <span class="material-symbols-outlined text-primary">settings</span>
                    <span class="font-body-md text-body-md">Settings</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
