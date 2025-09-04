@extends('admin_site.layouts.app')
@section('title', 'Gérer la Session')
@section('content')
<style>
    /* Base Styles */
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 0;
        margin-bottom: 1rem;
        width: 100%;
    }

    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
    }

    .btn {
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        border: none;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .required-field::after {
        content: " *";
        color: #ef4444;
    }

    /* File Input Customization */
    .form-control[type="file"] {
        padding: 0.375rem;
    }

    /* Layout Adjustments */
    .main-content {
        margin-left: 250px; /* Adjust based on your sidebar width */
        transition: all 0.3s;
    }

    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0;
            padding-top: 70px; /* For fixed header */
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .card-body {
            padding: 1rem;
        }

        .form-label {
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 767.98px) {
        .card-header {
            padding: 1rem;
        }

        .card-header h5 {
            font-size: 1.1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .main-header h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 575.98px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .row > div {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem;
        }

        .d-flex.justify-content-end .btn {
            width: 100%;
        }

        .main-header h1 {
            font-size: 1.25rem;
        }
    }
</style>
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-tasks me-2"></i> Gérer la Session</h1></div></div>
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="card mb-4">
            <div class="card-header"><h5>Assignation des Formateurs</h5></div>
            <div class="card-body p-0">
                <table class="table">
                    <thead><tr><th>Matière</th><th>Formateur(s) Assigné(s)</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($programmeSession->coursInstances as $instance)
                        <tr>
                            <td>{{ $instance->matiere->nom }}</td>
                            <td>
                                @forelse($instance->formateurs as $formateur)
                                    <span class="badge bg-primary">{{ $formateur->name }}</span>
                                @empty
                                    <span class="badge bg-warning">Aucun</span>
                                @endforelse
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $instance->id }}">Assigner</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Contenu Additionnel (Facultatif)</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addContenuModal"><i class="fas fa-plus"></i> Ajouter un contenu</button>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($programmeSession->contenusAdditionnels as $contenu)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('dashboard.contenu_additionnel.show', $contenu->id) }}"><strong>{{ $contenu->titre }}</strong></a>
                            <br><small class="text-muted">{{ $contenu->description }}</small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <form action="{{ route('dashboard.contenu_additionnel.toggle_visibility', $contenu->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-{{ $contenu->est_visible ? 'warning' : 'success' }}" title="{{ $contenu->est_visible ? 'Cacher aux étudiants' : 'Rendre visible' }}">
                                    <i class="fas fa-toggle-{{ $contenu->est_visible ? 'on' : 'off' }}"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $contenu->id }}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center">Aucun contenu additionnel pour cette session.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@foreach($programmeSession->coursInstances as $instance)
<div class="modal fade" id="assignModal{{ $instance->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigner à : {{ $instance->matiere->nom }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dashboard.programme_session.cours_instance.assigner_formateur', $instance->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Choisir un formateur --</option>
                        @foreach($formateurs as $formateur)
                        <option value="{{ $formateur->id }}">{{ $formateur->name }} {{ $formateur->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Assigner</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="addContenuModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Nouveau Contenu Additionnel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('dashboard.contenu_additionnel.store') }}" method="POST">
                @csrf
                <input type="hidden" name="programme_session_id" value="{{ $programmeSession->id }}">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Titre</label><input type="text" name="titre" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description (courte)</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary">Créer et gérer</button></div>
            </form>
        </div>
    </div>
</div>

@foreach($programmeSession->contenusAdditionnels as $contenu)
    @include('admin_site.global.delete-modal', [
        'id' => $contenu->id,
        'itemName' => 'le contenu "' . $contenu->titre . '"',
        'url' => route('dashboard.contenu_additionnel.destroy', $contenu->id)
    ])
@endforeach

@endsection
