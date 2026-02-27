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
        Schema::table('appointments', function (Blueprint $table) {
            // Ajouter la colonne doctor_availability_id avec la relation
            $table->foreignId('doctor_availability_id')
                  ->nullable() // facultatif si tu veux d'abord l'ajouter sans valeur
                  ->constrained()
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_availability_id']);
            $table->dropColumn('doctor_availability_id');
        });
    }
};