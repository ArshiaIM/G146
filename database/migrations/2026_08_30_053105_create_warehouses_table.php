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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');          // نام کالا
            $table->string('item_code')->nullable(); // کد کالا
            $table->integer('quantity')->default(0); // موجودی
            $table->string('unit')->nullable();   // واحد (عدد، کیلو، متر)
            $table->enum('status', ['available', 'low', 'out'])->default('available');
            $table->foreignId('responsible_personnel_id')
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
        Schema::dropIfExists('warehouses');
    }
};
