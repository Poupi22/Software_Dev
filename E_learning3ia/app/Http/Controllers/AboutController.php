<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{

// AboutController.php
public function index()
{

    $abouts = About::latest()->paginate(10); // ← Returns Paginator
    return view('admin_site.abouts.index', compact('abouts'));
}



    // Afficher le formulaire de création
    public function create()
    {
        return view('admin_site.abouts.create');
    }

    // Enregistrer une nouvelle entrée
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'valeurs' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|string',
            'lien' => 'nullable|url',
            'statut' => 'required|in:actif,brouillon',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('abouts', 'public');
        }

        About::create($validated);

        return redirect()->route('dashboard.about.index')->with('success', 'Contenu créé avec succès.');
    }
     // app/Http/Controllers/AboutController.php

public function show($id)
{
    // Retrieve the specific about record
    $about = About::findOrFail($id);

    return view('admin_site.abouts.show', compact('about'));
}
    // Afficher le formulaire d'édition
    public function edit(About $about)
    {
        return view('admin_site.abouts.edit', compact('about'));
    }

    // Mettre à jour une entrée
    public function update(Request $request, About $about)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'valeurs' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|string',
            'lien' => 'nullable|url',
            'statut' => 'required|in:actif,brouillon',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si nécessaire
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $validated['image'] = $request->file('image')->store('abouts', 'public');
        }

        $about->update($validated);

        return redirect()->route('dashboard.about.index')->with('success', 'Contenu mis à jour avec succès.');
    }

    // Supprimer une entrée
    public function destroy(About $about)
    {
        if ($about->image) {
            Storage::disk('public')->delete($about->image);
        }
        $about->delete();
        return redirect()->route('dashboard.about.index')->with('success', 'Contenu supprimé avec succès.');
    }
}
