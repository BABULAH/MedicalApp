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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            // Référence à l'utilisateur associé
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Référence à l'établissement (multi-tenant)
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');

            // Spécialité
            $table->foreignId('speciality_id')
            ->nullable()
            ->references('id')
            ->on('specialities')
            ->onDelete('set null');

            // Informations professionnelles
            $table->string('registration_number')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);

            // Contact et localisation
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('locality_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Tarification
            $table->decimal('consultation_price', 10, 2)->default(0);
            $table->decimal('emergency_price', 10, 2)->default(0);

            // Statut et vérification
            $table->boolean('is_verified')->default(false);
            $table->string('status')->default('active'); // ex: active, inactive, suspended

            $table->timestamps();

            // Index pour optimiser les recherches par établissement et spécialité
            $table->index(['establishment_id', 'speciality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
