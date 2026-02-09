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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Références aux utilisateurs, docteurs et établissements
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');

            // Créneau et raison du rendez-vous
            $table->foreignId('time_slot_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_reason_id')->constrained('appointment_reasons')->onDelete('cascade');

            // Date du rendez-vous
            $table->date('date');

            // Statut du rendez-vous (par exemple: pending, confirmed, cancelled)
            $table->string('status')->default('pending');

            // Commentaire du docteur et annulation
            $table->text('doctor_comment')->nullable();
            $table->string('cancelled_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
