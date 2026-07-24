<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->timestamp('submitted_for_review_at')->nullable()->after('processed_at');
            $table->timestamp('approved_at')->nullable()->after('submitted_for_review_at');
            $table->timestamp('paid_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['submitted_for_review_at', 'approved_at', 'paid_at']);
        });
    }
};
