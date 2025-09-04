@extends('admin_site.layouts.app')

@section('title', 'Modifier un contenu "À propos"')

@section('content')

<link rel="stylesheet" href="{{ asset('admin_site/assets/css/edit.css') }}">

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-edit me-2"></i>
                        Modifier "{{ $about->titre }}"
                    </h1>
                    <p class="mb-0 opacity-75 small">ID: {{ $about->id }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('dashboard.about.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('dashboard.about.update', $about->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" name="titre" id="titre" class="form-control form-control-lg" value="{{ old('titre', $about->titre) }}" required>
                        @error('titre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="8" class="form-control" required>{{ old('description', $about->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Image actuelle</label><br>
                            @if($about->image)
                                <img src="{{ asset('storage/' . $about->image) }}" class="current-image mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image">
                                    <label class="form-check-label text-danger" for="remove_image">
                                        <i class="fas fa-trash me-1"></i> Supprimer cette image
                                    </label>
                                </div>
                            @else
                                <p class="text-muted"><i class="fas fa-image me-1"></i> Aucune image</p>
                            @endif
                            <label for="image" class="form-label mt-2">Changer l'image</label>
                            <input type="file" name="image" id="image" class="form-control">
                            <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-select">
                                <option value="brouillon" {{ old('statut', $about->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="actif" {{ old('statut', $about->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                            </select>

                            <label for="video" class="form-label mt-3">Lien vidéo (optionnel)</label>
                            <input type="url" name="video" id="video" class="form-control" value="{{ old('video', $about->video) }}" placeholder="https://...">
                            @error('video')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
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

@push('scripts')
<script>
    // Preview new image when selected
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.createElement('img');
                preview.src = event.target.result;
                preview.className = 'current-image mb-3';
                document.querySelector('[for="image"]').before(preview);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection