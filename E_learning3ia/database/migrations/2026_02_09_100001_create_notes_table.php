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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cours_instance_id')->constrained()->onDelete('cascade');
            $table->decimal('note_cc', 4, 2)->nullable()->comment('Note Contrôle Continu /20');
            $table->decimal('note_normale', 4, 2)->nullable()->comment('Note Normale /20');
            $table->decimal('note_quiz', 4, 2)->nullable()->comment('Note Quiz en ligne /20 (Phase 2)');
            $table->timestamps();

            // Un étudiant ne peut avoir qu'une seule note par cours instance
            $table->unique(['user_id', 'cours_instance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
