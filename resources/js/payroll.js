/**
 * Payroll frontend helpers.
 * Backend routes are placeholders — wire these URLs when the API is ready.
 */
const appEl = () => document.getElementById('payroll-app');

export function getPayrollConfig() {
    const el = appEl();
    return {
        csrf: el?.dataset.csrf ?? document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        processUrl: el?.dataset.processUrl ?? '/api/payroll/process',
        listUrl: el?.dataset.listUrl ?? '/api/payroll',
        payslipUrlTemplate: el?.dataset.payslipUrlTemplate ?? '/api/payroll/:id/payslip',
    };
}

function formatMoney(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(amount) || 0);
}

function setButtonLoading(button, loading) {
    if (!button) return;
    button.disabled = loading;
    button.dataset.loading = loading ? 'true' : 'false';
    const label = button.querySelector('[data-label]');
    const spinner = button.querySelector('[data-spinner]');
    if (label) label.classList.toggle('opacity-0', loading);
    if (spinner) spinner.classList.toggle('hidden', !loading);
}

/**
 * POST /api/payroll/process
 * Body: { pay_period: "2026-07", department_id?: "engineering" }
 */
export async function processPayroll(payPeriod, departmentId = '') {
    const { csrf, processUrl } = getPayrollConfig();

    const body = { pay_period: payPeriod };
    if (departmentId) {
        body.department_id = departmentId;
    }

    const response = await fetch(processUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || `Payroll process failed (${response.status})`);
    }

    return response.json();
}

/**
 * GET /api/payroll?pay_period=YYYY-MM&department_id=
 */
export async function fetchPayrollList(payPeriod, departmentId = '') {
    const { csrf, listUrl } = getPayrollConfig();
    const url = new URL(listUrl, window.location.origin);
    url.searchParams.set('pay_period', payPeriod);
    if (departmentId) {
        url.searchParams.set('department_id', departmentId);
    }

    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        throw new Error(`Failed to load payroll (${response.status})`);
    }

    return response.json();
}

/**
 * GET /api/payroll/:id/payslip
 */
export async function fetchPayslip(payrollId) {
    const { csrf, payslipUrlTemplate } = getPayrollConfig();
    const url = payslipUrlTemplate.replace(':id', encodeURIComponent(payrollId));

    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        throw new Error(`Failed to load payslip (${response.status})`);
    }

    return response.json();
}

function renderPayrollRows(tbody, rows) {
    if (!tbody) return;

    if (!rows?.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                    No payroll records for this period yet. Click Process Payroll to calculate.
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = rows
        .map(
            (row) => `
        <tr
            class="transition-colors hover:bg-slate-50/70"
            data-payroll-id="${row.id ?? ''}"
            data-employee-id="${row.employee_id ?? ''}"
            data-employee-name="${row.employee_name ?? row.name ?? ''}"
            data-base-salary="${row.base_salary ?? 0}"
            data-allowances="${row.allowances ?? 0}"
            data-deductions="${row.deductions ?? 0}"
            data-net-pay="${row.net_pay ?? 0}"
            data-job-title="${row.job_title ?? row.title ?? ''}"
            data-department-id="${row.department_id ?? ''}"
            data-pay-period="${row.pay_period ?? ''}"
        >
            <td class="px-5 py-4">
                <p class="font-semibold text-slate-900">${row.employee_name ?? row.name ?? '—'}</p>
                <p class="text-xs text-slate-500">${row.job_title ?? row.title ?? ''}</p>
            </td>
            <td class="px-5 py-4 text-slate-700">${formatMoney(row.base_salary)}</td>
            <td class="px-5 py-4 font-medium text-emerald-600">+${formatMoney(row.allowances)}</td>
            <td class="px-5 py-4 font-medium text-red-500">-${formatMoney(row.deductions)}</td>
            <td class="px-5 py-4 font-bold text-slate-900">${formatMoney(row.net_pay)}</td>
            <td class="px-5 py-4 text-right">
                <button
                    type="button"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-50"
                    data-action="view-payslip"
                    data-payroll-id="${row.id ?? ''}"
                    data-employee-id="${row.employee_id ?? ''}"
                >
                    View Payslip
                </button>
            </td>
        </tr>`
        )
        .join('');
}

function updateRowCount() {
    const countEl = document.querySelector('[data-row-count]');
    if (!countEl) return;
    const visible = document.querySelectorAll('[data-payroll-table-body] tr[data-payroll-id]:not(.hidden)').length;
    countEl.textContent = `${visible} employee${visible === 1 ? '' : 's'}`;
}

/**
 * Client-side department filter. Empty value = show all (default).
 * Backend can later filter via ?department_id= on GET /api/payroll
 */
function filterByDepartment(departmentId = '') {
    const rows = document.querySelectorAll('[data-payroll-table-body] tr[data-payroll-id]');
    let emptyRow = document.querySelector('[data-payroll-table-body] tr[data-empty-filter]');

    rows.forEach((row) => {
        const match = !departmentId || row.dataset.departmentId === departmentId;
        row.classList.toggle('hidden', !match);
    });

    const visibleCount = [...rows].filter((row) => !row.classList.contains('hidden')).length;

    if (visibleCount === 0 && rows.length > 0) {
        if (!emptyRow) {
            const tbody = document.querySelector('[data-payroll-table-body]');
            emptyRow = document.createElement('tr');
            emptyRow.dataset.emptyFilter = 'true';
            emptyRow.innerHTML = `
                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                    No employees found for this department.
                </td>`;
            tbody?.appendChild(emptyRow);
        }
        emptyRow.classList.remove('hidden');
    } else if (emptyRow) {
        emptyRow.classList.add('hidden');
    }

    updateRowCount();
}

function fillPayslipModal(row) {
    const modal = document.getElementById('payslip-modal');
    if (!modal || !row) return;

    modal.querySelector('[data-field="employee_name"]').textContent = row.dataset.employeeName || '—';
    modal.querySelector('[data-field="job_title"]').textContent = row.dataset.jobTitle || '—';
    modal.querySelector('[data-field="pay_period"]').textContent = row.dataset.payPeriod || document.getElementById('pay_period')?.selectedOptions?.[0]?.text || '—';
    modal.querySelector('[data-field="base_salary"]').textContent = formatMoney(row.dataset.baseSalary);
    modal.querySelector('[data-field="allowances"]').textContent = formatMoney(row.dataset.allowances);
    modal.querySelector('[data-field="deductions"]').textContent = formatMoney(row.dataset.deductions);
    modal.querySelector('[data-field="net_pay"]').textContent = formatMoney(row.dataset.netPay);
    modal.querySelector('[data-field="employee_id"]').textContent = row.dataset.employeeId || '—';
    modal.dataset.payrollId = row.dataset.payrollId || '';
}

function openPayslipModal() {
    const modal = document.getElementById('payslip-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
}

function closePayslipModal() {
    const modal = document.getElementById('payslip-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
}

async function handleProcessClick(event) {
    const button = event.currentTarget;
    const periodSelect = document.getElementById('pay_period');
    const departmentSelect = document.getElementById('department_id');
    const statusEl = document.getElementById('payroll-process-status');
    const tbody = document.querySelector('[data-payroll-table-body]');
    const payPeriod = periodSelect?.value;
    const departmentId = departmentSelect?.value || '';

    if (!payPeriod) {
        statusEl && (statusEl.textContent = 'Please select a pay period first.');
        periodSelect?.focus();
        return;
    }

    setButtonLoading(button, true);
    if (statusEl) {
        statusEl.textContent = 'Processing payroll…';
        statusEl.className = 'text-sm text-slate-500';
    }

    try {
        let payload;
        try {
            payload = await processPayroll(payPeriod, departmentId);
        } catch (apiError) {
            console.warn('[Payroll] API not available yet — using demo refresh.', apiError.message);
            payload = { data: null, demo: true, message: 'Demo mode: connect POST /api/payroll/process when ready.' };
        }

        if (payload?.data) {
            renderPayrollRows(tbody, payload.data);
        } else {
            try {
                const list = await fetchPayrollList(payPeriod, departmentId);
                renderPayrollRows(tbody, list.data ?? list);
            } catch {
                document.querySelectorAll('[data-payroll-table-body] tr').forEach((tr) => {
                    tr.dataset.payPeriod = payPeriod;
                });
            }
        }

        filterByDepartment(departmentId);

        if (statusEl) {
            const label = periodSelect.selectedOptions[0]?.text || payPeriod;
            statusEl.textContent = payload?.demo
                ? `Ready for ${label}. Backend API not connected yet — UI flow works.`
                : `Payroll processed successfully for ${label}.`;
            statusEl.className = 'text-sm text-emerald-600';
        }
    } catch (error) {
        if (statusEl) {
            statusEl.textContent = error.message || 'Something went wrong while processing payroll.';
            statusEl.className = 'text-sm text-red-600';
        }
    } finally {
        setButtonLoading(button, false);
    }
}

function initPayrollProcessingPage() {
    const processBtn = document.getElementById('btn-process-payroll');
    if (!processBtn) return;

    processBtn.addEventListener('click', handleProcessClick);

    document.getElementById('pay_period')?.addEventListener('change', (e) => {
        const statusEl = document.getElementById('payroll-process-status');
        if (statusEl) {
            statusEl.textContent = `Selected period: ${e.target.selectedOptions[0]?.text || e.target.value}`;
            statusEl.className = 'text-sm text-slate-500';
        }
    });

    document.getElementById('department_id')?.addEventListener('change', (e) => {
        filterByDepartment(e.target.value || '');
    });

    // Apply department filter on load if a value is already selected
    filterByDepartment(document.getElementById('department_id')?.value || '');

    document.addEventListener('click', (e) => {
        const viewBtn = e.target.closest('[data-action="view-payslip"]');
        if (viewBtn) {
            const row = viewBtn.closest('tr');
            fillPayslipModal(row);
            openPayslipModal();
            return;
        }

        if (e.target.closest('[data-action="close-payslip"]')) {
            closePayslipModal();
        }
    });

    document.getElementById('btn-print-payslip')?.addEventListener('click', () => {
        window.print();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePayslipModal();
    });
}

document.addEventListener('DOMContentLoaded', initPayrollProcessingPage);
