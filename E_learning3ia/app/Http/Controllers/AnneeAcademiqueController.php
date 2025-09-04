<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        $annees = AnneeAcademique::latest()->paginate(10);
        return view('admin_site.annees_academiques.index', compact('annees'));
    }

    public function create()
    {
        return view('admin_site.annees_academiques.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:annee_academiques,libelle',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:Future,Active,Archivée',
        ]);

        if ($validated['statut'] === 'Active') {
            AnneeAcademique::where('statut', 'Active')->update(['statut' => 'Archivée']);
        }

        AnneeAcademique::create($validated);
        return redirect()->route('dashboard.annee_academique.index')->with('success', 'Année académique créée.');
    }

    public function edit(AnneeAcademique $anneeAcademique)
    {
        return view('admin_site.annees_academiques.edit', compact('anneeAcademique'));
    }

    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:annee_academiques,libelle,' . $anneeAcademique->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:Future,Active,Archivée',
        ]);

        if ($validated['statut'] === 'Active') {
            AnneeAcademique::where('id', '!=', $anneeAcademique->id)
                           ->where('statut', 'Active')
                           ->update(['statut' => 'Archivée']);
        }

        $anneeAcademique->update($validated);
        return redirect()->route('dashboard.annee_academique.index')->with('success', 'Année académique mise à jour.');
    }
    
    public function destroy(AnneeAcademique $anneeAcademique)
    {
        // Logique pour empêcher la suppression si des sessions y sont liées.
        // A implémenter pour plus de robustesse.
        $anneeAcademique->delete();
        return redirect()->route('dashboard.annee_academique.index')->with('success', 'Année académique supprimée.');
    }
}