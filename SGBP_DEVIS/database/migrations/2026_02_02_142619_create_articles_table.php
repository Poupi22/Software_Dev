<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['produit', 'service'])->default('produit');
            $table->string('nom');
            $table->string('reference')->nullable()->unique();
            $table->text('description')->nullable();
            $table->string('unite'); // Sac, Kg, M², Heure, etc.
            
            // Prix
            $table->decimal('prix_ht', 15, 2);
            $table->boolean('prix_modifiable')->default(true);
            
            // Stock
            $table->boolean('actif')->default(true);
            $table->boolean('gestion_stock')->default(false);
            $table->integer('stock_actuel')->default(0);
            $table->integer('stock_alerte')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'actif']);
            $table->index('nom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
