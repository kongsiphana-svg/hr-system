<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    public function __construct(private PayrollGenerator $generator) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pay_period' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'department_id' => ['nullable', 'string'],
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $payrolls = $this->filteredQuery($validated)
            ->latest('processed_at')
            ->paginate($validated['per_page'] ?? 10)
            ->withQueryString();

        return response()->json([
            'data' => collect($payrolls->items())->map(fn (Payroll $p) => $this->transform($p))->values(),
            'summary' => $this->summaryFor($validated),
            'meta' => [
                'current_page' => $payrolls->currentPage(),
                'last_page' => $payrolls->lastPage(),
                'per_page' => $payrolls->perPage(),
                'total' => $payrolls->total(),
            ],
        ]);
    }

    /**
     * Generate payroll drafts from employee + attendance + approved leave.
     */
    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pay_period' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'department_id' => ['nullable', 'string'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $employees = Employee::query()
            ->where(function ($q) {
                $q->where('is_active', true)->orWhere('status', 'active');
            })
            ->when(
                $validated['employee_id'] ?? null,
                fn ($query, $employeeId) => $query->whereKey($employeeId)
            )
            ->when(
                empty($validated['employee_id']) && ($validated['department_id'] ?? null),
                fn ($query) => $query->where('department', $validated['department_id'])
            )
            ->when(
                $validated['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($inner) use ($search) {
                        $inner->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('job_title', 'like', "%{$search}%");
                    });
                }
            )
            ->get();

        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'No matching employees found to generate payroll for.',
            ], 422);
        }

        $records = $employees->map(
            fn (Employee $employee) => $this->generator->generate($employee, $validated['pay_period'])
        );

        return response()->json([
            'message' => "Payroll generated as Draft for {$records->count()} employee(s). Ready for HR review.",
            'data' => $records->map(fn (Payroll $p) => $this->transform($p))->values(),
            'summary' => $this->summaryFor([
                'pay_period' => $validated['pay_period'],
                'department_id' => $validated['department_id'] ?? null,
            ]),
        ]);
    }

    public function show(Payroll $payroll): JsonResponse
    {
        return response()->json(['data' => $this->transform($payroll->load('employee'))]);
    }

    /**
     * Draft → Pending Review (after HR opens and confirms review).
     */
    public function submitReview(Payroll $payroll): JsonResponse
    {
        if (! $payroll->isDraft()) {
            return response()->json([
                'message' => 'Only Draft payroll can be submitted for review.',
            ], 422);
        }

        $payroll->update([
            'status' => PayrollGenerator::STATUS_PENDING_REVIEW,
            'submitted_for_review_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll submitted for HR review.',
            'data' => $this->transform($payroll->fresh('employee')),
        ]);
    }

    /**
     * Pending Review → Approved.
     */
    public function approve(Payroll $payroll): JsonResponse
    {
        if (! in_array($payroll->status, [
            PayrollGenerator::STATUS_DRAFT,
            PayrollGenerator::STATUS_PENDING_REVIEW,
        ], true)) {
            return response()->json([
                'message' => 'Only Draft or Pending Review payroll can be approved.',
            ], 422);
        }

        $payroll->update([
            'status' => PayrollGenerator::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll approved. Payslip can now be generated.',
            'data' => $this->transform($payroll->fresh('employee')),
        ]);
    }

    /**
     * Approved → Paid.
     */
    public function markPaid(Payroll $payroll): JsonResponse
    {
        if (! $payroll->isApproved()) {
            return response()->json([
                'message' => 'Only Approved payroll can be marked as Paid.',
            ], 422);
        }

        $payroll->update([
            'status' => PayrollGenerator::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll marked as Paid.',
            'data' => $this->transform($payroll->fresh('employee')),
        ]);
    }

    public function payslip(Payroll $payroll): JsonResponse
    {
        if (! in_array($payroll->status, [
            PayrollGenerator::STATUS_APPROVED,
            PayrollGenerator::STATUS_PAID,
        ], true)) {
            return response()->json([
                'message' => 'Payslip is available after payroll is Approved.',
            ], 422);
        }

        return response()->json(['data' => $this->transform($payroll->load('employee'))]);
    }

    public function updateStatus(Request $request, Payroll $payroll): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(PayrollGenerator::STATUSES)],
        ]);

        $next = $validated['status'];
        $current = $payroll->status;

        $allowed = match ($current) {
            PayrollGenerator::STATUS_DRAFT => [
                PayrollGenerator::STATUS_PENDING_REVIEW,
                PayrollGenerator::STATUS_APPROVED,
            ],
            PayrollGenerator::STATUS_PENDING_REVIEW => [
                PayrollGenerator::STATUS_APPROVED,
                PayrollGenerator::STATUS_DRAFT,
            ],
            PayrollGenerator::STATUS_APPROVED => [
                PayrollGenerator::STATUS_PAID,
            ],
            default => [],
        };

        if (! in_array($next, $allowed, true)) {
            return response()->json([
                'message' => "Cannot move payroll from {$current} to {$next}.",
            ], 422);
        }

        $timestampField = match ($next) {
            PayrollGenerator::STATUS_PENDING_REVIEW => 'submitted_for_review_at',
            PayrollGenerator::STATUS_APPROVED => 'approved_at',
            PayrollGenerator::STATUS_PAID => 'paid_at',
            default => null,
        };

        $payroll->update(array_filter([
            'status' => $next,
            $timestampField => $timestampField ? now() : null,
        ]));

        return response()->json([
            'message' => 'Payroll status updated.',
            'data' => $this->transform($payroll->fresh('employee')),
        ]);
    }

    private function filteredQuery(array $filters)
    {
        return Payroll::query()
            ->with('employee')
            ->when($filters['pay_period'] ?? null, fn ($q, $period) => $q->where('pay_period', $period))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when(
                $filters['department_id'] ?? null,
                fn ($q, $departmentId) => $q->whereHas(
                    'employee',
                    fn ($employeeQuery) => $employeeQuery->where('department', $departmentId)
                )
            )
            ->when(
                $filters['search'] ?? null,
                function ($q, $search) {
                    $q->whereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('job_title', 'like', "%{$search}%");
                    });
                }
            );
    }

    private function transform(Payroll $payroll): array
    {
        $employee = $payroll->employee;

        return [
            'id' => $payroll->id,
            'employee_id' => $employee?->id,
            'employee_name' => $employee?->name,
            'name' => $employee?->name,
            'job_title' => $employee?->job_title,
            'title' => $employee?->job_title,
            'department' => $employee?->department,
            'department_id' => $employee?->department,
            'employment_type' => $employee?->employment_type,
            'email' => $employee?->email,
            'pay_period' => $payroll->pay_period,
            'hours' => (float) $payroll->hours_worked,
            'hours_worked' => (float) $payroll->hours_worked,
            'standard_hours' => (int) ($employee?->standard_hours ?: 160),
            'approved_leave_days' => (float) $payroll->approved_leave_days,
            'overtime_hours' => (float) $payroll->overtime_hours,
            'base_salary' => (float) $payroll->base_salary,
            'allowances' => (float) $payroll->allowances,
            'deductions' => (float) $payroll->deductions,
            'unpaid_leave_deduction' => (float) $payroll->unpaid_leave_deduction,
            'gross_pay' => (float) $payroll->gross_pay,
            'gross_salary' => (float) $payroll->gross_pay,
            'net_pay' => (float) $payroll->net_pay,
            'net_salary' => (float) $payroll->net_pay,
            'status' => $payroll->status,
            'status_label' => $payroll->statusLabel(),
            'processed_at' => optional($payroll->processed_at)?->toIso8601String(),
        ];
    }

    private function summaryFor(array $filters): array
    {
        $rows = $this->filteredQuery($filters)->get([
            'gross_pay',
            'net_pay',
            'deductions',
            'employee_id',
        ]);

        return [
            'pay_period' => $filters['pay_period'] ?? null,
            'total_gross_pay' => (float) $rows->sum('gross_pay'),
            'total_net_pay' => (float) $rows->sum('net_pay'),
            'net_disbursable' => (float) $rows->sum('net_pay'),
            'total_deductions' => (float) $rows->sum('deductions'),
            'employees_count' => $rows->pluck('employee_id')->unique()->count(),
        ];
    }
}
