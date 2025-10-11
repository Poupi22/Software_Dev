<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. TABLE CLIENTS : niu → nif, rccm → bp ────────────────────────
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('niu', 'nif');
            $table->renameColumn('rccm', 'bp');
        });

        // ─── 2. TABLE DEVIS : dates → validite_mois, TVA → TPS+CSS ──────────
        Schema::table('devis', function (Blueprint $table) {
            $table->dropIndex('devis_statut_date_debut_index');
            $table->dropColumn(['appliquer_tva', 'taux_tva', 'total_tva', 'date_debut', 'date_fin']);
        });

        Schema::table('devis', function (Blueprint $table) {
            $table->unsignedTinyInteger('validite_mois')->default(1)->after('devise');
            $table->decimal('taux_tps', 5, 2)->default(9.50)->after('total_ht');
            $table->decimal('total_tps', 15, 2)->default(0)->after('taux_tps');
            $table->decimal('taux_css', 5, 2)->default(1.00)->after('total_tps');
            $table->decimal('total_css', 15, 2)->default(0)->after('taux_css');
        });

        // ─── 3. TABLE FACTURES : TVA → TPS+CSS ──────────────────────────────
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['appliquer_tva', 'taux_tva', 'total_tva']);
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->decimal('taux_tps', 5, 2)->default(9.50)->after('total_ht');
            $table->decimal('total_tps', 15, 2)->default(0)->after('taux_tps');
            $table->decimal('taux_css', 5, 2)->default(1.00)->after('total_tps');
            $table->decimal('total_css', 15, 2)->default(0)->after('taux_css');
        });

        // ─── 4. TABLE PARAMETRES : supprimer TVA ────────────────────────────
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn(['tva_fcfa', 'tva_eur', 'tva_usd']);
        });
    }

    public function down(): void
    {
        // Clients
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('nif', 'niu');
            $table->renameColumn('bp', 'rccm');
        });

        // Devis
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn(['validite_mois', 'taux_tps', 'total_tps', 'taux_css', 'total_css']);
        });
        Schema::table('devis', function (Blueprint $table) {
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('appliquer_tva')->default(true);
            $table->decimal('taux_tva', 5, 2)->default(19.25);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->index(['statut', 'date_debut'], 'devis_statut_date_debut_index');
        });

        // Factures
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['taux_tps', 'total_tps', 'taux_css', 'total_css']);
        });
        Schema::table('factures', function (Blueprint $table) {
            $table->boolean('appliquer_tva')->default(true);
            $table->decimal('taux_tva', 5, 2)->default(19.25);
            $table->decimal('total_tva', 15, 2)->default(0);
        });

        // Parametres
        Schema::table('parametres', function (Blueprint $table) {
            $table->decimal('tva_fcfa', 5, 2)->default(19.25);
            $table->decimal('tva_eur', 5, 2)->default(20.00);
            $table->decimal('tva_usd', 5, 2)->default(0.00);
        });
    }
};