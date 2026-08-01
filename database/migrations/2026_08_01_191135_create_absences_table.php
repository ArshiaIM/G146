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
       Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();

            $table->enum('type', [
                'absent',   // غیبت
                'escaped',  // فرار
            ]);

            $table->dateTime('started_at');              // شروع غیبت/فرار
            $table->dateTime('returned_at')->nullable(); // مراجعت

            $table->text('description')->nullable();
            $table->boolean('is_resolved')->default(false); // پیگیری شده؟

            // اقدامات انجام شده
            $table->text('actions_taken')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
