<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Matiere;
use App\Models\Programme;
use App\Models\CoursInstance;
use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgrammeController extends Controller
{
    // public function __construct()
    // {
    //     // Sécurité appliquée à toutes les actions du contrôleur
    //     $this->middleware('can:gérer programmes');
    // }

    public function index()
    {
        $programmes = Programme::with(['formation', 'qualification'])->withCount('matieres')->latest()->paginate(10);
        return view('admin_site.programmes.index', compact('programmes'));
    }

    public function create()
    {
        $formations = Formation::orderBy('nom')->get();
        $qualifications = Qualification::orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();
        return view('admin_site.programmes.create', compact('formations', 'qualifications', 'matieres'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateProgramme($request);
        $programme = Programme::create($validatedData);
        $this->syncMatieres($request, $programme);
        return redirect()->route('dashboard.programme.index')->with('success', 'Programme créé avec succès.');
    }

    public function show(Programme $programme)
    {
        $programme->load('matieres');
        return view('admin_site.programmes.show', compact('programme'));
    }

    public function edit(Programme $programme)
    {
        $programme->load('matieres');
        $formations = Formation::orderBy('nom')->get();
        $qualifications = Qualification::orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();
        return view('admin_site.programmes.edit', compact('programme', 'formations', 'qualifications', 'matieres'));
    }


    public function update(Request $request, Programme $programme)
    {
        \DB::beginTransaction(); // Utilisons une transaction pour la sécurité
        try {
            $validatedData = $this->validateProgramme($request, $programme);
            $programme->update($validatedData);

            // Cette méthode met à jour la table pivot programme_matiere
            $this->syncMatieres($request, $programme);

            // **NOUVEAU : On propage les changements aux sessions concernées**
            $this->syncProgrammeSessionsCours($programme);

            \DB::commit();
            return redirect()->route('dashboard.programme.index')->with('success', 'Programme mis à jour avec succès. Les sessions futures ont été synchronisées.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();
        return redirect()->route('dashboard.programme.index')->with('success', 'Programme supprimé.');
    }

    private function validateProgramme(Request $request, Programme $programme = null)
    {
        return $request->validate([
            'formation_id' => [
                'required',
                'exists:formations,id',
                Rule::unique('programmes')->where(function ($query) use ($request) {
                    return $query->where('qualification_id', $request->qualification_id);
                })->ignore($programme->id ?? null),
            ],
            'qualification_id' => 'required|exists:qualifications,id',
            'prix' => 'required|integer|min:0',
            'duree' => 'required|string|max:255',
            'matieres' => 'nullable|array',
            'matieres.*.trimestre' => 'nullable|required_with:matieres.*.id|integer|min:1',
        ]);
    }

    private function syncMatieres(Request $request, Programme $programme)
    {
        $matieres = collect($request->input('matieres', []))
            ->filter(fn ($matiere) => isset($matiere['id']) && !empty($matiere['trimestre']))
            ->mapWithKeys(fn ($matiere) => [$matiere['id'] => ['trimestre' => $matiere['trimestre']]]);

        $programme->matieres()->sync($matieres);
    }


    /**
     * Synchronise les CoursInstances des sessions non démarrées
     * en fonction des modifications apportées à un Programme.
     *
     * @param Programme $programme Le programme qui vient d'être mis à jour.
     */
    private function syncProgrammeSessionsCours(Programme $programme)
    {
        // 1. Récupérer toutes les sessions de ce programme qui ne sont pas encore commencées
        $sessionsToUpdate = $programme->sessions()
            ->whereIn('statut', ['Planifiée', 'Ouverte aux inscriptions'])
            ->get();

        // S'il n'y a aucune session à mettre à jour, on arrête ici
        if ($sessionsToUpdate->isEmpty()) {
            return;
        }

        // 2. Obtenir la nouvelle "carte" des matières du programme (id => trimestre)
        // C'est notre source de vérité.
        $newMatiereBlueprint = $programme->matieres()->pluck('trimestre', 'matiere_id');

        // 3. Boucler sur chaque session à synchroniser
        foreach ($sessionsToUpdate as $session) {
            // Obtenir les instances de cours actuelles pour cette session
            $currentCoursInstances = $session->coursInstances()->get()->keyBy('matiere_id');
            $currentMatiereIds = $currentCoursInstances->keys();

            // --- Comparaison et synchronisation ---

            // A. Matières à SUPPRIMER (celles qui sont dans la session mais plus dans le programme)
            $matiereIdsToDelete = $currentMatiereIds->diff($newMatiereBlueprint->keys());
            if ($matiereIdsToDelete->isNotEmpty()) {
                CoursInstance::where('programme_session_id', $session->id)
                            ->whereIn('matiere_id', $matiereIdsToDelete)
                            ->delete();
            }

            // B. Matières à AJOUTER (celles qui sont dans le programme mais pas encore dans la session)
            $matiereIdsToAdd = $newMatiereBlueprint->keys()->diff($currentMatiereIds);
            foreach ($matiereIdsToAdd as $matiereId) {
                CoursInstance::create([
                    'programme_session_id' => $session->id,
                    'matiere_id' => $matiereId,
                    'trimestre' => $newMatiereBlueprint[$matiereId] // On récupère le bon trimestre
                ]);
            }

            // C. Matières à METTRE À JOUR (celles dont le trimestre a pu changer)
            $matiereIdsToCheck = $currentMatiereIds->intersect($newMatiereBlueprint->keys());
            foreach ($matiereIdsToCheck as $matiereId) {
                $instance = $currentCoursInstances[$matiereId];
                $newTrimestre = $newMatiereBlueprint[$matiereId];

                // On met à jour seulement s'il y a un changement
                if ($instance->trimestre != $newTrimestre) {
                    $instance->update(['trimestre' => $newTrimestre]);
                }
            }
        }
    }
}
