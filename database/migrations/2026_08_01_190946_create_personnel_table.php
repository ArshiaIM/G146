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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // نوع پرسنل
            $table->enum('personnel_type', ['Career', 'Conscription']); // کادر / وظیفه

            // رتبه
            $table->enum('rank_type', [
                'officer_a',      // افسر الف  (کادر)
                'officer_b',      // افسر ب    (کادر)
                'nco',            // درجه‌دار   (کادر)
                'vazife_officer', // افسر وظیفه
                'vazife_nco',     // درجه‌دار وظیفه
                'soldier',        // سرباز
            ]);
            $table->enum('rank', [
                'private',                    // سرباز
                'corporal',                   // سرجوخه
                'sergeant',                   // گروهبان سوم
                'staff_sergeant',             // گروهبان دوم
                'sergeant_first_class',       // گروهبان یکم
                'sergeant_major',              // استوار دوم
                'command_sergeant_major',     // استوار یکم
                'third_lieutenant',             // ستوان سوم
                'second_lieutenant',            // ستوان دوم
                'first_lieutenant',             // ستوان یکم
                'captain',                     // سروان
                'major',                       // سرگرد
                'lieutenant_colonel',           // سرهنگ دوم
                'colonel',                     // سرهنگ
                'second_brigadier_general',     // سرتیپ دوم
                'brigadier_general',            // سرتیپ
                'major_general',                // سرلشکر
                'lieutenant_general',           // سپهبد
                'general',                     // ارتشبد
            ]);

            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_code', 10)->unique();
            $table->string('personnel_number')->unique()->nullable(); // شماره پرسنلی
            $table->string('phone')->nullable();
            $table->string('city')->nullable();                       // شهر

            // تاریخ‌ها
            $table->date('service_start_date')->nullable();  // تاریخ اعزام
            $table->date('service_end_date')->nullable();    // پایان خدمت / ترخیص

            // وضعیت فعلی
            $table->enum('status', [
                'active',   // فعال
                'leave',    // مرخصی
                'medical',  // بهداری
                'absent',   // غیبت
                'escaped',  // فرار
                'mission',  // مأموریت
                'released', // ترخیص شده
            ])->default('active');

            $table->text('notes')->nullable(); // یادداشت

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
