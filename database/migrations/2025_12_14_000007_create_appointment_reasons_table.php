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
        Schema::create('appointment_reasons', function (Blueprint $table) {
            $table->id();

            // Nom et description de la raison
            $table->string('name');
            $table->text('description')->nullable();

            // Référence à l'établissement (multi-tenant)
            $table->foreignId('establishment_id')
            ->nullable() // ← autorise null
            ->constrained()
            ->onDelete('cascade');


            $table->timestamps();

            // Index pour accélérer les recherches par établissement
            $table->index('establishment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_reasons');
    }
};
