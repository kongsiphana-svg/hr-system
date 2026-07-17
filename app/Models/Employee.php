<?php

namespace App\Models;

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
    }
}