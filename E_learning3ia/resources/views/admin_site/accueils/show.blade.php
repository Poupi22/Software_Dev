@extends('admin_site.layouts.app')

@section('title', 'Détails - Accueil')

@section('content')
<link rel="stylesheet" href="{{ asset('admin_site/assets/css/show.css') }}">

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-home me-2"></i>
                        Détails du contenu d'accueil
                    </h1>
                    <p class="mb-0 opacity-75 small">{{ $accueil->titre }}</p>
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
        <div class="card shadow-sm detail-card">
            <div class="card-body">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">{{ $accueil->titre }}</h2>
                        <span class="badge bg-{{ $accueil->statut === 'actif' ? 'success' : 'secondary' }} meta-badge">
                            {{ ucfirst($accueil->statut) }}
                        </span>
                    </div>
                </div>

                @if($accueil->photo)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $accueil->photo) }}" class="detail-image" alt="Image accueil">
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $accueil->photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-expand me-1"></i> Voir en grand
                        </a>
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <h3 class="h6 fw-bold text-muted mb-3">Description</h3>
                    <div class="prose">
                        {!! $accueil->description !!}
                    </div>
                </div>

                @if($accueil->lien)
                <div class="mb-4">
                    <h3 class="h6 fw-bold text-muted mb-3">Lien associé</h3>
                    <a href="{{ $accueil->lien }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Visiter le lien
                    </a>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="small text-muted">Créé le</div>
                        <div class="fw-medium">{{ $accueil->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="small text-muted">Mis à jour le</div>
                        <div class="fw-medium">{{ $accueil->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('dashboard.accueil.edit', $accueil->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <form action="{{ route('dashboard.accueil.destroy', $accueil->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu ?')">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection