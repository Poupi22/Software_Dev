@extends('admin_site.layouts.app')

@section('title', 'Ajouter une Actualité')

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
                        <i class="fas fa-newspaper me-2"></i>
                        Ajouter une Actualité
                    </h1>
                    <p class="mb-0 opacity-75 small">Créez un nouvel article d'actualité</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-plus-circle text-primary me-1"></i>
                            Nouvelle actualité
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="form-container">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-card bg-white">
                <h2 class="page-title">
                    <i class="fas fa-pen-alt me-2"></i>Formulaire de création
                </h2>

                <form action="{{ route('dashboard.actualite.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Titre -->
                    <div class="mb-4">
                        <label for="titre" class="form-label">Titre de l'actualité *</label>
                        <input type="text" name="titre" id="titre" value="{{ old('titre') }}" 
                               class="form-control" required placeholder="Entrez le titre de l'actualité">
                    </div>

                    <!-- Date -->
                    <div class="mb-4">
                        <label for="date_publication" class="form-label">Date de publication *</label>
                        <input type="date" name="date_publication" id="date_publication" 
                               value="{{ old('date_publication', now()->format('Y-m-d')) }}" 
                               class="form-control" required>
                    </div>

                    <!-- Contenu -->
                    <div class="mb-4">
                        <label for="contenu" class="form-label">Contenu *</label>
                        <textarea name="contenu" id="contenu" rows="8" class="form-control" 
                                  required placeholder="Rédigez le contenu de l'actualité...">{{ old('contenu') }}</textarea>
                    </div>

                    <!-- Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label">Image (optionnel)</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="text-muted">Formats acceptés: JPEG, PNG (max 2MB)</small>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
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
    // Set default date to today if not already set
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date_publication');
        if (!dateInput.value) {
            const today = new Date();
            dateInput.value = today.toISOString().slice(0, 10);
        }
    });
</script>
@endpush
@endsection