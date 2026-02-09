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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();

            // Références au docteur et à l'établissement (multi-tenant)
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');

            // Jour de la semaine (0 = dimanche, 6 = samedi)
            $table->tinyInteger('day_of_week');

            // Plage horaire
            $table->time('start_time');
            $table->time('end_time');

            // Indique si la disponibilité est active
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Index pour optimiser les recherches par docteur et établissement
            $table->index(['doctor_id', 'establishment_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
