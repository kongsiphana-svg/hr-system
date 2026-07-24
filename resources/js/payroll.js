/**
 * Payroll workflow UI
 * Generate → Draft → Review → Pending Review → Approve → Payslip → Mark Paid
 */

const STATUS_BADGES = {
    draft: 'bg-slate-100 text-slate-700 border-slate-200',
    pending_review: 'bg-amber-50 text-amber-800 border-amber-200',
    approved: 'bg-emerald-50 text-emerald-800 border-emerald-200',
    paid: 'bg-indigo-50 text-indigo-800 border-indigo-200',
};

const STATUS_LABELS = {
    draft: 'Draft',
    pending_review: 'Pending Review',
    approved: 'Approved',
    paid: 'Paid',
};

const appEl = () => document.getElementById('payroll-app');

export function getPayrollConfig() {
    const el = appEl();
    return {
        csrf: el?.dataset.csrf ?? document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        processUrl: el?.dataset.processUrl ?? '/api/payroll/process',
        listUrl: el?.dataset.listUrl ?? '/api/payroll',
        payslipUrlTemplate: el?.dataset.payslipUrlTemplate ?? '/api/payroll/:id/payslip',
        reviewUrlTemplate: el?.dataset.reviewUrlTemplate ?? '/api/payroll/:id/review',
        approveUrlTemplate: el?.dataset.approveUrlTemplate ?? '/api/payroll/:id/approve',
        markPaidUrlTemplate: el?.dataset.markPaidUrlTemplate ?? '/api/payroll/:id/mark-paid',
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
    if (spinner) {
        spinner.classList.toggle('hidden', !loading);
        spinner.classList.toggle('flex', loading);
    }
}

async function apiPost(url, body = null) {
    const { csrf } = getPayrollConfig();
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: body ? JSON.stringify(body) : JSON.stringify({}),
    });

    const payload = await response.json().catch(() => ({}));
    if (!response.ok) {
        throw new Error(payload.message || `Request failed (${response.status})`);
    }
    return payload;
}

export async function processPayroll(payPeriod, departmentId = '', search = '') {
    const { processUrl } = getPayrollConfig();
    const body = { pay_period: payPeriod };
    if (departmentId) body.department_id = departmentId;
    if (search) body.search = search;
    return apiPost(processUrl, body);
}

function statusBadgeHtml(status) {
    const label = STATUS_LABELS[status] || status;
    const classes = STATUS_BADGES[status] || STATUS_BADGES.draft;
    return `<span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold ${classes}" data-status-badge>${label}</span>`;
}

function actionsHtml(status) {
    if (status === 'draft') {
        return `<button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="review">Review</button>`;
    }
    if (status === 'pending_review') {
        return `
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="review">Review</button>
            <button type="button" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700" data-action="approve">Approve</button>`;
    }
    if (status === 'approved') {
        return `
            <button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="view-payslip">View Payslip</button>
            <button type="button" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700" data-action="mark-paid">Mark as Paid</button>`;
    }
    return `<button type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50" data-action="view-payslip">View Payslip</button>`;
}

function initialsFromName(name = '') {
    const parts = String(name).trim().split(/\s+/).filter(Boolean);
    return parts
        .slice(0, 2)
        .map((p) => p[0]?.toUpperCase() || '')
        .join('') || '?';
}

function rowDataset(row) {
    return {
        payrollId: row.id ?? '',
        employeeId: row.employee_id ?? '',
        employeeName: row.employee_name ?? row.name ?? '',
        jobTitle: row.job_title ?? row.title ?? '',
        department: row.department ?? row.department_id ?? '',
        email: row.email ?? '',
        baseSalary: row.base_salary ?? 0,
        hoursWorked: row.hours_worked ?? row.hours ?? 0,
        standardHours: row.standard_hours ?? 160,
        approvedLeaveDays: row.approved_leave_days ?? 0,
        overtimeHours: row.overtime_hours ?? 0,
        allowances: row.allowances ?? 0,
        deductions: row.deductions ?? 0,
        unpaidLeaveDeduction: row.unpaid_leave_deduction ?? 0,
        grossPay: row.gross_pay ?? row.gross_salary ?? 0,
        netPay: row.net_pay ?? row.net_salary ?? 0,
        status: row.status ?? 'draft',
        payPeriod: row.pay_period ?? '',
    };
}

function applyRowDataset(tr, data) {
    Object.entries({
        payrollId: 'payrollId',
        employeeId: 'employeeId',
        employeeName: 'employeeName',
        jobTitle: 'jobTitle',
        department: 'department',
        email: 'email',
        baseSalary: 'baseSalary',
        hoursWorked: 'hoursWorked',
        standardHours: 'standardHours',
        approvedLeaveDays: 'approvedLeaveDays',
        overtimeHours: 'overtimeHours',
        allowances: 'allowances',
        deductions: 'deductions',
        unpaidLeaveDeduction: 'unpaidLeaveDeduction',
        grossPay: 'grossPay',
        netPay: 'netPay',
        status: 'status',
        payPeriod: 'payPeriod',
    }).forEach(([datasetKey, sourceKey]) => {
        tr.dataset[datasetKey] = data[sourceKey] ?? '';
    });
}

function renderPayrollRows(tbody, rows) {
    if (!tbody) return;

    if (!rows?.length) {
        tbody.innerHTML = `
            <tr data-empty-row>
                <td colspan="9" class="px-5 py-10 text-center text-sm text-slate-500">
                    No payroll records for this period yet. Set filters and click <strong>Generate Payroll</strong>.
                </td>
            </tr>`;
        return;
    }

    tbody.innerHTML = rows
        .map((row) => {
            const d = rowDataset(row);
            return `
        <tr class="transition-colors hover:bg-slate-50/70"
            data-payroll-id="${d.payrollId}"
            data-employee-id="${d.employeeId}"
            data-employee-name="${d.employeeName}"
            data-job-title="${d.jobTitle}"
            data-department="${d.department}"
            data-email="${d.email}"
            data-base-salary="${d.baseSalary}"
            data-hours-worked="${d.hoursWorked}"
            data-standard-hours="${d.standardHours}"
            data-approved-leave-days="${d.approvedLeaveDays}"
            data-overtime-hours="${d.overtimeHours}"
            data-allowances="${d.allowances}"
            data-deductions="${d.deductions}"
            data-unpaid-leave-deduction="${d.unpaidLeaveDeduction}"
            data-gross-pay="${d.grossPay}"
            data-net-pay="${d.netPay}"
            data-status="${d.status}"
            data-pay-period="${d.payPeriod}">
            <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold text-white">${initialsFromName(d.employeeName)}</span>
                    <div>
                        <p class="font-semibold text-slate-900">${d.employeeName || '—'}</p>
                        <p class="text-xs text-slate-500">${d.jobTitle || ''}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4 text-slate-700">${d.department || '—'}</td>
            <td class="px-5 py-4 text-slate-700">${formatMoney(d.baseSalary)}</td>
            <td class="px-5 py-4 text-slate-700">${Number(d.hoursWorked).toFixed(1)}h</td>
            <td class="px-5 py-4 text-slate-700">${Number(d.approvedLeaveDays).toFixed(1)} days</td>
            <td class="px-5 py-4 font-medium text-slate-900">${formatMoney(d.grossPay)}</td>
            <td class="px-5 py-4 font-bold text-slate-900">${formatMoney(d.netPay)}</td>
            <td class="px-5 py-4">${statusBadgeHtml(d.status)}</td>
            <td class="px-5 py-4 text-right">
                <div class="inline-flex flex-wrap justify-end gap-2" data-actions>${actionsHtml(d.status)}</div>
            </td>
        </tr>`;
        })
        .join('');
}

function updateSummary(summary) {
    if (!summary) return;
    const map = {
        total_gross: summary.total_gross_pay,
        total_net: summary.total_net_pay ?? summary.net_disbursable,
        total_deductions: summary.total_deductions,
        employees_processed: summary.employees_count,
    };

    Object.entries(map).forEach(([key, value]) => {
        const card = document.querySelector(`[data-summary-key="${key}"] [data-summary-value]`);
        if (!card || value === undefined || value === null) return;
        card.textContent = key === 'employees_processed' ? String(value) : formatMoney(value);
    });
}

function fillFromRow(modal, row) {
    if (!modal || !row) return;
    const set = (field, value) => {
        const el = modal.querySelector(`[data-field="${field}"]`);
        if (el) el.textContent = value;
    };

    set('employee_name', row.dataset.employeeName || '—');
    set('job_title', row.dataset.jobTitle || '—');
    set('department', row.dataset.department || '—');
    set('email', row.dataset.email || '—');
    set('employee_id', row.dataset.employeeId || '—');
    set('pay_period', row.dataset.payPeriod || document.getElementById('pay_period')?.selectedOptions?.[0]?.text || '—');
    set('base_salary', formatMoney(row.dataset.baseSalary));
    set('hours_worked', `${Number(row.dataset.standardHours || 0).toFixed(1)}h`);
    set('approved_leave_days', `${Number(row.dataset.approvedLeaveDays || 0).toFixed(1)} days`);
    set('overtime_hours', `${Number(row.dataset.overtimeHours || 0).toFixed(1)}h`);
    set('allowances', formatMoney(row.dataset.allowances));
    set('deductions', formatMoney(row.dataset.deductions));
    set('unpaid_leave_deduction', formatMoney(row.dataset.unpaidLeaveDeduction));
    set('gross_pay', formatMoney(row.dataset.grossPay));
    set('net_pay', formatMoney(row.dataset.netPay));

    const badge = modal.querySelector('[data-field="status_badge"]');
    if (badge) {
        const status = row.dataset.status || 'draft';
        badge.className = `inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold ${STATUS_BADGES[status] || STATUS_BADGES.draft}`;
        badge.textContent = STATUS_LABELS[status] || status;
    }

    modal.dataset.payrollId = row.dataset.payrollId || '';
    modal.dataset.status = row.dataset.status || '';
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
}

function syncRowFromPayload(row, data) {
    if (!row || !data) return;
    const mapped = rowDataset(data);
    applyRowDataset(row, mapped);

    const statusCell = row.querySelector('[data-status-badge]')?.parentElement;
    if (statusCell) statusCell.innerHTML = statusBadgeHtml(mapped.status);

    const actions = row.querySelector('[data-actions]');
    if (actions) actions.innerHTML = actionsHtml(mapped.status);

    row.querySelector('[data-field="gross_pay"]')?.replaceChildren();
}

async function handleGenerateClick(button) {
    const periodSelect = document.getElementById('pay_period');
    const departmentSelect = document.getElementById('department_id');
    const searchInput = document.getElementById('search');
    const statusEl = document.getElementById('payroll-process-status');
    const payPeriod = periodSelect?.value;

    if (!payPeriod) {
        if (statusEl) {
            statusEl.textContent = 'Please select a payroll period first.';
            statusEl.className = 'text-sm text-red-600';
        }
        periodSelect?.focus();
        return;
    }

    setButtonLoading(button, true);
    if (statusEl) {
        statusEl.textContent = 'Generating payroll from attendance & approved leave…';
        statusEl.className = 'text-sm text-slate-500';
    }

    try {
        const payload = await processPayroll(
            payPeriod,
            departmentSelect?.value || '',
            searchInput?.value || ''
        );

        const tbody = document.querySelector('[data-payroll-table-body]');
        renderPayrollRows(tbody, payload.data || []);
        updateSummary(payload.summary);

        if (statusEl) {
            statusEl.textContent = payload.message || 'Payroll generated as Draft.';
            statusEl.className = 'text-sm text-emerald-600';
        }

        // Keep URL filters in sync after generate
        const url = new URL(window.location.href);
        url.searchParams.set('pay_period', payPeriod);
        if (departmentSelect?.value) url.searchParams.set('department_id', departmentSelect.value);
        else url.searchParams.delete('department_id');
        if (searchInput?.value) url.searchParams.set('search', searchInput.value);
        else url.searchParams.delete('search');
        window.history.replaceState({}, '', url);
    } catch (error) {
        if (statusEl) {
            statusEl.textContent = error.message || 'Failed to generate payroll.';
            statusEl.className = 'text-sm text-red-600';
        }
    } finally {
        setButtonLoading(button, false);
    }
}

async function transitionPayroll(row, action) {
    const { reviewUrlTemplate, approveUrlTemplate, markPaidUrlTemplate } = getPayrollConfig();
    const id = row.dataset.payrollId;
    if (!id) throw new Error('Missing payroll id.');

    const templates = {
        review: reviewUrlTemplate,
        approve: approveUrlTemplate,
        'mark-paid': markPaidUrlTemplate,
    };

    const url = (templates[action] || '').replace(':id', encodeURIComponent(id));
    if (!url) throw new Error('Unknown action.');

    const payload = await apiPost(url);
    syncRowFromPayload(row, payload.data);
    return payload;
}

function openReviewModal(row) {
    const modal = document.getElementById('review-modal');
    fillFromRow(modal, row);
    const status = row.dataset.status;
    document.getElementById('btn-submit-review')?.classList.toggle('hidden', status !== 'draft');
    document.getElementById('btn-approve-from-review')?.classList.toggle('hidden', status !== 'pending_review' && status !== 'draft');
    openModal('review-modal');
}

function openPayslipModal(row) {
    const modal = document.getElementById('payslip-modal');
    fillFromRow(modal, row);
    openModal('payslip-modal');
}

function findRowByPayrollId(id) {
    return document.querySelector(`[data-payroll-table-body] tr[data-payroll-id="${id}"]`);
}

function initPayrollPage() {
    if (!appEl()) return;

    document.getElementById('btn-generate-payroll')?.addEventListener('click', (e) => {
        handleGenerateClick(e.currentTarget);
    });

    // Also support legacy process page button
    document.getElementById('btn-process-payroll')?.addEventListener('click', (e) => {
        handleGenerateClick(e.currentTarget);
    });

    document.addEventListener('click', async (e) => {
        const actionBtn = e.target.closest('[data-action]');
        if (!actionBtn) return;

        const action = actionBtn.dataset.action;
        const row = actionBtn.closest('tr[data-payroll-id]') || findRowByPayrollId(document.getElementById('review-modal')?.dataset.payrollId);
        const statusEl = document.getElementById('payroll-process-status');

        try {
            if (action === 'review' && row) {
                openReviewModal(row);
                return;
            }

            if (action === 'view-payslip' && row) {
                openPayslipModal(row);
                return;
            }

            if (action === 'close-review') {
                closeModal('review-modal');
                return;
            }

            if (action === 'close-payslip') {
                closeModal('payslip-modal');
                return;
            }

            if (action === 'submit-review') {
                const target = findRowByPayrollId(document.getElementById('review-modal')?.dataset.payrollId);
                if (!target) return;
                actionBtn.disabled = true;
                const payload = await transitionPayroll(target, 'review');
                closeModal('review-modal');
                if (statusEl) {
                    statusEl.textContent = payload.message || 'Submitted for review.';
                    statusEl.className = 'text-sm text-emerald-600';
                }
                actionBtn.disabled = false;
                return;
            }

            if (action === 'approve' || action === 'approve-from-review') {
                const target = row || findRowByPayrollId(document.getElementById('review-modal')?.dataset.payrollId);
                if (!target) return;
                actionBtn.disabled = true;
                const payload = await transitionPayroll(target, 'approve');
                closeModal('review-modal');
                if (statusEl) {
                    statusEl.textContent = payload.message || 'Payroll approved.';
                    statusEl.className = 'text-sm text-emerald-600';
                }
                actionBtn.disabled = false;
                return;
            }

            if (action === 'mark-paid' && row) {
                actionBtn.disabled = true;
                const payload = await transitionPayroll(row, 'mark-paid');
                if (statusEl) {
                    statusEl.textContent = payload.message || 'Marked as paid.';
                    statusEl.className = 'text-sm text-emerald-600';
                }
                actionBtn.disabled = false;
            }
        } catch (error) {
            if (statusEl) {
                statusEl.textContent = error.message || 'Action failed.';
                statusEl.className = 'text-sm text-red-600';
            }
            actionBtn.disabled = false;
        }
    });

    document.getElementById('btn-print-payslip')?.addEventListener('click', () => window.print());

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('review-modal');
            closeModal('payslip-modal');
        }
    });
}

document.addEventListener('DOMContentLoaded', initPayrollPage);
