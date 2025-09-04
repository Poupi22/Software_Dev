@extends('admin_site.layouts.app')

@section('title', 'Modifier un Membre')

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
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier le Membre
                    </h1>
                    <p class="mb-0 opacity-75 small">Mettre à jour les informations du membre</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-id-card text-primary me-1"></i>
                            ID: {{ $personnel->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-user-tie me-2"></i> Formulaire de modification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.personnels.update', $personnel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label required-field">Nom complet</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="{{ old('nom', $personnel->nom) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="poste" class="form-label required-field">Poste</label>
                                <input type="text" class="form-control" id="poste" name="poste" 
                                       value="{{ old('poste', $personnel->poste) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="domaine_expertise" class="form-label required-field">Domaine d'expertise</label>
                                <input type="text" class="form-control" id="domaine_expertise" name="domaine_expertise" 
                                       value="{{ old('domaine_expertise', $personnel->domaine_expertise) }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $personnel->email) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                       value="{{ old('linkedin', $personnel->linkedin) }}" placeholder="https://linkedin.com/...">
                            </div>
                            
                            <div class="mb-3">
                                <label for="ordre_affichage" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control" id="ordre_affichage" name="ordre_affichage" 
                                       value="{{ old('ordre_affichage', $personnel->ordre_affichage) }}">
                                <small class="text-muted">Détermine l'ordre dans la liste des membres</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Formats acceptés: JPEG, PNG, JPG (max 2MB)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="image-preview-container">
                                @if($personnel->image)
                                    <img src="{{ asset('storage/' . $personnel->image) }}" alt="Current Image" class="current-image mb-2">
                                    <small class="text-muted">Image actuelle</small>
                                @else
                                    <div class="current-image mb-2 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user text-muted fa-3x"></i>
                                    </div>
                                    <small class="text-muted">Aucune image</small>
                                @endif
                                <img id="previewImage" src="#" alt="Preview" class="image-preview">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="bio" class="form-label">Biographie</label>
                        <textarea class="form-control form-textarea" id="bio" name="bio" rows="5">{{ old('bio', $personnel->bio) }}</textarea>
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
        const previewImage = document.getElementById('previewImage');
        const currentImage = document.querySelector('.current-image');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImage.setAttribute('src', this.result);
                    previewImage.style.display = 'block';
                    
                    // Hide current image when new one is selected
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                });
                
                reader.readAsDataURL(file);
            } else {
                previewImage.setAttribute('src', '#');
                previewImage.style.display = 'none';
                
                // Show current image again if no new image selected
                if (currentImage) {
                    currentImage.style.display = 'block';
                }
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