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
    @php
        /**
         * Demo rows — replace with $payrolls from controller when backend is ready.
         * Expected fields: id, employee_id, employee_name, job_title, department_id,
         * base_salary, allowances, deductions, net_pay, pay_period
         */
        $payPeriods = [
            '2026-07' => 'July 2026',
            '2026-06' => 'June 2026',
            '2026-05' => 'May 2026',
            '2023-10' => 'October 2023',
        ];

        $departments = [
            '' => 'All Departments',
            'design' => 'Design',
            'engineering' => 'Engineering',
            'marketing' => 'Marketing',
        ];

        $selectedPeriod = request('pay_period', '2026-07');
        $selectedDepartment = request('department_id', '');

        $payrolls = [
            [
                'id' => 101,
                'employee_id' => 1,
                'employee_name' => 'Jane Doe',
                'job_title' => 'Product Designer',
                'department_id' => 'design',
                'base_salary' => 8500.00,
                'allowances' => 450.00,
                'deductions' => 1200.00,
                'net_pay' => 7750.00,
                'pay_period' => $selectedPeriod,
            ],
            [
                'id' => 102,
                'employee_id' => 2,
                'employee_name' => 'Mark Smith',
                'job_title' => 'Frontend Engineer',
                'department_id' => 'engineering',
                'base_salary' => 7200.00,
                'allowances' => 300.00,
                'deductions' => 980.00,
                'net_pay' => 6520.00,
                'pay_period' => $selectedPeriod,
            ],
            [
                'id' => 103,
                'employee_id' => 3,
                'employee_name' => 'Amelia Lewis',
                'job_title' => 'Marketing Lead',
                'department_id' => 'marketing',
                'base_salary' => 9100.00,
                'allowances' => 600.00,
                'deductions' => 1450.00,
                'net_pay' => 8250.00,
                'pay_period' => $selectedPeriod,
            ],
            [
                'id' => 104,
                'employee_id' => 4,
                'employee_name' => 'Robert Wilson',
                'job_title' => 'Backend Engineer',
                'department_id' => 'engineering',
                'base_salary' => 8800.00,
                'allowances' => 250.00,
                'deductions' => 1100.00,
                'net_pay' => 7950.00,
                'pay_period' => $selectedPeriod,
            ],
            [
                'id' => 105,
                'employee_id' => 5,
                'employee_name' => 'Elena Cruz',
                'job_title' => 'UX Researcher',
                'department_id' => 'design',
                'base_salary' => 6950.00,
                'allowances' => 400.00,
                'deductions' => 850.00,
                'net_pay' => 6500.00,
                'pay_period' => $selectedPeriod,
            ],
        ];

        $money = fn (float $amount): string => '$' . number_format($amount, 2);
    @endphp

    <div class="mb-6">
        <a href="{{ route('payroll.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition-colors hover:text-slate-800">
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
        action="{{ route('payroll.process') }}"
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
                            <option value="{{ $value }}" @selected($value === $selectedPeriod)>{{ $label }}</option>
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
                            <option value="{{ $value }}" @selected($value === $selectedDepartment)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center">
                <p id="payroll-process-status" class="text-sm text-slate-500" role="status" aria-live="polite">
                    Ready to process {{ $payPeriods[$selectedPeriod] ?? $selectedPeriod }}.
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
                {{ count($payrolls) }} employees
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
                    @forelse ($payrolls as $row)
                        <tr
                            class="transition-colors hover:bg-slate-50/70"
                            data-payroll-id="{{ $row['id'] }}"
                            data-employee-id="{{ $row['employee_id'] }}"
                            data-employee-name="{{ $row['employee_name'] }}"
                            data-job-title="{{ $row['job_title'] }}"
                            data-base-salary="{{ $row['base_salary'] }}"
                            data-allowances="{{ $row['allowances'] }}"
                            data-deductions="{{ $row['deductions'] }}"
                            data-net-pay="{{ $row['net_pay'] }}"
                            data-pay-period="{{ $row['pay_period'] }}"
                        >
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $row['employee_name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $row['job_title'] }}</p>
                            </td>
                            <td class="px-5 py-4 text-slate-700">{{ $money($row['base_salary']) }}</td>
                            <td class="px-5 py-4 font-medium text-emerald-600">+{{ $money($row['allowances']) }}</td>
                            <td class="px-5 py-4 font-medium text-red-500">-{{ $money($row['deductions']) }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $money($row['net_pay']) }}</td>
                            <td class="px-5 py-4 text-right">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50"
                                    data-action="view-payslip"
                                    data-payroll-id="{{ $row['id'] }}"
                                    data-employee-id="{{ $row['employee_id'] }}"
                                    data-payslip-url="{{ url('/api/payroll/'.$row['id'].'/payslip') }}"
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
    </div>
@endsection

@push('modals')
    @include('Payroll.partials.payslip-modal')
@endpush

@push('scripts')
    {{-- Fallback when Vite is not running; mirrors resources/js/payroll.js handlers --}}
    @if (! file_exists(public_path('build/manifest.json')) && ! file_exists(public_path('hot')))
        <script>
            (function () {
                const app = document.getElementById('payroll-app');
                const csrf = app?.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '';
                const processUrl = app?.dataset.processUrl || '/api/payroll/process';
                const money = (n) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(Number(n) || 0);

                async function processPayroll(payPeriod) {
                    const res = await fetch(processUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ pay_period: payPeriod }),
                    });
                    if (!res.ok) throw new Error('API not ready');
                    return res.json();
                }

                function openModal() {
                    const modal = document.getElementById('payslip-modal');
                    modal?.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                function closeModal() {
                    const modal = document.getElementById('payslip-modal');
                    modal?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                function fillModal(row) {
                    const modal = document.getElementById('payslip-modal');
                    if (!modal || !row) return;
                    modal.querySelector('[data-field="employee_name"]').textContent = row.dataset.employeeName || '—';
                    modal.querySelector('[data-field="job_title"]').textContent = row.dataset.jobTitle || '—';
                    modal.querySelector('[data-field="pay_period"]').textContent =
                        row.dataset.payPeriod || document.getElementById('pay_period')?.selectedOptions?.[0]?.text || '—';
                    modal.querySelector('[data-field="base_salary"]').textContent = money(row.dataset.baseSalary);
                    modal.querySelector('[data-field="allowances"]').textContent = money(row.dataset.allowances);
                    modal.querySelector('[data-field="deductions"]').textContent = money(row.dataset.deductions);
                    modal.querySelector('[data-field="net_pay"]').textContent = money(row.dataset.netPay);
                    modal.querySelector('[data-field="employee_id"]').textContent = row.dataset.employeeId || '—';
                }

                const btn = document.getElementById('btn-process-payroll');
                const statusEl = document.getElementById('payroll-process-status');
                const period = document.getElementById('pay_period');

                btn?.addEventListener('click', async () => {
                    if (!period?.value) {
                        statusEl.textContent = 'Please select a pay period first.';
                        return;
                    }
                    btn.disabled = true;
                    statusEl.textContent = 'Processing payroll…';
                    statusEl.className = 'text-sm text-slate-500';
                    try {
                        try {
                            await processPayroll(period.value);
                            statusEl.textContent = `Payroll processed successfully for ${period.selectedOptions[0].text}.`;
                            statusEl.className = 'text-sm text-emerald-600';
                        } catch (e) {
                            document.querySelectorAll('[data-payroll-table-body] tr').forEach((tr) => {
                                tr.dataset.payPeriod = period.value;
                            });
                            statusEl.textContent = `Ready for ${period.selectedOptions[0].text}. Backend API not connected yet — UI flow works.`;
                            statusEl.className = 'text-sm text-emerald-600';
                        }
                    } finally {
                        btn.disabled = false;
                    }
                });

                document.addEventListener('click', (e) => {
                    const viewBtn = e.target.closest('[data-action="view-payslip"]');
                    if (viewBtn) {
                        fillModal(viewBtn.closest('tr'));
                        openModal();
                        return;
                    }
                    if (e.target.closest('[data-action="close-payslip"]')) closeModal();
                });

                document.getElementById('btn-print-payslip')?.addEventListener('click', () => window.print());
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
            })();
        </script>
    @endif
@endpush
