<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->json('fixed_work_days')->nullable()->after('fixed_end_time')
                ->comment('JSON array of work days: e.g. ["Mon","Tue","Wed","Thu","Fri"]');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('fixed_work_days');
        });
    }
};
