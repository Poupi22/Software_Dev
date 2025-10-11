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
            $table->string('forme_juridique')->nullable()->after('nom_entreprise');
            $table->string('signataire_nom')->nullable()->after('cachet_path');
            $table->string('signataire_fonction')->nullable()->after('signataire_nom');
            $table->string('banque_nom')->nullable()->after('site_web');
            $table->string('banque_titulaire')->nullable()->after('banque_nom');
            $table->string('banque_iban')->nullable()->after('banque_titulaire');
            $table->string('banque_swift')->nullable()->after('banque_iban');
        });
    }

    public function down(): void
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn([
                'forme_juridique', 'signataire_nom', 'signataire_fonction',
                'banque_nom', 'banque_titulaire', 'banque_iban', 'banque_swift',
            ]);
        });
    }
};
