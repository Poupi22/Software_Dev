<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bons_commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')->unique()->constrained('devis')->onDelete('cascade');
            $table->string('numero')->nullable();
            $table->string('fichier_path')->nullable(); // chemin du fichier stocké
            $table->string('fichier_nom')->nullable();  // nom original du fichier
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bons_commande');
    }
};