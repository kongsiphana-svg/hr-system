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
<<<<<<< HEAD
            $table->string('country')->nullable();
=======
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
>>>>>>> origin/feat/fe-employee-visal

            // Job Details
            $table->string('department');
            $table->string('job_title');
            $table->string('employment_type')->nullable();
            $table->date('start_date');
            $table->decimal('salary', 10, 2)->nullable();
<<<<<<< HEAD
=======
            $table->string('reporting_manager')->nullable();
>>>>>>> origin/feat/fe-employee-visal
            $table->string('work_location')->nullable();
            $table->string('probation_period')->nullable();
            $table->enum('status', ['active', 'on_leave', 'probation', 'terminated'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};