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
        Schema::create('learning_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('current_level')->default('beginner');
            $table->json('strengths')->nullable(); // نقاط القوة
            $table->json('weaknesses')->nullable(); // نقاط الضعف
            $table->string('preferred_learning_style')->nullable(); // نمط التعلم المفضل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_profiles');
    }
};
