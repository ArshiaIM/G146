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
        // مکان‌های نگهبانی (دستی تعریف میشن)
        Schema::create('guard_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');           // مثلاً: درب اصلی، انبار، برج دیده‌بانی
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // برنامه نگهبانی روزانه
        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('guard_date');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'guard_date']); // یه برنامه در روز
        });

        // پاس‌های هر نگهبانی (دستی کم و زیاد میشن)
        Schema::create('guard_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guard_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guard_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->string('shift_label');    // مثلاً: پاس اول، پاس دوم، پاس سوم
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_shifts');
        Schema::dropIfExists('guards');
        Schema::dropIfExists('guard_posts');
    }
};
