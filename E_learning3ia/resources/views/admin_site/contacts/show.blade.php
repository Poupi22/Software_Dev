@extends('admin_site.layouts.app')

@section('title', 'Détails du Contact')

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
                        <i class="fas fa-info-circle me-2"></i>
                        Détails du Contact
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur ce contact</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard.contact.edit', $contact->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <form action="{{ route('dashboard.contact.destroy', $contact->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contact?')">
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
                        <i class="fas fa-address-card me-2"></i>
                        Contact #{{ $contact->id }}
                    </h5>
                    <div class="info-badge">
                        <i class="fas fa-clock me-1"></i>
                        Créé le {{ $contact->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="contact-title">{{ $contact->nom ?? 'Contact sans nom' }}</h3>
                        <p class="contact-subtitle">
                            <i class="fas fa-id-card me-1"></i> 
                            {{ $contact->profession ?? 'Profession non spécifiée' }}
                        </p>
                        
                        <div class="mb-4">
                            <h6 class="detail-label"><i class="fas fa-envelope me-1"></i> Email</h6>
                            <p class="detail-value">
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </p>
                            
                            <h6 class="detail-label"><i class="fas fa-phone me-1"></i> Téléphone</h6>
                            <p class="detail-value">
                                <a href="tel:{{ $contact->telephone }}">{{ $contact->telephone }}</a>
                            </p>
                            
                            <h6 class="detail-label"><i class="fab fa-whatsapp me-1"></i> WhatsApp</h6>
                            <p class="detail-value">
                                <a href="https://wa.me/{{ $contact->whatsapp }}" target="_blank">{{ $contact->whatsapp }}</a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="detail-label"><i class="fas fa-map-marker-alt me-1"></i> Adresse</h6>
                            <p class="detail-value">
                                {!! nl2br(e($contact->adresse)) !!}
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="detail-label"><i class="fas fa-share-alt me-1"></i> Réseaux sociaux</h6>
                            <div class="d-flex flex-wrap">
                                @if($contact->facebook_link)
                                <a href="{{ $contact->facebook_link }}" target="_blank" class="social-badge facebook-badge">
                                    <i class="fab fa-facebook-f me-1"></i> Facebook
                                </a>
                                @endif
                                
                                @if($contact->tiktok_link)
                                <a href="{{ $contact->tiktok_link }}" target="_blank" class="social-badge tiktok-badge">
                                    <i class="fab fa-tiktok me-1"></i> TikTok
                                </a>
                                @endif
                                
                                @if($contact->linkedin_link)
                                <a href="{{ $contact->linkedin_link }}" target="_blank" class="social-badge linkedin-badge">
                                    <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                                </a>
                                @endif
                                
                                @if(!$contact->facebook_link && !$contact->tiktok_link && !$contact->linkedin_link)
                                <div class="text-muted">
                                    Aucun réseau social renseigné
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="detail-label"><i class="fas fa-map-marked-alt me-1"></i> Localisation</h6>
                            <div class="map-container">
                                {!! $contact->iframe_localisation !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end pt-3 border-top mt-4">
                    <a href="{{ route('dashboard.contact.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection