<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollPageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get selected month/pay_period (defaults to current month "YYYY-MM")
        $selectedMonth = $request->input('month', now()->format('Y-m'));

        // 2. Fetch active employees with department/type filters
        $employeesQuery = Employee::query()
            ->when($request->filled('department_id') && $request->department_id !== 'all', function ($query) use ($request) {
                $query->where('department', $request->department_id);
            })
            ->when($request->filled('employment_type') && $request->employment_type !== 'all', function ($query) use ($request) {
                $query->where('employment_type', $request->employment_type);
            });

        $payrolls = $employeesQuery->paginate(10)->withQueryString();

        // 3. Fetch processed payroll entries for this specific month
        $existingPayrolls = Payroll::where('pay_period', $selectedMonth)
            ->get()
            ->keyBy('employee_id');

        // 4. Map employees with their monthly payroll status
        $formattedEmployees = collect($payrolls->items())->map(function ($emp) use ($existingPayrolls) {
            $firstName = $emp->first_name ?? '';
            $lastName  = $emp->last_name ?? '';
            $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
            
            $baseSalary = $emp->salary ?? 0;

            // Check if payroll record already exists for this month
            $record = $existingPayrolls->get($emp->id);

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
                'department_id'   => $emp->department ?? '',
                'employment_type' => $emp->employment_type ?? '',
                'base_salary'     => $baseSalary,
                'allowances'      => $allowances,
                'deductions'      => $deductions,
                'net_pay'         => $netPay,
                'status'          => $status,
            ];
        });

        // 5. Helper function for currency formatting
        $money = fn ($amount) => '$' . number_format((float)$amount, 2);

        // 6. Calculate monthly summary stats
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

        $departments = array_merge(['all' => 'All Departments'], [
            'Engineering' => 'Engineering',
            'Operations'  => 'Operations',
            'Support'     => 'Support',
            'Sales'       => 'Sales',
            'Marketing'   => 'Marketing',
            'HR'          => 'HR',
        ]);

        $employmentTypes = [
            'all'       => 'All Types',
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'contract'  => 'Contract',
        ];

        return view('Payroll.index', [
            'payrolls'        => $payrolls,
            'employees'       => $formattedEmployees,
            'summary'         => $summary,
            'departments'     => $departments,
            'employmentTypes' => $employmentTypes,
            'selectedMonth'   => $selectedMonth,
            'money'           => $money,
        ]);
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