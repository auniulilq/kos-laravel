<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Menentukan apakah user pilih bayar Lunas atau DP saja di awal
            $table->enum('payment_method', ['full', 'dp'])->default('full')->after('total_price');
            
            // Mengubah/menambah status pembayaran agar lebih variatif
            // dp_paid: pembayaran DP sukses, lunas: pembayaran total sukses
            $table->string('payment_status')->default('pending')->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};