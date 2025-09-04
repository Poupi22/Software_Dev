@extends('admin_site.layouts.app')

@section('title', 'Détails de l\'Actualité')

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
                        <i class="fas fa-newspaper me-2"></i>
                        Détails de l'Actualité
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur l'actualité</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Détails
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-card card">
            <div class="card-header">
                <h5><i class="fas fa-newspaper me-2"></i> Fiche d'information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h3 class="news-title">{{ $actualite->titre }}</h3>
                        
                        <!-- Image -->
                        @if($actualite->image)
                            <img src="{{ asset('storage/' . $actualite->image) }}" class="news-image" alt="Image de l'actualité">
                        @endif
                        
                        <div class="news-content">
                            {!! nl2br(e($actualite->contenu)) !!}
                        </div>
                        
                        <div class="text-muted mt-4">
                            <i class="far fa-calendar-alt me-1"></i> Publié le {{ $actualite->created_at->translatedFormat('d F Y à H\hi') }}
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection