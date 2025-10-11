<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            
            // Entreprise
            $table->string('nom_entreprise')->nullable();
            $table->string('slogan')->nullable();
            $table->string('rccm')->nullable();
            $table->string('niu')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_secondaire')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('CM');
            $table->string('site_web')->nullable();
            
            // Fichiers
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('cachet_path')->nullable();
            
            // TVA
            $table->decimal('tva_fcfa', 5, 2)->default(19.25);
            $table->decimal('tva_eur', 5, 2)->default(20.00);
            $table->decimal('tva_usd', 5, 2)->default(0.00);
            
            // Notifications
            $table->boolean('notif_nouveau_devis')->default(true);
            $table->boolean('notif_devis_accepte')->default(true);
            $table->boolean('notif_nouvelle_facture')->default(true);
            $table->boolean('notif_paiement_recu')->default(true);
            $table->boolean('notif_nouveau_prospect')->default(true);
            $table->string('email_notifications')->nullable();
            
            // Relances
            $table->boolean('relance_auto_active')->default(false);
            $table->integer('delai_relance_devis')->default(7);
            $table->integer('delai_relance_facture')->default(3);
            
            // Textes légaux
            $table->text('conditions_generales')->nullable();
            $table->text('mentions_legales')->nullable();
            $table->text('politique_confidentialite')->nullable();
            
            // Templates emails
            $table->text('template_email_devis')->nullable();
            $table->text('template_email_facture')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
