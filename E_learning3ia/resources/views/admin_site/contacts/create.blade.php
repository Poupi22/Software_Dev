@extends('admin_site.layouts.app')

@section('title', 'Créer un Contact')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/create.css') }}">

<!-- Main Content Area -->
<div class="main-content">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-plus-circle me-2"></i>
                        Nouveau Contact
                    </h1>
                    <p class="mb-0 opacity-75 small">Ajouter de nouvelles informations de contact</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-address-card text-primary me-1"></i>
                            Nouveau contact
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle me-2"></i> Informations du contact</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" name="nom" id="nom" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label for="adresse" class="form-label required-field">Adresse</label>
                                <textarea name="adresse" id="adresse" class="form-control form-textarea" rows="3" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telephone" class="form-label required-field">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label required-field">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="iframe_localisation" class="form-label required-field">Iframe Localisation</label>
                                <textarea name="iframe_localisation" id="iframe_localisation" class="form-control form-textarea" rows="3" required></textarea>
                                <small class="text-muted">Code HTML pour l'iframe de localisation (Google Maps, etc.)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links-container">
                        <h6 class="fw-semibold mb-3"><i class="fas fa-share-alt me-2"></i>Réseaux sociaux</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="facebook_link" class="form-label">
                                        <i class="fab fa-facebook-f me-1 text-primary"></i> Facebook
                                    </label>
                                    <input type="url" name="facebook_link" id="facebook_link" class="form-control" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tiktok_link" class="form-label">
                                        <i class="fab fa-tiktok me-1 text-dark"></i> TikTok
                                    </label>
                                    <input type="url" name="tiktok_link" id="tiktok_link" class="form-control" placeholder="https://tiktok.com/...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="linkedin_link" class="form-label">
                                        <i class="fab fa-linkedin-in me-1 text-info"></i> LinkedIn
                                    </label>
                                    <input type="url" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="https://linkedin.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.contact.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any specific JavaScript for the contact form here
        // For example, phone number formatting or social media URL validation
        
        // Example: Format phone number input
        const phoneInputs = document.querySelectorAll('input[type="text"][id="telephone"], input[type="text"][id="whatsapp"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                e.target.value = value;
            });
        });
    });
</script>
@endpush
@endsection