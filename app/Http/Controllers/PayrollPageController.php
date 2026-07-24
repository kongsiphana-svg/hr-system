<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayrollPageController extends Controller
{
    public function index(Request $request): View
    {
        $payPeriod = $request->input('pay_period', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', $payPeriod)) {
            $payPeriod = now()->format('Y-m');
        }

        $query = Payroll::with('employee')
            ->where('pay_period', $payPeriod);

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department', $request->department_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $summaryQuery = clone $query;
        $totalGross = (float) $summaryQuery->sum('gross_pay');
        $totalNet = (float) (clone $query)->sum('net_pay');
        $totalDeductions = (float) (clone $query)->sum('deductions');
        $employeesProcessed = (clone $query)->count();

        $money = fn ($amount) => '$'.number_format((float) $amount, 2);

        $summary = [
            [
                'key' => 'total_gross',
                'label' => 'Total Gross Payroll',
                'value' => $money($totalGross),
                'hint' => 'Before deductions',
            ],
            [
                'key' => 'total_net',
                'label' => 'Total Net Payroll',
                'value' => $money($totalNet),
                'hint' => 'Take-home pay',
            ],
            [
                'key' => 'total_deductions',
                'label' => 'Total Deductions',
                'value' => $money($totalDeductions),
                'hint' => 'Taxes & unpaid leave',
            ],
            [
                'key' => 'employees_processed',
                'label' => 'Employees Processed',
                'value' => (string) $employeesProcessed,
                'hint' => 'In selected period',
            ],
        ];

        $payrolls = $query->orderByDesc('processed_at')->paginate(10)->withQueryString();

        $rows = collect($payrolls->items())->map(function (Payroll $payroll) {
            $emp = $payroll->employee;

            return [
                'id' => $payroll->id,
                'employee_id' => $emp?->id,
                'name' => $emp?->name ?? '—',
                'title' => $emp?->job_title ?? '',
                'department' => $emp?->department ?? '—',
                'email' => $emp?->email ?? '',
                'initials' => $emp?->initials() ?? '?',
                'avatar' => 'bg-indigo-600',
                'base_salary' => (float) $payroll->base_salary,
                'hours_worked' => (float) $payroll->hours_worked,
                'standard_hours' => (int) ($emp?->standard_hours ?: 160),
                'approved_leave_days' => (float) $payroll->approved_leave_days,
                'overtime_hours' => (float) $payroll->overtime_hours,
                'allowances' => (float) $payroll->allowances,
                'deductions' => (float) $payroll->deductions,
                'unpaid_leave_deduction' => (float) $payroll->unpaid_leave_deduction,
                'gross_pay' => (float) $payroll->gross_pay,
                'net_pay' => (float) $payroll->net_pay,
                'status' => $payroll->status,
                'status_label' => $payroll->statusLabel(),
                'pay_period' => $payroll->pay_period,
            ];
        });

        $departments = ['' => 'All Departments'] + Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department', 'department')
            ->all();

        $payPeriods = $this->payPeriodOptions($payPeriod);

        $statusBadges = [
            PayrollGenerator::STATUS_DRAFT => 'bg-slate-100 text-slate-700 border-slate-200',
            PayrollGenerator::STATUS_PENDING_REVIEW => 'bg-amber-50 text-amber-800 border-amber-200',
            PayrollGenerator::STATUS_APPROVED => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            PayrollGenerator::STATUS_PAID => 'bg-indigo-50 text-indigo-800 border-indigo-200',
        ];

        return view('Payroll.index', [
            'summary' => $summary,
            'departments' => $departments,
            'payPeriods' => $payPeriods,
            'payrolls' => $payrolls,
            'rows' => $rows,
            'money' => $money,
            'payPeriod' => $payPeriod,
            'statusBadges' => $statusBadges,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $payPeriod = $request->input('pay_period', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', $payPeriod)) {
            $payPeriod = now()->format('Y-m');
        }

        $query = Payroll::with('employee')
            ->where('pay_period', $payPeriod);

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department', $request->department_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('employee_id')->get();

        $filename = 'payroll-'.$payPeriod.'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($payrolls) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'Employee Name',
                'Department',
                'Job Title',
                'Pay Period',
                'Base Salary',
                'Hours Worked',
                'Approved Leave (days)',
                'Overtime Hours',
                'Allowances',
                'Deductions',
                'Unpaid Leave Deduction',
                'Gross Pay',
                'Net Pay',
                'Status',
            ]);

            foreach ($payrolls as $payroll) {
                $emp = $payroll->employee;
                fputcsv($handle, [
                    $emp?->name ?? '—',
                    $emp?->department ?? '—',
                    $emp?->job_title ?? '',
                    $payroll->pay_period,
                    number_format((float) $payroll->base_salary, 2),
                    number_format((float) $payroll->hours_worked, 1),
                    number_format((float) $payroll->approved_leave_days, 1),
                    number_format((float) $payroll->overtime_hours, 1),
                    number_format((float) $payroll->allowances, 2),
                    number_format((float) $payroll->deductions, 2),
                    number_format((float) $payroll->unpaid_leave_deduction, 2),
                    number_format((float) $payroll->gross_pay, 2),
                    number_format((float) $payroll->net_pay, 2),
                    $payroll->statusLabel(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function process(Request $request): \Illuminate\View\View
    {
        $payPeriod = $request->input('pay_period', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', $payPeriod)) {
            $payPeriod = now()->format('Y-m');
        }

        $query = Payroll::with('employee')
            ->where('pay_period', $payPeriod);

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department', $request->department_id);
            });
        }

        $payrolls = $query->orderByDesc('processed_at')->paginate(10)->withQueryString();

        $departments = ['' => 'All Departments'] + Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department', 'department')
            ->all();

        $payPeriods = $this->payPeriodOptions($payPeriod);

        $money = fn ($amount) => '$'.number_format((float) $amount, 2);

        return view('Payroll.process', [
            'departments' => $departments,
            'payPeriods' => $payPeriods,
            'payPeriod' => $payPeriod,
            'payrolls' => $payrolls,
            'money' => $money,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function payPeriodOptions(string $selected): array
    {
        $options = [];
        $cursor = Carbon::now()->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $key = $cursor->format('Y-m');
            $options[$key] = $cursor->format('F Y');
            $cursor->subMonth();
        }

        if (! isset($options[$selected])) {
            try {
                $options[$selected] = Carbon::createFromFormat('Y-m', $selected)->format('F Y');
            } catch (\Throwable) {
                // ignore invalid period
            }
        }

        return $options;
    }
}
