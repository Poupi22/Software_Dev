<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->morphs('quizzable');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('seuil_reussite')->default(50);
            $table->unsignedInteger('duree_minutes')->nullable();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('enonce');
            $table->timestamps();
        });

        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('texte');
            $table->boolean('est_correcte')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_tentatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('score_obtenu');
            $table->enum('statut', ['en_cours', 'terminee', 'reussie', 'echouee']);
            $table->timestamp('started_at')->comment('Heure de début enregistrée par le serveur')->nullable();
            $table->timestamp('submitted_at')->comment('Heure de soumission')->nullable();
            $table->json('questions_selected')->nullable();
            $table->timestamps();
        });

        Schema::create('tentative_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_tentative_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('reponse_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lecon_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->timestamps();
            $table->unique(['user_id', 'lecon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progressions');
        Schema::dropIfExists('tentative_reponses');
        Schema::dropIfExists('quiz_tentatives');
        Schema::dropIfExists('reponses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
