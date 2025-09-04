<?php

namespace App\Services;

use App\Models\User;
use App\Models\Note;
use App\Models\Assiduite;
use App\Models\ProgrammeSession;
use App\Models\CoursInstance;
use App\Models\Inscription;
use Illuminate\Support\Collection;

class BulletinService
{
    /**
     * Obtenir les données complètes pour un bulletin semestriel
     */
    public function getBulletinSemestriel(int $userId, int $programmeSessionId, int $semestre): array
    {
        $user = User::findOrFail($userId);
        $programmeSession = ProgrammeSession::with(['programme.formation', 'programme.qualification', 'anneeAcademique'])
            ->findOrFail($programmeSessionId);

        // Vérifier que l'étudiant est inscrit à cette session
        $inscription = Inscription::where('user_id', $userId)
            ->where('programme_session_id', $programmeSessionId)
            ->firstOrFail();

        // Récupérer les cours instances du semestre
        $coursInstances = CoursInstance::with('matiere')
            ->where('programme_session_id', $programmeSessionId)
            ->where('trimestre', $semestre)
            ->get();

        // Récupérer les notes de l'étudiant pour ce semestre
        $notes = $this->getNotesParSemestre($userId, $programmeSessionId, $semestre);

        // Récupérer l'assiduité
        $assiduite = Assiduite::where('user_id', $userId)
            ->where('programme_session_id', $programmeSessionId)
            ->where('semestre', $semestre)
            ->first();

        // Préparer les données des notes avec calculs
        $notesData = $this->prepareNotesData($coursInstances, $notes);

        // Calculer la moyenne générale pondérée
        $moyenneGenerale = $this->calculerMoyenneGenerale($notesData);

        // Déterminer la mention
        $mention = $this->determinerMention($moyenneGenerale);

        return [
            'etudiant' => [
                'id' => $user->id,
                'matricule' => $user->matricule,
                'nom' => $user->name,
                'prenom' => $user->prenom,
                'date_naissance' => $user->date_naissance?->format('d/m/Y'),
                'lieu_naissance' => $user->lieu_naissance,
                'photo' => $user->photo,
                'sexe' => $user->sexe,
            ],
            'formation' => [
                'nom' => $programmeSession->programme->formation->nom ?? '',
                'qualification' => $programmeSession->programme->qualification->nom ?? '',
                'code' => $programmeSession->programme->formation->code ?? '',
            ],
            'session' => [
                'annee_academique' => $programmeSession->anneeAcademique->libelle ?? '',
                'semestre' => $semestre,
            ],
            'notes' => $notesData,
            'moyenne_generale' => $moyenneGenerale,
            'mention' => $mention,
            'assiduite' => [
                'presence' => $assiduite?->pourcentage_presence ?? 0,
                'absence' => $assiduite?->pourcentage_absence ?? 100,
            ],
            'institution' => config('bulletin.institution'),
            'ponderation' => $this->getPonderationActive(),
        ];
    }

    /**
     * Obtenir les données pour un bulletin final (tous les semestres)
     */
    public function getBulletinFinal(int $userId, int $programmeSessionId): array
    {
        $user = User::findOrFail($userId);
        $programmeSession = ProgrammeSession::with(['programme.formation', 'programme.qualification', 'anneeAcademique'])
            ->findOrFail($programmeSessionId);

        // Récupérer tous les semestres disponibles
        $semestres = CoursInstance::where('programme_session_id', $programmeSessionId)
            ->distinct()
            ->pluck('trimestre')
            ->sort()
            ->values();

        $bulletinsParSemestre = [];
        $totalCredits = 0;
        $totalPoints = 0;

        foreach ($semestres as $semestre) {
            $bulletinSemestriel = $this->getBulletinSemestriel($userId, $programmeSessionId, $semestre);
            $bulletinsParSemestre[$semestre] = $bulletinSemestriel;

            // Accumuler pour la moyenne annuelle
            foreach ($bulletinSemestriel['notes'] as $noteData) {
                if ($noteData['note_finale'] !== null) {
                    $totalCredits += $noteData['credit'];
                    $totalPoints += $noteData['note_finale'] * $noteData['credit'];
                }
            }
        }

        $moyenneAnnuelle = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : null;
        $mentionFinale = $this->determinerMention($moyenneAnnuelle);

        return [
            'etudiant' => $bulletinsParSemestre[$semestres->first()]['etudiant'] ?? [],
            'formation' => $bulletinsParSemestre[$semestres->first()]['formation'] ?? [],
            'session' => [
                'annee_academique' => $programmeSession->anneeAcademique->libelle ?? '',
                'semestres' => $semestres->toArray(),
            ],
            'bulletins_semestres' => $bulletinsParSemestre,
            'moyenne_annuelle' => $moyenneAnnuelle,
            'mention_finale' => $mentionFinale,
            'institution' => config('bulletin.institution'),
        ];
    }

    /**
     * Récupérer les notes d'un étudiant pour un semestre
     */
    public function getNotesParSemestre(int $userId, int $programmeSessionId, int $semestre): Collection
    {
        return Note::whereHas('coursInstance', function ($query) use ($programmeSessionId, $semestre) {
            $query->where('programme_session_id', $programmeSessionId)
                  ->where('trimestre', $semestre);
        })
        ->where('user_id', $userId)
        ->with('coursInstance.matiere')
        ->get();
    }

    /**
     * Préparer les données des notes avec les calculs
     */
    protected function prepareNotesData(Collection $coursInstances, Collection $notes): array
    {
        $notesData = [];

        foreach ($coursInstances as $coursInstance) {
            $note = $notes->firstWhere('cours_instance_id', $coursInstance->id);
            $matiere = $coursInstance->matiere;

            $noteCC = $note?->note_cc;
            $noteNormale = $note?->note_normale;
            $noteQuiz = $note?->note_quiz;
            $noteFinale = $note?->note_final;

            $notesData[] = [
                'cours_instance_id' => $coursInstance->id,
                'matiere_id' => $matiere->id,
                'matiere_nom' => $matiere->nom,
                'matiere_code' => $matiere->code ?? 'N/A',
                'credit' => $matiere->credit ?? 1,
                'note_cc' => $noteCC,
                'note_normale' => $noteNormale,
                'note_quiz' => $noteQuiz,
                'note_finale' => $noteFinale,
            ];
        }

        return $notesData;
    }

    /**
     * Calculer la moyenne générale pondérée par les crédits
     */
    public function calculerMoyenneGenerale(array $notesData): ?float
    {
        $totalCredits = 0;
        $totalPoints = 0;

        foreach ($notesData as $note) {
            if ($note['note_finale'] !== null) {
                $credit = $note['credit'];
                $totalCredits += $credit;
                $totalPoints += $note['note_finale'] * $credit;
            }
        }

        if ($totalCredits === 0) {
            return null;
        }

        return round($totalPoints / $totalCredits, 2);
    }

    /**
     * Déterminer la mention selon la moyenne
     */
    public function determinerMention(?float $moyenne): string
    {
        if ($moyenne === null) {
            return 'Non évalué';
        }

        $mentions = config('bulletin.mentions');
        krsort($mentions); // Trier par seuil décroissant

        foreach ($mentions as $seuil => $mention) {
            if ($moyenne >= $seuil) {
                return $mention;
            }
        }

        return 'Insuffisant';
    }

    /**
     * Obtenir la pondération active (Phase 1 ou Phase 2)
     */
    public function getPonderationActive(): array
    {
        $includeQuiz = config('bulletin.include_quiz_online', false);

        if ($includeQuiz) {
            return [
                'phase' => 2,
                'quiz' => config('bulletin.ponderation_phase2.quiz'),
                'normale' => config('bulletin.ponderation_phase2.normale'),
                'cc' => config('bulletin.ponderation_phase2.cc'),
            ];
        }

        return [
            'phase' => 1,
            'quiz' => 0,
            'normale' => config('bulletin.ponderation_phase1.normale'),
            'cc' => config('bulletin.ponderation_phase1.cc'),
        ];
    }

    /**
     * Obtenir les semestres disponibles pour une session
     */
    public function getSemestresDisponibles(int $programmeSessionId): Collection
    {
        return CoursInstance::where('programme_session_id', $programmeSessionId)
            ->distinct()
            ->pluck('trimestre')
            ->sort()
            ->values();
    }

    /**
     * Obtenir les étudiants inscrits à une session
     */
    public function getEtudiantsInscrits(int $programmeSessionId): Collection
    {
        return Inscription::with('user')
            ->where('programme_session_id', $programmeSessionId)
            ->get()
            ->pluck('user');
    }

    /**
     * Obtenir les données pour le graphique à barres (CC vs Normale par matière)
     */
    public function getGraphiqueBarresData(array $notesData): array
    {
        $labels = [];
        $dataCc = [];
        $dataNormale = [];

        foreach ($notesData as $note) {
            $labels[] = $note['matiere_code'] ?? $note['matiere_nom'];
            $dataCc[] = $note['note_cc'] ?? 0;
            $dataNormale[] = $note['note_normale'] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'CC',
                    'data' => $dataCc,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                ],
                [
                    'label' => 'Normale',
                    'data' => $dataNormale,
                    'backgroundColor' => 'rgba(75, 192, 92, 0.8)',
                ],
            ],
        ];
    }

    /**
     * Obtenir les données pour le camembert assiduité
     */
    public function getCamembertAssiduiteData(float $presence): array
    {
        return [
            'labels' => ['Présence', 'Absence'],
            'datasets' => [
                [
                    'data' => [$presence, 100 - $presence],
                    'backgroundColor' => ['rgba(75, 192, 92, 0.8)', 'rgba(255, 99, 132, 0.8)'],
                ],
            ],
        ];
    }
}
