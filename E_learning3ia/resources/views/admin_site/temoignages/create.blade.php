@extends('admin_site.layouts.app')

@section('title', 'Créer un Témoignage')

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
                        <i class="fas fa-comment-alt me-2"></i>
                        Créer un Témoignage
                    </h1>
                    <p class="mb-0 opacity-75 small">Ajoutez un nouveau témoignage à votre site</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-plus-circle text-success me-1"></i>
                            Nouveau témoignage
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-user-edit me-2"></i> Formulaire de création</h5>
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

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('dashboard.temoignage.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nom -->
                            <div class="mb-3">
                                <label for="nom" class="form-label required-field">Nom</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="form-control" required>
                            </div>

                            <!-- Profession -->
                            <div class="mb-3">
                                <label for="profession" class="form-label">Profession</label>
                                <select name="profession" id="profession" class="form-select">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($options['professions'] as $key => $value)
                                        <option value="{{ $key }}" {{ old('profession') == $key ? 'selected' : '' }}>
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
                                <input type="file" name="photo" id="photo" class="form-control">
                                <small class="form-text text-muted">Formats: {{ $uploadConfig['mimes'] }}, Taille max: {{ $uploadConfig['max_size'] }}Ko</small>
                            </div>

                            <!-- Note -->
                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <select name="note" id="note" class="form-select">
                                    @foreach ($options['notes'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('note', $defaults['note']) == $key ? 'selected' : '' }}>
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
                        <textarea name="message" id="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                    </div>

                    <!-- Publie -->
                    <div class="form-check mb-4">
                        <input type="checkbox" name="publie" id="publie" class="form-check-input" {{ old('publie', $defaults['publie']) ? 'checked' : '' }}>
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
        // Initialize any testimonial-specific scripts here
        // For example, you could add image preview functionality
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // You could add a preview functionality here
                }
            });
        }
    });
</script>
@endpush
@endsection