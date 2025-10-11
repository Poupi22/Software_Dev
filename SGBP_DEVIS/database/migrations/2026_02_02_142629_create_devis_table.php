<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // DEV-2026-001
            $table->string('titre');
            
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            
            // Infos générales
            $table->enum('type', ['provisoire', 'final'])->default('provisoire');
            $table->enum('devise', ['FCFA', 'EUR', 'USD'])->default('FCFA');
            $table->date('date_debut');
            $table->date('date_fin');
            
            // TVA
            $table->boolean('appliquer_tva')->default(true);
            $table->decimal('taux_tva', 5, 2)->default(19.25);
            
            // Textes
            $table->text('introduction')->nullable();
            $table->text('conclusion')->nullable();
            
            // Totaux
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->decimal('main_oeuvre', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            
            // Statut
            $table->enum('statut', ['brouillon', 'envoye', 'accepte', 'refuse', 'expire'])->default('brouillon');
            $table->timestamp('date_envoi')->nullable();
            $table->timestamp('date_acceptation')->nullable();
            
            // Conversion
            // $table->foreignId('facture_id')->nullable()->constrained('factures')->nullOnDelete();
            $table->unsignedBigInteger('facture_id')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['statut', 'date_debut']);
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
