<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Inscription;
use App\Models\QuizTentative;
use App\Models\CoursInstance;
use App\Models\Lecon;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user's inscriptions
        $inscriptions = Inscription::where('user_id', $user->id)
            ->with(['programmeSession.coursInstances.matiere', 'programmeSession.coursInstances.chapitres.lecons'])
            ->get();

        // Calculate statistics
        $stats = $this->calculateStats($user, $inscriptions);

        // Get enrolled courses with progress
        $courses = $this->getCoursesWithProgress($inscriptions);

        // Get recent messages from conversations
        $messages = $this->getRecentMessages($user);

        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Get upcoming deadlines (third card)
        $upcomingDeadlines = $this->getUpcomingDeadlines($user);

        return view('etudiant.index', compact(
            'stats',
            'courses',
            'messages',
            'recentActivity',
            'upcomingDeadlines'
        ));
    }

    /**
     * Calculate dashboard statistics
     */
    private function calculateStats($user, $inscriptions)
    {
        // Count enrolled courses
        $coursesEnrolled = 0;
        $completedCourses = 0;

        foreach ($inscriptions as $inscription) {
            if ($inscription->programmeSession && $inscription->programmeSession->coursInstances) {
                $coursesEnrolled += $inscription->programmeSession->coursInstances->count();

                // Check for completed courses (all lessons completed)
                foreach ($inscription->programmeSession->coursInstances as $coursInstance) {
                    if ($this->isCourseCompleted($user, $coursInstance)) {
                        $completedCourses++;
                    }
                }
            }
        }

        // Calculate average quiz score
        $averageQuizScore = QuizTentative::where('user_id', $user->id)
            ->where('statut', '!=', 'en_cours')
            ->avg('score_obtenu') ?? 0;

        // Calculate study hours (approximate based on lesson completion)
        $studyHours = $this->calculateStudyHours($user);

        return [
            'courses_enrolled' => $coursesEnrolled,
            'completed_courses' => $completedCourses,
            'average_quiz_score' => round($averageQuizScore),
            'study_hours' => $studyHours
        ];
    }

    /**
     * Check if a course is completed
     */
    private function isCourseCompleted($user, $coursInstance)
    {
        $totalLessons = 0;
        $completedLessons = 0;

        foreach ($coursInstance->chapitres as $chapitre) {
            foreach ($chapitre->lecons as $lecon) {
                $totalLessons++;
                // Check if lesson has a completed quiz
                if ($lecon->quiz) {
                    $completedQuiz = QuizTentative::where('user_id', $user->id)
                        ->where('quiz_id', $lecon->quiz->id)
                        ->where('statut', 'reussie')
                        ->exists();

                    if ($completedQuiz) {
                        $completedLessons++;
                    }
                }
            }
        }

        return $totalLessons > 0 && $completedLessons === $totalLessons;
    }

    /**
     * Calculate approximate study hours
     */
    private function calculateStudyHours($user)
    {
        // Get all completed quizzes
        $completedQuizzes = QuizTentative::where('user_id', $user->id)
            ->where('statut', 'reussie')
            ->count();

        // Estimate 2 hours per completed quiz (including study time)
        return $completedQuizzes * 2;
    }

    /**
     * Get courses with progress information
     */
    private function getCoursesWithProgress($inscriptions)
    {
        $courses = [];
        $user = Auth::user();

        foreach ($inscriptions as $inscription) {
            if ($inscription->programmeSession && $inscription->programmeSession->coursInstances) {
                foreach ($inscription->programmeSession->coursInstances as $coursInstance) {
                    $totalLessons = 0;
                    $completedLessons = 0;

                    foreach ($coursInstance->chapitres as $chapitre) {
                        foreach ($chapitre->lecons as $lecon) {
                            $totalLessons++;
                            // Check if lesson has a completed quiz
                            if ($lecon->quiz) {
                                $completedQuiz = QuizTentative::where('user_id', $user->id)
                                    ->where('quiz_id', $lecon->quiz->id)
                                    ->where('statut', 'reussie')
                                    ->exists();

                                if ($completedQuiz) {
                                    $completedLessons++;
                                }
                            }
                        }
                    }

                    $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

                    $courses[] = [
                        'name' => $coursInstance->matiere->nom ?? 'Unknown Course',
                        'progress' => $progress,
                        'total_lessons' => $totalLessons,
                        'completed_lessons' => $completedLessons
                    ];
                }
            }
        }

        return $courses;
    }

    /**
     * Get recent messages from conversations
     */
    private function getRecentMessages($user)
    {
        // Get user's conversations
        $conversationIds = DB::table('conversation_user')
            ->where('user_id', $user->id)
            ->pluck('conversation_id');

        if ($conversationIds->isEmpty()) {
            return collect();
        }

        // Get the latest message from each conversation
        $latestMessages = Message::whereIn('conversation_id', $conversationIds)
            ->with(['user', 'conversation.users' => function($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('conversation_id')
            ->map(function($messages) {
                return $messages->first();
            })
            ->take(5);

        return $latestMessages;
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity($user)
    {
        $activities = [];

        // Get recent quiz attempts
        $quizAttempts = QuizTentative::where('user_id', $user->id)
            ->with('quiz.quizzable')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($quizAttempts as $attempt) {
            $quizable = $attempt->quiz->quizzable;
            $courseName = 'Unknown Course';

            if ($quizable instanceof Lecon && $quizable->chapitre && $quizable->chapitre->coursInstance) {
                $courseName = $quizable->chapitre->coursInstance->matiere->nom ?? 'Unknown Course';
            }

           $activities[] = [
    'type' => 'quiz',
    'title' => $attempt->statut === 'reussie'
        ? "Completed: " . ($quizable->titre ?? 'Quiz')
        : "Quiz Score: {$attempt->score_obtenu}% on " . ($quizable->titre ?? 'Quiz'),
    'course' => $courseName,
    'time' => $attempt->created_at->diffForHumans(),
    'icon' => $attempt->statut === 'reussie' ? 'check' : 'question-circle',
    'icon_color' => $attempt->statut === 'reussie' ? 'text-green-500' : 'text-yellow-500',
];

        }

        // Get recent forum posts
        $forumPosts = DB::table('posts')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($forumPosts as $post) {
            $activities[] = [
                'type' => 'forum',
                'title' => "Posted in forum",
                'course' => 'Discussion',
                'time' => \Carbon\Carbon::parse($post->created_at)->diffForHumans(),
                'icon' => 'comment',
                'icon_color' => 'purple'
            ];
        }

        // Sort activities by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 5);
    }

    /**
     * Get upcoming deadlines (for the third card)
     */
    private function getUpcomingDeadlines($user)
    {
        $deadlines = [];

        // Get user's courses
        $inscriptions = Inscription::where('user_id', $user->id)
            ->with(['programmeSession.coursInstances.matiere'])
            ->get();

        foreach ($inscriptions as $inscription) {
            if ($inscription->programmeSession && $inscription->programmeSession->coursInstances) {
                foreach ($inscription->programmeSession->coursInstances as $coursInstance) {
                    // Add course end date as a deadline (using created_at + 30 days as example)
                    $dueDate = $coursInstance->created_at->addDays(30);

                    if ($dueDate->isFuture()) {
                        $daysUntilDeadline = now()->diffInDays($dueDate, false);

                        if ($daysUntilDeadline >= 0 && $daysUntilDeadline <= 14) {
                            $deadlines[] = [
                                'title' => "Complete {$coursInstance->matiere->nom}",
                                'due_date' => $dueDate->format('M j, Y'),
                                'days_left' => $daysUntilDeadline,
                                'type' => 'course',
                                'priority' => $daysUntilDeadline <= 7 ? 'high' : 'medium'
                            ];
                        }
                    }
                }
            }
        }

        // Sort by days left
        usort($deadlines, function($a, $b) {
            return $a['days_left'] - $b['days_left'];
        });

        return array_slice($deadlines, 0, 5);
    }
}
