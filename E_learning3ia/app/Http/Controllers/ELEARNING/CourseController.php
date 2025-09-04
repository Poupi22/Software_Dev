<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Inscription;
use App\Models\ProgrammeSession;
use App\Models\CoursInstance;
use App\Models\Matiere;
use App\Models\Chapitre;
use App\Models\Lecon;
use App\Models\QuizTentative;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $inscriptions = Inscription::where('user_id', $user->id)
            ->with([
                'programmeSession:id,programme_id',
                'programmeSession.programme:id,formation_id',
                'programmeSession.programme.formation:id,nom',
                'programmeSession.coursInstances:id,programme_session_id,matiere_id,trimestre',
                'programmeSession.coursInstances.matiere:id,nom,description',
                'programmeSession.coursInstances.formateurs:id,name',
                'programmeSession.coursInstances.chapitres' => function ($query) {
                    $query->withCount(['lecons as lessons_count']);
                },
                'programmeSession.coursInstances.chapitres.lecons.quiz.tentatives' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->where('statut', 'reussie');
                },
                'programmeSession.coursInstances.matiere.quizzes.tentatives' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->get(['id', 'user_id', 'programme_session_id']);

        $matieresParTrimestre = [];
        $formations = [];

        $semester1Completed = true; // on part du principe que oui, puis on vérifiera

        foreach ($inscriptions as $inscription) {
            $programmeSession = $inscription->programmeSession;

            if (!$programmeSession) {
                continue;
            }

            if ($programmeSession->programme && $programmeSession->programme->formation) {
                $formations[] = $programmeSession->programme->formation->nom;
            }

            foreach ($programmeSession->coursInstances as $coursInstance) {
                if (!$coursInstance->matiere) {
                    continue;
                }

                $trimestre = $coursInstance->trimestre;
                if (!isset($matieresParTrimestre[$trimestre])) {
                    $matieresParTrimestre[$trimestre] = [];
                }

                $formateurName = 'Non attribué';
                if ($coursInstance->formateurs->isNotEmpty()) {
                    $formateurName = $coursInstance->formateurs->first()->name;
                }

                // Check if user passed the subject quiz
                $subjectQuizPassed = false;
                $subjectQuiz = $coursInstance->matiere->quizzes->first();

                if ($subjectQuiz) {
                    $subjectQuizPassed = $subjectQuiz->tentatives
                        ->where('statut', 'reussie')
                        ->isNotEmpty();
                }

                // Calculate lesson progress
                $totalLessons = 0;
                $completedLessons = 0;

                foreach ($coursInstance->chapitres as $chapitre) {
                    $totalLessons += $chapitre->lecons->count();

                    foreach ($chapitre->lecons as $lecon) {
                        if ($lecon->quiz && $lecon->quiz->tentatives->isNotEmpty()) {
                            $completedLessons++;
                        }
                    }
                }

                // Progress logic
                if ($subjectQuizPassed) {
                    $progress = 100;
                } else {
                    $lessonProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                    $progress = min($lessonProgress, 99);
                }

                $chapters_count = $coursInstance->chapitres->count();
                $lessons_count = $coursInstance->chapitres->sum('lessons_count');

                // Vérifier si matière de semestre 1 est validée
                if ($trimestre == 1 && (!$subjectQuizPassed || $progress < 100)) {
                    $semester1Completed = false;
                }

                $matieresParTrimestre[$trimestre][] = [
                    'matiere' => $coursInstance->matiere,
                    'formateur' => $formateurName,
                    'cours_instance_id' => $coursInstance->id,
                    'chapters_count' => $chapters_count,
                    'lessons_count' => $lessons_count,
                    'progress' => $progress,
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                    'subject_quiz_passed' => $subjectQuizPassed,
                    'has_subject_quiz' => !is_null($subjectQuiz),
                    'all_lessons_completed' => ($completedLessons === $totalLessons) && ($totalLessons > 0),
                    // ✅ Blocage des matières de semestre 2 si semestre 1 pas validé
                    'is_accessible' => ($trimestre == 1) || ($trimestre == 2 && $semester1Completed)
                ];
            }
        }

        ksort($matieresParTrimestre);
        $formations = array_unique($formations);

        return view('etudiant.courses', compact('matieresParTrimestre', 'formations'));
    }

    public function show($coursInstanceId)
    {
        $user = auth()->user();

        $coursInstance = CoursInstance::findOrFail($coursInstanceId);

        // Vérifier inscription
        $inscription = Inscription::where('user_id', $user->id)
            ->where('programme_session_id', $coursInstance->programme_session_id)
            ->exists();

        if (!$inscription) {
            abort(403, 'Vous n\'êtes pas inscrit à ce cours.');
        }

        // ✅ Vérifier si l'étudiant a terminé tout le semestre 1 avant d'accéder au semestre 2
        if ($coursInstance->trimestre == 2) {
            $semester1Courses = CoursInstance::where('programme_session_id', $coursInstance->programme_session_id)
                ->where('trimestre', 1)
                ->with(['matiere.quizzes.tentatives' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->where('statut', 'reussie');
                }, 'chapitres.lecons.quiz.tentatives' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)->where('statut', 'reussie');
                }])
                ->get();

            foreach ($semester1Courses as $ci) {
                $subjectQuiz = $ci->matiere->quizzes->first();
                $quizPassed = $subjectQuiz && $subjectQuiz->tentatives->isNotEmpty();

                $totalLessons = $ci->chapitres->sum(fn($c) => $c->lecons->count());
                $completedLessons = 0;
                foreach ($ci->chapitres as $chap) {
                    foreach ($chap->lecons as $lecon) {
                        if ($lecon->quiz && $lecon->quiz->tentatives->isNotEmpty()) {
                            $completedLessons++;
                        }
                    }
                }

                if (!$quizPassed || $completedLessons < $totalLessons) {
                    abort(403, "Vous devez terminer toutes les matières du semestre 1 avant d'accéder au semestre 2.");
                }
            }
        }

        // Charger le cours et ses chapitres
        $coursInstance->load([
            'matiere:id,nom,description',
            'formateurs:id,name',
            'chapitres' => function ($query) use ($user) {
                $query->orderBy('ordre')->with([
                    'lecons' => function ($query) use ($user) {
                        $query->orderBy('ordre')->with([
                            'quiz' => function ($query) use ($user) {
                                $query->withCount([
                                    'tentatives as user_attempts_count' => function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ]);

        // Quiz final de la matière
        $subjectQuiz = Quiz::where('quizzable_type', 'App\Models\Matiere')
            ->where('quizzable_id', $coursInstance->matiere->id)
            ->first();

        // Déterminer l’accessibilité des chapitres/leçons
        $allChaptersCompleted = true;
        $previousChapterCompleted = true;

        foreach ($coursInstance->chapitres as $index => $chapitre) {
            $chapterCompleted = true;
            $chapitre->is_accessible = $index === 0 ? true : $previousChapterCompleted;

            foreach ($chapitre->lecons as $leconIndex => $lecon) {
                if ($lecon->quiz) {
                    $lecon->quiz_status = $this->getQuizStatus($user->id, $lecon->quiz);
                    $lecon->is_completed = $lecon->quiz_status['has_passed'];
                    $lecon->is_accessible = $chapitre->is_accessible &&
                        ($leconIndex === 0 || $chapitre->lecons[$leconIndex - 1]->is_completed);
                    $lecon->is_content_accessible = $lecon->is_accessible;

                    if (!$lecon->is_completed) {
                        $chapterCompleted = false;
                    }
                } else {
                    $lecon->is_completed = false;
                    $lecon->is_accessible = false;
                    $lecon->is_content_accessible = false;
                    $chapterCompleted = false;
                }
            }

            $chapitre->is_completed = $chapterCompleted;
            $previousChapterCompleted = $chapterCompleted;

            if (!$chapterCompleted) {
                $allChaptersCompleted = false;
            }
        }

        $subjectQuizAvailable = $allChaptersCompleted && $subjectQuiz;
        $subjectQuizStatus = null;

        if ($subjectQuiz) {
            $subjectQuizStatus = $this->getQuizStatus($user->id, $subjectQuiz);
            $subjectQuiz->is_blocked = $subjectQuizStatus['all_failed'];
            $subjectQuiz->can_retry = $subjectQuizStatus['can_retry'];
            $subjectQuiz->has_passed = $subjectQuizStatus['has_passed'];
        }

        $formateurName = $coursInstance->formateurs->isNotEmpty()
            ? $coursInstance->formateurs->first()->name
            : 'Non attribué';

        return view('etudiant.courses', [
            'coursInstance' => $coursInstance,
            'chapitres' => $coursInstance->chapitres,
            'formateur' => $formateurName,
            'subjectQuiz' => $subjectQuiz,
            'subjectQuizAvailable' => $subjectQuizAvailable,
            'subjectQuizStatus' => $subjectQuizStatus,
            'matieresParTrimestre' => [],
            'formations' => []
        ]);
    }

    private function getQuizStatus($userId, $quiz)
    {
        $attempts = QuizTentative::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAttempts = $attempts->count();
        $hasPassed = $attempts->where('statut', 'reussie')->count() > 0;
        $allFailed = $totalAttempts >= 3 && $attempts->where('statut', 'reussie')->count() === 0;

        return [
            'has_passed' => $hasPassed,
            'all_failed' => $allFailed,
            'total_attempts' => $totalAttempts,
            'max_attempts' => 3,
            'can_retry' => !$hasPassed && $totalAttempts < 3
        ];
    }
}
