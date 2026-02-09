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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Informations personnelles
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            // Authentification
            $table->string('password');
            $table->rememberToken();

            // Profil
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();

            // Localisation
            $table->foreignId('locality_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Rôle & multi-tenant
            $table->string('role')->default('user'); 
            // ex: super_admin, admin, doctor, user

            $table->foreignId('establishment_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade');
            /*
             | super_admin → establishment_id = NULL
             | admin / doctor → lié à un établissement
             | user (patient) → optionnel selon logique métier
             */

            $table->timestamps();

            // Index pour performances
            $table->index(['role', 'establishment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
