<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Valid values for the `status` column.
     * Kept here so the controller/views can share one source of truth.
     */
    public const STATUSES = ['active', 'on_leave', 'probation', 'terminated'];

    /**
     * Valid values for the `gender` column.
     */
    public const GENDERS = ['male', 'female', 'other', 'prefer_not_to_say'];

    /**
     * Valid values for the `employment_type` column.
     */
    public const EMPLOYMENT_TYPES = ['Full-time', 'Part-time', 'Contract', 'Intern'];

    /**
     * Valid values for the `work_location` column.
     */
    public const WORK_LOCATIONS = ['Remote', 'On-site', 'Hybrid'];

    /**
     * Valid values for the `probation_period` column.
     */
    public const PROBATION_PERIODS = ['None', '3 Months', '6 Months'];

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'identification_id',
        'email',
        'personal_email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'department',
        'job_title',
        'employment_type',
        'start_date',
        'salary',
        'reporting_manager',
        'work_location',
        'probation_period',
        'status',
        'avatar_url',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'start_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Convenience accessor so existing views ($employee->name) keep working
     * without every call site needing to know about first/last name.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
=======
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'title', 'department_id', 'employment_type', 'pay_type',
    'base_salary', 'hourly_rate', 'standard_hours', 'allowances',
    'deduction_rate', 'fixed_deductions', 'is_active',
])]

class Employee extends Model {
    use HasFactory;

    protected function casts(): array {
        return [
            'base_salary' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'standard_hours' => 'integer',
            'allowances' => 'decimal:2',
            'deduction_rate' => 'decimal:4',
            'fixed_deductions' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function payrolls(): HasMany {
        return $this->hasMany(Payroll::class);
    }

    public function initials(): string {
        $parts = preg_split('/\s+/', trim($this->name)) ?: [];
        $letters = array_map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)), array_filter($parts));

        return implode('', array_slice($letters, 0, 2)) ?: '?';
>>>>>>> origin/feat/be-payroll-phanna
    }
}