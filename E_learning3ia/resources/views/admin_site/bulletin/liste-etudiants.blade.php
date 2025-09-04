@extends('admin_site.layouts.app')

@section('title', 'Liste des Étudiants - Bulletins')

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

        .student-photo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
    <div class="main-content">
        <div class="main-header">
            <div class="container">
                <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-file-pdf me-2"></i> Génération des Bulletins</h1>
                <p class="mb-0">Prévisualiser et télécharger les bulletins des étudiants</p>
            </div>
        </div>

        <div class="container">
            <div class="mb-3">
                <a href="{{ route('dashboard.bulletin.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Sélection de la session et du semestre -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Sélection</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.bulletin.liste-etudiants') }}" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Session de Programme</label>
                            <select name="programme_session_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Choisir une session --</option>
                                @foreach ($programmeSessions as $session)
                                    <option value="{{ $session->id }}"
                                        {{ request('programme_session_id') == $session->id ? 'selected' : '' }}>
                                        {{ $session->programme->formation->nom ?? '' }} -
                                        {{ $session->programme->qualification->nom ?? '' }}
                                        ({{ $session->anneeAcademique->libelle ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Semestre</label>
                            <select name="semestre" class="form-select" {{ $semestres->isEmpty() ? 'disabled' : '' }}
                                onchange="this.form.submit()">
                                <option value="">-- Choisir un semestre --</option>
                                @foreach ($semestres as $sem)
                                    <option value="{{ $sem }}"
                                        {{ request('semestre') == $sem ? 'selected' : '' }}>
                                        Semestre {{ $sem }}
                                    </option>
                                @endforeach
                                @if ($semestres->count() >= 2)
                                    <option value="final" {{ request('semestre') == 'final' ? 'selected' : '' }}>
                                        📋 Bulletin Final (tous semestres)
                                    </option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Afficher
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($semestre && $etudiants->isNotEmpty())
                <!-- Liste des étudiants -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            @if ($semestre === 'final')
                                Bulletins Finaux
                            @else
                                Bulletins - Semestre {{ $semestre }}
                            @endif
                        </h5>
                        <span class="badge bg-info">{{ $etudiants->count() }} étudiants</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Étudiant</th>
                                    <th>Matricule</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etudiants as $index => $etudiant)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($etudiant->photo)
                                                    <img src="{{ asset('storage/' . $etudiant->photo) }}" alt="Photo"
                                                        class="student-photo me-2">
                                                @else
                                                    <div
                                                        class="student-photo me-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                        {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $etudiant->name }} {{ $etudiant->prenom }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $etudiant->matricule }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                @if ($semestre === 'final')
                                                    <a href="{{ route('dashboard.bulletin.preview', ['user_id' => $etudiant->id, 'programme_session_id' => $programmeSessionId, 'type' => 'final']) }}"
                                                        class="btn btn-outline-info" target="_blank" title="Prévisualiser">
                                                        <i class="fas fa-eye"></i> Prévisualiser
                                                    </a>
                                                    <a href="{{ route('dashboard.bulletin.download-pdf', ['user_id' => $etudiant->id, 'programme_session_id' => $programmeSessionId, 'type' => 'final']) }}"
                                                        class="btn btn-outline-danger" title="Télécharger PDF">
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    </a>
                                                @else
                                                    <a href="{{ route('dashboard.bulletin.preview', ['user_id' => $etudiant->id, 'programme_session_id' => $programmeSessionId, 'semestre' => $semestre, 'type' => 'semestriel']) }}"
                                                        class="btn btn-outline-info" target="_blank" title="Prévisualiser">
                                                        <i class="fas fa-eye"></i> Prévisualiser
                                                    </a>
                                                    <a href="{{ route('dashboard.bulletin.download-pdf', ['user_id' => $etudiant->id, 'programme_session_id' => $programmeSessionId, 'semestre' => $semestre, 'type' => 'semestriel']) }}"
                                                        class="btn btn-outline-danger" title="Télécharger PDF">
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($semestre && $etudiants->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Aucun étudiant inscrit à cette session.
                </div>
            @endif
        </div>
    </div>
@endsection
