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
        Schema::create('personnel_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('role_title');         // عنوان شغلی مثلاً: تیربارچی، آرپی‌جی زن
            $table->string('position')->nullable(); // پست سازمانی
            $table->text('responsibilities')->nullable(); // مسئولیت‌ها
            $table->boolean('is_primary')->default(true); // نقش اصلی
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_roles');
    }
};
