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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();

            // چه مدلی تغییر میکنه
            $table->string('model_type');          // مثلاً App\Models\Personnel
            $table->unsignedBigInteger('model_id')->nullable(); // آیدی رکورد (null برای create)

            // چه اکشنی
            $table->enum('action', ['create', 'update', 'delete']);

            // داده‌های جدید (برای create و update)
            $table->json('payload')->nullable();

            // داده‌های قبلی (برای update)
            $table->json('original_data')->nullable();

            // وضعیت
            $table->enum('status', [
                'pending',                  // در انتظار تایید فرمانده گروهان
                'approved_by_company',      // تایید شده توسط فرمانده گروهان
                'approved_by_battalion',    // تایید شده توسط فرمانده گردان
                'rejected',                 // رد شده
                'executed',                 // اجرا شده
            ])->default('pending');

            // توضیح درخواست
            $table->text('reason')->nullable();

            // توضیح رد شدن
            $table->text('rejection_reason')->nullable();

            // درخواست دهنده
            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // تایید کننده گروهان
            $table->foreignId('approved_by_company_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('company_approved_at')->nullable();

            // تایید کننده گردان
            $table->foreignId('approved_by_battalion_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('battalion_approved_at')->nullable();

            // گروهان و گردان مرتبط
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('battalion_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
