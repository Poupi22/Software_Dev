@extends('admin_site.layouts.app')

@section('title', 'Modifier un Contact')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/edit.css') }}">

<!-- Main Content Area -->
<div class="main-content">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le Contact
                    </h1>
                    <p class="mb-0 opacity-75 small">Mettre à jour les informations de contact</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-id-card text-primary me-1"></i>
                            ID: {{ $contact->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-user-edit me-2"></i> Formulaire de modification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.contact.update', $contact->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="{{ old('nom', $contact->nom) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="telephone" class="form-label required-field">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" 
                                       value="{{ old('telephone', $contact->telephone) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $contact->email) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="whatsapp" class="form-label required-field">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" 
                                       value="{{ old('whatsapp', $contact->whatsapp) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="facebook_link" class="form-label">Lien Facebook</label>
                                <input type="url" class="form-control" id="facebook_link" name="facebook_link" 
                                       value="{{ old('facebook_link', $contact->facebook_link) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="tiktok_link" class="form-label">Lien TikTok</label>
                                <input type="url" class="form-control" id="tiktok_link" name="tiktok_link" 
                                       value="{{ old('tiktok_link', $contact->tiktok_link) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links-container">
                        <h6 class="fw-semibold mb-3"><i class="fas fa-share-alt me-2"></i>Réseaux sociaux</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="linkedin_link" class="form-label">
                                        <i class="fab fa-linkedin-in me-1 text-info"></i> LinkedIn
                                    </label>
                                    <input type="url" class="form-control" id="linkedin_link" name="linkedin_link" 
                                           value="{{ old('linkedin_link', $contact->linkedin_link) }}" placeholder="https://linkedin.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label required-field">Adresse</label>
                        <textarea class="form-control form-textarea" id="adresse" name="adresse" rows="3" required>{{ old('adresse', $contact->adresse) }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="iframe_localisation" class="form-label required-field">Iframe Localisation</label>
                        <textarea class="form-control form-textarea" id="iframe_localisation" name="iframe_localisation" rows="5" required>{{ old('iframe_localisation', $contact->iframe_localisation) }}</textarea>
                        <small class="text-muted">Code HTML de l'iframe de localisation (Google Maps, etc.)</small>
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
        // Phone number formatting
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
        
        // Social media URL validation
        const socialInputs = document.querySelectorAll('input[type="url"]');
        socialInputs.forEach(input => {
            input.addEventListener('blur', function(e) {
                if (e.target.value && !e.target.value.startsWith('http')) {
                    e.target.value = 'https://' + e.target.value;
                }
            });
        });
    });
</script>
@endpush
@endsection