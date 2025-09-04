<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Inscription;
use App\Models\QuizTentative;
use App\Models\Quiz;
use App\Models\CoursInstance;
use App\Models\Formation;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function profile(Request $request)
    {
        // Get search parameters
        $search = $request->input('search');
        $specialty = $request->input('specialty');

        // Get overall statistics
        $stats = $this->getDashboardStats();

        // Get students data with filters
        $students = $this->getStudentsData($search, $specialty);

        // Get quiz results
        $quizResults = $this->getQuizResults();

        // Get progress data
        $progressData = $this->getProgressData($search, $specialty);

        // Get blocked students
        $blockedStudents = $this->getBlockedStudents();

        // Get all specialties for filter dropdown
        $specialties = Formation::pluck('nom', 'id');

        return view('etudiant.profile', compact(
            'stats',
            'students',
            'quizResults',
            'progressData',
            'blockedStudents',
            'specialties',
            'search',
            'specialty'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_students' => User::role('Etudiant')->count(),
            'active_courses' => CoursInstance::where('statut', 'Planifié')->count(),
            'quiz_attempts' => QuizTentative::count(),
            'blocked_students' => $this->getBlockedStudentsCount(),
        ];
    }

    /**
     * Get students data with progress information
     */
    private function getStudentsData($search = null, $specialty = null)
    {
        $query = User::role('Etudiant')
            ->with(['inscriptions.programmeSession.coursInstances' => function($query) {
                $query->with(['matiere', 'chapitres.lecons.quiz.tentatives' => function($q) {
                    $q->where('statut', 'reussie');
                }]);
            }]);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Apply specialty filter
        if ($specialty) {
            $query->whereHas('inscriptions.programmeSession.programme.formation', function($q) use ($specialty) {
                $q->where('id', $specialty);
            });
        }

        return $query->get()->map(function($student) {
            $progress = $this->calculateStudentProgress($student);

            return [
                'id' => $student->id,
                'name' => $student->name . ' ' . $student->prenom,
                'email' => $student->email,
                'specialty' => $this->getStudentSpecialty($student),
                'specialty_id' => $this->getStudentSpecialtyId($student),
                'progress' => $progress['percentage'],
                'last_activity' => $this->getLastActivity($student),
                'status' => $this->getStudentStatus($student),
                'completed_lessons' => $progress['completed'],
                'total_lessons' => $progress['total'],
                'average_score' => $this->getAverageScore($student),
                'subject_quizzes_passed' => $progress['subject_quizzes_passed'],
                'total_subject_quizzes' => $progress['total_subject_quizzes']
            ];
        });
    }

    /**
     * Calculate student progress - 100% ONLY if all subject quizzes passed
     */
    private function calculateStudentProgress($student)
    {
        $completedLessons = 0;
        $totalLessons = 0;
        $subjectQuizzesPassed = 0;
        $totalSubjectQuizzes = 0;

        foreach ($student->inscriptions as $inscription) {
            foreach ($inscription->programmeSession->coursInstances as $coursInstance) {
                // Count subject quizzes
                $subjectQuiz = Quiz::where('quizzable_type', 'App\Models\Matiere')
                    ->where('quizzable_id', $coursInstance->matiere_id)
                    ->first();

                if ($subjectQuiz) {
                    $totalSubjectQuizzes++;
                    $passed = QuizTentative::where('user_id', $student->id)
                        ->where('quiz_id', $subjectQuiz->id)
                        ->where('statut', 'reussie')
                        ->exists();

                    if ($passed) {
                        $subjectQuizzesPassed++;
                    }
                }

                // Count lessons
                foreach ($coursInstance->chapitres as $chapitre) {
                    $totalLessons += $chapitre->lecons->count();
                    foreach ($chapitre->lecons as $lecon) {
                        if ($lecon->quiz && $lecon->quiz->tentatives->isNotEmpty()) {
                            $completedLessons++;
                        }
                    }
                }
            }
        }

        // Progress is 100% ONLY if all subject quizzes are passed
        // Otherwise, it's based on lesson completion but capped at 99%
        if ($totalSubjectQuizzes > 0 && $subjectQuizzesPassed === $totalSubjectQuizzes) {
            $percentage = 100;
        } else {
            $lessonProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $percentage = min($lessonProgress, 99); // NEVER 100% without all subject quizzes
        }

        return [
            'completed' => $completedLessons,
            'total' => $totalLessons,
            'percentage' => $percentage,
            'subject_quizzes_passed' => $subjectQuizzesPassed,
            'total_subject_quizzes' => $totalSubjectQuizzes
        ];
    }

    /**
     * Get student specialty (formation)
     */
    private function getStudentSpecialty($student)
    {
        $specialty = 'Non inscrit';

        if ($student->inscriptions->isNotEmpty()) {
            $inscription = $student->inscriptions->first();
            if ($inscription->programmeSession && $inscription->programmeSession->programme) {
                $specialty = $inscription->programmeSession->programme->formation->nom ?? 'Non spécifié';
            }
        }

        return $specialty;
    }

    /**
     * Get student specialty ID
     */
    private function getStudentSpecialtyId($student)
    {
        if ($student->inscriptions->isNotEmpty()) {
            $inscription = $student->inscriptions->first();
            if ($inscription->programmeSession && $inscription->programmeSession->programme) {
                return $inscription->programmeSession->programme->formation->id ?? null;
            }
        }

        return null;
    }

    /**
     * Get student last activity
     */
    private function getLastActivity($student)
    {
        $lastQuizAttempt = QuizTentative::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastQuizAttempt) {
            return $lastQuizAttempt->created_at->diffForHumans();
        }

        return 'Aucune activité';
    }

    /**
     * Get student status (active/blocked)
     */
    private function getStudentStatus($student)
    {
        // Check if student has any blocked quizzes (3 failed attempts)
        $blockedQuizzes = QuizTentative::where('user_id', $student->id)
            ->select('quiz_id', DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->having('attempts', '>=', 3)
            ->whereNotIn('quiz_id', function($query) use ($student) {
                $query->select('quiz_id')
                    ->from('quiz_tentatives')
                    ->where('user_id', $student->id)
                    ->where('statut', 'reussie');
            })
            ->exists();

        return $blockedQuizzes ? 'blocked' : 'active';
    }

    /**
     * Get student average quiz score
     */
    private function getAverageScore($student)
    {
        $scores = QuizTentative::where('user_id', $student->id)
            ->where('statut', '!=', 'en_cours')
            ->pluck('score_obtenu')
            ->toArray();

        if (count($scores) > 0) {
            return round(array_sum($scores) / count($scores));
        }

        return 0;
    }

    /**
     * Get quiz results data
     */
    private function getQuizResults()
    {
        return QuizTentative::with(['quiz.quizzable', 'user'])
            ->where('statut', '!=', 'en_cours')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function($attempt) {
                $quizable = $attempt->quiz->quizzable;
                $quizType = $quizable ? (get_class($quizable) === 'App\Models\Lecon' ? 'lesson' : 'subject') : 'unknown';

                if ($quizType === 'lesson') {
                    $quizName = $quizable->titre ?? 'Quiz de leçon';
                } else if ($quizType === 'subject') {
                    $quizName = $quizable->nom ?? 'Quiz de matière';
                } else {
                    $quizName = 'Quiz inconnu';
                }

                return [
                    'student_name' => $attempt->user->name . ' ' . $attempt->user->prenom,
                    'quiz_name' => $quizName,
                    'score' => $attempt->score_obtenu,
                    'attempts' => $this->getQuizAttemptsCount($attempt->user_id, $attempt->quiz_id),
                    'date' => $attempt->created_at->format('Y-m-d'),
                    'type' => $quizType,
                    'quiz_id' => $attempt->quiz_id,
                    'student_id' => $attempt->user_id
                ];
            });
    }

    /**
     * Get number of attempts for a specific quiz
     */
    private function getQuizAttemptsCount($userId, $quizId)
    {
        return QuizTentative::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->count();
    }

    /**
     * Get progress data for all students
     */
    private function getProgressData($search = null, $specialty = null)
    {
        return $this->getStudentsData($search, $specialty);
    }

    /**
     * Get blocked students
     */
    private function getBlockedStudents()
    {
        $blockedStudents = [];

        $students = User::role('Etudiant')->get();

        foreach ($students as $student) {
            $blockedQuizzes = QuizTentative::where('user_id', $student->id)
                ->select('quiz_id', DB::raw('COUNT(*) as attempts'))
                ->groupBy('quiz_id')
                ->having('attempts', '>=', 3)
                ->whereNotIn('quiz_id', function($query) use ($student) {
                    $query->select('quiz_id')
                        ->from('quiz_tentatives')
                        ->where('user_id', $student->id)
                        ->where('statut', 'reussie');
                })
                ->get();

            if ($blockedQuizzes->isNotEmpty()) {
                foreach ($blockedQuizzes as $blockedQuiz) {
                    $quiz = Quiz::find($blockedQuiz->quiz_id);
                    $quizable = $quiz->quizzable;

                    if ($quizable) {
                        $quizType = get_class($quizable) === 'App\Models\Lecon' ? 'lesson' : 'subject';
                        $quizName = $quizType === 'lesson' ?
                            ($quizable->titre ?? 'Quiz de leçon') :
                            ($quizable->nom ?? 'Quiz de matière');
                    } else {
                        $quizName = 'Quiz inconnu';
                    }

                    $blockedStudents[] = [
                        'student_id' => $student->id,
                        'student_name' => $student->name . ' ' . $student->prenom,
                        'student_email' => $student->email,
                        'quiz_name' => $quizName,
                        'quiz_id' => $blockedQuiz->quiz_id,
                        'attempts' => $blockedQuiz->attempts,
                        'block_date' => QuizTentative::where('user_id', $student->id)
                            ->where('quiz_id', $blockedQuiz->quiz_id)
                            ->orderBy('created_at', 'desc')
                            ->first()
                            ->created_at
                            ->format('Y-m-d')
                    ];
                }
            }
        }

        return $blockedStudents;
    }

    /**
     * Get count of blocked students
     */
    private function getBlockedStudentsCount()
    {
        $blockedCount = 0;
        $students = User::role('Etudiant')->get();

        foreach ($students as $student) {
            $hasBlockedQuiz = QuizTentative::where('user_id', $student->id)
                ->select('quiz_id', DB::raw('COUNT(*) as attempts'))
                ->groupBy('quiz_id')
                ->having('attempts', '>=', 3)
                ->whereNotIn('quiz_id', function($query) use ($student) {
                    $query->select('quiz_id')
                        ->from('quiz_tentatives')
                        ->where('user_id', $student->id)
                        ->where('statut', 'reussie');
                })
                ->exists();

            if ($hasBlockedQuiz) {
                $blockedCount++;
            }
        }

        return $blockedCount;
    }

    /**
     * Get average scores by subject
     */
    public function getAverageScores()
    {
        $averageScores = QuizTentative::join('quizzes', 'quiz_tentatives.quiz_id', '=', 'quizzes.id')
            ->join('matieres', function($join) {
                $join->on('quizzes.quizzable_id', '=', 'matieres.id')
                     ->where('quizzes.quizzable_type', 'App\Models\Matiere');
            })
            ->select('matieres.nom as subject_name', DB::raw('AVG(quiz_tentatives.score_obtenu) as average_score'))
            ->groupBy('matieres.id', 'matieres.nom')
            ->get();

        return response()->json($averageScores);
    }

    /**
     * Get student details
     */
    public function getStudentDetails($studentId)
    {
        $student = User::with([
            'inscriptions.programmeSession.programme.formation',
            'inscriptions.programmeSession.coursInstances.matiere',
            'inscriptions.programmeSession.coursInstances.chapitres.lecons.quiz.tentatives' => function($q) use ($studentId) {
                $q->where('user_id', $studentId);
            }
        ])->findOrFail($studentId);

        $progress = $this->calculateStudentProgress($student);
        $quizAttempts = QuizTentative::where('user_id', $studentId)
            ->with('quiz.quizzable')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'student' => $student,
            'progress' => $progress,
            'quiz_attempts' => $quizAttempts
        ]);
    }

    /**
     * Get quiz details for a student
     */
    public function getQuizDetails($studentId, $quizId)
    {
        $quiz = Quiz::with('quizzable')->findOrFail($quizId);
        $student = User::findOrFail($studentId);

        $attempts = QuizTentative::where('user_id', $studentId)
            ->where('quiz_id', $quizId)
            ->orderBy('created_at', 'desc')
            ->get();

        $quizable = $quiz->quizzable;
        $quizType = $quizable ? (get_class($quizable) === 'App\Models\Lecon' ? 'Lesson Quiz' : 'Subject Quiz') : 'Unknown Quiz';
        $quizName = $quizable ? ($quizable->titre ?? $quizable->nom ?? 'Unknown Quiz') : 'Unknown Quiz';

        return response()->json([
            'quiz_name' => $quizName,
            'quiz_type' => $quizType,
            'student_name' => $student->name . ' ' . $student->prenom,
            'total_attempts' => $attempts->count(),
            'best_score' => $attempts->max('score_obtenu') ?? 0,
            'attempts' => $attempts
        ]);
    }

    /**
     * Reset quiz attempts for a student
     */
    public function resetQuizAttempts(Request $request, $studentId, $quizId)
    {
        QuizTentative::where('user_id', $studentId)
            ->where('quiz_id', $quizId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz attempts reset successfully'
        ]);
    }

    /**
     * Unblock student - Reset all quiz attempts
     */
    public function unblockStudent(Request $request, $studentId)
    {
        $student = User::findOrFail($studentId);

        // Reset quiz attempts for blocked quizzes
        $blockedQuizzes = QuizTentative::where('user_id', $student->id)
            ->select('quiz_id', DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->having('attempts', '>=', 3)
            ->whereNotIn('quiz_id', function($query) use ($student) {
                $query->select('quiz_id')
                    ->from('quiz_tentatives')
                    ->where('user_id', $student->id)
                    ->where('statut', 'reussie');
            })
            ->get();

        foreach ($blockedQuizzes as $blockedQuiz) {
            // Delete all attempts for this quiz
            QuizTentative::where('user_id', $student->id)
                ->where('quiz_id', $blockedQuiz->quiz_id)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Student unblocked successfully'
        ]);
    }
}
