@extends('Payroll.layout')

@section('page-title', 'Payroll Processing')

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #payslip-modal,
        #payslip-modal * { visibility: visible; }
        #payslip-modal { position: static; display: block !important; }
        #payslip-print-area { padding: 0; }
    }
</style>
@endpush

@section('payroll')
    <div class="mb-6">
        <a href="{{ route('admin.payroll.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition-colors hover:text-slate-800">
            @include('Payroll.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4'])
            Back to Payroll Management
        </a>
    </div>

    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Payroll Processing</h2>
            <p class="mt-1 text-sm text-slate-500">Select a pay period, process compensation, then review or print payslips.</p>
        </div>
    </div>

    {{-- Filter + Process action bar --}}
    <form
        id="payroll-process-form"
        class="mb-6 rounded-xl border border-slate-200 bg-white p-5"
        method="GET"
        action="{{ route('admin.payroll.process') }}"
        onsubmit="return false;"
        data-action="payroll-process"
    >
        @csrf

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-end">
                <div class="w-full max-w-xs">
                    <label for="pay_period" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Pay Period
                    </label>
                    <select
                        id="pay_period"
                        name="pay_period"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                        required
                    >
                        @foreach ($payPeriods as $value => $label)
                            <option value="{{ $value }}" @selected($value === $payPeriod)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full max-w-xs">
                    <label for="department_id" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Department
                    </label>
                    <select
                        id="department_id"
                        name="department_id"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                        data-filter="department"
                    >
                        @foreach ($departments as $value => $label)
                            <option value="{{ $value }}" @selected($value === request('department_id', ''))>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center">
                <p id="payroll-process-status" class="text-sm text-slate-500" role="status" aria-live="polite">
                    Ready to process {{ $payPeriods[$payPeriod] ?? $payPeriod }}.
                </p>
                <button
                    type="button"
                    id="btn-process-payroll"
                    name="action"
                    value="process"
                    class="relative inline-flex shrink-0 items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-slate-800 disabled:cursor-wait disabled:opacity-70"
                    data-endpoint="{{ url('/api/payroll/process') }}"
                    data-method="POST"
                >
                    <span data-label>Process Payroll</span>
                    <span data-spinner class="absolute inset-0 hidden flex items-center justify-center">
                        <svg class="h-4 w-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </form>

    {{-- Processed payroll table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Processed Payroll</h3>
                <p class="text-xs text-slate-500">Results for the selected pay period</p>
            </div>
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600" data-row-count>
                {{ $payrolls->total() ?? 0 }} employees
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm" id="payroll-results-table">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Employee Name</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Base Salary</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Allowances</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Deductions</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Net Pay</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold tracking-wider text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" data-payroll-table-body>
                    @forelse ($payrolls as $payroll)
                        @php
                            $emp = $payroll->employee;
                        @endphp
                        <tr
                            class="transition-colors hover:bg-slate-50/70"
                            data-payroll-id="{{ $payroll->id }}"
                            data-employee-id="{{ $emp?->id }}"
                            data-employee-name="{{ $emp?->name ?? '—' }}"
                            data-job-title="{{ $emp?->job_title ?? '' }}"
                            data-base-salary="{{ $payroll->base_salary }}"
                            data-allowances="{{ $payroll->allowances }}"
                            data-deductions="{{ $payroll->deductions }}"
                            data-net-pay="{{ $payroll->net_pay }}"
                            data-pay-period="{{ $payroll->pay_period }}"
                        >
                            <td class="px-5 py-4">
                                @if ($emp)
                                    <a href="{{ route('admin.employees.show', $emp) }}" class="group">
                                        <p class="font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">{{ $emp->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $emp->job_title ?? '' }}</p>
                                    </a>
                                @else
                                    <p class="font-semibold text-slate-900">—</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-700">{{ $money($payroll->base_salary) }}</td>
                            <td class="px-5 py-4 font-medium text-emerald-600">+{{ $money($payroll->allowances) }}</td>
                            <td class="px-5 py-4 font-medium text-red-500">-{{ $money($payroll->deductions) }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $money($payroll->net_pay) }}</td>
                            <td class="px-5 py-4 text-right">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50"
                                    data-action="view-payslip"
                                    data-payroll-id="{{ $payroll->id }}"
                                    data-employee-id="{{ $emp?->id }}"
                                    data-payslip-url="{{ url('/api/payroll/'.$payroll->id.'/payslip') }}"
                                >
                                    View Payslip
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                                No payroll records for this period yet. Click Process Payroll to calculate.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payrolls->hasPages())
            <div class="flex flex-col items-center gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-between">
                <a
                    href="{{ $payrolls->previousPageUrl() }}"
                    class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium {{ $payrolls->onFirstPage() ? 'pointer-events-none text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    @include('Payroll.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4'])
                    Previous
                </a>
                <nav class="flex items-center gap-1" aria-label="Pagination">
                    @for ($page = 1; $page <= $payrolls->lastPage(); $page++)
                        <a
                            href="{{ $payrolls->url($page) }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-medium transition-colors {{ $payrolls->currentPage() === $page ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
                        >{{ $page }}</a>
                    @endfor
                </nav>
                <a
                    href="{{ $payrolls->nextPageUrl() }}"
                    class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium {{ $payrolls->onLastPage() ? 'pointer-events-none text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    Next
                    @include('Payroll.partials.icons', ['name' => 'chevron-right', 'class' => 'h-4 w-4'])
                </a>
            </div>
        @endif
    </div>
@endsection

@push('modals')
    @include('Payroll.partials.payslip-modal')
@endpush
