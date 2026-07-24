{{-- Employee payroll detail / HR review modal --}}
<div
    id="review-modal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="review-modal-title"
>
    <div class="absolute inset-0 bg-slate-900/50" data-action="close-review"></div>

    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h3 id="review-modal-title" class="text-base font-semibold text-slate-900">Payroll Review</h3>
                <button type="button" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700" data-action="close-review" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-6">
                <div class="mb-6 rounded-xl bg-slate-50 px-4 py-3">
                    <p class="text-lg font-semibold text-slate-900" data-field="employee_name">—</p>
                    <p class="text-sm text-slate-500">
                        <span data-field="job_title">—</span>
                        · <span data-field="department">—</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-400" data-field="email">—</p>
                </div>

                <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Base Salary</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900" data-field="base_salary">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Hours Worked</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900" data-field="hours_worked">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Approved Leave</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900" data-field="approved_leave_days">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Overtime</dt>
                        <dd class="mt-0.5 font-semibold text-slate-900" data-field="overtime_hours">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Allowances</dt>
                        <dd class="mt-0.5 font-semibold text-emerald-600" data-field="allowances">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2">
                        <dt class="text-xs text-slate-500">Deductions</dt>
                        <dd class="mt-0.5 font-semibold text-red-500" data-field="deductions">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-100 px-3 py-2 sm:col-span-2">
                        <dt class="text-xs text-slate-500">Unpaid Leave Deduction</dt>
                        <dd class="mt-0.5 font-semibold text-red-500" data-field="unpaid_leave_deduction">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        <dt class="text-xs text-slate-500">Gross Salary</dt>
                        <dd class="mt-0.5 text-base font-bold text-slate-900" data-field="gross_pay">—</dd>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                        <dt class="text-xs text-slate-500">Net Salary</dt>
                        <dd class="mt-0.5 text-base font-bold text-slate-900" data-field="net_pay">—</dd>
                    </div>
                </dl>

                <div class="mt-4 flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-sm">
                    <span class="text-slate-500">Payroll Status</span>
                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold" data-field="status_badge">—</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-6 py-4">
                <button type="button" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50" data-action="close-review">
                    Close
                </button>
                <button
                    type="button"
                    id="btn-submit-review"
                    class="hidden rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700"
                    data-action="submit-review"
                >
                    Submit for Review
                </button>
                <button
                    type="button"
                    id="btn-approve-from-review"
                    class="hidden rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700"
                    data-action="approve-from-review"
                >
                    Approve Payroll
                </button>
            </div>
        </div>
    </div>
</div>
