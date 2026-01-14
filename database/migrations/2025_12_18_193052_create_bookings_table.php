<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('room_id');
        $table->string('duration_type'); // Mingguan, Bulanan, Tahunan
        $table->date('start_date');
        $table->date('end_date'); // Otomatis dihitung sistem
        $table->decimal('total_price', 12, 2);
        $table->decimal('amount_paid', 12, 2)->default(0); // Untuk cek sudah lunas/baru DP
        $table->enum('payment_status', ['dp', 'lunas', 'unpaid'])->default('unpaid');
        $table->string('status')->default('pending'); // pending, active, completed
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
