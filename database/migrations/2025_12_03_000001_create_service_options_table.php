<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_options', function (Blueprint $table) {
            $table->id();
            $table->enum('service_type', ['laundry', 'blanket', 'repair', 'other']);
            $table->string('name');
            $table->enum('pricing_type', ['fixed', 'per_unit', 'quote'])->default('fixed');
            $table->string('unit_name')->nullable(); // e.g. kg, item
            $table->unsignedInteger('price')->nullable(); // null for 'quote'
            $table->unsignedInteger('min_qty')->default(1);
            $table->unsignedInteger('max_qty')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_options');
    }
};