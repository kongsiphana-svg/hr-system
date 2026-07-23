<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('leave_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('leave_type');
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        $table->text('reason')->nullable();
        $table->string('status')->default('Pending');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};