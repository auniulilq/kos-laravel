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
    Schema::table('payments', function (Blueprint $table) {
        // Menambahkan kolom snap_token setelah kolom status (atau kolom lain)
        $table->string('snap_token')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn('snap_token');
    });
}
};
