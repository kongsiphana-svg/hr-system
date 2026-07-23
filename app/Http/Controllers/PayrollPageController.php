<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollPageController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        // Base employee query with filters
        $employeesQuery = Employee::query()
            ->when($request->filled('department_id') && $request->department_id !== 'all', function ($query) use ($request) {
                $query->where('department', $request->department_id);
            })
            ->when($request->filled('employment_type') && $request->employment_type !== 'all', function ($query) use ($request) {
                $query->where('employment_type', $request->employment_type);
            });

        // -------------------------------------------------------------
        // MODE A: MASTER HISTORY VIEW (All Months / Cycles)
        // -------------------------------------------------------------
        if ($selectedMonth === 'all') {
            $historyPayrolls = Payroll::with('employee')
                ->whereHas('employee', function ($q) use ($request) {
                    if ($request->filled('department_id') && $request->department_id !== 'all') {
                        $q->where('department', $request->department_id);
                    }
                    if ($request->filled('employment_type') && $request->employment_type !== 'all') {
                        $q->where('employment_type', $request->employment_type);
                    }
                })
                ->orderBy('pay_period', 'desc')
                ->paginate(15)
                ->withQueryString();

            return view('Payroll.index', [
                'isHistoryView'   => true,
                'historyPayrolls' => $historyPayrolls,
                'selectedMonth'   => 'all',
                'departments'     => $this->getDepartments(),
                'employmentTypes' => $this->getEmploymentTypes(),
                'summary'         => $this->getSummaryStats('all'),
                'money'           => fn ($amt) => '$' . number_format((float)$amt, 2),
            ]);
        }

        // -------------------------------------------------------------
        // MODE B: MONTHLY CYCLE PROCESSING (e.g., 2026-02)
        // -------------------------------------------------------------
        $payrolls = $employeesQuery->paginate(10)->withQueryString();

        $existingPayrolls = Payroll::where('pay_period', $selectedMonth)
            ->get()
            ->keyBy('employee_id');

        $formattedEmployees = collect($payrolls->items())->map(function ($emp) use ($existingPayrolls) {
            $firstName = $emp->first_name ?? '';
            $lastName  = $emp->last_name ?? '';
            $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
            
            $baseSalary = $emp->salary ?? 0;
            $record     = $existingPayrolls->get($emp->id);

            $allowances = $record ? $record->allowances : 0;
            $deductions = $record ? $record->deductions : 0;
            $netPay     = $record ? $record->net_pay : ($baseSalary + $allowances - $deductions);
            $status     = $record ? $record->status : 'Pending';

            return [
                'employee_id' => $emp->id,
                'name'        => trim("{$firstName} {$lastName}"),
                'title'       => $emp->job_title ?? 'Employee',
                'initials'    => $initials ?: 'EM',
                'avatar'      => 'bg-slate-700',
                'base_salary' => $baseSalary,
                'allowances'  => $allowances,
                'deductions'  => $deductions,
                'net_pay'     => $netPay,
                'status'      => $status,
            ];
        });

        return view('Payroll.index', [
            'isHistoryView'   => false,
            'payrolls'        => $payrolls,
            'employees'       => $formattedEmployees,
            'selectedMonth'   => $selectedMonth,
            'departments'     => $this->getDepartments(),
            'employmentTypes' => $this->getEmploymentTypes(),
            'summary'         => $this->getSummaryStats($selectedMonth, $existingPayrolls, $payrolls->total()),
            'money'           => fn ($amt) => '$' . number_format((float)$amt, 2),
        ]);
    }

    public function processStore(Request $request, $employeeId)
    {
        $request->validate([
            'pay_period' => 'required|string',
            'allowances' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
        ]);

        $employee   = Employee::findOrFail($employeeId);
        $base       = $employee->salary ?? 0;
        $allowances = $request->allowances;
        $deductions = $request->deductions;
        $grossPay   = $base + $allowances;
        $netPay     = $grossPay - $deductions;

        Payroll::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'pay_period'  => $request->pay_period,
            ],
            [
                'base_salary'  => $base,
                'gross_pay'    => $grossPay,
                'allowances'   => $allowances,
                'deductions'   => $deductions,
                'net_pay'      => $netPay,
                'status'       => 'Processed',
                'processed_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Payroll processed successfully!');
    }

    public function reset($employeeId, Request $request)
    {
        $payPeriod = $request->input('pay_period', now()->format('Y-m'));

        Payroll::where('employee_id', $employeeId)
            ->where('pay_period', $payPeriod)
            ->delete();

        return redirect()->back()->with('success', 'Payroll record reset to Pending.');
    }

    private function getDepartments()
    {
        return [
            'all'         => 'All Departments',
            'Engineering' => 'Engineering',
            'Operations'  => 'Operations',
            'Support'     => 'Support',
            'Sales'       => 'Sales',
            'Marketing'   => 'Marketing',
            'HR'          => 'HR',
        ];
    }

    private function getEmploymentTypes()
    {
        return [
            'all'       => 'All Types',
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'contract'  => 'Contract',
        ];
    }

    private function getSummaryStats($month, $existingPayrolls = null, $totalEmployees = 0)
    {
        $money = fn ($amt) => '$' . number_format((float)$amt, 2);

        if ($month === 'all') {
            return [
                ['key' => 'total_base', 'label' => 'Total Base Salary', 'value' => $money(Employee::sum('salary')), 'hint' => 'Monthly total'],
                ['key' => 'total_disbursed', 'label' => 'All-Time Disbursed', 'value' => $money(Payroll::sum('net_pay')), 'hint' => 'Across all cycles'],
                ['key' => 'total_processed', 'label' => 'Total Processed Logs', 'value' => Payroll::count(), 'hint' => 'All historical records'],
            ];
        }

        $processedCount    = $existingPayrolls->where('status', 'Processed')->count();
        $totalNetDisbursed = $existingPayrolls->sum('net_pay');

        return [
            ['key' => 'total_payroll', 'label' => 'Total Base Payroll', 'value' => $money(Employee::sum('salary')), 'hint' => 'Gross base salary'],
            ['key' => 'net_disbursed', 'label' => "Net Disbursed ({$month})", 'value' => $money($totalNetDisbursed), 'hint' => 'Disbursed for selected cycle'],
            ['key' => 'processed', 'label' => 'Employees Processed', 'value' => "{$processedCount} / {$totalEmployees}", 'hint' => 'In current view'],
        ];
    }
}