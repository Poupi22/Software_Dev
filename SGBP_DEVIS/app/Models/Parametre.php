<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parametre extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_entreprise',
        'forme_juridique',
        'slogan',
        'rccm',
        'niu',
        'email',
        'email_expediteur',
        'telephone',
        'telephone_secondaire',
        'adresse',
        'boite_postale',
        'ville',
        'pays',
        'site_web',
        'banque_nom',
        'banque_titulaire',
        'banque_iban',
        'banque_swift',
        'logo_path',
        'signature_path',
        'cachet_path',
        'signataire_nom',
        'signataire_fonction',
        'notif_nouveau_devis',
        'notif_devis_accepte',
        'notif_nouvelle_facture',
        'notif_paiement_recu',
        'notif_nouveau_prospect',
        'email_notifications',
        'relance_auto_active',
        'delai_relance_devis',
        'delai_relance_facture',
        'conditions_generales',
        'mentions_legales',
        'politique_confidentialite',
        'apropos_texte',
        'apropos_image_path',
        'apropos_annee_creation',
        'apropos_nombre_employes',
        'apropos_mission',
        'apropos_vision',
        'horaires_ouverture',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
    ];

    protected $casts = [
        'notif_nouveau_devis'     => 'boolean',
        'notif_devis_accepte'     => 'boolean',
        'notif_nouvelle_facture'  => 'boolean',
        'notif_paiement_recu'     => 'boolean',
        'notif_nouveau_prospect'  => 'boolean',
        'relance_auto_active'     => 'boolean',
    ];

    // Singleton pattern
    public static function get()
    {
        return self::first() ?? self::create([]);
    }
}
