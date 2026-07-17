<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController extends Controller 
{

    /** 
     * Get 
     */
    public function index(Request $request): JsonResponse {

        $validated = $request->validate([

            'pay_period' => ['nullable', 'string'],
            'department_id' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $payrolls = Payroll::query()

            ->with('employee')
            ->when($validated['pay_period'] ?? null, fn ($query, $period) => $query->where('pay_period', $period))
            ->when(
                $validated['department_id'] ?? null,
                fn ($query, $departmentId) => $query->whereHas(
                    'employee',
                    fn($employeeQuery) => $employeeQuery->where('department', $departmentId)

                )
            )

            ->latest('processed_at')
            ->paginate($validated['per_page'] ?? 10)
            ->withQueryString();
        $data = collect($payrolls->items())->map($this->transform(...))->values();

        return response()->json([
            'data' => $data,
            'summary' => $this->summaryFor($validated['pay_period'] ?? null, $validated['department_id'] ?? null),
            'meta' => [
                'current_page' => $payrolls->currentPage(),
                'last_page' => $payrolls->lastPage(),
                'per_page' => $payrolls->perPage(),
                'total' => $payrolls->total(),
            ],
        ]);

    }

    /**
     * postttttttttttttttttttttttttt
     */
    public function process(Request $request): JsonResponse {
        
        $validated = $request->validate([
            'pay_period' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'department_id' => ['nullable', 'string'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'hours_worked' => ['nullable', 'numeric', 'min:0'],
        ]);

        $employees = Employee::query()
            ->where('is_active', true)
            ->when(
                $validated['employee_id'] ?? null,
                fn ($query, $employeeId) => $query->whereKey($employeeId)
            )
            ->when(
                empty($validated['employee_id']) && ($validated['department_id'] ?? null),
                fn ($query) => $query->where('department', $validated['department_id'])
            )
            ->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    'message' => 'No matching employee found to process payroll for.',
                ], 422);
            }

            $records = $employees->map(function (Employee $employee) use ($validated) {
                return $this->generatePayroll($employee, $validated['pay_period'], $validated['hours_worked'] ?? null);
            });

            return response()->json([
                'message' => "Payroll processed for {$records->count()} employee(s).",
                'data' => $records->map($this->transform(...))->values(),
            ]);
    }

   public function show(Payroll $payroll): JsonResponse {
        return response()->json(['data' => $this->transform($payroll->load('employee'))]);
    }

    public function payslip(Payroll $payroll): JsonResponse {
        return response()->json(['data' => $this->transform($payroll->load('employee'))]);
    }

    private function generatePayroll(Employee $employee, string $payPeriod, ?float $hoursWorked): Payroll {
        $hours = $hoursWorked ?? (float) $employee->standard_hours;

        $grossPay = $employee->pay_type === 'hourly'
            ? round((float) $employee->hourly_rate * $hours, 2)
            : round((float) $employee->base_salary, 2);
        $allowances = round((float) $employee->allowances, 2);
        $deduction = round(($grossPay * (float) $employee->deduction_rate) + (float) $employee->fixed_deductions, 2);
        $netPay = round($grossPay + $allowances - $deduction, 2);

        return Payroll::updateOrCreate(
            ['employee_id' => $employee->id, 'pay_period' => $payPeriod],
            [
                'hours_worked' => $hours,
                'base_salary' => $grossPay,
                'gross_pay' => $grossPay,
                'allowances' => $allowances,
                'deductions' => $deduction,
                'net_pay' => $netPay,
                'status' => 'processed',
                'processed_at' => now(),
            ]
        )->load('employee');
    }

    private function transform(Payroll $payroll): array {
        $employee = $payroll->employee;

        return [
            'id' => $payroll->id,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'name' => $employee->name,
            'job_title' => $employee->job_title,
            'title' => $employee->job_title,
            'department_id' => $employee->department,
            'employment_type' => $employee->employment_type,
            'pay_period' => $payroll->pay_period,
            'hours' => (float) $payroll->hours_worked,
            'base_salary' => (float) $payroll->base_salary,
            'allowances' => (float) $payroll->allowances,
            'deductions' => (float) $payroll->deductions,
            'net_pay' => (float) $payroll->net_pay,
            'status' => $payroll->status,
        ];
    }

    private function summaryFor(?string $payPeriod, ?string $departmentId): array {
        $rows = Payroll::query()
            ->when($payPeriod, fn ($query, $period) => $query->where('pay_period', $period))
            ->when(
                $departmentId,
                fn ($query, $deptId) => $query->whereHas(
                    'employee',
                    fn ($employeeQuery) => $employeeQuery->where('department', $deptId)
                )
            )
            ->get(['gross_pay', 'net_pay', 'employee_id']);
            
            return [
                'pay_period' => $payPeriod,
                'total_gross_pay' => (float) $rows->sum('gross_pay'),
                'net_disbursable' => (float) $rows->sum('net_pay'),
                'employees_count' => $rows->pluck('employee_id')->unique()->count(),
            ];
    }
    
}