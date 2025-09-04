@extends('admin_site.layouts.app')

@section('title', 'Saisie des Notes')

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

        .note-input {
            width: 70px;
            text-align: center;
        }

        .table-notes th,
        .table-notes td {
            vertical-align: middle;
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
                <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-pen me-2"></i> Saisie des Notes</h1>
                <p class="mb-0">Saisir les notes CC et Normale pour chaque matière</p>
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
                    <form method="GET" action="{{ route('dashboard.bulletin.saisie-notes') }}" class="row g-3">
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
                <!-- Tableau de saisie des notes -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Étudiants - Semestre {{ $semestre }}
                        </h5>
                        <span class="badge bg-primary">{{ $etudiants->count() }} étudiants</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.bulletin.store-notes') }}">
                            @csrf
                            <input type="hidden" name="programme_session_id"
                                value="{{ request('programme_session_id') }}">
                            <input type="hidden" name="semestre" value="{{ $semestre }}">

                            <div class="table-responsive">
                                <table class="table table-bordered table-notes">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2" class="align-middle">Étudiant</th>
                                            @foreach ($coursInstances as $coursInstance)
                                                <th colspan="2" class="text-center">
                                                    {{ $coursInstance->matiere->code ?? $coursInstance->matiere->nom }}
                                                    <br>
                                                    <small class="text-muted">({{ $coursInstance->matiere->credit ?? 1 }}
                                                        cr.)</small>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($coursInstances as $coursInstance)
                                                <th class="text-center bg-info bg-opacity-25">CC</th>
                                                <th class="text-center bg-success bg-opacity-25">Norm.</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($etudiants as $index => $etudiant)
                                            <tr>
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
                                                        <div>
                                                            <strong>{{ $etudiant->name }} {{ $etudiant->prenom }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $etudiant->matricule }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                @foreach ($coursInstances as $coursInstance)
                                                    @php
                                                        $note = $etudiant->notes_saisies[$coursInstance->id] ?? null;
                                                    @endphp
                                                    <td class="text-center">
                                                        <input type="hidden"
                                                            name="notes[{{ $index }}_{{ $coursInstance->id }}][user_id]"
                                                            value="{{ $etudiant->id }}">
                                                        <input type="hidden"
                                                            name="notes[{{ $index }}_{{ $coursInstance->id }}][cours_instance_id]"
                                                            value="{{ $coursInstance->id }}">
                                                        <input type="number"
                                                            name="notes[{{ $index }}_{{ $coursInstance->id }}][note_cc]"
                                                            class="form-control note-input mx-auto"
                                                            value="{{ $note?->note_cc }}" min="0" max="20"
                                                            step="0.25" placeholder="/20">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number"
                                                            name="notes[{{ $index }}_{{ $coursInstance->id }}][note_normale]"
                                                            class="form-control note-input mx-auto"
                                                            value="{{ $note?->note_normale }}" min="0"
                                                            max="20" step="0.25" placeholder="/20">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Enregistrer les Notes
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
            @elseif(request('programme_session_id') && $semestres->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun cours n'a été configuré pour cette session. Veuillez d'abord ajouter des matières dans les cours
                    instances.
                </div>
            @endif
        </div>
    </div>
@endsection
