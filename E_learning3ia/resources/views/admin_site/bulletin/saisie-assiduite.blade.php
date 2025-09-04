@extends('admin_site.layouts.app')

@section('title', 'Saisie de l\'Assiduité')

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

        .presence-input {
            width: 100px;
            text-align: center;
        }

        .student-photo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .presence-high {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .presence-medium {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .presence-low {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
    <div class="main-content">
        <div class="main-header">
            <div class="container">
                <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-calendar-check me-2"></i> Saisie de l'Assiduité</h1>
                <p class="mb-0">Enregistrer le pourcentage de présence par étudiant</p>
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
                    <form method="GET" action="{{ route('dashboard.bulletin.saisie-assiduite') }}" class="row g-3">
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
                <!-- Tableau de saisie de l'assiduité -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Assiduité - Semestre {{ $semestre }}
                        </h5>
                        <span class="badge bg-success">{{ $etudiants->count() }} étudiants</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.bulletin.store-assiduite') }}">
                            @csrf
                            <input type="hidden" name="programme_session_id"
                                value="{{ request('programme_session_id') }}">
                            <input type="hidden" name="semestre" value="{{ $semestre }}">

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Étudiant</th>
                                            <th>Matricule</th>
                                            <th class="text-center">% Présence</th>
                                            <th class="text-center">% Absence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($etudiants as $index => $etudiant)
                                            @php
                                                $presence = $etudiant->assiduite?->pourcentage_presence ?? 0;
                                                $rowClass =
                                                    $presence >= 80
                                                        ? 'presence-high'
                                                        : ($presence >= 50
                                                            ? 'presence-medium'
                                                            : 'presence-low');
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($etudiant->photo)
                                                            <img src="{{ asset('storage/' . $etudiant->photo) }}"
                                                                alt="Photo" class="student-photo me-2">
                                                        @else
                                                            <div
                                                                class="student-photo me-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                                {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <strong>{{ $etudiant->name }} {{ $etudiant->prenom }}</strong>
                                                    </div>
                                                </td>
                                                <td>{{ $etudiant->matricule }}</td>
                                                <td class="text-center">
                                                    <input type="hidden" name="assiduite[{{ $index }}][user_id]"
                                                        value="{{ $etudiant->id }}">
                                                    <div class="input-group justify-content-center"
                                                        style="width: 130px; margin: 0 auto;">
                                                        <input type="number"
                                                            name="assiduite[{{ $index }}][pourcentage_presence]"
                                                            class="form-control presence-input" value="{{ $presence }}"
                                                            min="0" max="100" step="0.5"
                                                            onchange="updateAbsence(this, {{ $index }})"
                                                            placeholder="0">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span id="absence-{{ $index }}"
                                                        class="badge bg-{{ $presence >= 80 ? 'success' : ($presence >= 50 ? 'warning' : 'danger') }} fs-6">
                                                        {{ number_format(100 - $presence, 1) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i> Enregistrer l'Assiduité
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif($semestre && $etudiants->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Aucun étudiant inscrit à cette session pour le semestre {{ $semestre }}.
                </div>
            @endif
        </div>
    </div>

    <script>
        function updateAbsence(input, index) {
            const presence = parseFloat(input.value) || 0;
            const absence = 100 - presence;
            const badge = document.getElementById('absence-' + index);
            badge.textContent = absence.toFixed(1) + '%';

            // Update badge color
            badge.className = 'badge fs-6 ';
            if (presence >= 80) {
                badge.className += 'bg-success';
            } else if (presence >= 50) {
                badge.className += 'bg-warning';
            } else {
                badge.className += 'bg-danger';
            }
        }
    </script>
@endsection
