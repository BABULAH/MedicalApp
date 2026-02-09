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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Relations principales
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade');

            // Multi-tenant (établissement)
            $table->foreignId('establishment_id')->constrained()->onDelete('cascade');

            // Contenu de la notification
            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable(); // ex: appointment_created, appointment_cancelled, reminder

            // Statut de lecture
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // Index pour performances (dashboard & notifications temps réel)
            $table->index(['user_id', 'is_read']);
            $table->index(['doctor_id', 'is_read']);
            $table->index(['establishment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
