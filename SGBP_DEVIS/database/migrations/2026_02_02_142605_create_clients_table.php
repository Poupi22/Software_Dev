<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['particulier', 'societe'])->default('particulier');
            
            // PARTICULIER
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            
            // SOCIÉTÉ
            $table->string('raison_sociale')->nullable();
            $table->string('rccm')->nullable();
            $table->string('niu')->nullable();
            $table->string('representant_legal')->nullable();
            $table->string('fonction_representant')->nullable();
            $table->string('secteur_activite')->nullable();
            $table->string('site_web')->nullable();
            
            // COMMUN
            $table->string('email')->unique()->nullable();
            $table->string('telephone_principal');
            $table->string('telephone_secondaire')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('CM');
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour recherche
            $table->index(['email', 'telephone_principal']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
