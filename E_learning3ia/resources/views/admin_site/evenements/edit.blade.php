@extends('admin_site.layouts.app')

@section('title', 'Modifier l\'Événement')

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
                        <i class="fas fa-calendar-alt me-2"></i>
                        Modifier l'Événement
                    </h1>
                    <p class="mb-0 opacity-75 small">Mettez à jour les détails de votre événement</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-edit text-primary me-1"></i>
                            Modification
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-edit me-2"></i> Formulaire de modification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.evenement.update', $evenement) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titre" class="form-label required-field">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" value="{{ $evenement->titre }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lieu" class="form-label required-field">Lieu</label>
                                <input type="text" class="form-control" id="lieu" name="lieu" value="{{ $evenement->lieu }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_debut" class="form-label required-field">Date début</label>
                                <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" 
                                       value="{{ $evenement->date_debut->format('Y-m-d\TH:i') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="datetime-local" class="form-control" id="date_fin" name="date_fin" 
                                       value="{{ $evenement->date_fin ? $evenement->date_fin->format('Y-m-d\TH:i') : '' }}">
                                <small class="text-muted">Laissez vide si l'événement n'a pas de date de fin spécifique</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_evenement" class="form-label">Type d'événement</label>
                                <input type="text" class="form-control" id="type_evenement" name="type_evenement" value="{{ $evenement->type_evenement }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="statut" class="form-label required-field">Statut</label>
                                <select class="form-select" id="statut" name="statut" required>
                                    @foreach(App\Models\Evenement::STATUTS as $key => $value)
                                        <option value="{{ $key }}" {{ $evenement->statut === $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if($evenement->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $evenement->image) }}" alt="Image événement" class="img-thumbnail" style="max-height: 120px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                            <label class="form-check-label text-danger" for="remove_image">
                                                Supprimer l'image actuelle
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ $evenement->description }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.evenement.index') }}" class="btn btn-secondary me-2">
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

@push('scripts')
<script>
    // Initialize date inputs with better defaults
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('date_debut');
        const endDateInput = document.getElementById('date_fin');
        
        // Set min date to today for start date
        const now = new Date();
        startDateInput.min = now.toISOString().slice(0, 16);
        
        // Update end date min when start date changes
        startDateInput.addEventListener('change', function() {
            if (endDateInput) {
                endDateInput.min = this.value;
            }
        });
        
        // If end date exists, set its min to start date
        if (endDateInput.value && startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
    });
</script>
@endpush
@endsection