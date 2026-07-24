<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollPageController extends Controller
{
    public function index(Request $request)
{
    // 1. Get selected month/pay_period
    $selectedMonth = $request->input('month', now()->format('Y-m'));
    $isHistoryView = $selectedMonth === 'all';

    // If "View All" history mode is active
    if ($isHistoryView) {
        $historyPayrolls = Payroll::with('employee')
            ->when($request->filled('department_id') && $request->department_id !== 'all', function ($query) use ($request) {
                $query->whereHas('employee', fn ($q) => $q->where('department', $request->department_id));
            })
            ->when($request->filled('employment_type') && $request->employment_type !== 'all', function ($query) use ($request) {
                $query->whereHas('employee', fn ($q) => $q->where('employment_type', $request->employment_type));
            })
            ->orderBy('pay_period', 'desc')
            ->get();

        $summary = [
            [
                'key'   => 'total_payroll',
                'label' => 'Total Base Payroll',
                'value' => '$' . number_format((float) Employee::sum('salary'), 2),
                'hint'  => 'Gross base salary',
            ],
            [
                'key'   => 'net_disbursed',
                'label' => 'Total Historical Disbursed',
                'value' => '$' . number_format((float) $historyPayrolls->sum('net_pay'), 2),
                'hint'  => 'All processed cycles',
            ],
            [
                'key'   => 'processed',
                'label' => 'Total Records',
                'value' => $historyPayrolls->count(),
                'hint'  => 'Historical entries',
            ],
        ];

        return view('Payroll.index', [
            'historyPayrolls' => $historyPayrolls,
            'summary'         => $summary,
            'departments'     => $this->getDepartments(),
            'employmentTypes' => $this->getEmploymentTypes(),
            'selectedMonth'   => 'all',
            'money'           => fn ($amount) => '$' . number_format((float)$amount, 2),
        ]);
    }

    // Standard Monthly View Logic
    $employeesQuery = Employee::query()
        ->when($request->filled('department_id') && $request->department_id !== 'all', function ($query) use ($request) {
            $query->where('department', $request->department_id);
        })
        ->when($request->filled('employment_type') && $request->employment_type !== 'all', function ($query) use ($request) {
            $query->where('employment_type', $request->employment_type);
        });

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
            'employee_id'     => $emp->id,
            'name'            => trim("{$firstName} {$lastName}"),
            'title'           => $emp->job_title ?? 'Employee',
            'initials'        => $initials ?: 'EM',
            'avatar'          => 'bg-slate-700',
            'avatar_url'      => $emp->avatar_url ?? null,
            'department_id'   => $emp->department ?? '',
            'employment_type' => $emp->employment_type ?? '',
            'base_salary'     => $baseSalary,
            'allowances'      => $allowances,
            'deductions'      => $deductions,
            'net_pay'         => $netPay,
            'status'          => $status,
        ];
    });

    $money = fn ($amount) => '$' . number_format((float)$amount, 2);

    $totalPayroll = Employee::sum('salary');
    $processedCount = $existingPayrolls->where('status', 'Processed')->count();
    $totalNetDisbursed = $existingPayrolls->sum('net_pay');

    $summary = [
        [
            'key'   => 'total_payroll',
            'label' => 'Total Base Payroll',
            'value' => $money($totalPayroll),
            'hint'  => 'Gross base salary',
        ],
        [
            'key'   => 'net_disbursed',
            'label' => 'Net Disbursed (' . $selectedMonth . ')',
            'value' => $money($totalNetDisbursed),
            'hint'  => 'Disbursed for selected cycle',
        ],
        [
            'key'   => 'processed',
            'label' => 'Employees Processed',
            'value' => "{$processedCount} / " . $payrolls->total(),
            'hint'  => 'In current view',
        ],
    ];

    return view('Payroll.index', [
        'payrolls'        => $payrolls,
        'employees'       => $formattedEmployees,
        'summary'         => $summary,
        'departments'     => $this->getDepartments(),
        'employmentTypes' => $this->getEmploymentTypes(),
        'selectedMonth'   => $selectedMonth,
        'money'           => $money,
    ]);
}

private function getDepartments(): array
{
    return array_merge(['all' => 'All Departments'], [
        'Engineering' => 'Engineering',
        'Operations'  => 'Operations',
        'Support'     => 'Support',
        'Sales'       => 'Sales',
        'Marketing'   => 'Marketing',
        'HR'          => 'HR',
    ]);
}

private function getEmploymentTypes(): array
{
    return [
        'all'       => 'All Types',
        'full_time' => 'Full-time',
        'part_time' => 'Part-time',
        'contract'  => 'Contract',
    ];
}

    // Store/Process payroll for individual employee
    public function processStore(Request $request, $employeeId)
    {
        $request->validate([
            'pay_period' => 'required|string',
            'allowances' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($employeeId);
        $base = $employee->salary ?? 0;
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

    // Reset / Reverse payroll for an employee in a specific month
    public function reset($employeeId, Request $request)
    {
        $payPeriod = $request->input('pay_period', now()->format('Y-m'));

        Payroll::where('employee_id', $employeeId)
            ->where('pay_period', $payPeriod)
            ->delete();

        return redirect()->back()->with('success', 'Payroll record reset to Pending.');
    }

    
}