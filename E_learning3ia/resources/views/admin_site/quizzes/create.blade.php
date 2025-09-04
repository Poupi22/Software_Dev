@extends('admin_site.layouts.app')
@section('title', 'Créer un Quiz')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1>Créer un Quiz pour : {{ $parent->titre ?? $parent->nom }}</h1></div></div>
    <div class="container">
        <div class="card">
            @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.quiz.store') }}" method="POST" id="quiz-form">
                @csrf
                <input type="hidden" name="quizzable_type" value="{{ get_class($parent) }}">
                <input type="hidden" name="quizzable_id" value="{{ $parent->id }}">
                <div class="card-body">@include('admin_site.quizzes.form')</div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Retour</a>
                    <button type="submit" class="btn btn-success">Enregistrer le Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialise l'index des questions. S'adapte à la page 'edit' ou 'create'.
    let questionIndex = document.querySelectorAll('.question-block').length;
    const container = document.getElementById('questions-container');

    // --- GESTIONNAIRES D'ÉVÉNEMENTS ---

    // 1. Ajouter une question
    document.getElementById('add-question').addEventListener('click', function() {
        addQuestionBlock(questionIndex);
        questionIndex++;
    });

    // 2. Gestion des clics à l'intérieur du conteneur principal (délégation d'événements)
    container.addEventListener('click', function(e) {
        // Clic sur "Ajouter une réponse"
        if (e.target.classList.contains('add-reponse')) {
            const qIndex = e.target.dataset.qIndex;
            const reponseContainer = e.target.previousElementSibling;
            addReponseBlock(qIndex, reponseContainer);
        }

        // NOUVEAU : Clic sur "Supprimer une réponse"
        // On utilise .closest() pour être sûr de capturer le clic même sur l'icône à l'intérieur du bouton
        if (e.target.closest('.delete-reponse')) {
            e.preventDefault(); // Bonne pratique
            // Trouve le parent .input-group le plus proche et le supprime du DOM
            e.target.closest('.input-group').remove();
        }
    });


    // --- FONCTIONS DE CRÉATION DE BLOCS HTML ---

    function addQuestionBlock(qIndex) {
        const questionHtml = `
            <div class="card mb-3 question-block">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Nouvelle Question</span>
                    <button type="button" class="btn-close" onclick="this.closest('.question-block').remove()"></button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Énoncé de la question</label>
                        <textarea name="questions[${qIndex}][enonce]" class="form-control" required></textarea>
                    </div>
                    <h6>Réponses (cochez la/les bonne(s))</h6>
                    <div class="reponses-container">
                        <div class="input-group mb-2">
                            <div class="input-group-text"><input class="form-check-input" type="checkbox" name="questions[${qIndex}][correct][]" value="0"></div>
                            <input type="text" name="questions[${qIndex}][reponses][0][texte]" class="form-control" placeholder="Texte de la réponse 1" required>
                            <button type="button" class="btn btn-outline-danger delete-reponse" title="Supprimer cette réponse">&times;</button>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><input class="form-check-input" type="checkbox" name="questions[${qIndex}][correct][]" value="1"></div>
                            <input type="text" name="questions[${qIndex}][reponses][1][texte]" class="form-control" placeholder="Texte de la réponse 2" required>
                            <button type="button" class="btn btn-outline-danger delete-reponse" title="Supprimer cette réponse">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-reponse" data-q-index="${qIndex}">Ajouter une réponse</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHtml);
    }

    function addReponseBlock(qIndex, reponseContainer) {
        const reponseCount = reponseContainer.children.length;
        const reponseHtml = `
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input class="form-check-input" type="checkbox" name="questions[${qIndex}][correct][]" value="${reponseCount}">
                </div>
                <input type="text" name="questions[${qIndex}][reponses][${reponseCount}][texte]" class="form-control" placeholder="Texte de la réponse ${reponseCount + 1}" required>
                <button type="button" class="btn btn-outline-danger delete-reponse" title="Supprimer cette réponse">&times;</button>
            </div>
        `;
        reponseContainer.insertAdjacentHTML('beforeend', reponseHtml);
    }
});
</script>
@endpush
