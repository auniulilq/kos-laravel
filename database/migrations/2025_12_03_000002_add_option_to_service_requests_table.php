<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('service_option_id')->nullable()->after('service_type');
            $table->unsignedInteger('quantity')->default(1)->after('service_option_id');

            $table->foreign('service_option_id')
                ->references('id')->on('service_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['service_option_id']);
            $table->dropColumn(['service_option_id', 'quantity']);
        });
    }
};