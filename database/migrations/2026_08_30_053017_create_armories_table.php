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
        Schema::create('armories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('weapon_type');        // نوع سلاح
            $table->string('serial_number')->nullable(); // شماره سریال
            $table->integer('quantity')->default(0);     // تعداد
            $table->enum('status', ['active', 'repair', 'lost'])->default('active');
            $table->foreignId('responsible_personnel_id') // مسئول
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armories');
    }
};
