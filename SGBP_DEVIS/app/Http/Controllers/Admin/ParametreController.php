<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    public function index()
    {
        $parametre = Parametre::firstOrCreate([]);
        return view('admin.parametres.index', compact('parametre'));
    }

    public function update(Request $request)
    {
        $parametre = Parametre::firstOrCreate([]);

        $validated = $request->validate([
            // Entreprise
            'nom_entreprise' => 'nullable|string|max:255',
            'forme_juridique' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:255',
            'niu' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'email_expediteur' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'telephone_secondaire' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:500',
            'boite_postale' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'site_web' => 'nullable|url|max:255',
            // Banque
            'banque_nom' => 'nullable|string|max:255',
            'banque_titulaire' => 'nullable|string|max:255',
            'banque_iban' => 'nullable|string|max:255',
            'banque_swift' => 'nullable|string|max:255',
            // Signature
            'signataire_nom' => 'nullable|string|max:255',
            'signataire_fonction' => 'nullable|string|max:255',
            // Fichiers
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'cachet' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            // TVA
            'tva_fcfa' => 'nullable|numeric|min:0|max:100',
            'tva_eur' => 'nullable|numeric|min:0|max:100',
            'tva_usd' => 'nullable|numeric|min:0|max:100',
            // Notifications
            'email_notifications' => 'nullable|email|max:255',
            'delai_relance_devis' => 'nullable|integer|min:1',
            'delai_relance_facture' => 'nullable|integer|min:1',
            // Textes
            'conditions_generales' => 'nullable|string',
            'mentions_legales' => 'nullable|string',
            // À propos
            'apropos_texte' => 'nullable|string',
            'apropos_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'apropos_annee_creation' => 'nullable|string|max:10',
            'apropos_nombre_employes' => 'nullable|string|max:50',
            'apropos_mission' => 'nullable|string',
            'apropos_vision' => 'nullable|string',
            'horaires_ouverture' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ]);

        // Gestion uploads fichiers
        foreach (['logo', 'signature', 'cachet', 'apropos_image'] as $file) {
            if ($request->hasFile($file)) {
                $pathKey = $file . '_path';
                $oldPath = $parametre->{$pathKey};
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $validated[$pathKey] = $request->file($file)->store('parametres', 'public');
            }
            unset($validated[$file]);
        }

        // Suppression fichiers si demandé
        foreach (['logo', 'signature', 'cachet', 'apropos_image'] as $file) {
            if ($request->boolean('supprimer_' . $file)) {
                $oldPath = $parametre->{$file . '_path'};
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $validated[$file . '_path'] = null;
            }
        }

        // Boolean checkboxes
        $validated['notif_nouveau_devis'] = $request->boolean('notif_nouveau_devis');
        $validated['notif_devis_accepte'] = $request->boolean('notif_devis_accepte');
        $validated['notif_nouvelle_facture'] = $request->boolean('notif_nouvelle_facture');
        $validated['notif_paiement_recu'] = $request->boolean('notif_paiement_recu');
        $validated['notif_nouveau_prospect'] = $request->boolean('notif_nouveau_prospect');
        $validated['relance_auto_active'] = $request->boolean('relance_auto_active');

        $parametre->update($validated);

        return back()->with('success', 'Paramètres mis à jour avec succès !');
    }
}
