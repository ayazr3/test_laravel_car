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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand', 255);
            $table->string('model', 255);
            $table->year('year');
            $table->unsignedInteger('price');
            $table->string('currency')->default('SAR');
            $table->json('images')->nullable();
            $table->text('description');
            $table->boolean('sold')->default(false);
            $table->string('color', 100);
            $table->json('location')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('featured_status')->nullable();
            $table->text('rejection_reason')->nullable();
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
