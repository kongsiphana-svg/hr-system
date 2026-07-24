<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('fixed_start_time', 5)->nullable()->after('standard_hours')->comment('Fixed daily start time (e.g. 09:00)');
            $table->string('fixed_end_time', 5)->nullable()->after('fixed_start_time')->comment('Fixed daily end time (e.g. 18:00)');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['fixed_start_time', 'fixed_end_time']);
        });
    }
};
