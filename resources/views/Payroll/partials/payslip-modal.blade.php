{{-- Print-friendly payslip modal. Backend can hydrate via GET /api/payroll/{id}/payslip --}}
<div
    id="payslip-modal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="payslip-modal-title"
>
    <div class="absolute inset-0 bg-slate-900/50 print:hidden" data-action="close-payslip"></div>

    <div class="relative flex min-h-full items-center justify-center p-4 print:block print:p-0">
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl print:max-w-none print:rounded-none print:border-0 print:shadow-none">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 print:hidden">
                <h3 id="payslip-modal-title" class="text-base font-semibold text-slate-900">Employee Payslip</h3>
                <button type="button" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700" data-action="close-payslip" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="payslip-print-area" class="px-6 py-6">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">HR Portal</p>
                        <h4 class="mt-1 text-xl font-bold text-slate-900">Payslip</h4>
                        <p class="mt-1 text-sm text-slate-500">
                            Period: <span data-field="pay_period">—</span>
                        </p>
                    </div>
                    <div class="text-right text-sm text-slate-500">
                        <p>Employee ID</p>
                        <p class="font-medium text-slate-800" data-field="employee_id">—</p>
                    </div>
                </div>

                <div class="mb-6 rounded-xl bg-slate-50 px-4 py-3">
                    <p class="text-lg font-semibold text-slate-900" data-field="employee_name">—</p>
                    <p class="text-sm text-slate-500" data-field="job_title">—</p>
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <dt class="text-slate-500">Base Salary</dt>
                        <dd class="font-medium text-slate-900" data-field="base_salary">—</dd>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <dt class="text-slate-500">Allowances</dt>
                        <dd class="font-medium text-emerald-600" data-field="allowances">—</dd>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <dt class="text-slate-500">Deductions</dt>
                        <dd class="font-medium text-red-500" data-field="deductions">—</dd>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <dt class="text-base font-semibold text-slate-900">Net Pay</dt>
                        <dd class="text-lg font-bold text-slate-900" data-field="net_pay">—</dd>
                    </div>
                </dl>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-6 py-4 print:hidden">
                <button type="button" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50" data-action="close-payslip">
                    Close
                </button>
                <button
                    type="button"
                    id="btn-print-payslip"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                >
                    Print Payslip
                </button>
            </div>
        </div>
    </div>
</div>
