@extends('etudiant.layouts.app')

@section('title', 'Mes Bulletins')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3"><i class="fas fa-file-alt me-2"></i>Mes Bulletins</h1>
        </div>

        @if (count($bulletinsDisponibles) > 0)
            <div class="row g-4">
                @foreach ($bulletinsDisponibles as $bulletin)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">{{ $bulletin['formation'] }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    <strong>Année :</strong> {{ $bulletin['annee'] }}
                                </p>
                                <p class="mb-3">
                                    <i class="fas fa-book me-2"></i>
                                    <strong>Semestre :</strong> {{ $bulletin['semestre'] }}
                                </p>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('etudiant.bulletin.show', ['programme_session_id' => $bulletin['programme_session_id'], 'semestre' => $bulletin['semestre']]) }}"
                                        class="btn btn-outline-primary flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a>
                                    <a href="{{ route('etudiant.bulletin.download-pdf', ['programme_session_id' => $bulletin['programme_session_id'], 'semestre' => $bulletin['semestre'], 'type' => 'semestriel']) }}"
                                        class="btn btn-danger flex-grow-1">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                // Regrouper par session pour afficher le bulletin final
                $sessionsAvecMultipleSemestres = collect($bulletinsDisponibles)
                    ->groupBy('programme_session_id')
                    ->filter(fn($group) => $group->count() >= 2);
            @endphp

            @if ($sessionsAvecMultipleSemestres->isNotEmpty())
                <hr class="my-5">
                <h3 class="mb-4"><i class="fas fa-graduation-cap me-2"></i>Bulletins Finaux</h3>
                <div class="row g-4">
                    @foreach ($sessionsAvecMultipleSemestres as $sessionId => $bulletins)
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Bulletin Final - {{ $bulletins->first()['formation'] }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <i class="fas fa-calendar me-2"></i>
                                        <strong>Année :</strong> {{ $bulletins->first()['annee'] }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-book me-2"></i>
                                        <strong>Semestres :</strong> {{ $bulletins->pluck('semestre')->implode(', ') }}
                                    </p>
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('etudiant.bulletin.final', ['programme_session_id' => $sessionId]) }}"
                                            class="btn btn-outline-success flex-grow-1">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                        <a href="{{ route('etudiant.bulletin.download-pdf', ['programme_session_id' => $sessionId, 'type' => 'final']) }}"
                                            class="btn btn-success flex-grow-1">
                                            <i class="fas fa-file-pdf me-1"></i> PDF Final
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucun bulletin disponible pour le moment. Vos bulletins apparaîtront ici une fois que vos notes auront été
                saisies.
            </div>
        @endif
    </div>
@endsection
