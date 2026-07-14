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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // FK later once cuz the other backend doesnt exist yet..
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('shift_type')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
