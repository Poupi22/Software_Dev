@extends('admin_site.layouts.app')

@section('title', 'Détails de l\'Événement')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/show.css') }}">

<!-- Main Content Area -->
<div class="main-content">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Détails de l'Événement
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur l'événement</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Détails
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-card card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-day me-2"></i> Fiche d'information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="event-title">{{ $evenement->titre }}</h3>
                        <p class="event-type">
                            <i class="fas fa-tag me-1"></i> {{ $evenement->type_evenement ?? 'Non spécifié' }}
                        </p>
                        
                        <div class="event-description">
                            <h6 class="detail-label">Description :</h6>
                            <p>{{ $evenement->description ?? 'Aucune description disponible' }}</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="detail-label"><i class="fas fa-map-marker-alt me-1"></i> Lieu</h6>
                                    <p class="detail-value">{{ $evenement->lieu }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="detail-label"><i class="fas fa-info-circle me-1"></i> Statut</h6>
                                    <p class="detail-value">
                                        <span class="badge bg-{{ $evenement->statut === 'actif' ? 'success' : ($evenement->statut === 'archive' ? 'secondary' : 'warning') }}">
                                            {{ $evenement->statut }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="detail-label"><i class="fas fa-clock me-1"></i> Date début</h6>
                                    <p class="detail-value">{{ $evenement->date_debut->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @if($evenement->date_fin)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="detail-label"><i class="fas fa-clock me-1"></i> Date fin</h6>
                                    <p class="detail-value">{{ $evenement->date_fin->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($evenement->image)
                    <div class="col-md-4">
                        <div class="card border-0">
                            <img src="{{ asset('storage/' . $evenement->image) }}" class="event-image" alt="Image événement">
                            <div class="card-body text-center">
                                <a href="{{ asset('storage/' . $evenement->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-expand me-1"></i> Voir en grand
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection