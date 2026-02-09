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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();

            // Disponibilité parente
            $table->foreignId('availability_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Multi-tenant (établissement)
            $table->foreignId('establishment_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Créneau horaire précis
            $table->time('start_time');
            $table->time('end_time');

            // Statut de réservation
            $table->boolean('is_booked')->default(false);

            $table->timestamps();

            // Index pour performances
            $table->index(['availability_id', 'start_time']);
            $table->index(['establishment_id', 'is_booked']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
