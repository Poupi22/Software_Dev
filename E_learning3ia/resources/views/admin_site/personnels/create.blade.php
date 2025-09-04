@extends('admin_site.layouts.app')

@section('title', 'Ajouter un Membre')

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
                        <i class="fas fa-user-plus me-2"></i>
                        Nouveau Membre
                    </h1>
                    <p class="mb-0 opacity-75 small">Ajouter un nouveau membre à l'équipe</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-users text-primary me-1"></i>
                            Nouveau membre
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-user-tie me-2"></i> Informations du membre</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.personnels.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label required-field">Nom complet</label>
                                <input type="text" name="nom" id="nom" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="poste" class="form-label required-field">Poste</label>
                                <input type="text" name="poste" id="poste" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="domaine_expertise" class="form-label required-field">Domaine d'expertise</label>
                                <input type="text" name="domaine_expertise" id="domaine_expertise" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" name="linkedin" id="linkedin" class="form-control" placeholder="https://linkedin.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label for="ordre_affichage" class="form-label">Ordre d'affichage</label>
                                <input type="number" name="ordre_affichage" id="ordre_affichage" class="form-control">
                                <small class="text-muted">Détermine l'ordre dans la liste des membres</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label required-field">Photo</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                <small class="text-muted">Formats acceptés: JPEG, PNG, JPG (max 2MB)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="image-preview-container">
                                <div id="imagePreview" class="mb-2" style="display: none;">
                                    <img id="previewImage" src="#" alt="Preview" class="image-preview">
                                </div>
                                <small class="text-muted">Aperçu de l'image</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biographie</label>
                        <textarea name="bio" id="bio" class="form-control form-textarea" rows="5"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.personnels.index') }}" class="btn btn-secondary me-2">
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
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImage.setAttribute('src', this.result);
                    imagePreview.style.display = 'block';
                });
                
                reader.readAsDataURL(file);
            } else {
                previewImage.setAttribute('src', '#');
                imagePreview.style.display = 'none';
            }
        });
        
        // LinkedIn URL validation
        const linkedinInput = document.getElementById('linkedin');
        linkedinInput.addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !value.startsWith('https://linkedin.com/') && !value.startsWith('https://www.linkedin.com/')) {
                alert('Veuillez entrer une URL LinkedIn valide (commençant par https://linkedin.com/ ou https://www.linkedin.com/)');
                this.value = '';
            }
        });
    });
</script>
@endpush
@endsection