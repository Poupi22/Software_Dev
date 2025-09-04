@extends('admin_site.layouts.app')

@section('title', 'Modifier un Témoignage')

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
                        Modifier le Témoignage
                    </h1>
                    <p class="mb-0 opacity-75 small">Modifiez les détails de ce témoignage</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-comment-alt text-primary me-1"></i>
                            ID: {{ $temoignage->id }}
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Erreurs :</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.temoignage.update', $temoignage->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="nom" class="form-label required-field">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom', $temoignage->nom) }}" class="form-control" required>
                            </div>

                            <!-- Profession -->
                            <div class="mb-3">
                                <label for="profession" class="form-label">Profession</label>
                                <select name="profession" id="profession" class="form-select">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($options['professions'] as $key => $value)
                                        <option value="{{ $key }}" {{ old('profession', $temoignage->profession) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Photo -->
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo <small class="text-muted">({{ $uploadConfig['dimensions'] }})</small></label>
                                @if($temoignage->photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $temoignage->photo) }}" alt="Photo actuelle" class="img-thumbnail" style="max-height: 100px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo">
                                            <label class="form-check-label text-danger" for="remove_photo">
                                                Supprimer la photo actuelle
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="photo" id="photo" class="form-control">
                                <small class="form-text text-muted">Formats: {{ $uploadConfig['mimes'] }}, Taille max: {{ $uploadConfig['max_size'] }}Ko</small>
                            </div>

                            <!-- Note -->
                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <select name="note" id="note" class="form-select">
                                    @foreach ($options['notes'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('note', $temoignage->note) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <label for="message" class="form-label required-field">Message</label>
                        <textarea name="message" id="message" class="form-control" rows="5" required>{{ old('message', $temoignage->message) }}</textarea>
                    </div>

                    <!-- Publie -->
                    <div class="form-check mb-4">
                        <input type="checkbox" name="publie" id="publie" class="form-check-input" {{ old('publie', $temoignage->publie) ? 'checked' : '' }}>
                        <label class="form-check-label" for="publie">Publier ce témoignage</label>
                    </div>

                    <!-- Submit -->
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.temoignage.index') }}" class="btn btn-secondary me-2">
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
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('photo-preview');
                        if (!preview) {
                            const previewContainer = document.createElement('div');
                            previewContainer.className = 'mb-2';
                            previewContainer.innerHTML = `
                                <img id="photo-preview" src="${event.target.result}" alt="Nouvelle photo" class="img-thumbnail" style="max-height: 100px;">
                            `;
                            photoInput.parentNode.insertBefore(previewContainer, photoInput);
                        } else {
                            preview.src = event.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection