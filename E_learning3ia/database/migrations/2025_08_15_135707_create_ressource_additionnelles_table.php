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
        Schema::create('ressource_additionnelles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contenu_additionnel_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->enum('type', ['lien_externe', 'fichier_pdf', 'video_youtube', 'page_texte']);
            $table->text('contenu');
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressources_additionnelles');
    }
};
