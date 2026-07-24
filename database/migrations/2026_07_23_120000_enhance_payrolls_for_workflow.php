<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('approved_leave_days', 6, 2)->default(0)->after('hours_worked');
            $table->decimal('overtime_hours', 6, 2)->default(0)->after('approved_leave_days');
            $table->decimal('unpaid_leave_deduction', 10, 2)->default(0)->after('deductions');
        });

        // Map legacy "processed" rows into the new workflow statuses.
        DB::table('payrolls')
            ->where('status', 'processed')
            ->update(['status' => 'draft']);
    }

    public function down(): void
    {
        DB::table('payrolls')
            ->whereIn('status', ['draft', 'pending_review', 'approved', 'paid'])
            ->update(['status' => 'processed']);

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['approved_leave_days', 'overtime_hours', 'unpaid_leave_deduction']);
        });
    }
};
