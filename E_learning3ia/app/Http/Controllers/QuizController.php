<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Lecon;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'quizzable_type' => 'required|in:Matiere,Lecon',
            'quizzable_id' => 'required|integer',
        ]);

        $quizzableClass = "App\\Models\\" . $validated['quizzable_type'];
        $parent = $quizzableClass::findOrFail($validated['quizzable_id']);

        return view('admin_site.quizzes.create', compact('parent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'seuil_reussite' => 'required|integer|min:0|max:100',
            'duree_minutes' => 'nullable|integer|min:1',
            'quizzable_type' => 'required|string',
            'quizzable_id' => 'required|integer',
            'questions' => 'required|array|min:1',
            'questions.*.enonce' => 'required|string',
            'questions.*.reponses' => 'required|array|min:2',
            'questions.*.reponses.*.texte' => 'required|string',
            'questions.*.correct' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $quizzableClass = $validated['quizzable_type'];
            if (!in_array($quizzableClass, [Matiere::class, Lecon::class])) {
                throw new \Exception("Type de parent invalide.");
            }
            $parent = $quizzableClass::findOrFail($validated['quizzable_id']);

            if ($parent->quiz) {
                return back()->with('error', 'Cet élément a déjà un quiz associé.');
            }

            $quiz = $parent->quiz()->create([
                'titre' => $validated['titre'],
                'seuil_reussite' => $validated['seuil_reussite'],
                'duree_minutes' => $validated['duree_minutes'],
            ]);

            foreach ($validated['questions'] as $qIndex => $questionData) {
                $question = $quiz->questions()->create(['enonce' => $questionData['enonce']]);
                $correctIndexes = $questionData['correct'] ?? [];

                foreach ($questionData['reponses'] as $rIndex => $reponseData) {
                    $question->reponses()->create([
                        'texte' => $reponseData['texte'],
                        'est_correcte' => in_array($rIndex, $correctIndexes),
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création du quiz : ' . $e->getMessage());
        }

        $matiereId = $parent instanceof Matiere ? $parent->id : $parent->chapitre->matiere_id;
        return redirect()->route('dashboard.matiere.show', $matiereId)->with('success', 'Quiz créé avec succès.');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('questions.reponses');
        return view('admin_site.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'seuil_reussite' => 'required|integer|min:0|max:100',
            'duree_minutes' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.enonce' => 'required|string',
            'questions.*.reponses' => 'required|array|min:2',
            'questions.*.reponses.*.texte' => 'required|string',
            'questions.*.correct' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $quiz->update([
                'titre' => $validated['titre'],
                'seuil_reussite' => $validated['seuil_reussite'],
                'duree_minutes' => $validated['duree_minutes'],
            ]);

            $quiz->questions()->delete();

            foreach ($validated['questions'] as $qIndex => $questionData) {
                $question = $quiz->questions()->create(['enonce' => $questionData['enonce']]);
                $correctIndexes = $questionData['correct'] ?? [];

                foreach ($questionData['reponses'] as $rIndex => $reponseData) {
                    $question->reponses()->create([
                        'texte' => $reponseData['texte'],
                        'est_correcte' => in_array($rIndex, $correctIndexes),
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour du quiz.');
        }

        $parent = $quiz->quizzable;
        $matiereId = $parent instanceof Matiere ? $parent->id : $parent->chapitre->matiere_id;
        return redirect()->route('dashboard.matiere.show', $matiereId)->with('success', 'Quiz mis à jour avec succès.');
    }

    public function destroy(Quiz $quiz)
    {
        $parent = $quiz->quizzable;
        $matiereId = $parent instanceof Matiere ? $parent->id : $parent->chapitre->matiere_id;
        $quiz->delete();
        return redirect()->route('dashboard.matiere.show', $matiereId)->with('success', 'Quiz supprimé avec succès.');
    }
}
