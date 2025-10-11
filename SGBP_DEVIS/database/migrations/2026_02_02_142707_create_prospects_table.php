<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('entreprise')->nullable();
            $table->string('objet')->nullable();
            $table->text('message');
            
            // Provenance
            $table->enum('source', ['site_web', 'manuel', 'import'])->default('site_web');
            $table->string('page_origine')->nullable();
            $table->string('ip_address')->nullable();
            
            // Statut
            $table->enum('statut', ['nouveau', 'contacte', 'qualifie', 'converti', 'perdu'])->default('nouveau');
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamp('date_premier_contact')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('statut');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
