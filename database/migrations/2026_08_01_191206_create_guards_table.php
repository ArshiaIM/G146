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
        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->date('guard_date');                     // تاریخ نگهبانی
            $table->string('post');                         // پست نگهبانی
            $table->enum('shift', ['morning', 'evening', 'night']); // شیفت

            $table->timestamps();
        });

        // جدول پرسنل نگهبانی (pivot)
        Schema::create('guard_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guard_id')->constrained()->cascadeOnDelete();
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['commander', 'assistant', 'guard'])->default('guard');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_personnel');
        Schema::dropIfExists('guards');
    }
};
