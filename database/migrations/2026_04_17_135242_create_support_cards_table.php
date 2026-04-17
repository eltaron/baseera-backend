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
        Schema::create('support_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title');             // عنوان الكارت (مثلاً: أعطال النظام)
            $table->text('description');        // نص الوصف
            $table->string('icon');              // اسم الأيقونة (مثلاً: fa-screwdriver-wrench)
            $table->string('button_text');       // نص الزر (فتح تذكرة دعم)
            $table->string('button_url');        // رابط الزر (#contact)
            $table->string('border_color')->default('#FF7B00'); // لون الإطار السفلي
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_cards');
    }
};
