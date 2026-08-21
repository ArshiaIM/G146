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
            $table->foreignId('personnel_id')->constrained('personnel')->cascadeOnDelete();

            $table->enum('type', [
                'hourly',   // ساعتی
                'city',     // شهرستان
                'normal',   // عادی
                'reward',   // پاداشی
            ]);

            $table->dateTime('start_datetime');           // شروع مرخصی
            $table->dateTime('end_datetime');             // پایان مرخصی
            $table->dateTime('returned_at')->nullable();  // مراجعت از مرخصی

            $table->text('reason')->nullable();           // دلیل مرخصی
            $table->string('destination')->nullable();    // مقصد (برای شهرستان)

            $table->enum('status', [
                'pending',   // در انتظار تأیید
                'approved',  // تأیید شده
                'rejected',  // رد شده
                'returned',  // مراجعت کرده
                'overdue',   // تأخیر در مراجعت
            ])->default('pending');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();

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
