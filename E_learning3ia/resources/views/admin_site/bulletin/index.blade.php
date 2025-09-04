@extends('admin_site.layouts.app')

@section('title', 'Gestion des Bulletins')

@section('content')
    <style>
        .main-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 1rem;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
    <div class="main-content">
        <div class="main-header">
            <div class="container">
                <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-file-alt me-2"></i> Gestion des Bulletins</h1>
                <p class="mb-0">Saisie des notes, assiduité et génération des bulletins</p>
            </div>
        </div>

        <div class="container">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <!-- Saisie des Notes -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="fas fa-pen"></i>
                            </div>
                            <h5 class="card-title">Saisie des Notes</h5>
                            <p class="card-text text-muted">Saisir les notes CC et Normale pour chaque étudiant</p>
                            <a href="{{ route('dashboard.bulletin.saisie-notes') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i> Accéder
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Saisie de l'Assiduité -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-success">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="card-title">Saisie de l'Assiduité</h5>
                            <p class="card-text text-muted">Enregistrer le pourcentage de présence par semestre</p>
                            <a href="{{ route('dashboard.bulletin.saisie-assiduite') }}" class="btn btn-success">
                                <i class="fas fa-arrow-right me-1"></i> Accéder
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Générer les Bulletins -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-info">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <h5 class="card-title">Générer les Bulletins</h5>
                            <p class="card-text text-muted">Prévisualiser et télécharger les bulletins en PDF</p>
                            <a href="{{ route('dashboard.bulletin.liste-etudiants') }}" class="btn btn-info text-white">
                                <i class="fas fa-arrow-right me-1"></i> Accéder
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations sur les pondérations -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Pondérations actuelles</h5>
                </div>
                <div class="card-body">
                    @php
                        $includeQuiz = config('bulletin.include_quiz_online', false);
                    @endphp

                    @if ($includeQuiz)
                        <div class="alert alert-info mb-0">
                            <strong>Phase 2 (Avec Quiz en ligne)</strong>
                            <ul class="mb-0 mt-2">
                                <li>Quiz en ligne : <strong>{{ config('bulletin.ponderation_phase2.quiz') }}%</strong></li>
                                <li>Note Normale : <strong>{{ config('bulletin.ponderation_phase2.normale') }}%</strong>
                                </li>
                                <li>Note CC : <strong>{{ config('bulletin.ponderation_phase2.cc') }}%</strong></li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <strong>Phase 1 (Sans Quiz en ligne)</strong>
                            <ul class="mb-0 mt-2">
                                <li>Note Normale : <strong>{{ config('bulletin.ponderation_phase1.normale') }}%</strong>
                                </li>
                                <li>Note CC : <strong>{{ config('bulletin.ponderation_phase1.cc') }}%</strong></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sessions disponibles -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Sessions de Programme</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Formation</th>
                                <th>Qualification</th>
                                <th>Année Académique</th>
                                <th>Statut</th>
                                <th>Inscrits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programmeSessions as $session)
                                <tr>
                                    <td>{{ $session->programme->formation->nom ?? 'N/A' }}</td>
                                    <td>{{ $session->programme->qualification->nom ?? 'N/A' }}</td>
                                    <td>{{ $session->anneeAcademique->libelle ?? 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $session->statut === 'En cours' ? 'success' : 'secondary' }}">
                                            {{ $session->statut }}
                                        </span>
                                    </td>
                                    <td>{{ $session->inscriptions->count() }} étudiants</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune session disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
