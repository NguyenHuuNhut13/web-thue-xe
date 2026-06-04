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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Car owner
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('fuel_type'); // gasoline, diesel, electric, hybrid
            $table->string('transmission'); // manual, automatic
            $table->integer('seats'); // 4, 5, 7, 16...
            $table->decimal('price_per_day', 15, 2); // Price per day
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Array of image paths
            $table->string('location')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('status')->default('pending'); // pending, active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
