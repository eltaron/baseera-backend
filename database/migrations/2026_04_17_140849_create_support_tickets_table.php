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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            // نربطها باليوزر لو مسجل دخول (اختياري)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique(); // رقم التذكرة (مثل: TIC-1234)
            $table->string('name');
            $table->string('email');
            $table->string('subject'); // موضوع المشكلة
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // درجة الأهمية
            $table->text('message');
            $table->enum('status', ['open', 'pending', 'resolved', 'closed'])->default('open'); // حالة التذكرة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
