<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les valeurs existantes qui ne correspondent pas
        DB::statement("
            UPDATE appointments
            SET status = 'attente'
            WHERE status NOT IN ('attente', 'valide', 'annule')
        ");

        // Modifier la colonne en ENUM avec les nouveaux statuts
        DB::statement("
            ALTER TABLE appointments
            MODIFY status ENUM('attente','valide','annule') NOT NULL DEFAULT 'attente'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à VARCHAR si besoin
        DB::statement("
            ALTER TABLE appointments
            MODIFY status VARCHAR(255) NOT NULL DEFAULT 'attente'
        ");
    }
};