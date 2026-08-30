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
        Schema::create('battalions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // گردان ۱، گردان ۲، ...
            // $table->string('commander')->nullable();   // نام فرمانده
            $table->foreignId('commander_id')
                ->nullable()
                ->constrained('personnel')
                ->nullOnDelete();
            $table->string('code')->unique()->nullable(); // کد گردان
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battalions');
    }
};
