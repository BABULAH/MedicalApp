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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');

            // Évaluation
            $table->unsignedTinyInteger('rating'); // 1 à 5
            $table->text('comment')->nullable();

            $table->timestamps();

            // Contraintes & index
            $table->unique(['user_id', 'doctor_id', 'establishment_id'], 'unique_user_doctor_review');
            $table->index(['doctor_id', 'rating']);
            $table->index(['establishment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
