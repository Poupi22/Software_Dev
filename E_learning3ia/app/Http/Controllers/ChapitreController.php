<?php

namespace App\Http\Controllers;

use App\Models\Chapitre;
use App\Models\Matiere;
use Illuminate\Http\Request;

class ChapitreController extends Controller
{


    public function create() { /* Logique pour une page dédiée si nécessaire */ }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'matiere_id' => 'required|exists:matieres,id',
        ]);

        $matiere = Matiere::find($validated['matiere_id']);
        $validated['ordre'] = ($matiere->chapitres()->max('ordre') ?? 0) + 1;

        Chapitre::create($validated);

        return redirect()->route('dashboard.matiere.show', $validated['matiere_id'])
                         ->with('success', 'Chapitre ajouté avec succès.');
    }

    public function edit(Chapitre $chapitre)
    {
        return view('admin_site.chapitres.edit', compact('chapitre'));
    }

    public function update(Request $request, Chapitre $chapitre)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $chapitre->update($validated);

        return redirect()->route('dashboard.matiere.show', $chapitre->matiere_id)
                         ->with('success', 'Chapitre mis à jour avec succès.');
    }

    public function destroy(Chapitre $chapitre)
    {
        $matiereId = $chapitre->matiere_id;
        $chapitre->delete();

        return redirect()->route('dashboard.matiere.show', $matiereId)
                         ->with('success', 'Chapitre supprimé avec succès.');
    }
}
