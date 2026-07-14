@extends('Payroll.layout')

@section('title', 'Payroll Management')

@section('content')
    @php
        /**
         * Demo data — swap for controller/API payload later.
         * Expected employee fields for backend:
         * id, employee_id, name, title, department_id, employment_type,
         * base_salary, hours, allowances, deductions, net_pay
         */
        $summary = [
            ['key' => 'pay_period', 'label' => 'Pay Period', 'value' => 'Oct 01 – Oct 31, 2023', 'hint' => null],
            ['key' => 'total_gross_pay', 'label' => 'Total Gross Pay', 'value' => '$412,850.00', 'hint' => null],
            ['key' => 'net_disbursable', 'label' => 'Net Disbursable', 'value' => '$328,140.00', 'hint' => null],
            ['key' => 'employees_count', 'label' => 'Employees Count', 'value' => '148', 'hint' => '142 active this period'],
        ];

        $employees = [
            [
                'id' => 1,
                'employee_id' => 1,
                'name' => 'Jane Doe',
                'title' => 'Product Designer',
                'department_id' => 'design',
                'employment_type' => 'full_time',
                'initials' => 'JD',
                'avatar' => 'bg-violet-500',
                'base_salary' => 8500.00,
                'hours' => 160,
                'allowances' => 450.00,
                'deductions' => 1200.00,
                'net_pay' => 7750.00,
            ],
            [
                'id' => 2,
                'employee_id' => 2,
                'name' => 'Mark Smith',
                'title' => 'Frontend Engineer',
                'department_id' => 'engineering',
                'employment_type' => 'full_time',
                'initials' => 'MS',
                'avatar' => 'bg-sky-500',
                'base_salary' => 7200.00,
                'hours' => 160,
                'allowances' => 300.00,
                'deductions' => 980.00,
                'net_pay' => 6520.00,
            ],
            [
                'id' => 3,
                'employee_id' => 3,
                'name' => 'Amelia Lewis',
                'title' => 'Marketing Lead',
                'department_id' => 'marketing',
                'employment_type' => 'full_time',
                'initials' => 'AL',
                'avatar' => 'bg-pink-500',
                'base_salary' => 9100.00,
                'hours' => 152,
                'allowances' => 600.00,
                'deductions' => 1450.00,
                'net_pay' => 8250.00,
            ],
            [
                'id' => 4,
                'employee_id' => 4,
                'name' => 'Robert Wilson',
                'title' => 'Backend Engineer',
                'department_id' => 'engineering',
                'employment_type' => 'full_time',
                'initials' => 'RW',
                'avatar' => 'bg-rose-500',
                'base_salary' => 8800.00,
                'hours' => 168,
                'allowances' => 250.00,
                'deductions' => 1100.00,
                'net_pay' => 7950.00,
            ],
            [
                'id' => 5,
                'employee_id' => 5,
                'name' => 'Elena Cruz',
                'title' => 'UX Researcher',
                'department_id' => 'design',
                'employment_type' => 'contract',
                'initials' => 'EC',
                'avatar' => 'bg-indigo-600',
                'base_salary' => 6950.00,
                'hours' => 160,
                'allowances' => 400.00,
                'deductions' => 850.00,
                'net_pay' => 6500.00,
            ],
            [
                'id' => 6,
                'employee_id' => 6,
                'name' => 'David Park',
                'title' => 'DevOps Engineer',
                'department_id' => 'engineering',
                'employment_type' => 'full_time',
                'initials' => 'DP',
                'avatar' => 'bg-emerald-600',
                'base_salary' => 8300.00,
                'hours' => 160,
                'allowances' => 350.00,
                'deductions' => 1050.00,
                'net_pay' => 7600.00,
            ],
            [
                'id' => 7,
                'employee_id' => 7,
                'name' => 'Sofia Martinez',
                'title' => 'HR Specialist',
                'department_id' => 'hr',
                'employment_type' => 'full_time',
                'initials' => 'SM',
                'avatar' => 'bg-amber-500',
                'base_salary' => 6400.00,
                'hours' => 160,
                'allowances' => 280.00,
                'deductions' => 920.00,
                'net_pay' => 5760.00,
            ],
            [
                'id' => 8,
                'employee_id' => 8,
                'name' => 'Liam Chen',
                'title' => 'Data Analyst',
                'department_id' => 'engineering',
                'employment_type' => 'part_time',
                'initials' => 'LC',
                'avatar' => 'bg-cyan-600',
                'base_salary' => 7100.00,
                'hours' => 155,
                'allowances' => 320.00,
                'deductions' => 980.00,
                'net_pay' => 6440.00,
            ],
            [
                'id' => 9,
                'employee_id' => 9,
                'name' => 'Nora Abdullah',
                'title' => 'Finance Manager',
                'department_id' => 'finance',
                'employment_type' => 'full_time',
                'initials' => 'NA',
                'avatar' => 'bg-teal-600',
                'base_salary' => 9800.00,
                'hours' => 160,
                'allowances' => 500.00,
                'deductions' => 1600.00,
                'net_pay' => 8700.00,
            ],
            [
                'id' => 10,
                'employee_id' => 10,
                'name' => 'Owen Blake',
                'title' => 'QA Engineer',
                'department_id' => 'engineering',
                'employment_type' => 'full_time',
                'initials' => 'OB',
                'avatar' => 'bg-orange-500',
                'base_salary' => 6800.00,
                'hours' => 160,
                'allowances' => 220.00,
                'deductions' => 860.00,
                'net_pay' => 6160.00,
            ],
        ];

        $money = fn (float $amount): string => '$' . number_format($amount, 2);
        $departments = [
            '' => 'All Departments',
            'design' => 'Design',
            'engineering' => 'Engineering',
            'marketing' => 'Marketing',
            'hr' => 'HR',
            'finance' => 'Finance',
        ];
        $employmentTypes = [
            '' => 'All Employment Types',
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
        ];
    @endphp

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Payroll Management</h2>
            <p class="mt-1 text-sm text-slate-500">Review and approve employee compensation for the current cycle.</p>
        </div>
        <a
            href="{{ route('payroll.process') }}"
            id="btn-go-process-payroll"
            class="inline-flex shrink-0 items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-slate-800"
            data-action="navigate-process"
        >
            Process Payroll
        </a>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" data-payroll-summary>
        @foreach ($summary as $card)
            <div
                class="rounded-xl border border-slate-200 bg-white px-5 py-4"
                data-summary-key="{{ $card['key'] }}"
            >
                <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">{{ $card['label'] }}</p>
                <p class="mt-2 text-xl font-bold tracking-tight text-slate-900" data-summary-value>{{ $card['value'] }}</p>
                @if ($card['hint'])
                    <p class="mt-1 text-xs font-medium text-emerald-600" data-summary-hint>{{ $card['hint'] }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <form
            id="payroll-filters"
            method="GET"
            action="{{ route('payroll.index') }}"
            class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
            data-filter-form
        >
            <div class="flex flex-wrap items-center gap-2">
                <label class="sr-only" for="department_id">Department</label>
                <select
                    id="department_id"
                    name="department_id"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                >
                    @foreach ($departments as $value => $label)
                        <option value="{{ $value }}" @selected(request('department_id') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label class="sr-only" for="employment_type">Employment Type</label>
                <select
                    id="employment_type"
                    name="employment_type"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 focus:outline-none"
                >
                    @foreach ($employmentTypes as $value => $label)
                        <option value="{{ $value }}" @selected(request('employment_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span data-pagination-label>Showing 1–10 of 142 employees</span>
                <input type="hidden" name="page" id="page" value="{{ request('page', 1) }}">
                <button type="submit" class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600" aria-label="Apply filters">
                    @include('Payroll.partials.icons', ['name' => 'filter', 'class' => 'h-4 w-4'])
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm" id="payroll-management-table">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80">
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Employee</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Base Salary</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Hours</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Allowances</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Deductions</th>
                        <th class="px-5 py-3 text-xs font-semibold tracking-wider text-slate-500 uppercase">Net Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" data-employee-table-body>
                    @foreach ($employees as $employee)
                        <tr
                            class="transition-colors hover:bg-slate-50/70"
                            data-employee-id="{{ $employee['employee_id'] }}"
                            data-department-id="{{ $employee['department_id'] }}"
                            data-employment-type="{{ $employee['employment_type'] }}"
                            data-base-salary="{{ $employee['base_salary'] }}"
                            data-hours="{{ $employee['hours'] }}"
                            data-allowances="{{ $employee['allowances'] }}"
                            data-deductions="{{ $employee['deductions'] }}"
                            data-net-pay="{{ $employee['net_pay'] }}"
                        >
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white {{ $employee['avatar'] }}">
                                        {{ $employee['initials'] }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-900" data-field="name">{{ $employee['name'] }}</p>
                                        <p class="text-xs text-slate-500" data-field="title">{{ $employee['title'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-700" data-field="base_salary">{{ $money($employee['base_salary']) }}</td>
                            <td class="px-5 py-4 text-slate-700" data-field="hours">{{ $employee['hours'] }}</td>
                            <td class="px-5 py-4 font-medium text-emerald-600" data-field="allowances">+{{ $money($employee['allowances']) }}</td>
                            <td class="px-5 py-4 font-medium text-red-500" data-field="deductions">-{{ $money($employee['deductions']) }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900" data-field="net_pay">{{ $money($employee['net_pay']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-between" data-pagination>
            <a
                href="{{ route('payroll.index', array_merge(request()->query(), ['page' => max(1, (int) request('page', 1) - 1)])) }}"
                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-slate-300 pointer-events-none"
                data-page-prev
                aria-disabled="true"
            >
                @include('Payroll.partials.icons', ['name' => 'chevron-left', 'class' => 'h-4 w-4'])
                Previous
            </a>

            <nav class="flex items-center gap-1" aria-label="Pagination">
                @foreach ([1, 2, 3] as $pageNum)
                    <a
                        href="{{ route('payroll.index', array_merge(request()->query(), ['page' => $pageNum])) }}"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-medium transition-colors {{ (int) request('page', 1) === $pageNum ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}"
                        data-page="{{ $pageNum }}"
                    >{{ $pageNum }}</a>
                @endforeach
                <span class="px-1 text-sm text-slate-400">…</span>
                <a
                    href="{{ route('payroll.index', array_merge(request()->query(), ['page' => 15])) }}"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100"
                    data-page="15"
                >15</a>
            </nav>

            <a
                href="{{ route('payroll.index', array_merge(request()->query(), ['page' => (int) request('page', 1) + 1])) }}"
                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100"
                data-page-next
            >
                Next
                @include('Payroll.partials.icons', ['name' => 'chevron-right', 'class' => 'h-4 w-4'])
            </a>
        </div>
    </div>
@endsection
