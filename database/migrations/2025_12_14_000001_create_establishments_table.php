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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('type'); // hospital, clinic, cabinet
            $table->string('address')->nullable();

            // Localité
            $table->foreignId('locality_id')->nullable()->constrained()->onDelete('set null');

            // Coordonnées GPS
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Contact
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();

            // Index pour optimiser les recherches par type et localisation
            $table->index(['type', 'locality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
