<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            // Connects the leave request to the employee
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('leave_type'); // e.g., Sick Leave, Vacation, Personal
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->text('reason')->nullable(); // For the details preview panel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};