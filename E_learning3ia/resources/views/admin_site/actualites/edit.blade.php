@extends('admin_site.layouts.app')

@section('title', 'Modifier l\'Actualité')

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
                        Modifier l'Actualité
                    </h1>
                    <p class="mb-0 opacity-75 small">Mettre à jour les informations de l'actualité</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-newspaper text-primary me-1"></i>
                            Édition
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="edit-card card">
            <div class="card-body p-4 p-md-5">
                <h2 class="form-title">Modifier l'actualité</h2>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.actualite.update', $actualite->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" name="titre" id="titre" value="{{ old('titre', $actualite->titre) }}"
                               class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="contenu" class="form-label">Contenu</label>
                        <textarea name="contenu" id="contenu" rows="8"
                                  class="form-control" required>{{ old('contenu', $actualite->contenu) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Image actuelle</label><br>
                        @if ($actualite->image)
                            <img src="{{ asset('storage/' . $actualite->image) }}" alt="Image actuelle" class="current-image me-3">
                            <a href="{{ asset('storage/' . $actualite->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-expand me-1"></i> Voir en grand
                            </a>
                        @else
                            <p class="text-muted"><i class="fas fa-image me-1"></i> Aucune image enregistrée</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label">Changer l'image (facultatif)</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection