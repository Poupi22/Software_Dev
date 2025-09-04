@extends('admin_site.layouts.app')

@section('title', 'Gestion de la page "À propos"')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/index.css') }}">
<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="h4 h-md-3 fw-bold mb-1">
                    <i class="fas fa-info-circle me-2"></i>
                    Gestion de la page "À propos"
                </h1>
                <p class="mb-0 opacity-75 small">Gérez le contenu de votre page "À propos"</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-card d-inline-block p-2 p-md-3">
                    <div class="text-dark small">
                        <i class="fas fa-layer-group text-primary me-1"></i>
                        <strong>{{ $abouts->count() }}</strong> éléments
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
                <form method="GET" action="{{ route('dashboard.about.index') }}" class="mb-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-0 bg-light"
                               placeholder="Rechercher...">
                        @if(request('search'))
                        <a href="{{ route('dashboard.about.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.about.create') }}" class="btn btn-primary w-100">
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

    <!-- Table -->
    <div class="table-container mb-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th class="d-none d-md-table-cell">Description</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($abouts as $about)
                        <tr>
                            <td>
                                <div class="fw-bold mb-1">{{ Str::limit($about->titre, 30) }}</div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="content-preview small">
                                    {{ Str::limit(strip_tags($about->description), 50) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($about->image)
                                    <img src="{{ asset('storage/' . $about->image) }}" 
                                         class="about-image" alt="Image à propos">
                                @else
                                    <span class="text-muted small">
                                        <i class="fas fa-image"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $about->statut === 'actif' ? 'success' : 'secondary' }}">
                                    {{ $about->statut }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dashboard.about.show', $about->id) }}" 
                                       class="action-btn btn btn-outline-info" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.about.edit', $about->id) }}" 
                                       class="action-btn btn btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.about.destroy', $about->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cet élément ?');">
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
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x mb-2 text-light" style="opacity: 0.5;"></i>
                                <h6 class="fw-light">Aucun contenu "À propos" trouvé</h6>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.about.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.about.create') }}" class="btn btn-sm btn-primary mt-2">
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
    @if($abouts->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
        <div class="small text-muted mb-2 mb-md-0">
            <i class="fas fa-info-circle me-1"></i>
            Affichage de <strong>{{ $abouts->firstItem() }}</strong> à <strong>{{ $abouts->lastItem() }}</strong> sur <strong>{{ $abouts->total() }}</strong>
        </div>
        <div>
            {{ $abouts->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
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