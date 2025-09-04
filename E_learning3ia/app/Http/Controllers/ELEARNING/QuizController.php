<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\QuizTentative;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $lecon = Lecon::find($quiz->quizzable_id);

        if (!$lecon) {
            abort(404, 'Leçon non trouvée pour ce quiz');
        }

        $user = Auth::user();

        // Vérif inscription à la formation
        if (!$user->inscriptions()->whereHas('programmeSession', function($q) use ($lecon) {
            $q->whereHas('programme', function($q2) use ($lecon) {
                $q2->whereHas('matieres', function($q3) use ($lecon) {
                    $q3->whereHas('chapitres', function($q4) use ($lecon) {
                        $q4->whereHas('lecons', function($q5) use ($lecon) {
                            $q5->where('id', $lecon->id);
                        });
                    });
                });
            });
        })->exists()) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Check if quiz is locked (failed all 3 attempts)
        $attemptsCount = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();

        $passedAttempt = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // If user failed all 3 attempts and hasn't passed, block the quiz
        if ($attemptsCount >= 3 && !$passedAttempt) {
            return view('etudiant.quiz-locked', compact('quiz', 'lecon'));
        }

        // Get all questions for this quiz
        $allQuestions = $quiz->questions()->with('reponses')->get();

        // Check for existing active attempt
        $activeAttempt = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'en_cours')
            ->first();

        // If no active attempt, create one with random questions
        if (!$activeAttempt) {
            // Calculate how many questions to show (50% of total)
            $questionCount = $allQuestions->count();
            $questionsToShow = max(1, ceil($questionCount * 0.3333333333)); // At least 1 question

            // Ensure we don't try to get more questions than available
            $questionsToShow = min($questionsToShow, $questionCount);

            // Get random questions
            $randomQuestions = $allQuestions->random($questionsToShow);
            $randomQuestionIds = $randomQuestions->pluck('id')->toArray();

            $activeAttempt = QuizTentative::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score_obtenu' => 0,
                'statut' => 'en_cours',
                'started_at' => now(),
                'questions_selected' => $randomQuestionIds // Store the selected question IDs
            ]);

            // Store end time in session if quiz has time limit
            if ($quiz->duree_minutes) {
                $endTime = now()->addMinutes($quiz->duree_minutes);
                session(['quiz_end_time_' . $activeAttempt->id => $endTime]);
            }
        } else {
            // Use the previously selected questions from the attempt
            $selectedQuestionIds = $activeAttempt->questions_selected ?? [];

            if (empty($selectedQuestionIds)) {
                // If no questions selected yet, generate new ones and save them
                $questionCount = $allQuestions->count();
                $questionsToShow = max(1, ceil($questionCount * 0.5));
                $questionsToShow = min($questionsToShow, $questionCount);

                $randomQuestions = $allQuestions->random($questionsToShow);
                $selectedQuestionIds = $randomQuestions->pluck('id')->toArray();

                $activeAttempt->update([
                    'questions_selected' => $selectedQuestionIds
                ]);
            }

            // Get the questions that were previously selected
            $randomQuestions = Question::whereIn('id', $selectedQuestionIds)
                ->with('reponses')
                ->get()
                ->sortBy(function ($q) use ($selectedQuestionIds) {
                    return array_search($q->id, $selectedQuestionIds);
                })
                ->values();

            // Check if quiz time has expired
            if ($this->isQuizTimeExpired($activeAttempt)) {
                return $this->autoSubmitFromShow($quiz, $activeAttempt);
            }
        }

        // Calculate remaining time based on session end time
        $remainingTime = $this->getRemainingTime($activeAttempt);

        return view('etudiant.quiz-show', compact('quiz', 'randomQuestions', 'lecon', 'activeAttempt', 'remainingTime'));
    }

    /**
     * Check if quiz time has expired using session-stored end time
     */
    private function isQuizTimeExpired(QuizTentative $attempt): bool
    {
        $endTime = session('quiz_end_time_' . $attempt->id);

        if (!$endTime) {
            return false; // No time limit
        }

        return now()->greaterThanOrEqualTo($endTime);
    }

    /**
     * Calculate remaining time for quiz using session-stored end time
     */
    private function getRemainingTime(QuizTentative $attempt): int
    {
        $endTime = session('quiz_end_time_' . $attempt->id);

        if (!$endTime) {
            return 0; // No time limit
        }

        $remaining = now()->diffInSeconds($endTime, false);
        return max(0, $remaining);
    }

    // Helper method to auto-submit from the show method
    private function autoSubmitFromShow(Quiz $quiz, QuizTentative $attempt)
    {
        $user = Auth::user();

        // Get answers from session
        $answers = session('quiz_answers_' . $attempt->id, []);

        $selectedQuestionIds = $attempt->questions_selected ?? [];
        $score = $this->calculateScore($quiz, $answers, $selectedQuestionIds);

        // Check if user already has a passing attempt
        $hasPassed = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // If user already passed, this is just practice - don't record the score
        if ($hasPassed) {
            // Clear session data
            session()->forget('quiz_answers_' . $attempt->id);
            session()->forget('quiz_end_time_' . $attempt->id);

            // Delete this practice attempt
            $attempt->delete();

            // Redirect to results page with message
            return redirect()->route('etudiant.quiz.practice_results', [
                'quiz' => $quiz->id,
                'score' => $score
            ])->with('info', 'Temps écoulé ! Ceci était un exercice pratique.');
        }

        $statut = $score >= $quiz->seuil_reussite ? 'reussie' : 'echouee';

        // Update the attempt
        $attempt->update([
            'score_obtenu' => $score,
            'statut' => $statut,
            'submitted_at' => now()
        ]);

        // Clear session data
        session()->forget('quiz_answers_' . $attempt->id);
        session()->forget('quiz_end_time_' . $attempt->id);

        // Redirect to results page
        return redirect()->route('etudiant.quiz.results', [
            'quiz' => $quiz->id,
            'attempt' => $attempt->id
        ])->with('info', 'Temps écoulé ! Votre quiz a été soumis automatiquement.');
    }

    public function submit(Quiz $quiz, Request $request)
    {
        $user = Auth::user();

        // Get the active attempt
        $attempt = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        // Check if quiz time has expired before accepting submission
        if ($this->isQuizTimeExpired($attempt)) {
            return $this->autoSubmitFromShow($quiz, $attempt);
        }

        // Check if user already has a passing attempt
        $hasPassed = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // On récupère toutes les réponses envoyées
        $answers = $request->input('answers', []); // ['question_id' => ['reponse_id1', 'reponse_id2']]

        $selectedQuestionIds = $attempt->questions_selected ?? [];
        $score = $this->calculateScore($quiz, $answers, $selectedQuestionIds);

        // If user already passed, this is just practice - don't record the score
        if ($hasPassed) {
            // Clear session data
            session()->forget('quiz_answers_' . $attempt->id);
            session()->forget('quiz_end_time_' . $attempt->id);

            // Delete this practice attempt
            $attempt->delete();

            // Redirect to practice results
            return redirect()->route('etudiant.quiz.practice_results', [
                'quiz' => $quiz->id,
                'score' => $score
            ])->with('info', 'Ceci était un exercice pratique. Score: ' . $score . '%');
        }

        $statut = $score >= $quiz->seuil_reussite ? 'reussie' : 'echouee';

        // Update the attempt
        $attempt->update([
            'score_obtenu' => $score,
            'statut' => $statut,
            'submitted_at' => now()
        ]);

        // Store answers in session with the SPECIFIC attempt ID
        session(['quiz_answers_' . $attempt->id => $answers]);

        // Clear end time from session
        session()->forget('quiz_end_time_' . $attempt->id);

        // Redirect to results with the SPECIFIC attempt ID
        return redirect()->route('etudiant.quiz.results', [
            'quiz' => $quiz->id,
            'attempt' => $attempt->id
        ])->with('success', 'Quiz terminé ! Votre score: ' . $score . '%');
    }

    // FIXED: Improved scoring calculation
    private function calculateScore(Quiz $quiz, array $answers, array $selectedQuestionIds)
    {
        $totalScore = 0;

        // Only count the questions that were actually presented to the student
        $totalQuestions = count($selectedQuestionIds);

        if ($totalQuestions === 0) {
            return 0;
        }

        foreach ($selectedQuestionIds as $questionId) {
            $question = Question::find($questionId);
            if ($question) {
                $questionScore = $this->calculateQuestionScore($question, $answers[$questionId] ?? []);
                $totalScore += $questionScore;
            }
        }

        // Calculate percentage score
        return round(($totalScore / $totalQuestions) * 100);
    }

    // FIXED: Simplified and corrected question scoring
    private function calculateQuestionScore($question, $selectedAnswerIds)
    {
        $correctAnswers = $question->reponses->where('est_correcte', 1)->pluck('id')->toArray();
        $selectedAnswerIds = is_array($selectedAnswerIds) ? $selectedAnswerIds : [];

        $totalCorrect = count($correctAnswers);
        $totalSelectedCorrect = 0;
        $totalSelectedIncorrect = 0;

        if ($totalCorrect === 0) {
            return 0; // No correct answers defined for this question
        }

        // Count correct and incorrect selections
        foreach ($selectedAnswerIds as $selectedId) {
            if (in_array($selectedId, $correctAnswers)) {
                $totalSelectedCorrect++;
            } else {
                $totalSelectedIncorrect++;
            }
        }

        // For multiple correct answers questions
        if ($totalCorrect > 1) {
            // Award partial credit based on correct selections minus penalty for wrong answers
            $score = max(0, ($totalSelectedCorrect - ($totalSelectedIncorrect * 0.5)) / $totalCorrect);
            return $score;
        }
        // For single correct answer questions
        else {
            // All or nothing for single answer questions
            if ($totalSelectedCorrect === 1 && $totalSelectedIncorrect === 0) {
                return 1; // Full credit
            } else {
                return 0; // No credit
            }
        }
    }

    public function autoSubmit(Quiz $quiz, $attemptId)
    {
        $user = Auth::user();

        // Get the active attempt
        $attempt = QuizTentative::where('id', $attemptId)
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        // Check if user already has a passing attempt
        $hasPassed = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // Get answers from session
        $answers = session('quiz_answers_' . $attempt->id, []);

        $selectedQuestionIds = $attempt->questions_selected ?? [];
        $score = $this->calculateScore($quiz, $answers, $selectedQuestionIds);

        // If user already passed, this is just practice - don't record the score
        if ($hasPassed) {
            // Clear session data
            session()->forget('quiz_answers_' . $attempt->id);
            session()->forget('quiz_end_time_' . $attempt->id);

            // Delete this practice attempt
            $attempt->delete();

            // Redirect to practice results
            return redirect()->route('etudiant.quiz.practice_results', [
                'quiz' => $quiz->id,
                'score' => $score
            ])->with('info', 'Temps écoulé ! Ceci était un exercice pratique.');
        }

        $statut = $score >= $quiz->seuil_reussite ? 'reussie' : 'echouee';

        // Update the attempt
        $attempt->update([
            'score_obtenu' => $score,
            'statut' => $statut,
            'submitted_at' => now()
        ]);

        // Clear session data
        session()->forget('quiz_answers_' . $attempt->id);
        session()->forget('quiz_end_time_' . $attempt->id);

        // 👉 Redirige directement vers la page résultats
        return redirect()->route('etudiant.quiz.results', [
            'quiz' => $quiz->id,
            'attempt' => $attempt->id
        ])->with('info', 'Temps écoulé ! Votre quiz a été soumis automatiquement.');
    }

    public function results(Quiz $quiz, Request $request)
    {
        $user = Auth::user();

        // Get the SPECIFIC attempt ID from the URL parameter
        $attemptId = $request->route('attempt');

        if ($attemptId) {
            // Get the specific attempt
            $attempt = QuizTentative::where('id', $attemptId)
                ->where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->firstOrFail();
        } else {
            // Fallback: get the latest attempt (for direct URL access)
            $attempt = QuizTentative::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->latest()
                ->firstOrFail();
        }

        $lecon = Lecon::find($quiz->quizzable_id);

        // Get total attempts count
        $attemptsCount = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();

        // Check if user has passed
        $hasPassed = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // Get remaining attempts
        $remainingAttempts = $hasPassed ? 0 : max(0, 3 - $attemptsCount);

        // Get the questions that were actually shown to the student
        $selectedQuestionIds = $attempt->questions_selected ?? [];
        $questions = Question::whereIn('id', $selectedQuestionIds)
            ->with('reponses')
            ->get()
            ->sortBy(function ($q) use ($selectedQuestionIds) {
                return array_search($q->id, $selectedQuestionIds);
            })
            ->values();

        // Get answers for THIS SPECIFIC attempt
        $userAnswers = session('quiz_answers_' . $attempt->id, []);

        return view('etudiant.quiz-results', compact(
            'quiz', 'attempt', 'lecon', 'questions', 'userAnswers',
            'attemptsCount', 'remainingAttempts', 'hasPassed'
        ));
    }

    // New method for practice results
    public function practiceResults(Quiz $quiz, Request $request)
    {
        $score = $request->query('score', 0);
        $lecon = Lecon::find($quiz->quizzable_id);

        return view('etudiant.quiz-practice-results', compact('quiz', 'lecon', 'score'));
    }

    public function retry(Quiz $quiz)
    {
        $user = Auth::user();

        // Check if user already passed
        $hasPassed = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'reussie')
            ->exists();

        // Check total attempts
        $attemptsCount = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();

        // If user already passed, this is just practice
        if ($hasPassed) {
            // Delete any existing practice attempts
            QuizTentative::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('statut', 'en_cours')
                ->delete();

            // Clear all session data for this quiz attempts
            $sessionKeys = array_keys(session()->all());
            foreach ($sessionKeys as $key) {
                if (str_starts_with($key, 'quiz_answers_') || str_starts_with($key, 'quiz_end_time_')) {
                    session()->forget($key);
                }
            }

            return redirect()->route('etudiant.quiz.show', $quiz->id)
                ->with('info', 'Mode pratique activé !');
        }

        // If user has no attempts remaining, block access
        if ($attemptsCount >= 3) {
            return redirect()->route('etudiant.quiz.show', $quiz->id)
                ->with('error', 'Vous avez épuisé toutes vos tentatives pour ce quiz.');
        }

        // Delete previous incomplete attempts
        QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'en_cours')
            ->delete();

        // Clear all session data for this quiz attempts
        $sessionKeys = array_keys(session()->all());
        foreach ($sessionKeys as $key) {
            if (str_starts_with($key, 'quiz_answers_') || str_starts_with($key, 'quiz_end_time_')) {
                session()->forget($key);
            }
        }

        return redirect()->route('etudiant.quiz.show', $quiz->id)
            ->with('info', 'Nouvelle tentative commencée !');
    }

    public function saveAnswer(Quiz $quiz, Request $request)
    {
        $user = Auth::user();

        // Get the active attempt
        $attempt = QuizTentative::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('statut', 'en_cours')
            ->firstOrFail();

        // Check if quiz time has expired before saving answer
        if ($this->isQuizTimeExpired($attempt)) {
            return response()->json([
                'success' => false,
                'expired' => true,
                'redirect' => route('etudiant.quiz.results', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
            ]);
        }

        // Save answer to session for auto-save during quiz
        $questionId = $request->input('question_id');
        $answerId = $request->input('answer_id');
        $isChecked = $request->input('is_checked');

        $answers = session('quiz_answers_' . $attempt->id, []);

        if (!isset($answers[$questionId])) {
            $answers[$questionId] = [];
        }

        if ($isChecked) {
            // Add answer to the array
            if (!in_array($answerId, $answers[$questionId])) {
                $answers[$questionId][] = $answerId;
            }
        } else {
            // Remove answer from the array
            $answers[$questionId] = array_diff($answers[$questionId], [$answerId]);
        }

        session(['quiz_answers_' . $attempt->id => $answers]);

        return response()->json(['success' => true]);
    }

    // NEW: Check time endpoint for AJAX requests
    public function checkTime(Quiz $quiz, $attemptId)
    {
        $user = Auth::user();

        $attempt = QuizTentative::where('id', $attemptId)
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        // Check if quiz time has expired
        if ($this->isQuizTimeExpired($attempt)) {
            return response()->json([
                'expired' => true,
                'redirect' => route('etudiant.quiz.results', ['quiz' => $quiz->id, 'attempt' => $attempt->id])
            ]);
        }

        $remainingTime = $this->getRemainingTime($attempt);

        return response()->json([
            'remaining_time' => $remainingTime,
            'time_limit' => $quiz->duree_minutes * 60,
            'expired' => false
        ]);
    }
}
