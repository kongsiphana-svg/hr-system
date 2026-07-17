<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\PayrollFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    
    'employee_id', 'pay_period', 'hours_worked', 'base_salary', 'gross_pay',
    'allowances', 'deductions', 'net_pay', 'status', 'processed_at',
])]

class Payroll extends Model
{
    use HasFactory;

    protected function casts(): array {
        return [

            'hours_worked' => 'decimal:2',
            'base_salary' => 'decimal:2',
            'gross_pay' => 'decimal:2',
            'allowances' => 'decimal:2',
            'deductions' => 'decimal:2',
            'net_pay' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo {

        return $this->belongsTo(Employee::class);
    }
}
