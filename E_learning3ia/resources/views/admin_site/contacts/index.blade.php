@extends('admin_site.layouts.app')

@section('title', 'Liste des Contacts')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/index.css') }}">

<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="h4 h-md-3 fw-bold mb-1">
                    <i class="fas fa-address-book me-2"></i>
                    Gestion des Contacts
                </h1>
                <p class="mb-0 opacity-75 small">Liste des informations de contact</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="stats-card d-inline-block p-2 p-md-3">
                    <div class="text-dark small">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <strong>{{ $contacts->total() }}</strong> contacts
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
                <form method="GET" action="{{ route('dashboard.contact.index') }}" class="mb-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-0 bg-light"
                               placeholder="Rechercher...">
                        @if(request('search'))
                        <a href="{{ route('dashboard.contact.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <a href="{{ route('dashboard.contact.create') }}" class="btn btn-success w-100">
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
                        <th>Coordonnées</th>
                        <th class="d-none d-md-table-cell">Adresse</th>
                        <th>Réseaux</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge bg-secondary">#{{ str_pad($contact->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="contact-icon me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $contact->nom ?? 'Non spécifié' }}</div>
                                        <div class="small text-muted">
                                            <i class="fas fa-phone me-1"></i> {{ $contact->telephone }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fas fa-envelope me-1"></i> {{ $contact->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="small">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                    {{ Str::limit($contact->adresse, 40) }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    @if($contact->facebook_link)
                                    <a href="{{ $contact->facebook_link }}" target="_blank" class="social-icon facebook-icon">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    @endif
                                    @if($contact->tiktok_link)
                                    <a href="{{ $contact->tiktok_link }}" target="_blank" class="social-icon tiktok-icon">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                    @endif
                                    @if($contact->linkedin_link)
                                    <a href="{{ $contact->linkedin_link }}" target="_blank" class="social-icon linkedin-icon">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    @endif
                                    @if($contact->whatsapp)
                                    <a href="https://wa.me/{{ $contact->whatsapp }}" target="_blank" class="social-icon whatsapp-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('dashboard.contact.show', $contact->id) }}" 
                                       class="action-btn btn btn-outline-success" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('dashboard.contact.edit', $contact->id) }}" 
                                       class="action-btn btn btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.contact.destroy', $contact->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce contact ?');">
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
                                <i class="fas fa-address-book fa-2x mb-2 text-light" style="opacity: 0.5;"></i>
                                <h6 class="fw-light">Aucun contact trouvé</h6>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.contact.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        Réinitialiser
                                    </a>
                                @else
                                    <a href="{{ route('dashboard.contact.create') }}" class="btn btn-sm btn-success mt-2">
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
            Affichage de <strong>{{ $contacts->firstItem() }}</strong> à <strong>{{ $contacts->lastItem() }}</strong> sur <strong>{{ $contacts->total() }}</strong>
        </div>
        <div>
            {{ $contacts->onEachSide(1)->links('pagination::bootstrap-5') }}
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