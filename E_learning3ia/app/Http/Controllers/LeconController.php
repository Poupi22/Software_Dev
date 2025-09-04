<?php

namespace App\Http\Controllers;

use App\Models\Lecon;
use App\Models\Chapitre;
use Illuminate\Http\Request;

class LeconController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'chapitre_id' => 'required|exists:chapitres,id',
        ]);

        $chapitre = Chapitre::find($validated['chapitre_id']);
        $validated['ordre'] = ($chapitre->lecons()->max('ordre') ?? 0) + 1;

        $lecon = Lecon::create($validated);

        return redirect()->route('dashboard.lecon.edit', $lecon->id)
                         ->with('success', 'Leçon créée. Vous pouvez maintenant y ajouter des ressources.');
    }

    public function edit(Lecon $lecon)
    {
        $lecon->load('ressources', 'chapitre');
        return view('admin_site.lecons.edit', compact('lecon'));
    }

    public function update(Request $request, Lecon $lecon)
    {
        $validated = $request->validate(['titre' => 'required|string|max:255']);
        $lecon->update($validated);
        return redirect()->route('dashboard.matiere.show', $lecon->chapitre->matiere_id)
                         ->with('success', 'Leçon mise à jour.');
    }

    public function destroy(Lecon $lecon)
    {
        $matiereId = $lecon->chapitre->matiere_id;
        $lecon->delete();
        return redirect()->route('dashboard.matiere.show', $matiereId)
                         ->with('success', 'Leçon supprimée.');
    }
}
