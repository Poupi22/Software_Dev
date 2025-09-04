<?php

namespace App\Http\Controllers;

use App\Models\ContenuAdditionnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContenuAdditionnelController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'programme_session_id' => 'required|exists:programme_sessions,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();

        ContenuAdditionnel::create($validated);
        return back()->with('success', 'Contenu additionnel créé.');
    }

    public function show(ContenuAdditionnel $contenuAdditionnel)
    {
        $contenuAdditionnel->load('ressources');
        return view('admin_site.contenus_additionnels.show', compact('contenuAdditionnel'));
    }

    public function update(Request $request, ContenuAdditionnel $contenuAdditionnel)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'est_visible' => 'sometimes|boolean',
        ]);
        $contenuAdditionnel->update($validated);
        return back()->with('success', 'Contenu mis à jour.');
    }

    public function destroy(ContenuAdditionnel $contenuAdditionnel)
    {
        $contenuAdditionnel->delete();
        return redirect()->route('dashboard.programme_session.show', $contenuAdditionnel->programme_session_id)
                         ->with('success', 'Contenu additionnel supprimé.');
    }
    public function toggleVisibility(ContenuAdditionnel $contenuAdditionnel)
    {
        $contenuAdditionnel->est_visible = !$contenuAdditionnel->est_visible;
        $contenuAdditionnel->save();

        $action = $contenuAdditionnel->est_visible ? 'rendu visible' : 'caché';

        return back()->with('success', "Le contenu '{$contenuAdditionnel->titre}' a été {$action}.");
}
}
