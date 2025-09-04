@extends('admin_site.layouts.app')

@section('title', 'Créer un Événement')

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
                        <i class="fas fa-calendar-alt me-2"></i>
                        Créer un Événement
                    </h1>
                    <p class="mb-0 opacity-75 small">Ajoutez un nouvel événement à votre calendrier</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-plus-circle text-primary me-1"></i>
                            Nouvel événement
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-plus me-2"></i> Formulaire de création</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.evenement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titre" class="form-label required-field">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lieu" class="form-label required-field">Lieu</label>
                                <input type="text" class="form-control" id="lieu" name="lieu" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_debut" class="form-label required-field">Date début</label>
                                <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_fin" class="form-label">Date fin</label>
                                <input type="datetime-local" class="form-control" id="date_fin" name="date_fin">
                                <small class="text-muted">Laissez vide si l'événement n'a pas de date de fin spécifique</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_evenement" class="form-label">Type d'événement</label>
                                <input type="text" class="form-control" id="type_evenement" name="type_evenement" placeholder="Conférence, Séminaire, etc.">
                            </div>
                            
                            <div class="mb-3">
                                <label for="statut" class="form-label required-field">Statut</label>
                                <select class="form-select" id="statut" name="statut" required>
                                    @foreach(App\Models\Evenement::STATUTS as $key => $value)
                                        <option value="{{ $key }}" {{ $key === 'brouillon' ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="text-muted">Formats acceptés: JPEG, PNG, GIF (max 2MB)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Décrivez votre événement..."></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ route('dashboard.evenement.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Annuler
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
    // Initialize date inputs with better defaults
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const startDateInput = document.getElementById('date_debut');
        
        // Set default start time to next full hour
        const nextHour = new Date(now);
        nextHour.setHours(now.getHours() + 1);
        nextHour.setMinutes(0);
        
        // Format for datetime-local input
        const formatForInput = (date) => {
            return date.toISOString().slice(0, 16);
        };
        
        startDateInput.value = formatForInput(nextHour);
        
        // Set min date to today
        startDateInput.min = formatForInput(now);
        
        // Update end date min when start date changes
        startDateInput.addEventListener('change', function() {
            const endDateInput = document.getElementById('date_fin');
            if (endDateInput) {
                endDateInput.min = this.value;
            }
        });
    });
</script>
@endpush
@endsection