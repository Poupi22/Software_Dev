@extends('admin_site.layouts.app')

@section('title', 'Liste du Personnel')

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
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
    
    .personnel-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .linkedin-icon {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #0077b5;
        color: white;
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
        
        .btn-success {
            width: 100%;
            margin-top: 0.5rem;
        }
        
        .table thead th,
        .table tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
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
        
        .table thead th:nth-child(3),
        .table tbody td:nth-child(3),
        .table thead th:nth-child(4),
        .table tbody td:nth-child(4) {
            display: none;
        }
        
        .action-btn {
            padding: 0.2rem 0.3rem;
            margin: 0 0.05rem;
        }
    }

    /* Extra Small Screens */
    @media (max-width: 575.98px) {
        .main-header h1 {
            font-size: 1.25rem;
        }
        
        .table thead th:nth-child(1),
        .table tbody td:nth-child(1) {
            display: none;
        }
        
        .d-flex.flex-column.flex-md-row {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="h4 h-md-3 fw-bold mb-1">
                    <i class="fas fa-users me-2"></i>
                    Gestion du Personnel
                </h1>
                <p class="mb-0 opacity-75 small">Liste des membres de l'équipe</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-card d-inline-block p-2 p-md-3">
                    <div class="text-dark small">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <strong>{{ $personnels->total() }}</strong> membres
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
                <form method="GET" action="{{ route('dashboard.personnels.index') }}" class="mb-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-0 bg-light"
                               placeholder="Rechercher...">
                        @if(request('search'))
                        <a href="{{ route('dashboard.personnels.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.personnels.create') }}" class="btn btn-success w-100">
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
                        <th class="d-none d-sm-table-cell">ID</th>
                        <th>Membre</th>
                        <th class="d-none d-md-table-cell">Poste</th>
                        <th>Expertise</th>
                        <th>LinkedIn</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personnels as $personnel)
                        <tr>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge bg-secondary">#{{ str_pad($personnel->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($personnel->image)
                                    <img src="{{ asset('storage/' . $personnel->image) }}" alt="{{ $personnel->nom }}" class="personnel-image me-3">
                                    @else
                                    <div class="personnel-image me-3 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $personnel->nom ?? 'Non spécifié' }}</div>
                                        <div class="small text-muted">
                                            <i class="fas fa-envelope me-1"></i> {{ $personnel->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="small">
                                    <i class="fas fa-briefcase me-1 text-primary"></i>
                                    {{ $personnel->poste ?? 'Non spécifié' }}
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="fas fa-star me-1 text-warning"></i>
                                    {{ Str::limit($personnel->domaine_expertise, 30) ?? 'Non spécifié' }}
                                </div>
                            </td>
                            <td>
                                @if($personnel->linkedin)
                                <a href="{{ $personnel->linkedin }}" target="_blank" class="linkedin-icon">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                @else
                                <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dashboard.personnels.show', $personnel->id) }}" 
                                       class="action-btn btn btn-outline-success" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.personnels.edit', $personnel->id) }}" 
                                       class="action-btn btn btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.personnels.destroy', $personnel->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce membre ?');">
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
                                <i class="fas fa-users fa-2x mb-2 text-light" style="opacity: 0.5;"></i>
                                <h6 class="fw-light">Aucun membre trouvé</h6>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.personnels.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.personnels.create') }}" class="btn btn-sm btn-success mt-2">
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
            Affichage de <strong>{{ $personnels->firstItem() }}</strong> à <strong>{{ $personnels->lastItem() }}</strong> sur <strong>{{ $personnels->total() }}</strong>
        </div>
        <div>
            {{ $personnels->onEachSide(1)->links('pagination::bootstrap-5') }}
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