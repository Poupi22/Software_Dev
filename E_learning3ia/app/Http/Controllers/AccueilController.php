<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Accueil;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index()
    {
        $accueils = Accueil::latest()->paginate(10);
        return view('admin_site.accueils.index', compact('accueils'));
    }

    public function create()
    {
        return view('admin_site.accueils.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('accueils', 'public');
        }

        Accueil::create($validated);

        return redirect()->route('dashboard.accueil.index')
                         ->with('success', 'Élément ajouté avec succès');
    }

    public function show(Accueil $accueil)
    {
        return view('admin_site.accueils.show', compact('accueil'));
    }

    public function edit(Accueil $accueil)
    {
        return view('admin_site.accueils.edit', compact('accueil'));
    }

    public function update(Request $request, Accueil $accueil)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            if ($accueil->photo) {
                Storage::disk('public')->delete($accueil->photo);
            }
            $validated['photo'] = $request->file('photo')->store('accueils', 'public');
        }

        $accueil->update($validated);

        return redirect()->route('dashboard.accueil.index')
                         ->with('success', 'Élément mis à jour avec succès');
    }

    public function destroy(Accueil $accueil)
    {
        if ($accueil->photo) {
            Storage::disk('public')->delete($accueil->photo);
        }

        $accueil->delete();

        return redirect()->route('dashboard.accueil.index')
                         ->with('success', 'Élément supprimé avec succès');
    }
}
