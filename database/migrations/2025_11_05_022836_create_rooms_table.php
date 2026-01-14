<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['single', 'double', 'suite'])->default('single');
            $table->integer('price')->default(0);
            $table->enum('status', ['empty', 'occupied', 'maintenance'])->default('empty');
            $table->text('facilities')->nullable(); // JSON or comma-separated
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Current occupant
            $table->timestamps();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};