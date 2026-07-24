<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PayrollGenerator
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PAID = 'paid';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_REVIEW,
        self::STATUS_APPROVED,
        self::STATUS_PAID,
    ];

    /**
     * Generate or refresh a Draft payroll for one employee in a pay period.
     * Workflow: Employee → Attendance → Approved Leave → Generate Payroll (Draft)
     */
    public function generate(Employee $employee, string $payPeriod): Payroll
    {
        [$periodStart, $periodEnd] = $this->periodBounds($payPeriod);

        $baseSalary = $this->resolveBaseSalary($employee);
        $hoursWorked = $this->resolveAttendanceHours($employee, $periodStart, $periodEnd);
        $leaveSummary = $this->resolveApprovedLeave($employee, $periodStart, $periodEnd);
        $standardHours = max(1, (float) ($employee->standard_hours ?: 160));

        $overtimeHours = max(0, round($hoursWorked - $standardHours, 2));
        $hourlyRate = $this->resolveHourlyRate($employee, $baseSalary, $standardHours);
        $overtimePay = round($overtimeHours * $hourlyRate * 1.5, 2);
        $allowances = round((float) ($employee->allowances ?? 0), 2);

        // Gross: base (or hours × rate) + allowances + overtime
        if (($employee->pay_type ?? 'salary') === 'hourly') {
            $grossPay = round(($hourlyRate * $hoursWorked) + $allowances + $overtimePay, 2);
        } else {
            $grossPay = round($baseSalary + $allowances + $overtimePay, 2);
        }

        $standardDeductions = round(
            ($grossPay * (float) ($employee->deduction_rate ?? 0)) + (float) ($employee->fixed_deductions ?? 0),
            2
        );

        $workingDays = $this->workingDaysInPeriod($periodStart, $periodEnd);
        $dailyRate = $workingDays > 0 ? round($baseSalary / $workingDays, 2) : 0;
        $unpaidLeaveDeduction = round($leaveSummary['unpaid_days'] * $dailyRate, 2);

        $deductions = round($standardDeductions + $unpaidLeaveDeduction, 2);
        $netPay = round(max(0, $grossPay - $deductions), 2);

        return Payroll::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'pay_period' => $payPeriod,
            ],
            [
                'hours_worked' => $hoursWorked,
                'approved_leave_days' => $leaveSummary['total_days'],
                'overtime_hours' => $overtimeHours,
                'base_salary' => $baseSalary,
                'gross_pay' => $grossPay,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'net_pay' => $netPay,
                'status' => self::STATUS_DRAFT,
                'processed_at' => now(),
            ]
        )->load('employee');
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public function periodBounds(string $payPeriod): array
    {
        $start = Carbon::createFromFormat('Y-m', $payPeriod)->startOfMonth()->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

        return [$start, $end];
    }

    private function resolveBaseSalary(Employee $employee): float
    {
        $base = (float) ($employee->base_salary ?: $employee->salary ?: 0);

        if ($base <= 0 && ($employee->pay_type ?? '') === 'hourly') {
            $hours = (float) ($employee->standard_hours ?: 160);
            $base = round((float) $employee->hourly_rate * $hours, 2);
        }

        return round($base, 2);
    }

    private function resolveHourlyRate(Employee $employee, float $baseSalary, float $standardHours): float
    {
        if (($employee->pay_type ?? '') === 'hourly' && (float) $employee->hourly_rate > 0) {
            return round((float) $employee->hourly_rate, 2);
        }

        return round($baseSalary / max(1, $standardHours), 2);
    }

    /**
     * Attendance hours from the Schedule module for the pay period.
     * Falls back to the employee's standard hours when no shifts exist.
     */
    private function resolveAttendanceHours(Employee $employee, Carbon $start, Carbon $end): float
    {
        $schedules = Schedule::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->whereNotIn('status', ['conflict', 'cancelled'])
            ->get();

        if ($schedules->isEmpty()) {
            return round((float) ($employee->standard_hours ?: 160), 2);
        }

        $total = $schedules->sum(function (Schedule $schedule) {
            $date = $schedule->date->format('Y-m-d');
            $startAt = Carbon::parse($date.' '.$this->normalizeTime($schedule->start_time));
            $endAt = Carbon::parse($date.' '.$this->normalizeTime($schedule->end_time));

            if ($endAt->lessThanOrEqualTo($startAt)) {
                $endAt->addDay();
            }

            return $startAt->diffInMinutes($endAt) / 60;
        });

        return round((float) $total, 2);
    }

    /**
     * Approved leave overlapping the pay period, matched via employee email → user.
     *
     * @return array{total_days: float, unpaid_days: float, paid_days: float}
     */
    private function resolveApprovedLeave(Employee $employee, Carbon $start, Carbon $end): array
    {
        $email = trim((string) ($employee->email ?? ''));
        $name = trim((string) $employee->name);

        if ($email === '' && $name === '') {
            return ['total_days' => 0, 'unpaid_days' => 0, 'paid_days' => 0];
        }

        $userIds = User::query()
            ->where(function ($q) use ($email, $name) {
                if ($email !== '') {
                    $q->orWhere('email', $email);
                }

                if ($name !== '') {
                    $q->orWhereRaw('LOWER(name) = ?', [mb_strtolower($name)]);
                }
            })
            ->pluck('id');

        if ($userIds->isEmpty()) {
            return ['total_days' => 0, 'unpaid_days' => 0, 'paid_days' => 0];
        }

        $leaves = LeaveRequest::query()
            ->whereIn('user_id', $userIds)
            ->where(function ($q) {
                $q->whereRaw('LOWER(status) = ?', ['approved']);
            })
            ->whereDate('start_date', '<=', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->get();

        $paidDays = 0.0;
        $unpaidDays = 0.0;

        foreach ($leaves as $leave) {
            $days = $this->overlapWorkingDays(
                Carbon::parse($leave->start_date)->max($start)->startOfDay(),
                Carbon::parse($leave->end_date)->min($end)->startOfDay()
            );

            if ($this->isUnpaidLeaveType((string) $leave->leave_type)) {
                $unpaidDays += $days;
            } else {
                $paidDays += $days;
            }
        }

        return [
            'total_days' => round($paidDays + $unpaidDays, 2),
            'unpaid_days' => round($unpaidDays, 2),
            'paid_days' => round($paidDays, 2),
        ];
    }

    private function isUnpaidLeaveType(string $type): bool
    {
        return str_contains(strtolower($type), 'unpaid');
    }

    private function overlapWorkingDays(Carbon $start, Carbon $end): float
    {
        if ($end->lt($start)) {
            return 0;
        }

        $days = 0;
        foreach (CarbonPeriod::create($start, $end) as $day) {
            if (! $day->isWeekend()) {
                $days++;
            }
        }

        return (float) $days;
    }

    private function workingDaysInPeriod(Carbon $start, Carbon $end): int
    {
        $days = 0;
        foreach (CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay()) as $day) {
            if (! $day->isWeekend()) {
                $days++;
            }
        }

        return max(1, $days);
    }

    private function normalizeTime(mixed $value): string
    {
        if ($value instanceof Carbon) {
            return $value->format('H:i:s');
        }

        $raw = (string) $value;

        return strlen($raw) === 5 ? $raw.':00' : substr($raw, 0, 8);
    }
}
