@extends('employee.layout')

@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="font-display-lg text-display-lg text-on-surface">Welcome back, {{ $user->name }}!</h2>
    <p class="font-body-md text-body-md text-secondary mt-1">Your personal HR overview.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-amber-600">hourglass_top</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pending Leaves</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $pendingLeaves }}</p>
        <p class="font-body-sm text-body-sm text-secondary mt-1">Awaiting approval</p>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-emerald-600">payments</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Latest Payroll</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">
            {{ $latestPayroll ? '$'.number_format($latestPayroll->net_pay, 2) : '—' }}
        </p>
        <p class="font-body-sm text-body-sm text-secondary mt-1">
            {{ $latestPayroll ? $latestPayroll->pay_period : 'No records yet' }}
        </p>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-blue-600">calendar_month</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pay Periods</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $payPeriods->count() }}</p>
        <p class="font-body-sm text-body-sm text-secondary mt-1">Processed payslips</p>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-indigo-600">person</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">My Profile</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface text-sm">{{ $user->email }}</p>
        <p class="font-body-sm text-body-sm text-secondary mt-1">
            {{ $employee ? $employee->job_title ?? 'Employee' : 'Employee' }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Leaves --}}
    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-title-lg text-title-lg text-on-surface">My Recent Leave Requests</h3>
            <a href="{{ route('employee.leaves') }}" class="font-label-md text-label-md text-primary hover:underline">View all</a>
        </div>
        <div class="divide-y divide-surface-container-high">
            @forelse($recentLeaves as $leave)
                <div class="px-5 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $leave->leave_type }}</p>
                        <p class="font-body-sm text-body-sm text-secondary">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M j') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M j, Y') }}
                        </p>
                    </div>
                    @php
                        $statusClass = match(strtolower($leave->status)) {
                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'rejected', 'declined' => 'bg-red-50 text-red-700 border-red-200',
                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full border text-label-sm font-label-sm {{ $statusClass }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-10 text-center font-body-md text-secondary">
                    No recent leave requests.
                    <div class="mt-3">
                        <a href="{{ route('employee.leaves.create') }}" class="text-primary font-label-md">Submit a leave request</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant">
            <h3 class="font-title-lg text-title-lg text-on-surface">Quick Actions</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('employee.leaves.create') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                <span class="material-symbols-outlined text-primary">event_busy</span>
                <span class="font-body-md text-body-md">Request Leave</span>
            </a>
            <a href="{{ route('employee.payroll') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                <span class="material-symbols-outlined text-primary">receipt_long</span>
                <span class="font-body-md text-body-md">View Payslips</span>
            </a>
            <a href="{{ route('employee.settings') }}" class="flex items-center gap-3 rounded-lg border border-outline-variant px-4 py-3 hover:bg-surface-container-low transition-colors no-underline text-on-surface">
                <span class="material-symbols-outlined text-primary">settings</span>
                <span class="font-body-md text-body-md">Profile Settings</span>
            </a>
        </div>
    </div>

    {{-- Latest Payslip --}}
    @if ($latestPayroll)
    <div class="lg:col-span-2 bg-white border border-outline-variant rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-title-lg text-title-lg text-on-surface">Latest Payslip</h3>
            <a href="{{ route('employee.payroll.show', $latestPayroll) }}" class="font-label-md text-label-md text-primary hover:underline">View details</a>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase">Gross Pay</p>
                <p class="font-headline-sm text-headline-sm text-on-surface mt-1">${{ number_format($latestPayroll->gross_pay, 2) }}</p>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase">Deductions</p>
                <p class="font-headline-sm text-headline-sm text-red-600 mt-1">-${{ number_format($latestPayroll->deductions, 2) }}</p>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase">Allowances</p>
                <p class="font-headline-sm text-headline-sm text-emerald-600 mt-1">+${{ number_format($latestPayroll->allowances, 2) }}</p>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-secondary uppercase">Net Pay</p>
                <p class="font-headline-sm text-headline-sm text-primary mt-1">${{ number_format($latestPayroll->net_pay, 2) }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
