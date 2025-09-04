<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inscription;
use App\Models\ProgrammeSession;
use App\Models\CoursInstance;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Support\Facades\Http;

class PublicStudentController extends Controller
{
    /**
     * Affiche la liste de tous les étudiants
     */
    public function showAllStudents()
    {
        $students = User::whereHas('roles', function($query) {
                $query->where('name', 'Etudiant');
            })
            ->with(['inscriptions.programmeSession.programme.formation',
                   'inscriptions.programmeSession.anneeAcademique'])
            ->whereNotNull('matricule')
            ->orderBy('name')
            ->get();

        return view('acceuil.students-list', compact('students'));
    }

    /**
     * Affiche les notes et informations d'un étudiant spécifique
     * (Page de vérification via QR Code)
     */
    public function showStudentMarks($studentId)
    {
        // Récupérer les informations de l'étudiant
        $student = User::with([
                'inscriptions.programmeSession.programme.formation',
                'inscriptions.programmeSession.anneeAcademique',
                'inscriptions.programmeSession.programme.qualification'
            ])
            ->findOrFail($studentId);

        // Vérifier que l'utilisateur est bien un étudiant
        if (!$student->hasRole('Etudiant')) {
            abort(404, 'Étudiant non trouvé');
        }

        $inscription = $student->inscriptions->first();

        if (!$inscription) {
            return view('acceuil.student-marks', compact('student'))
                ->with('error', 'Aucune inscription trouvée pour cet étudiant.');
        }

        // Récupérer les notes réelles groupées par trimestre
        $matieres = $this->getStudentMarks($studentId, $inscription->programme_session_id);

        // Récupérer les statistiques réelles
        $stats = $this->getStudentStats($studentId, $inscription->programme_session_id);

        // Générer le QR Code pointant vers cette page
        $pageUrl = route('acceuil.student.marks', ['id' => $studentId]);
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($pageUrl);
        $qrBase64 = null;
        try {
            $response = Http::timeout(5)->get($qrCodeUrl);
            if ($response->successful()) {
                $qrBase64 = 'data:image/png;base64,' . base64_encode($response->body());
            }
        } catch (\Exception $e) {
            // QR code non disponible, on continue sans
        }

        return view('acceuil.student-marks', compact('student', 'matieres', 'stats', 'qrBase64', 'pageUrl'));
    }

    /**
     * Récupère les vraies notes de l'étudiant par matière, groupées par trimestre
     */
    private function getStudentMarks($studentId, $programmeSessionId)
    {
        $includeQuiz = config('bulletin.include_quiz_online', false);
        $ponderationPhase1 = config('bulletin.ponderation_phase1');
        $ponderationPhase2 = config('bulletin.ponderation_phase2');

        // Récupérer les instances de cours pour cette session
        $coursInstances = CoursInstance::where('programme_session_id', $programmeSessionId)
            ->with(['matiere', 'formateurs'])
            ->get();

        $matieresWithMarks = [];

        foreach ($coursInstances as $cours) {
            // Récupérer la note réelle depuis la table notes
            $note = Note::where('user_id', $studentId)
                ->where('cours_instance_id', $cours->id)
                ->first();

            if (!$note) {
                // Pas de note saisie pour cette matière
                $matieresWithMarks[] = [
                    'id'         => $cours->matiere->id ?? null,
                    'nom'        => $cours->matiere->nom ?? 'N/A',
                    'code'       => $cours->matiere->code ?? '',
                    'credit'     => $cours->matiere->credit ?? 1,
                    'trimestre'  => $cours->trimestre,
                    'formateur'  => $this->getFormateurName($cours),
                    'note_cc'    => null,
                    'note_normale' => null,
                    'note_quiz'  => null,
                    'moyenne'    => null,
                    'statut'     => 'Non évalué',
                    'mention'    => '-',
                ];
                continue;
            }

            $noteNormale = $note->note_normale;
            $noteCc      = $note->note_cc;
            $noteQuiz    = $note->note_quiz;

            // Calcul de la moyenne selon la phase
            $moyenne = null;
            if ($noteNormale !== null || $noteCc !== null) {
                $nn = $noteNormale ?? 0;
                $nc = $noteCc ?? 0;
                $nq = $noteQuiz ?? 0;

                if ($includeQuiz && $noteQuiz !== null) {
                    $moyenne = ($nq * $ponderationPhase2['quiz'] / 100)
                             + ($nn * $ponderationPhase2['normale'] / 100)
                             + ($nc * $ponderationPhase2['cc'] / 100);
                } else {
                    $moyenne = ($nn * $ponderationPhase1['normale'] / 100)
                             + ($nc * $ponderationPhase1['cc'] / 100);
                }
                $moyenne = round($moyenne, 2);
            }

            $matieresWithMarks[] = [
                'id'           => $cours->matiere->id ?? null,
                'nom'          => $cours->matiere->nom ?? 'N/A',
                'code'         => $cours->matiere->code ?? '',
                'credit'       => $cours->matiere->credit ?? 1,
                'trimestre'    => $cours->trimestre,
                'formateur'    => $this->getFormateurName($cours),
                'note_cc'      => $noteCc,
                'note_normale' => $noteNormale,
                'note_quiz'    => $noteQuiz,
                'moyenne'      => $moyenne,
                'statut'       => $this->getStatut($moyenne),
                'mention'      => $this->getMention($moyenne),
            ];
        }

        // Grouper par trimestre
        $groupedByTrimestre = [];
        foreach ($matieresWithMarks as $matiere) {
            $groupedByTrimestre[$matiere['trimestre']][] = $matiere;
        }
        ksort($groupedByTrimestre);

        return $groupedByTrimestre;
    }

    /**
     * Récupère le nom du formateur d'un cours instance
     */
    private function getFormateurName($cours): string
    {
        $formateur = $cours->formateurs->first();
        if (!$formateur) {
            return 'Non assigné';
        }
        return trim(($formateur->name ?? '') . ' ' . ($formateur->prenom ?? ''));
    }

    /**
     * Retourne le statut selon la moyenne (sur 20)
     */
    private function getStatut(?float $moyenne): string
    {
        if ($moyenne === null) return 'Non évalué';
        if ($moyenne >= 10) return 'Admis';
        if ($moyenne >= 8)  return 'Rattrapage';
        return 'Ajourné';
    }

    /**
     * Retourne la mention selon la moyenne (sur 20)
     */
    private function getMention(?float $moyenne): string
    {
        if ($moyenne === null) return '-';
        $mentions = config('bulletin.mentions', [
            16 => 'Très Bien',
            14 => 'Bien',
            12 => 'Assez Bien',
            10 => 'Passable',
            0  => 'Insuffisant',
        ]);
        foreach ($mentions as $seuil => $mention) {
            if ($moyenne >= $seuil) return $mention;
        }
        return 'Insuffisant';
    }

    /**
     * Récupère les statistiques réelles de l'étudiant
     */
    private function getStudentStats($studentId, $programmeSessionId)
    {
        $includeQuiz = config('bulletin.include_quiz_online', false);
        $ponderationPhase1 = config('bulletin.ponderation_phase1');
        $ponderationPhase2 = config('bulletin.ponderation_phase2');

        $coursInstances = CoursInstance::where('programme_session_id', $programmeSessionId)
            ->pluck('id');

        $notes = Note::where('user_id', $studentId)
            ->whereIn('cours_instance_id', $coursInstances)
            ->with('coursInstance.matiere')
            ->get();

        $totalCredits    = 0;
        $creditsObtenus  = 0;
        $sommePonderee   = 0;
        $totalMoyennes   = 0;
        $matieresAdmises = 0;

        foreach ($notes as $note) {
            if ($note->note_normale === null && $note->note_cc === null) continue;

            $nn = $note->note_normale ?? 0;
            $nc = $note->note_cc ?? 0;
            $nq = $note->note_quiz ?? 0;

            if ($includeQuiz && $note->note_quiz !== null) {
                $moyenne = ($nq * $ponderationPhase2['quiz'] / 100)
                         + ($nn * $ponderationPhase2['normale'] / 100)
                         + ($nc * $ponderationPhase2['cc'] / 100);
            } else {
                $moyenne = ($nn * $ponderationPhase1['normale'] / 100)
                         + ($nc * $ponderationPhase1['cc'] / 100);
            }

            $credit = $note->coursInstance->matiere->credit ?? 1;
            $totalCredits   += $credit;
            $sommePonderee  += $moyenne * $credit;
            $totalMoyennes++;

            if ($moyenne >= 10) {
                $matieresAdmises++;
                $creditsObtenus += $credit;
            }
        }

        $moyenneGenerale = $totalCredits > 0 ? round($sommePonderee / $totalCredits, 2) : null;

        return [
            'moyenne_generale'  => $moyenneGenerale,
            'mention_generale'  => $this->getMention($moyenneGenerale),
            'matieres_evaluees' => $totalMoyennes,
            'matieres_admises'  => $matieresAdmises,
            'total_credits'     => $totalCredits,
            'credits_obtenus'   => $creditsObtenus,
        ];
    }
}
