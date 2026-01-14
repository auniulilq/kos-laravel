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
    Schema::table('rooms', function (Blueprint $table) {
        $table->string('area')->nullable(); // Untuk ukuran (3x4)
        $table->string('electricity')->nullable(); // Untuk tipe listrik
        $table->integer('capacity')->default(1); // Untuk jumlah orang
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
        });
    }
};
