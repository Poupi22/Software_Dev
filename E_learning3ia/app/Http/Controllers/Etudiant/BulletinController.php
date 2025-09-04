<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\ProgrammeSession;
use App\Services\BulletinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinController extends Controller
{
    protected BulletinService $bulletinService;

    public function __construct(BulletinService $bulletinService)
    {
        $this->bulletinService = $bulletinService;
    }

    /**
     * Afficher la liste des bulletins disponibles pour l'étudiant connecté
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer les inscriptions de l'étudiant
        $inscriptions = Inscription::with(['programmeSession.programme.formation', 'programmeSession.anneeAcademique'])
            ->where('user_id', $user->id)
            ->get();

        $bulletinsDisponibles = [];

        foreach ($inscriptions as $inscription) {
            $programmeSessionId = $inscription->programme_session_id;
            $semestres = $this->bulletinService->getSemestresDisponibles($programmeSessionId);

            foreach ($semestres as $semestre) {
                // Vérifier si des notes existent pour ce semestre
                $notes = $this->bulletinService->getNotesParSemestre($user->id, $programmeSessionId, $semestre);
                
                if ($notes->isNotEmpty()) {
                    $bulletinsDisponibles[] = [
                        'inscription' => $inscription,
                        'semestre' => $semestre,
                        'programme_session_id' => $programmeSessionId,
                        'formation' => $inscription->programmeSession->programme->formation->nom ?? '',
                        'annee' => $inscription->programmeSession->anneeAcademique->libelle ?? '',
                    ];
                }
            }
        }

        return view('etudiant.bulletins', compact('bulletinsDisponibles'));
    }

    /**
     * Afficher un bulletin semestriel
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');

        // Vérifier que l'étudiant est inscrit à cette session
        $inscription = Inscription::where('user_id', $user->id)
            ->where('programme_session_id', $programmeSessionId)
            ->firstOrFail();

        $bulletinData = $this->bulletinService->getBulletinSemestriel($user->id, $programmeSessionId, $semestre);
        $graphiqueBarres = $this->bulletinService->getGraphiqueBarresData($bulletinData['notes']);
        $camembertAssiduite = $this->bulletinService->getCamembertAssiduiteData($bulletinData['assiduite']['presence']);

        return view('etudiant.bulletin-show', compact('bulletinData', 'graphiqueBarres', 'camembertAssiduite'));
    }

    /**
     * Afficher le bulletin final
     */
    public function showFinal(Request $request)
    {
        $user = Auth::user();
        $programmeSessionId = $request->get('programme_session_id');

        // Vérifier que l'étudiant est inscrit à cette session
        $inscription = Inscription::where('user_id', $user->id)
            ->where('programme_session_id', $programmeSessionId)
            ->firstOrFail();

        $bulletinData = $this->bulletinService->getBulletinFinal($user->id, $programmeSessionId);

        return view('etudiant.bulletin-final', compact('bulletinData'));
    }

    /**
     * Télécharger le bulletin en PDF
     */
    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        $programmeSessionId = $request->get('programme_session_id');
        $semestre = $request->get('semestre');
        $type = $request->get('type', 'semestriel');

        // Vérifier que l'étudiant est inscrit à cette session
        $inscription = Inscription::where('user_id', $user->id)
            ->where('programme_session_id', $programmeSessionId)
            ->firstOrFail();

        if ($type === 'final') {
            $bulletinData = $this->bulletinService->getBulletinFinal($user->id, $programmeSessionId);
            $pdf = Pdf::loadView('pdf.bulletin-final', compact('bulletinData'));
            $filename = 'bulletin_final_' . $bulletinData['etudiant']['matricule'] . '.pdf';
        } else {
            $bulletinData = $this->bulletinService->getBulletinSemestriel($user->id, $programmeSessionId, $semestre);
            $graphiqueBarres = $this->bulletinService->getGraphiqueBarresData($bulletinData['notes']);
            $camembertAssiduite = $this->bulletinService->getCamembertAssiduiteData($bulletinData['assiduite']['presence']);
            
            $pdf = Pdf::loadView('pdf.bulletin-semestriel', compact('bulletinData', 'graphiqueBarres', 'camembertAssiduite'));
            $filename = 'bulletin_S' . $semestre . '_' . $bulletinData['etudiant']['matricule'] . '.pdf';
        }

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }
}
