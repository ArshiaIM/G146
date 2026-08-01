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
         Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battalion_id')->constrained()->cascadeOnDelete();
            $table->string('name');                      // گروهان ۱، گروهان ۲، ...
            $table->string('commander')->nullable();     // نام فرمانده گروهان
            $table->string('code')->unique()->nullable(); // کد گروهان برای لاگین
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
