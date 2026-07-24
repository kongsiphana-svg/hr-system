@extends('employee.layout')

@section('title', 'Payslip Detail')
@section('page-title', 'Payslip — ' . \Carbon\Carbon::createFromFormat('Y-m', $payroll->pay_period)->format('F Y'))

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('employee.payroll') }}" class="inline-flex items-center gap-1.5 text-secondary hover:text-primary transition-colors font-body-md mb-6 no-underline">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to payslips
    </a>

    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 bg-gradient-to-r from-primary to-primary/90 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-headline-md text-headline-md">Payslip</h2>
                    <p class="font-body-md text-body-md text-white/80 mt-1">{{ \Carbon\Carbon::createFromFormat('Y-m', $payroll->pay_period)->format('F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-label-sm text-label-sm text-white/70 uppercase">Status</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full bg-white/20 text-white text-label-sm font-label-sm">
                        {{ $payroll->statusLabel() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Employee Info --}}
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low/50">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-label-sm text-label-sm text-secondary uppercase">Employee</p>
                    <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $payroll->employee?->name ?? $user->name }}</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-secondary uppercase">Department</p>
                    <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $payroll->employee?->department ?? '—' }}</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-secondary uppercase">Job Title</p>
                    <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $payroll->employee?->job_title ?? '—' }}</p>
                </div>
                <div>
                    <p class="font-label-sm text-label-sm text-secondary uppercase">Processed</p>
                    <p class="font-body-md text-body-md text-on-surface mt-0.5">{{ $payroll->processed_at?->format('M j, Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Status History Timeline --}}
        @php
            $statusFlow = [
                'draft' => ['label' => 'Draft', 'icon' => 'draft', 'desc' => 'Payroll generated from attendance & leave', 'date' => $payroll->created_at?->format('M j, Y')],
                'pending_review' => ['label' => 'Pending Review', 'icon' => 'rate_review', 'desc' => 'Submitted for HR review', 'date' => $payroll->submitted_for_review_at?->format('M j, Y')],
                'approved' => ['label' => 'Approved', 'icon' => 'check_circle', 'desc' => 'Approved by HR', 'date' => $payroll->approved_at?->format('M j, Y')],
                'paid' => ['label' => 'Paid', 'icon' => 'payments', 'desc' => 'Payment released', 'date' => $payroll->paid_at?->format('M j, Y')],
            ];
            $currentIdx = array_search($payroll->status, array_keys($statusFlow));
        @endphp
        <div class="px-6 py-5 border-b border-outline-variant">
            <h3 class="font-title-lg text-title-lg text-on-surface mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-secondary">timeline</span>
                Status History
            </h3>
            <div class="relative">
                {{-- Vertical line --}}
                <div class="absolute left-[17px] top-2 bottom-2 w-0.5 bg-outline-variant"></div>

                <div class="space-y-0">
                    @foreach ($statusFlow as $key => $step)
                        @php
                            $stepIdx = array_search($key, array_keys($statusFlow));
                            $isComplete = $stepIdx <= $currentIdx;
                            $isCurrent = $key === $payroll->status;

                            $circleColor = $isCurrent ? 'bg-primary text-white ring-4 ring-primary/20' : ($isComplete ? 'bg-emerald-500 text-white' : 'bg-surface-container-high text-secondary/40');
                            $lineColor = $isComplete ? 'border-emerald-400' : 'border-outline-variant';
                            $textColor = $isComplete ? 'text-on-surface' : 'text-secondary/50';
                        @endphp
                        <div class="relative flex items-start gap-4 pb-6 last:pb-0">
                            {{-- Circle --}}
                            <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full transition-all duration-300 {{ $circleColor }}">
                                @if ($isCurrent)
                                    <span class="material-symbols-outlined text-[18px]">{{ $step['icon'] }}</span>
                                @elseif ($isComplete)
                                    <span class="material-symbols-outlined text-[16px]">check</span>
                                @else
                                    <span class="material-symbols-outlined text-[16px]">radio_button_unchecked</span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 pt-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-body-md text-body-md font-semibold {{ $textColor }} {{ $isCurrent ? 'text-primary' : '' }}">
                                        {{ $step['label'] }}
                                    </p>
                                    @if ($isCurrent)
                                        <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold">Current</span>
                                    @endif
                                </div>
                                <p class="font-body-sm text-body-sm text-secondary/70 mt-0.5">{{ $step['desc'] }}</p>
                                @if ($step['date'])
                                    <p class="text-[11px] text-secondary/50 mt-0.5">{{ $step['date'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Earnings & Deductions --}}
        <div class="px-6 py-5">
            <h3 class="font-title-lg text-title-lg text-on-surface mb-4">Earnings</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-md text-body-md text-secondary">Base Salary</span>
                    <span class="font-body-md text-body-md text-on-surface">${{ number_format($payroll->base_salary, 2) }}</span>
                </div>
                @if ($payroll->allowances > 0)
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-md text-body-md text-secondary">Allowances</span>
                    <span class="font-body-md text-body-md text-emerald-600">+${{ number_format($payroll->allowances, 2) }}</span>
                </div>
                @endif
                @if ($payroll->overtime_hours > 0)
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-md text-body-md text-secondary">Overtime ({{ $payroll->overtime_hours }}h)</span>
                    <span class="font-body-md text-body-md text-emerald-600">+${{ number_format($payroll->overtime_hours * ($payroll->base_salary / max($payroll->hours_worked, 1)) * 1.5, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2 border-t border-outline-variant pt-3">
                    <span class="font-body-md text-body-md font-semibold text-on-surface">Gross Pay</span>
                    <span class="font-body-md text-body-md font-semibold text-on-surface">${{ number_format($payroll->gross_pay, 2) }}</span>
                </div>
            </div>

            <h3 class="font-title-lg text-title-lg text-on-surface mt-6 mb-4">Deductions</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-md text-body-md text-secondary">Tax & Withholdings</span>
                    <span class="font-body-md text-body-md text-red-600">-${{ number_format($payroll->deductions, 2) }}</span>
                </div>
                @if ($payroll->unpaid_leave_deduction > 0)
                <div class="flex justify-between items-center py-2">
                    <span class="font-body-md text-body-md text-secondary">Unpaid Leave</span>
                    <span class="font-body-md text-body-md text-red-600">-${{ number_format($payroll->unpaid_leave_deduction, 2) }}</span>
                </div>
                @endif
            </div>

            {{-- Net Pay --}}
            <div class="mt-6 p-4 bg-primary-fixed rounded-xl flex justify-between items-center">
                <div>
                    <p class="font-body-md text-body-md text-on-primary-fixed-variant">Net Pay</p>
                    <p class="font-label-sm text-label-sm text-on-primary-fixed-variant/70">Take-home amount</p>
                </div>
                <p class="font-headline-md text-headline-md text-primary">${{ number_format($payroll->net_pay, 2) }}</p>
            </div>
        </div>

        {{-- Hours Summary --}}
        @if ($payroll->hours_worked > 0 || $payroll->approved_leave_days > 0)
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low/30">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">{{ number_format($payroll->hours_worked, 1) }}</p>
                    <p class="font-label-sm text-label-sm text-secondary">Hours Worked</p>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">{{ number_format($payroll->approved_leave_days, 1) }}</p>
                    <p class="font-label-sm text-label-sm text-secondary">Leave Days</p>
                </div>
                <div>
                    <p class="font-headline-sm text-headline-sm text-on-surface">{{ $payroll->hours_worked > 0 ? number_format($payroll->net_pay / $payroll->hours_worked, 2) : '—' }}</p>
                    <p class="font-label-sm text-label-sm text-secondary">$/Hour</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('employee.payroll') }}" class="px-5 py-2.5 border border-outline-variant rounded-xl text-secondary hover:bg-surface-container-low font-label-md transition-colors no-underline">
            Back to List
        </a>
    </div>
</div>
@endsection
