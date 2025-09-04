@extends('admin_site.layouts.app')
@section('title', 'Détails du Quiz')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1>{{ $quiz->titre }}</h1></div></div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <p><strong>Associé à :</strong> {{ $quiz->quizzable->titre ?? $quiz->quizzable->nom }} ({{ class_basename($quiz->quizzable_type) }})</p>
                <p><strong>Seuil de réussite :</strong> {{ $quiz->seuil_reussite }}%</p>
                <hr>
                @foreach($quiz->questions as $question)
                <div class="mb-3">
                    <h6>{{ $loop->iteration }}. {{ $question->enonce }}</h6>
                    <ul>
                        @foreach($question->reponses as $reponse)
                        <li class="{{ $reponse->est_correcte ? 'text-success fw-bold' : '' }}">{{ $reponse->texte }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
