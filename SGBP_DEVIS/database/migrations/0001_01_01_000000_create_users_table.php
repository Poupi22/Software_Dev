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
        // 1. Création de la table USERS avec la structure finale
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Informations personnelles
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Coordonnées
            $table->string('telephone')->nullable();
            $table->string('avatar')->nullable();
            
            /** 
             * Note : On ne crée pas la colonne 'role' ici car ton autre 
             * migration demandait explicitement sa suppression.
             * Si tu utilises Spatie/Permission ou une autre table, c'est parfait.
             */
            
            // Statut
            $table->boolean('actif')->default(true);
            
            // Laravel auth & Meta
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Index pour performances (uniquement sur 'actif' puisque 'role' est supprimé)
            $table->index('email');
            $table->index('actif');
        });

        // 2. Création de la table PASSWORD_RESET_TOKENS
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Création de la table SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
