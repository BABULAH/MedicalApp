<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            // 1️⃣ Supprimer la foreign key existante
            $table->dropForeign(['doctor_availability_id']);

            // 2️⃣ Renommer la colonne
            $table->renameColumn('doctor_availability_id', 'availability_id');

            // 3️⃣ Recréer la foreign key proprement
            $table->foreign('availability_id')
                  ->references('id')
                  ->on('availabilities')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {

            $table->dropForeign(['availability_id']);

            $table->renameColumn('availability_id', 'doctor_availability_id');

            $table->foreign('doctor_availability_id')
                  ->references('id')
                  ->on('availabilities')
                  ->onDelete('cascade');
        });
    }
};