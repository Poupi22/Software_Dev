<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // FACT-2026-001
            $table->string('titre');
            
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('devis_id')->nullable()->constrained()->nullOnDelete();
            
            // Infos générales
            $table->enum('type', ['provisoire', 'final'])->default('provisoire');
            $table->enum('devise', ['FCFA', 'EUR', 'USD'])->default('FCFA');
            $table->date('date_emission');
            $table->date('date_echeance');
            
            // TVA
            $table->boolean('appliquer_tva')->default(true);
            $table->decimal('taux_tva', 5, 2)->default(19.25);
            
            // Textes
            $table->text('introduction')->nullable();
            $table->text('conclusion')->nullable();
            $table->text('conditions_paiement')->nullable();
            
            // Totaux
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->decimal('main_oeuvre', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            
            // Paiement
            $table->enum('statut_paiement', ['non_paye', 'partiel', 'paye'])->default('non_paye');
            $table->decimal('montant_paye', 15, 2)->default(0);
            
            // Statut
            $table->enum('statut', ['brouillon', 'envoye', 'accepte', 'annule'])->default('brouillon');
            $table->timestamp('date_envoi')->nullable();
            
            // Conversion PV
            // $table->foreignId('pv_id')->nullable()->constrained('pvs')->nullOnDelete();
            $table->unsignedBigInteger('pv_id')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['statut', 'statut_paiement']);
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};

