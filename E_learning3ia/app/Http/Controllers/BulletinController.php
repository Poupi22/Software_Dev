<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Assiduite;
use App\Models\ProgrammeSession;
use App\Models\CoursInstance;
use App\Models\Inscription;
use App\Models\User;
use App\Services\BulletinService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class BulletinController extends Controller
{
    protected BulletinService $bulletinService;

    public function __construct(BulletinService $bulletinService)
    {
        $this->bulletinService = $bulletinService;
    }

    /**
     * Page d'accueil de gestion des bulletins
     */
    public function index()
    {
        $programmeSessions = ProgrammeSession::with(['programme.formation', 'programme.qualification', 'anneeAcademique'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_site.bulletin.index', compact('programmeSessions'));
    }

    /**
     * Afficher la liste des étudiants pour saisir les notes
     */
    public function saisieNotes(Request $request)
    {
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');

        $programmeSessions = ProgrammeSession::with(['programme.formation', 'anneeAcademique'])
            ->orderBy('created_at', 'desc')
            ->get();

        $etudiants = collect();
        $coursInstances = collect();
        $semestres = collect();
        $selectedSession = null;

        if ($programmeSessionId) {
            $selectedSession = ProgrammeSession::find($programmeSessionId);
            $semestres = $this->bulletinService->getSemestresDisponibles($programmeSessionId);
            
            if ($semestre) {
                $etudiants = $this->bulletinService->getEtudiantsInscrits($programmeSessionId);
                $coursInstances = CoursInstance::with('matiere')
                    ->where('programme_session_id', $programmeSessionId)
                    ->where('trimestre', $semestre)
                    ->get();

                // Récupérer les notes existantes
                foreach ($etudiants as $etudiant) {
                    $etudiant->notes_saisies = Note::where('user_id', $etudiant->id)
                        ->whereIn('cours_instance_id', $coursInstances->pluck('id'))
                        ->get()
                        ->keyBy('cours_instance_id');
                }
            }
        }

        return view('admin_site.bulletin.saisie-notes', compact(
            'programmeSessions',
            'selectedSession',
            'semestres',
            'semestre',
            'etudiants',
            'coursInstances'
        ));
    }

    /**
     * Enregistrer les notes
     */
    public function storeNotes(Request $request)
    {
        $request->validate([
            'notes' => 'required|array',
            'notes.*.user_id' => 'required|exists:users,id',
            'notes.*.cours_instance_id' => 'required|exists:cours_instances,id',
            'notes.*.note_cc' => 'nullable|numeric|min:0|max:20',
            'notes.*.note_normale' => 'nullable|numeric|min:0|max:20',
        ]);

        foreach ($request->notes as $noteData) {
            // Ignorer si les deux notes sont vides
            if ($noteData['note_cc'] === null && $noteData['note_normale'] === null) {
                continue;
            }

            Note::updateOrCreate(
                [
                    'user_id' => $noteData['user_id'],
                    'cours_instance_id' => $noteData['cours_instance_id'],
                ],
                [
                    'note_cc' => $noteData['note_cc'],
                    'note_normale' => $noteData['note_normale'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Notes enregistrées avec succès.');
    }

    /**
     * Saisie des notes par étudiant (vue individuelle)
     */
    public function saisieNotesEtudiant(Request $request, User $etudiant)
    {
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');

        // Récupérer l'inscription de l'étudiant
        $inscription = Inscription::where('user_id', $etudiant->id)
            ->where('programme_session_id', $programmeSessionId)
            ->firstOrFail();

        $coursInstances = CoursInstance::with('matiere')
            ->where('programme_session_id', $programmeSessionId)
            ->where('trimestre', $semestre)
            ->get();

        $notes = Note::where('user_id', $etudiant->id)
            ->whereIn('cours_instance_id', $coursInstances->pluck('id'))
            ->get()
            ->keyBy('cours_instance_id');

        $programmeSession = ProgrammeSession::with(['programme.formation', 'anneeAcademique'])
            ->find($programmeSessionId);

        return view('admin_site.bulletin.saisie-notes-etudiant', compact(
            'etudiant',
            'coursInstances',
            'notes',
            'programmeSession',
            'semestre'
        ));
    }

    /**
     * Enregistrer les notes d'un étudiant
     */
    public function storeNotesEtudiant(Request $request, User $etudiant)
    {
        $request->validate([
            'programme_session_id' => 'required|exists:programme_sessions,id',
            'semestre' => 'required|integer',
            'notes' => 'required|array',
            'notes.*.cours_instance_id' => 'required|exists:cours_instances,id',
            'notes.*.note_cc' => 'nullable|numeric|min:0|max:20',
            'notes.*.note_normale' => 'nullable|numeric|min:0|max:20',
        ]);

        foreach ($request->notes as $noteData) {
            Note::updateOrCreate(
                [
                    'user_id' => $etudiant->id,
                    'cours_instance_id' => $noteData['cours_instance_id'],
                ],
                [
                    'note_cc' => $noteData['note_cc'] ?? null,
                    'note_normale' => $noteData['note_normale'] ?? null,
                ]
            );
        }

        return redirect()->route('dashboard.bulletin.saisie-notes', [
            'programme_session_id' => $request->programme_session_id,
            'semestre' => $request->semestre,
        ])->with('success', 'Notes de ' . $etudiant->prenom . ' ' . $etudiant->name . ' enregistrées avec succès.');
    }

    /**
     * Afficher la page de saisie d'assiduité
     */
    public function saisieAssiduite(Request $request)
    {
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');

        $programmeSessions = ProgrammeSession::with(['programme.formation', 'anneeAcademique'])
            ->orderBy('created_at', 'desc')
            ->get();

        $etudiants = collect();
        $semestres = collect();
        $selectedSession = null;

        if ($programmeSessionId) {
            $selectedSession = ProgrammeSession::find($programmeSessionId);
            $semestres = $this->bulletinService->getSemestresDisponibles($programmeSessionId);

            if ($semestre) {
                $etudiants = $this->bulletinService->getEtudiantsInscrits($programmeSessionId);

                // Récupérer l'assiduité existante
                foreach ($etudiants as $etudiant) {
                    $etudiant->assiduite = Assiduite::where('user_id', $etudiant->id)
                        ->where('programme_session_id', $programmeSessionId)
                        ->where('semestre', $semestre)
                        ->first();
                }
            }
        }

        return view('admin_site.bulletin.saisie-assiduite', compact(
            'programmeSessions',
            'selectedSession',
            'semestres',
            'semestre',
            'etudiants'
        ));
    }

    /**
     * Enregistrer l'assiduité
     */
    public function storeAssiduite(Request $request)
    {
        $request->validate([
            'programme_session_id' => 'required|exists:programme_sessions,id',
            'semestre' => 'required|integer',
            'assiduite' => 'required|array',
            'assiduite.*.user_id' => 'required|exists:users,id',
            'assiduite.*.pourcentage_presence' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->assiduite as $data) {
            Assiduite::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'programme_session_id' => $request->programme_session_id,
                    'semestre' => $request->semestre,
                ],
                [
                    'pourcentage_presence' => $data['pourcentage_presence'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Assiduité enregistrée avec succès.');
    }

    /**
     * Prévisualiser le bulletin d'un étudiant
     */
    public function preview(Request $request)
    {
        $userId = $request->get('user_id');
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');
        $type = $request->get('type', 'semestriel');

        if ($type === 'final') {
            $bulletinData = $this->bulletinService->getBulletinFinal($userId, $programmeSessionId);
            return view('admin_site.bulletin.preview-final', compact('bulletinData'));
        }

        $bulletinData = $this->bulletinService->getBulletinSemestriel($userId, $programmeSessionId, $semestre);
        $graphiqueBarres = $this->bulletinService->getGraphiqueBarresData($bulletinData['notes']);
        $camembertAssiduite = $this->bulletinService->getCamembertAssiduiteData($bulletinData['assiduite']['presence']);

        return view('admin_site.bulletin.preview', compact('bulletinData', 'graphiqueBarres', 'camembertAssiduite'));
    }

    
    /**
     * Télécharger le bulletin en PDF
     */

    public function downloadPdf(Request $request)
    {
        $userId = $request->get('user_id');
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');
        $type = $request->get('type', 'semestriel');

        // 🔥 Génération du QR Code pointant vers la page de vérification de l'étudiant
        $verificationUrl = route('acceuil.student.marks', ['id' => $userId]);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($verificationUrl);

        $qrBase64 = null;
        try {
            $response = Http::timeout(5)->get($qrUrl);
            if ($response->successful()) {
                $qrBase64 = 'data:image/png;base64,' . base64_encode($response->body());
            }
        } catch (\Exception $e) {
            // QR code non disponible, on continue sans
        }

        if ($type === 'final') {
            $bulletinData = $this->bulletinService->getBulletinFinal($userId, $programmeSessionId);

            $pdf = Pdf::loadView(
                'pdf.bulletin-final',
                compact('bulletinData', 'qrBase64')
            );

            $filename = 'bulletin_final_' . $bulletinData['etudiant']['matricule'] . '.pdf';

        } else {
            $bulletinData = $this->bulletinService->getBulletinSemestriel($userId, $programmeSessionId, $semestre);
            $graphiqueBarres = $this->bulletinService->getGraphiqueBarresData($bulletinData['notes']);
            $camembertAssiduite = $this->bulletinService->getCamembertAssiduiteData($bulletinData['assiduite']['presence']);

            $pdf = Pdf::loadView(
                'pdf.bulletin-semestriel',
                compact('bulletinData', 'graphiqueBarres', 'camembertAssiduite', 'qrBase64')
            );

            $filename = 'bulletin_S' . $semestre . '_' . $bulletinData['etudiant']['matricule'] . '.pdf';
        }

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Liste des bulletins générables pour les étudiants
     */
    public function listeEtudiants(Request $request)
    {
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');

        $programmeSessions = ProgrammeSession::with(['programme.formation', 'anneeAcademique'])
            ->orderBy('created_at', 'desc')
            ->get();

        $etudiants = collect();
        $semestres = collect();
        $selectedSession = null;

        if ($programmeSessionId) {
            $selectedSession = ProgrammeSession::find($programmeSessionId);
            $semestres = $this->bulletinService->getSemestresDisponibles($programmeSessionId);

            if ($semestre) {
                $etudiants = $this->bulletinService->getEtudiantsInscrits($programmeSessionId);
            }
        }

        return view('admin_site.bulletin.liste-etudiants', compact(
            'programmeSessions',
            'selectedSession',
            'semestres',
            'semestre',
            'etudiants',
            'programmeSessionId'
        ));
    }
}
