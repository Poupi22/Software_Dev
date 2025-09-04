@extends('admin_site.layouts.app')

@section('title', 'Détails du Témoignage')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/show.css') }}">

<!-- Main Content Area -->
<div class="main-content">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-eye me-2"></i>
                        Détails du Témoignage
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur ce témoignage</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard.temoignage.edit', $temoignage->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <form action="{{ route('dashboard.temoignage.destroy', $temoignage->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage?')">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-card card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-comment-alt me-2"></i> Témoignage #{{ $temoignage->id }}</h5>
                    <span class="badge bg-{{ $temoignage->publie ? 'success' : 'secondary' }}">
                        {{ $temoignage->publie ? 'Publié' : 'Non publié' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Photo -->
                        <div class="detail-item text-center mb-4">
                            @if($temoignage->photo)
                                <img src="{{ asset('storage/' . $temoignage->photo) }}" alt="Photo" class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Nom -->
                        <div class="detail-item">
                            <div class="detail-label">Nom</div>
                            <div class="detail-value">{{ $temoignage->nom }}</div>
                        </div>
                        
                        <!-- Profession -->
                        <div class="detail-item">
                            <div class="detail-label">Profession</div>
                            <div class="detail-value">
                                {{ $options['professions'][$temoignage->profession] ?? 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <!-- Note -->
                        <div class="detail-item">
                            <div class="detail-label">Note</div>
                            <div class="detail-value">
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $temoignage->note ? '' : '-empty' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-dark">({{ $options['notes'][$temoignage->note] ?? $temoignage->note }}/5)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Message -->
                        <div class="detail-item">
                            <div class="detail-label">Message</div>
                            <div class="detail-value p-3" style="min-height: 150px;">
                                {{ $temoignage->message }}
                            </div>
                        </div>
                        
                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Date de création</div>
                                    <div class="detail-value">
                                        {{ $temoignage->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Dernière modification</div>
                                    <div class="detail-value">
                                        {{ $temoignage->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end pt-3 border-top mt-4">
                    <a href="{{ route('dashboard.temoignage.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Any specific scripts for the show page can go here
</script>
@endpush
@endsection