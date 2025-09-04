@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Titre du Quiz</label>
        <input type="text" name="titre" class="form-control" value="{{ old('titre', $quiz->titre ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Seuil de réussite (%)</label>
        <input type="number" name="seuil_reussite" class="form-control"
            value="{{ old('seuil_reussite', $quiz->seuil_reussite ?? 50) }}" required min="0" max="100">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Durée (minutes)</label>
        <input type="number" name="duree_minutes" class="form-control"
            value="{{ old('duree_minutes', $quiz->duree_minutes ?? '') }}" placeholder="Laisser vide pour illimité"
            min="1">
    </div>
</div>
<hr>
<div id="questions-container">
    @isset($quiz)
        @foreach ($quiz->questions as $qIndex => $question)
            <div class="card mb-3 question-block">
                <div class="card-header d-flex justify-content-between">
                    <span>Question {{ $qIndex + 1 }}</span>
                    <button type="button" class="btn-close" onclick="this.closest('.question-block').remove()"></button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Énoncé de la question</label>
                        <textarea name="questions[{{ $qIndex }}][enonce]" class="form-control" required>{{ $question->enonce }}</textarea>
                    </div>
                    <h6>Réponses (cochez la/les bonne(s))</h6>
                    <div class="reponses-container">
                        @isset($quiz)
                            @foreach ($question->reponses as $rIndex => $reponse)
                                {{-- La ligne de réponse est maintenant un input-group --}}
                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <input class="form-check-input" type="checkbox"
                                            name="questions[{{ $qIndex }}][correct][]" value="{{ $rIndex }}"
                                            @checked($reponse->est_correcte)>
                                    </div>
                                    <input type="text"
                                        name="questions[{{ $qIndex }}][reponses][{{ $rIndex }}][texte]"
                                        class="form-control" value="{{ $reponse->texte }}" required>

                                    {{-- NOUVEAU : Bouton de suppression --}}
                                    <button type="button" class="btn btn-outline-danger delete-reponse"
                                        title="Supprimer cette réponse">&times;</button>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-reponse"
                        data-q-index="{{ $qIndex }}">Ajouter une réponse</button>
                </div>
            </div>
        @endforeach
    @endisset
</div>
<button type="button" id="add-question" class="btn btn-secondary mt-2"><i class="fas fa-plus"></i> Ajouter une
    question</button>
