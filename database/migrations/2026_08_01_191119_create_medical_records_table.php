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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->cascadeOnDelete();

            $table->dateTime('sent_at');                    // تاریخ اعزام به بهداری
            $table->dateTime('returned_at')->nullable();    // تاریخ مراجعت از بهداری

            $table->string('diagnosis')->nullable();        // تشخیص
            $table->enum('type', [
                'sick',      // بیمار
                'injured',   // مجروح
                'checkup',   // معاینه
            ])->default('sick');

            $table->integer('rest_days')->nullable();       // روزهای استراحت
            $table->string('doctor')->nullable();           // نام پزشک
            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);   // آیا هنوز در بهداری است

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
