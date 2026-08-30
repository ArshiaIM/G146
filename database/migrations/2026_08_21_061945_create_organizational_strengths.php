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
        Schema::create('organizational_strengths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->integer('officer_a')->default(0);    // افسر الف
            $table->integer('officer_b')->default(0);    // افسر ب
            $table->integer('vazife_officer')->default(0); // افسر وظیفه
            $table->integer('nco')->default(0);          // درجه‌دار پایور
            $table->integer('vazife_nco')->default(0);   // درجه‌دار وظیفه
            $table->integer('soldier')->default(0);      // سرباز
            $table->timestamps();

            $table->unique('company_id'); // هر گروهان یه رکورد
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_strengths');
    }
};
