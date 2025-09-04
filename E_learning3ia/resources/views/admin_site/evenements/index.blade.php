@extends('admin_site.layouts.app')

@section('title', 'Gestion des Événements')

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

    .stats-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: none;
        transition: transform 0.2s ease-in-out;
        margin-bottom: 1rem;
    }

    .search-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    .table thead th {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-weight: 600;
        color: #475569;
        padding: 0.75rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        display: inline-block;
    }

    .event-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
    }

    .content-preview {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-btn {
        padding: 0.25rem 0.5rem;
        margin: 0 0.1rem;
    }

    /* Layout Adjustments for Sidebar */
    @media (min-width: 992px) {
        .main-header {
            width: calc(100% - 300px);
            margin-left: 300px;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 0 0 20px 20px;
        }

        .container {
            margin-left: 300px;
            width: calc(100% - 300px);
            padding-right: 1.5rem;
            padding-left: 1.5rem;
        }
    }

    /* Tablet Styles */
    @media (max-width: 991.98px) {
        .main-header {
            padding: 1rem 0;
            border-radius: 0;
        }

        .container {
            margin-left: 0;
            width: 100%;
            padding: 0 1rem;
        }

        .stats-card {
            text-align: center;
            padding: 0.5rem;
        }

        .search-container {
            padding: 0.75rem;
        }

        .btn-primary {
            width: 100%;
            margin-top: 0.5rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .content-preview {
            max-width: 150px;
        }

        .event-image {
            width: 40px;
            height: 40px;
        }
    }

    /* Mobile Styles */
    @media (max-width: 767.98px) {
        .main-header .col-md-8,
        .main-header .col-md-4 {
            text-align: center;
        }

        .main-header h1 {
            font-size: 1.5rem;
        }

        .stats-card {
            margin-bottom: 0.5rem;
        }

        .search-container .col-md-8,
        .search-container .col-md-4 {
            padding-left: 0;
            padding-right: 0;
        }

        .table thead th:nth-child(3),
        .table tbody td:nth-child(3),
        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            display: none;
        }

        .content-preview {
            max-width: 100px;
        }

        .action-btn {
            padding: 0.2rem 0.3rem;
            margin: 0 0.05rem;
        }

        .btn-group {
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-group .btn {
            margin-bottom: 0.25rem;
        }
    }

    /* Extra Small Screens */
    @media (max-width: 575.98px) {
        .main-header h1 {
            font-size: 1.25rem;
        }

        .stats-card {
            padding: 0.25rem;
        }

        .table thead th:nth-child(1),
        .table tbody td:nth-child(1) {
            display: none;
        }

        .content-preview {
            max-width: 80px;
            font-size: 0.8rem;
        }

        .event-image {
            width: 30px;
            height: 30px;
        }

        .d-flex.flex-column.flex-md-row {
            flex-direction: column;
            align-items: center;
        }

        .d-flex.flex-column.flex-md-row > div {
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="h4 h-md-3 fw-bold mb-1">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Gestion des Événements
                </h1>
                <p class="mb-0 opacity-75 small">Gérez et organisez vos événements</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-card d-inline-block p-2 p-md-3">
                    <div class="text-dark small">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <strong>{{ $evenements->total() }}</strong> événements
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Search and Actions -->
    <div class="search-container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-2 mb-md-0">
                <form method="GET" action="{{ route('dashboard.evenement.index') }}" class="mb-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-0 bg-light"
                               placeholder="Rechercher...">
                        @if(request('search'))
                        <a href="{{ route('dashboard.evenement.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.evenement.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus me-1"></i>
                    <span class="d-none d-md-inline">Ajouter</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <div class="small">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="stats-card card text-center p-2">
                <i class="fas fa-calendar-alt text-primary mb-1"></i>
                <h6 class="mb-0 small">{{ $evenements->total() }}</h6>
                <small class="text-muted">Total</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="stats-card card text-center p-2">
                <i class="fas fa-eye text-success mb-1"></i>
                <h6 class="mb-0 small">{{ $activeCount ?? 0 }}</h6>
                <small class="text-muted">Actifs</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="stats-card card text-center p-2">
                <i class="fas fa-edit text-warning mb-1"></i>
                <h6 class="mb-0 small">{{ $draftCount ?? 0 }}</h6>
                <small class="text-muted">Brouillons</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="stats-card card text-center p-2">
                <i class="fas fa-archive text-info mb-1"></i>
                <h6 class="mb-0 small">{{ $archivedCount ?? 0 }}</h6>
                <small class="text-muted">Archivés</small>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container mb-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="d-none d-sm-table-cell">ID</th>
                        <th>Titre</th>
                        <th class="d-none d-sm-table-cell">Date</th>
                        <th class="d-none d-md-table-cell">Lieu</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evenements as $evenement)
                        <tr>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge bg-secondary">#{{ str_pad($evenement->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="fw-bold mb-1">{{ Str::limit($evenement->titre, 20) }}</div>
                                <span class="status-badge {{ $evenement->statut === 'actif' ? 'bg-success text-white' : ($evenement->statut === 'archive' ? 'bg-secondary text-white' : 'bg-warning text-dark') }}">
                                    {{ $evenement->statut }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <div class="small">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ $evenement->date_debut->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="content-preview small">
                                    {{ Str::limit($evenement->lieu, 40) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($evenement->image)
                                    <img src="{{ asset('storage/' . $evenement->image) }}"
                                         class="event-image" alt="Image événement">
                                @else
                                    <span class="text-muted small">
                                        <i class="fas fa-image"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dashboard.evenement.show', $evenement->id) }}"
                                       class="action-btn btn btn-outline-success"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.evenement.edit', $evenement->id) }}"
                                       class="action-btn btn btn-outline-primary"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.evenement.destroy', $evenement->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cet événement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-btn btn btn-outline-danger"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-alt fa-2x mb-2 text-light" style="opacity: 0.5;"></i>
                                <h6 class="fw-light">Aucun événement trouvé</h6>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.evenement.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.evenement.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Créer
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
        <div class="small text-muted mb-2 mb-md-0">
            <i class="fas fa-info-circle me-1"></i>
            Affichage de <strong>{{ $evenements->firstItem() }}</strong> à <strong>{{ $evenements->lastItem() }}</strong> sur <strong>{{ $evenements->total() }}</strong>
        </div>
        <div>
            {{ $evenements->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
