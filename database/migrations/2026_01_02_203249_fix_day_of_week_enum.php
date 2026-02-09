<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // 1️⃣ Convertir les valeurs FR -> EN
        DB::statement("
            UPDATE availabilities
            SET day_of_week = CASE day_of_week
                WHEN 'lundi' THEN 'monday'
                WHEN 'mardi' THEN 'tuesday'
                WHEN 'mercredi' THEN 'wednesday'
                WHEN 'jeudi' THEN 'thursday'
                WHEN 'vendredi' THEN 'friday'
                WHEN 'samedi' THEN 'saturday'
                WHEN 'dimanche' THEN 'sunday'
            END
        ");

        // 2️⃣ Modifier l'ENUM MySQL
        DB::statement("
            ALTER TABLE availabilities
            MODIFY day_of_week ENUM(
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE availabilities
            MODIFY day_of_week ENUM(
                'lundi',
                'mardi',
                'mercredi',
                'jeudi',
                'vendredi',
                'samedi',
                'dimanche'
            ) NOT NULL
        ");
    }
};
