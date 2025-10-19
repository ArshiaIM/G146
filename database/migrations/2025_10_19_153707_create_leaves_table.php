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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            // FK به جدول پرسنل (فرض جدول: employees)
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('reason')->nullable();

            // وضعیت: pending / approved / rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // تاییدکننده (ممکنه از بین پرسنل باشه) - nullable
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
