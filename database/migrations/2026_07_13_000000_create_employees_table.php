<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('identification_id')->nullable();
            $table->string('avatar_url')->nullable();

            // Contact Details
            $table->string('email')->unique();
            $table->string('personal_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Job Details
            $table->string('department');
            $table->string('job_title');
            $table->string('employment_type')->nullable();
            $table->date('start_date');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('reporting_manager')->nullable();
            $table->string('work_location')->nullable();
            $table->string('probation_period')->nullable();
            $table->enum('status', ['active', 'on_leave', 'probation', 'terminated'])->default('active');

            // Payroll-compatible fields
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

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
