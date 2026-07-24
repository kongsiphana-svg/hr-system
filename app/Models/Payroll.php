<?php

namespace App\Models;

use App\Services\PayrollGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pay_period',
        'hours_worked',
        'approved_leave_days',
        'overtime_hours',
        'base_salary',
        'gross_pay',
        'allowances',
        'deductions',
        'unpaid_leave_deduction',
        'net_pay',
        'status',
        'processed_at',
        'submitted_for_review_at',
        'approved_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'hours_worked' => 'decimal:2',
            'approved_leave_days' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'base_salary' => 'decimal:2',
            'gross_pay' => 'decimal:2',
            'allowances' => 'decimal:2',
            'deductions' => 'decimal:2',
            'unpaid_leave_deduction' => 'decimal:2',
            'net_pay' => 'decimal:2',
            'processed_at' => 'datetime',
            'submitted_for_review_at' => 'datetime',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function isDraft(): bool
    {
        return $this->status === PayrollGenerator::STATUS_DRAFT;
    }

    public function isPendingReview(): bool
    {
        return $this->status === PayrollGenerator::STATUS_PENDING_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === PayrollGenerator::STATUS_APPROVED;
    }

    public function isPaid(): bool
    {
        return $this->status === PayrollGenerator::STATUS_PAID;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            PayrollGenerator::STATUS_DRAFT => 'Draft',
            PayrollGenerator::STATUS_PENDING_REVIEW => 'Pending Review',
            PayrollGenerator::STATUS_APPROVED => 'Approved',
            PayrollGenerator::STATUS_PAID => 'Paid',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }
}
