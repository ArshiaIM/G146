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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', [
                'present',   // حاضر
                'mission',   // مأمور
                'leave',     // مرخصی
                'medical',   // بهداری
                'absent',    // غیبت
                'arrested',  // بازداشت
                'course',    // دوره
            ])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['personnel_id', 'date']); // هر پرسنل یه رکورد در روز
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
