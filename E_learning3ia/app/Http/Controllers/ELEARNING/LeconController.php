<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Lecon;
use App\Models\QuizTentative;
use Illuminate\Support\Facades\Auth;

class LeconController extends Controller
{
    public function showRessources($leconId)
    {
        $user = Auth::user();
        
        // Charger la leçon avec les relations nécessaires
        $lecon = Lecon::with([
            'chapitre.matiere.coursInstances.programmeSession.inscriptions',
            'ressources',
            'quiz' // Charger le quiz associé à la leçon
        ])->findOrFail($leconId);

        // Vérifier si l'utilisateur a accès à cette leçon
        $hasAccess = $user->inscriptions()
            ->whereHas('programmeSession', function($query) use ($lecon) {
                $query->whereHas('coursInstances', function($query) use ($lecon) {
                    $query->whereHas('matiere', function($query) use ($lecon) {
                        $query->whereHas('chapitres', function($query) use ($lecon) {
                            $query->whereHas('lecons', function($query) use ($lecon) {
                                $query->where('id', $lecon->id);
                            });
                        });
                    });
                });
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Vous n\'êtes pas inscrit à ce cours.');
        }

        // Récupérer les ressources de la leçon
        $ressources = $lecon->ressources()->orderBy('ordre')->get();
        
        // Récupérer la tentative de quiz de l'utilisateur s'il y a un quiz
        $userQuizAttempt = null;
        if ($lecon->quiz) {
            $userQuizAttempt = QuizTentative::where('user_id', $user->id)
                ->where('quiz_id', $lecon->quiz->id)
                ->latest()
                ->first();
        }

        return view('etudiant.lecon.ressources', compact('lecon', 'ressources', 'userQuizAttempt'));
    }

    public function show($leconId)
    {
        $user = Auth::user();
        
        // Charger la leçon avec les relations nécessaires
        $lecon = Lecon::with([
            'chapitre.matiere.coursInstances.programmeSession.inscriptions',
            'ressources', // Charger les ressources
            'quiz' // Charger le quiz associé à la leçon
        ])->findOrFail($leconId);

        // Vérification d'accès
        $hasAccess = $user->inscriptions()
            ->whereHas('programmeSession', function($query) use ($lecon) {
                $query->whereHas('coursInstances', function($query) use ($lecon) {
                    $query->whereHas('matiere', function($query) use ($lecon) {
                        $query->whereHas('chapitres', function($query) use ($lecon) {
                            $query->whereHas('lecons', function($query) use ($lecon) {
                                $query->where('id', $lecon->id);
                            });
                        });
                    });
                });
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer la tentative de quiz de l'utilisateur s'il y a un quiz
        $userQuizAttempt = null;
        if ($lecon->quiz) {
            $userQuizAttempt = QuizTentative::where('user_id', $user->id)
                ->where('quiz_id', $lecon->quiz->id)
                ->latest()
                ->first();
        }

        return view('etudiant.lecon-show', compact('lecon', 'userQuizAttempt'));
    }
}