<?php

namespace App\Http\Controllers;

use App\Models\FichePreinscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FichePreinscriptionController extends Controller
{
    public function index()
    {
        $fiches = FichePreinscription::all();
        return view('admin_site.fiche_preinscription.index', compact('fiches'));
    }

    public function create()
    {
        if (FichePreinscription::count() > 0) {
            return redirect()->route('dashboard.fiche_preinscription.index')->with('error', 'Une fiche existe déjà. Vous pouvez la modifier.');
        }
        return view('admin_site.fiche_preinscription.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fiche' => 'required|file|mimes:pdf|max:5120',
        ]);

        $fichier = $request->file('fiche');
        $chemin = $fichier->store('fiches_preinscription', 'public');
        $nomOriginal = $fichier->getClientOriginalName();

        // Supprimer l'ancienne fiche s'il y en a une
        if ($ficheExistante = FichePreinscription::first()) {
            Storage::disk('public')->delete($ficheExistante->chemin_fichier);
            $ficheExistante->delete();
        }

        FichePreinscription::create([
            'chemin_fichier' => $chemin,
            'nom_original' => $nomOriginal,
        ]);

        return redirect()->route('dashboard.fiche_preinscription.index')->with('success', 'La fiche de préinscription a été enregistrée.');
    }

    public function show(FichePreinscription $fichePreinscription)
    {
        // La page show n'a pas beaucoup de sens ici, on redirige vers l'index.
        return redirect()->route('dashboard.fiche_preinscription.index');
    }

    public function edit(FichePreinscription $fichePreinscription)
    {
        return view('admin_site.fiche_preinscription.edit', compact('fichePreinscription'));
    }

    public function update(Request $request, FichePreinscription $fichePreinscription)
    {
        $validated = $request->validate([
            'fiche' => 'required|file|mimes:pdf|max:5120',
        ]);

        $fichier = $request->file('fiche');
        $chemin = $fichier->store('fiches_preinscription', 'public');
        $nomOriginal = $fichier->getClientOriginalName();

        if ($fichePreinscription->chemin_fichier) {
            Storage::disk('public')->delete($fichePreinscription->chemin_fichier);
        }

        $fichePreinscription->update([
            'chemin_fichier' => $chemin,
            'nom_original' => $nomOriginal,
        ]);

        return redirect()->route('dashboard.fiche_preinscription.index')->with('success', 'La fiche de préinscription a été mise à jour.');
    }

    public function destroy(FichePreinscription $fichePreinscription)
    {
        if ($fichePreinscription->chemin_fichier) {
            Storage::disk('public')->delete($fichePreinscription->chemin_fichier);
        }
        $fichePreinscription->delete();
        return redirect()->route('dashboard.fiche_preinscription.index')->with('success', 'La fiche de préinscription a été supprimée.');
    }
}
