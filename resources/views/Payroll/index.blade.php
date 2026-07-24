@extends('Payroll.layout')

@section('page-title', 'Payroll Management')

@section('payroll')
    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Payroll Management</h2>
        <p class="mt-1 text-sm text-slate-500">
            Employee → Attendance → Approved Leave → Generate → HR Review → Approve → Payslip → Paid
        </p>
    </div>

    {{-- Summary cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" data-payroll-summary>
        @foreach ($summary as $card)
            <div class="rounded-xl border border-slate-200 bg-white px-5 py-4" data-summary-key="{{ $card['key'] }}">
                <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">{{ $card['label'] }}</p>
                <p class="mt-2 text-xl font-bold tracking-tight text-slate-900" data-summary-value>{{ $card['value'] }}</p>
                @if ($card['hint'])
                    <p class="mt-1 text-xs font-medium text-slate-500" data-summary-hint>{{ $card['hint'] }}</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Controls --}}
    <form
        id="payroll-controls"
        method="GET"
        action="{{ route('admin.payroll.index') }}"
        class="mb-6 rounded-xl border border-slate-200 bg-white p-5"
        data-payroll-controls
    >
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="grid w-full grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="pay_period" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Payroll Period
                    </label>
                    <select
                        id="pay_period"
                        name="pay_period"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                    >
                        @foreach ($payPeriods as $value => $label)
                            <option value="{{ $value }}" @selected($value === $payPeriod)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="department_id" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Department
                    </label>
                    <select
                        id="department_id"
                        name="department_id"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                    >
                        @foreach ($departments as $value => $label)
                            <option value="{{ $value }}" @selected(request('department_id') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="search" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Employee Search
                    </label>
                    <input
                        id="search"
                        name="search"
                        type="search"
                        value="{{ request('search') }}"
                        placeholder="Name, email, or title…"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                    >
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <p id="payroll-process-status" class="text-sm text-slate-500" role="status" aria-live="polite"></p>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
                >
                    Apply Filters
                </button>
                <a
                    href="{{ route('admin.payroll.export', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0-3-3m3 3 3-3m2 8H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/>
                    </svg>
                    Export CSV
                </a>
                <button
                    type="button"
                    id="btn-generate-payroll"
                    class="relative inline-flex shrink-0 items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-slate-800 disabled:cursor-wait disabled:opacity-70"
                >
                    <span data-label>Generate Payroll</span>
                    <span data-spinner class="absolute inset-0 hidden items-center justify-center">
                        <svg class="h-4 w-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </form>

    {{-- Payroll table --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Payroll Records</h3>
                <p class="text-xs text-slate-500">Showing results for {{ $payPeriods[$payPeriod] ?? $payPeriod }}</p>
            </div>
            <span class="text-sm text-slate-500" data-pagination-label>
                Showing {{ $payrolls->firstItem() ?? 0 }}–{{ $payrolls->lastItem() ?? 0 }} of {{ $payrolls->total() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm" id="payroll-management-table">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Employee</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Department</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Base Salary</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Hours Worked</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Approved Leave</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Gross Salary</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Net Salary</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold tracking-wider text-slate-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" data-payroll-table-body>
                    @forelse ($rows as $row)
                        <tr
                            class="transition-colors hover:bg-slate-50/70"
                            data-payroll-id="{{ $row['id'] }}"
                            data-employee-id="{{ $row['employee_id'] }}"
                            data-employee-name="{{ $row['name'] }}"
                            data-job-title="{{ $row['title'] }}"
                            data-department="{{ $row['department'] }}"
                            data-email="{{ $row['email'] }}"
                            data-base-salary="{{ $row['base_salary'] }}"
                            data-hours-worked="{{ $row['hours_worked'] }}"
                            data-standard-hours="{{ $row['standard_hours'] }}"
                            data-approved-leave-days="{{ $row['approved_leave_days'] }}"
                            data-overtime-hours="{{ $row['overtime_hours'] }}"
                            data-allowances="{{ $row['allowances'] }}"
                            data-deductions="{{ $row['deductions'] }}"
                            data-unpaid-leave-deduction="{{ $row['unpaid_leave_deduction'] }}"
                            data-gross-pay="{{ $row['gross_pay'] }}"
                            data-net-pay="{{ $row['net_pay'] }}"
                            data-status="{{ $row['status'] }}"
                            data-pay-period="{{ $row['pay_period'] }}"
                        >
                            <td class="px-5 py-4">
                                <a href="{{ $row['employee_id'] ? route('admin.employees.show', $row['employee_id']) : '#' }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white {{ $row['avatar'] }} group-hover:ring-2 group-hover:ring-indigo-300 transition-all">
                                        {{ $row['initials'] }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">{{ $row['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $row['title'] }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-700">{{ $row['department'] }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $money($row['base_salary']) }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ number_format($row['hours_worked'], 1) }}h</td>
                            <td class="px-5 py-4 text-slate-700">{{ number_format($row['approved_leave_days'], 1) }} days</td>
                            <td class="px-5 py-4 font-medium text-slate-900">{{ $money($row['gross_pay']) }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $money($row['net_pay']) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusBadges[$row['status']] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}" data-status-badge>
                                    {{ $row['status_label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2" data-actions>
                                    @if ($row['status'] === 'draft')
                                        <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="review">Review</button>
                                    @elseif ($row['status'] === 'pending_review')
                                        <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="review">Review</button>
                                        <button type="button" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700" data-action="approve">Approve</button>
                                    @elseif ($row['status'] === 'approved')
                                        <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="view-payslip">View Payslip</button>
                                        <button type="button" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700" data-action="mark-paid">Mark as Paid</button>
                                    @elseif ($row['status'] === 'paid')
                                        <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="view-payslip">View Payslip</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr data-empty-row>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500">
                                No payroll records for this period yet. Set filters and click <strong>Generate Payroll</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-between" data-pagination>
            @php $onFirstPage = $payrolls->currentPage() <= 1; @endphp
            <a
                href="{{ $onFirstPage ? '#' : $payrolls->previousPageUrl() }}"
                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium {{ $onFirstPage ? 'pointer-events-none text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}"
                @if ($onFirstPage) aria-disabled="true" @endif
            >
                @include('Payroll.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4'])
                Previous
            </a>

            <nav class="flex items-center gap-1" aria-label="Pagination">
                @for ($pageNum = 1; $pageNum <= $payrolls->lastPage(); $pageNum++)
                    <a
                        href="{{ $payrolls->url($pageNum) }}"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-medium transition-colors {{ $payrolls->currentPage() === $pageNum ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
                    >{{ $pageNum }}</a>
                @endfor
            </nav>

            @php $onLastPage = $payrolls->currentPage() >= $payrolls->lastPage(); @endphp
            <a
                href="{{ $onLastPage ? '#' : $payrolls->nextPageUrl() }}"
                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium {{ $onLastPage ? 'pointer-events-none text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}"
                @if ($onLastPage) aria-disabled="true" @endif
            >
                Next
                @include('Payroll.partials.icons', ['name' => 'chevron-right', 'class' => 'h-4 w-4'])
            </a>
        </div>
    </div>
@endsection

@push('modals')
    @include('Payroll.partials.review-modal')
    @include('Payroll.partials.payslip-modal')
@endpush
