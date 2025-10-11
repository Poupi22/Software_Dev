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
        Schema::table('parametres', function (Blueprint $table) {
            $table->text('apropos_texte')->nullable();
            $table->string('apropos_image_path')->nullable();
            $table->string('apropos_annee_creation')->nullable();
            $table->string('apropos_nombre_employes')->nullable();
            $table->text('apropos_mission')->nullable();
            $table->text('apropos_vision')->nullable();
            $table->string('horaires_ouverture')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn([
                'apropos_texte', 'apropos_image_path', 'apropos_annee_creation',
                'apropos_nombre_employes', 'apropos_mission', 'apropos_vision',
                'horaires_ouverture', 'facebook_url', 'twitter_url', 'linkedin_url',
            ]);
        });
    }
};
