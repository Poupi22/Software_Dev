<?php

namespace App\Http\Controllers;

use App\Models\RessourceAdditionnelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourceAdditionnelleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenu_additionnel_id' => 'required|exists:contenu_additionnels,id',
            'titre' => 'required|string|max:255',
            'type' => 'required|in:lien_externe,fichier_pdf,video_youtube,page_texte',
            'contenu' => 'required_without:fichier|nullable|string',
            'fichier' => 'required_if:type,fichier_pdf|nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $validated;

        if ($request->hasFile('fichier')) {
            $data['contenu'] = $request->file('fichier')->store('ressources_additionnelles', 'public');
        }

        RessourceAdditionnelle::create($data);
        return back()->with('success', 'Ressource ajoutée.');
    }

    public function destroy(RessourceAdditionnelle $ressourceAdditionnelle)
    {
        if ($ressourceAdditionnelle->type === 'fichier_pdf' && $ressourceAdditionnelle->contenu) {
            Storage::disk('public')->delete($ressourceAdditionnelle->contenu);
        }
        $ressourceAdditionnelle->delete();
        return back()->with('success', 'Ressource supprimée.');
    }
}
