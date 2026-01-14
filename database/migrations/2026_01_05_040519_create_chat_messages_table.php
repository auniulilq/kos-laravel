<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Menghubungkan pesan dengan user yang sedang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Isi pesan
            $table->text('message');
            
            // Pengirim: 'user' (penghuni), 'bot' (asisten otomatis), 'system' (cron job/pengingat)
            $table->enum('sender', ['user', 'bot', 'system'])->default('bot');
            
            // Status pesan (untuk fitur badge notifikasi nanti)
            $table->boolean('is_read')->default(false);
            
            $table->timestamps(); // create_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};