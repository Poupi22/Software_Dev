@extends('admin_site.layouts.app')

@section('title', 'Détails du Membre')

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
    
    .detail-card {
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
    
    .member-title {
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .member-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.25rem;
    }
    
    .detail-value {
        color: #334155;
        margin-bottom: 1rem;
    }
    
    .linkedin-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
        background-color: #0077b5;
        color: white;
    }
    
    .linkedin-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .member-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .bio-container {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .info-badge {
        background-color: #e2e8f0;
        color: #475569;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
    }
    
    /* Layout Adjustments */
    .main-content {
        margin-left: 250px;
        transition: all 0.3s;
    }

    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0;
            padding-top: 70px;
        }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .card-body {
            padding: 1rem;
        }
        
        .member-title {
            font-size: 1.5rem;
        }
        
        .member-subtitle {
            font-size: 1rem;
        }
        
        .member-image {
            width: 150px;
            height: 150px;
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
    }
    
    @media (max-width: 575.98px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .member-title {
            font-size: 1.3rem;
        }
        
        .member-image {
            width: 120px;
            height: 120px;
        }
        
        .bio-container {
            padding: 1rem;
        }
    }
</style>

<!-- Main Content Area -->
<div class="main-content">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-user-circle me-2"></i>
                        Détails du Membre
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur ce membre</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard.personnels.edit', $personnel->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <form action="{{ route('dashboard.personnels.destroy', $personnel->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre?')">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-card card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Membre #{{ $personnel->id }}
                    </h5>
                    <div class="info-badge">
                        <i class="fas fa-clock me-1"></i>
                        Créé le {{ $personnel->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center text-md-start">
                        @if($personnel->image)
                            <img src="{{ asset('storage/' . $personnel->image) }}" alt="{{ $personnel->nom }}" class="member-image">
                        @else
                            <div class="member-image bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-muted fa-4x"></i>
                            </div>
                        @endif
                        
                        @if($personnel->linkedin)
                        <div class="mt-3">
                            <a href="{{ $personnel->linkedin }}" target="_blank" class="linkedin-badge">
                                <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h3 class="member-title">{{ $personnel->nom ?? 'Membre sans nom' }}</h3>
                        <p class="member-subtitle">
                            <i class="fas fa-briefcase me-1"></i> 
                            {{ $personnel->poste ?? 'Poste non spécifié' }}
                        </p>
                        
                        <div class="mb-4">
                            <h6 class="detail-label"><i class="fas fa-star me-1"></i> Domaine d'expertise</h6>
                            <p class="detail-value">{{ $personnel->domaine_expertise ?? 'Non spécifié' }}</p>
                            
                            <h6 class="detail-label"><i class="fas fa-envelope me-1"></i> Email</h6>
                            <p class="detail-value">
                                @if($personnel->email)
                                    <a href="mailto:{{ $personnel->email }}">{{ $personnel->email }}</a>
                                @else
                                    Non spécifié
                                @endif
                            </p>
                            
                            <h6 class="detail-label"><i class="fas fa-sort-numeric-up me-1"></i> Ordre d'affichage</h6>
                            <p class="detail-value">{{ $personnel->ordre_affichage ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
                
                @if($personnel->bio)
                <div class="bio-container">
                    <h6 class="detail-label"><i class="fas fa-book-open me-1"></i> Biographie</h6>
                    <div class="detail-value">
                        {!! nl2br(e($personnel->bio)) !!}
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-end pt-3 border-top mt-4">
                    <a href="{{ route('dashboard.personnels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection