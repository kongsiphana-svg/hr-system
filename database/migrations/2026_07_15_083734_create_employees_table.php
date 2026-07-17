<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('department_id')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract'])->default('full_time');

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
