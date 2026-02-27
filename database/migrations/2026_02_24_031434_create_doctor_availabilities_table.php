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
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();

            // Médecin concerné
            $table->foreignId('doctor_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Jour de la semaine (0 = dimanche, 6 = samedi)
            $table->unsignedTinyInteger('day_of_week');

            // Créneau horaire
            $table->foreignId('time_slot_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();

            // Empêche les doublons (même médecin, même jour, même créneau)
            $table->unique(['doctor_id', 'day_of_week', 'time_slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};