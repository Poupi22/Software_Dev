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
        Schema::create('assiduite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('programme_session_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('semestre')->comment('Numéro du semestre (1, 2, etc.)');
            $table->decimal('pourcentage_presence', 5, 2)->default(0)->comment('Pourcentage de présence 0-100');
            $table->timestamps();

            // Un étudiant ne peut avoir qu'une seule assiduité par session/semestre
            $table->unique(['user_id', 'programme_session_id', 'semestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assiduite');
    }
};
