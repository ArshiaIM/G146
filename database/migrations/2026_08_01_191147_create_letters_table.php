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
       Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('subject');                     // موضوع نامه
            $table->text('content');                       // متن نامه
            $table->string('letter_number')->nullable();   // شماره نامه
            $table->date('letter_date');                   // تاریخ نامه

            $table->enum('type', [
                'incoming', // وارده
                'outgoing', // صادره
            ]);

            $table->enum('category', [
                'leave',        // مرخصی
                'punishment',   // تنبیه
                'reward',       // تشویق
                'medical',      // بهداری
                'absence',      // غیبت
                'general',      // عمومی
            ])->default('general');

            $table->string('attachment')->nullable(); // فایل پیوست

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
