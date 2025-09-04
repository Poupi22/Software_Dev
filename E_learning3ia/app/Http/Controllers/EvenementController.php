<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Evenement::orderBy('date_debut', 'desc')->paginate(10);
        return view('admin_site.evenements.index', compact('evenements'));
    }

    public function create()
    {
        return view('admin_site.evenements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'type_evenement' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'statut' => 'required|in:brouillon,actif,archive'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('evenements', 'public');
        }

        Evenement::create($validated);

        return redirect()->route('dashboard.evenement.index')
                         ->with('success', 'Événement créé avec succès');
    }

    public function show(Evenement $evenement)
    {
        return view('admin_site.evenements.show', compact('evenement'));
    }

    public function edit(Evenement $evenement)
    {
        return view('admin_site.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lieu' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'type_evenement' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'statut' => 'required|in:brouillon,actif,archive'
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($evenement->image) {
                Storage::disk('public')->delete($evenement->image);
            }
            $validated['image'] = $request->file('image')->store('evenements', 'public');
        }

        $evenement->update($validated);

        return redirect()->route('dashboard.evenement.index')
                         ->with('success', 'Événement mis à jour avec succès');
    }

    public function destroy(Evenement $evenement)
    {
        if ($evenement->image) {
            Storage::disk('public')->delete($evenement->image);
        }

        $evenement->delete();

        return redirect()->route('dashboard.evenement.index')
                         ->with('success', 'Événement supprimé avec succès');
    }
}
