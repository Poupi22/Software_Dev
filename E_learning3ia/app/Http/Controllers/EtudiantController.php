<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = User::role('Etudiant')->latest()->paginate(15);
        return view('admin_site.etudiants.index', compact('etudiants'));
    }

    public function show(User $etud)
    {
        $this->authorizeRole($etud);
        $etud->load('inscriptions.programmeSession.programme.formation','inscriptions.programmeSession.programme.qualification');
        return view('admin_site.etudiants.show', compact('etud'));
    }

    public function edit(User $etud)
    {
        $this->authorizeRole($etud);
        return view('admin_site.etudiants.edit', compact('etud'));
    }

    public function update(Request $request, User $etud)
    {
        $this->authorizeRole($etud);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'prenom'         => 'required|string|max:255',
            'sexe'           => 'required|string',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'nationalite'    => 'required|string|max:255',
            'tel1'           => 'required|string|max:20',
            'tel2'           => 'nullable|string|max:20',
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($etud->id)],
            'ville'          => 'required|string|max:255',
            'tuteur'         => 'required|string|max:255',
            'tel_tuteur'     => 'required|string|max:20',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Gestion de la suppression de photo
            if ($request->boolean('supprimer_photo') && $etud->photo) {
                Storage::disk('public')->delete($etud->photo);
                $validated['photo'] = null;
            }

            // Gestion de l'upload d'une nouvelle photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($etud->photo) {
                    Storage::disk('public')->delete($etud->photo);
                }
                $validated['photo'] = $request->file('photo')->store('photos_etudiants', 'public');
            } else {
                // Ne pas écraser la photo existante si aucun fichier n'est envoyé
                unset($validated['photo']);
            }

            $etud->update($validated);
            return redirect()->route('dashboard.etud.index')->with('success', 'Informations de l\'étudiant mises à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

public function search(Request $request)
{
    $query = $request->get('query', '');

    $etud = User::role('Etudiant')
        ->where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('prenom', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('matricule', 'LIKE', "%{$query}%");
        })
        ->limit(10)
        ->get(['id', 'name', 'prenom', 'matricule', 'email']);

    return response()->json($etud);
}

    private function authorizeRole(User $etud)
    {
        if (!$etud->hasRole('Etudiant')) {
            abort(404);
        }
    }
}
