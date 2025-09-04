@extends('admin_site.layouts.app')

@section('title', 'Gestion des Actualités')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/index.css') }}">

<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="h4 h-md-3 fw-bold mb-1">
                    <i class="fas fa-newspaper me-2"></i>
                    Gestion des Actualités
                </h1>
                <p class="mb-0 opacity-75 small">Gérez et organisez vos articles d'actualité</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-card d-inline-block p-2 p-md-3">
                    <div class="text-dark small">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <strong>{{ $actualites->total() }}</strong> articles
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
                <form method="GET" action="{{ route('dashboard.actualite.index') }}" class="mb-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-0 bg-light"
                               placeholder="Rechercher...">
                        @if(request('search'))
                        <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.actualite.create') }}" class="btn btn-primary w-100">
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
                <i class="fas fa-newspaper text-primary mb-1"></i>
                <h6 class="mb-0 small">{{ $actualites->total() }}</h6>
                <small class="text-muted">Total</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="stats-card card text-center p-2">
                <i class="fas fa-eye text-success mb-1"></i>
                <h6 class="mb-0 small">{{ $publishedCount ?? 0 }}</h6>
                <small class="text-muted">Publiées</small>
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
                <i class="fas fa-calendar text-info mb-1"></i>
                <h6 class="mb-0 small">{{ $thisWeekCount ?? 0 }}</h6>
                <small class="text-muted">Cette semaine</small>
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
                        <th class="d-none d-md-table-cell">Contenu</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actualites as $actualite)
                        <tr>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge bg-secondary">#{{ str_pad($actualite->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="fw-bold mb-1">{{ Str::limit($actualite->titre, 20) }}</div>
                                <span class="status-badge {{ $actualite->is_published ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                    {{ $actualite->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <div class="small">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ $actualite->date_publication->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="content-preview small" title="{{ strip_tags($actualite->contenu) }}">
                                    {{ Str::limit(strip_tags($actualite->contenu), 40) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($actualite->image)
                                    <img src="{{ asset('storage/' . $actualite->image) }}" 
                                         class="news-image" alt="Image">
                                @else
                                    <span class="text-muted small">
                                        <i class="fas fa-image"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dashboard.actualite.show', $actualite->id) }}" 
                                       class="action-btn btn btn-outline-success" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.actualite.edit', $actualite->id) }}" 
                                       class="action-btn btn btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.actualite.destroy', $actualite->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette actualité ?');">
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
                                <i class="fas fa-newspaper fa-2x mb-2 text-light" style="opacity: 0.5;"></i>
                                <h6 class="fw-light">Aucune actualité trouvée</h6>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.actualite.create') }}" class="btn btn-sm btn-primary mt-2">
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
            Affichage de <strong>{{ $actualites->firstItem() }}</strong> à <strong>{{ $actualites->lastItem() }}</strong> sur <strong>{{ $actualites->total() }}</strong>
        </div>
        <div>
            {{ $actualites->onEachSide(1)->links('pagination::bootstrap-5') }}
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