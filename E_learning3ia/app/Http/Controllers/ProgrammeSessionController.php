<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\Models\CoursInstance;
use App\Models\Programme;
use App\Models\ProgrammeSession;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgrammeSessionController extends Controller
{
    public function index()
    {
        $sessions = ProgrammeSession::with('programme.formation', 'anneeAcademique')->latest()->paginate(10);
        return view('admin_site.programme_sessions.index', compact('sessions'));
    }

    public function create()
    {
        $programmes = Programme::with('formation', 'qualification')->get();
        $annees = AnneeAcademique::whereIn('statut', ['Future', 'Active'])->get();
        // dd($annees);
        return view('admin_site.programme_sessions.create', compact('programmes', 'annees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'annee_academique_id' => 'required|exists:annee_academiques,id',
            'statut' => 'required|string',
        ]);
        //dd($validated);

        DB::beginTransaction();
        try {
            $session = ProgrammeSession::create($validated);

            $programme = Programme::with('matieresPivot')->find($validated['programme_id']);
            //dd($programme);
            foreach ($programme->matieresPivot as $matierePivot) {
                CoursInstance::create([
                    'programme_session_id' => $session->id,
                    'matiere_id' => $matierePivot->matiere_id,
                    'trimestre' => $matierePivot->trimestre,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.programme_session.index')->with('success', 'Session de programme créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show(ProgrammeSession $programmeSession)
    {
        $programmeSession->load('coursInstances.matiere', 'coursInstances.formateurs', 'contenusAdditionnels');
        $formateurs = User::role('Formateur')->get();
        return view('admin_site.programme_sessions.show', compact('programmeSession', 'formateurs'));
    }

    public function edit(ProgrammeSession $programmeSession)
    {
        $programmes = Programme::with('formation', 'qualification')->get();
        $annees = AnneeAcademique::all();
        return view('admin_site.programme_sessions.edit', compact('programmeSession', 'programmes', 'annees'));
    }

    public function update(Request $request, ProgrammeSession $programmeSession)
    {
        $validated = $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'statut' => 'required|string',
        ]);
        $programmeSession->update($validated);
        return redirect()->route('dashboard.programme_session.index')->with('success', 'Session mise à jour.');
    }

    public function assignerFormateur(Request $request, CoursInstance $coursInstance)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $coursInstance->formateurs()->sync([$validated['user_id']]);

        return back()->with('success', 'Formateur assigné avec succès.');
    }

    public function destroy(ProgrammeSession $programmeSession)
    {
        $programmeSession->delete();
        return redirect()->route('dashboard.programme_session.index')->with('success', 'Session de programme supprimée.');
    }

public function changeStatus(Request $request, ProgrammeSession $programmeSession)
{
    $nouveauStatut = $request->input('statut');
    $statutActuel = $programmeSession->statut;

    $transitionsValides = [
        'Planifiée' => ['Ouverte aux inscriptions'],
        'Ouverte aux inscriptions' => ['En cours'],
        'En cours' => ['Terminée'],
        'Terminée' => ['Archivée'],
    ];

    if (!isset($transitionsValides[$statutActuel]) || !in_array($nouveauStatut, $transitionsValides[$statutActuel])) {
        return back()->with('error', 'Transition de statut non autorisée de "' . $statutActuel . '" à "' . $nouveauStatut . '".');
    }

    if ($nouveauStatut === 'En cours' && $programmeSession->inscriptions()->count() === 0) {
        return back()->with('error', 'Impossible de démarrer les cours pour une session sans aucun étudiant inscrit.');
    }

    try {
        $programmeSession->update(['statut' => $nouveauStatut]);
        return back()->with('success', 'Le statut de la session a été mis à jour avec succès.');
    } catch (\Exception $e) {
        return back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
    }
}
}
