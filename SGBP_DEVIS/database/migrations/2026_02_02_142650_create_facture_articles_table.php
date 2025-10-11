<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facture_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facture_category_id')->nullable()->constrained('facture_categories')->cascadeOnDelete();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            
            $table->string('designation');
            $table->string('unite');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire_ht', 15, 2);
            $table->decimal('remise_pourcentage', 5, 2)->default(0);
            $table->integer('ordre')->default(0);
            
            $table->decimal('montant_remise', 15, 2)->default(0);
            $table->decimal('total_ht', 15, 2);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_articles');
    }
};
