@extends('admin_site.layouts.app')

@section('title', 'Gestion des Types de Formations')

@section('content')

<style>
    /* Reuse the same styles from create.blade.php */
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


</style>

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-layer-group me-2"></i>
                        Gestion des Types de Formations
                    </h1>
                    <p class="mb-0 opacity-75 small">Liste des types de formations disponibles</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-swatchbook text-primary me-1"></i>
                            <strong>{{ $type_formations->count() }}</strong> types de formations
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('dashboard.type_formation.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div class="small">{{ session('success') }}</div>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-times-circle me-2"></i>
            <div class="small">{{ session('error') }}</div>
        </div>
        @endif

        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($type_formations as $type_formation)
                    <tr>
                        <td>{{ $type_formation->nom }}</td>
                        <td>{{ $type_formation->duree ?? 'Non définie' }}</td>
                        <td>
                            <span class="badge bg-{{ $type_formation->statut ? 'success' : 'secondary' }}">
                                {{ $type_formation->statut ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dashboard.type_formation.show', $type_formation->id) }}" class="btn btn-outline-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.type_formation.edit', $type_formation->id) }}" class="btn btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button type="button" class="btn btn-outline-{{ $type_formation->statut ? 'warning' : 'success' }}" title="{{ $type_formation->statut ? 'Désactiver' : 'Activer' }}" data-bs-toggle="modal" data-bs-target="#statut{{ $type_formation->id }}">
                                    <i class="fas fa-toggle-{{ $type_formation->statut ? 'off' : 'on' }}"></i>
                                </button>

                                <button type="button" class="btn btn-outline-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#delete{{ $type_formation->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @include('admin_site.global.change-status-modal', [
                        'item' => $type_formation,
                        'url' => route('dashboard.type_formation.toggle_status', $type_formation->id),
                    ])

                    @include('admin_site.global.delete-modal', [
                        'id' => $type_formation->id,
                        'itemName' => $type_formation->nom,
                        'url' => route('dashboard.type_formation.destroy', $type_formation->id),
                    ])

                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Aucun type de formation trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($type_formations->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $type_formations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
