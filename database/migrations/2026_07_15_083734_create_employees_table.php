<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Legacy payroll branch created a separate employees schema.
 * The directory employees table already exists at this point, so this
 * migration is a no-op to keep migrate history intact.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('department');
                $table->string('job_title');
                $table->date('start_date');
                $table->enum('status', ['active', 'on_leave', 'probation', 'terminated'])->default('active');
                $table->enum('pay_type', ['salary', 'hourly'])->default('salary');
                $table->decimal('base_salary', 10, 2)->default(0);
                $table->decimal('hourly_rate', 8, 2)->nullable();
                $table->unsignedInteger('standard_hours')->default(160);
                $table->decimal('allowances', 10, 2)->default(0);
                $table->decimal('deduction_rate', 5, 4)->default(0.1000);
                $table->decimal('fixed_deductions', 10, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally empty: employees table is owned by the earlier migration.
    }
};
