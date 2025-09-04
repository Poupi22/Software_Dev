@extends('admin_site.layouts.app')

@section('title', 'Modifier un élément')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/edit.css') }}">

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'élément
                    </h1>
                    <p class="mb-0 opacity-75 small">Modification d'un élément de la page d'accueil</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('dashboard.accueil.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('dashboard.accueil.update', $accueil->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" name="titre" id="titre" class="form-control form-control-lg" value="{{ old('titre', $accueil->titre) }}">
                        @error('titre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="8" class="form-control">{{ old('description', $accueil->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Photo actuelle</label><br>
                        @if($accueil->photo)
                            <img src="{{ asset('storage/' . $accueil->photo) }}" class="current-image mb-3">
                            <a href="{{ asset('storage/' . $accueil->photo) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-3">
                                <i class="fas fa-expand me-1"></i> Voir en grand
                            </a>
                        @else
                            <p class="text-muted"><i class="fas fa-image me-1"></i> Aucune photo</p>
                        @endif
                        <label for="photo" class="form-label mt-2">Changer la photo</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-5">
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