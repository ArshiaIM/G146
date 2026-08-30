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
        Schema::create('motor_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('vehicle_type');       // نوع وسیله
            $table->string('plate_number')->nullable(); // پلاک
            $table->string('serial_number')->nullable();
            $table->enum('status', [
                'active',    // فعال
                'repair',    // در تعمیر
                'inactive',  // از رده خارج
            ])->default('active');
            $table->foreignId('driver_personnel_id') // راننده
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();
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
        Schema::dropIfExists('motor_pools');
    }
};
