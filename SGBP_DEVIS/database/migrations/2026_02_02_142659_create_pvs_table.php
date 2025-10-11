<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pvs', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // PV-2026-001
            $table->string('titre');
            
            $table->foreignId('facture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            
            $table->date('date_reception');
            $table->string('lieu_reception')->nullable();
            
            // Observations
            $table->text('description_travaux')->nullable();
            $table->text('observations')->nullable();
            $table->text('reserves')->nullable();
            $table->enum('etat_travaux', ['conforme', 'reserve_mineure', 'reserve_majeure', 'non_conforme'])->default('conforme');
            
            // Signatures
            $table->string('signature_client_path')->nullable();
            $table->string('signature_entreprise_path')->nullable();
            $table->timestamp('date_signature_client')->nullable();
            $table->timestamp('date_signature_entreprise')->nullable();
            
            $table->enum('statut', ['brouillon', 'signe', 'archive'])->default('brouillon');
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pvs');
    }
};

