<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    // Display the list of actualités
    public function index()
    {
        $actualites = Actualite::latest()->paginate(10);
        return view('admin_site.actualites.index', compact('actualites'));
    }

    // Show the form to create a new actualité
    public function create()
    {
        return view('admin_site.actualites.create');
    }

    // Store a new actualité
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('actualites', 'public');
        }

        Actualite::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'image' => $imagePath,
        ]);

        return redirect()->route('dashboard.actualite.index')->with('success', 'Actualité ajoutée.');
    }

    // Show the form to edit an existing actualité
    public function edit(Actualite $actualite)
    {
        return view('admin_site.actualites.edit', compact('actualite'));
    }

    // Update the actualité
    public function update(Request $request, Actualite $actualite)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            // Store new image
            $actualite->image = $request->file('image')->store('actualites', 'public');
        }

        $actualite->titre = $request->titre;
        $actualite->contenu = $request->contenu;
        $actualite->save();

        return redirect()->route('dashboard.actualite.index')->with('success', 'Actualité mise à jour.');
    }

    public function show(Actualite $actualite)
    {
    return view('admin_site.actualites.show', compact('actualite'));
    }
    public function destroy($id)
{

    $actualite = Actualite::findOrFail($id);
    if ($actualite->image) {
        \Storage::disk('public')->delete($actualite->image);
    }
    $actualite->delete();

    return redirect()->route('dashboard.actualite.index')->with('success', "Actualité supprimée avec succès.");
}


}
