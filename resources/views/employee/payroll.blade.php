@extends('employee.layout')

@section('title', 'My Payroll')
@section('page-title', 'My Payslips')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-emerald-600">payments</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Net Earnings</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">${{ number_format($totals['net'], 2) }}</p>
        <p class="font-body-sm text-body-sm text-secondary mt-1">Across {{ $payrolls->total() }} payslip(s)</p>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-primary">account_balance</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Gross Pay</p>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">${{ number_format($totals['gross'], 2) }}</p>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-red-600">trending_down</span>
            <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Deductions</p>
        </div>
        <p class="font-headline-md text-headline-md text-red-600">${{ number_format($totals['deductions'], 2) }}</p>
    </div>
</div>

{{-- Payslips Table --}}
<div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
    @if ($payrolls->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Pay Period</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Hours</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Gross Pay</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Deductions</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Net Pay</th>
                        <th class="text-left px-5 py-3 font-label-md text-label-md text-secondary uppercase">Status</th>
                        <th class="text-right px-5 py-3 font-label-md text-label-md text-secondary uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high">
                    @foreach ($payrolls as $payroll)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-body-md text-body-md text-on-surface font-semibold">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $payroll->pay_period)->format('F Y') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 font-body-md text-body-md text-on-surface">
                                {{ number_format($payroll->hours_worked, 1) }}h
                            </td>
                            <td class="px-5 py-4 font-body-md text-body-md text-on-surface">
                                ${{ number_format($payroll->gross_pay, 2) }}
                            </td>
                            <td class="px-5 py-4 font-body-md text-body-md text-red-600">
                                -${{ number_format($payroll->deductions, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-body-md text-body-md text-on-surface font-semibold">${{ number_format($payroll->net_pay, 2) }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusClass = match($payroll->status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full border text-label-sm font-label-sm {{ $statusClass }}">
                                    {{ $payroll->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a
                                    href="{{ route('employee.payroll.show', $payroll) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white rounded-lg font-label-sm text-label-sm hover:bg-primary/90 transition-colors no-underline"
                                >
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-outline-variant">
            {{ $payrolls->links('partials.pagination') }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <span class="material-symbols-outlined text-[48px] text-secondary/40 mb-4">receipt_long</span>
            <p class="font-body-lg text-body-lg text-secondary">No payslips available yet.</p>
            <p class="font-body-sm text-body-sm text-secondary mt-1">Payslips will appear here once payroll is processed.</p>
        </div>
    @endif
</div>
@endsection
