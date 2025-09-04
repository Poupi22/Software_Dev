@extends('admin_site.layouts.app')

@section('title', 'Détails du Type de Formation')

@section('content')
<style>
    /* Styles (identiques au modèle) */
    .detail-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
    .card-header { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-bottom: 1px solid #e2e8f0; padding: 1.25rem 1.5rem; }
    .card-body { padding: 1.5rem; }
    .detail-item { margin-bottom: 1.5rem; }
    .detail-label { font-weight: 600; color: #475569; margin-bottom: 0.25rem; font-size: 0.875rem; }
    .detail-value { font-size: 1rem; color: #1e293b; padding: 0.75rem 1rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
    .btn { font-weight: 500; padding: 0.625rem 1.25rem; border-radius: 8px; transition: all 0.2s; }
</style>

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-eye me-2"></i>
                        Détails du Type de Formation
                    </h1>
                    <p class="mb-0 opacity-75 small">Informations complètes sur ce type de formation</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard.type_formation.edit', $type_formation->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <form action="{{ route('dashboard.type_formation.destroy', $type_formation->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de formation ?')">
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
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i> Type de Formation : <span class="text-primary">{{ $type_formation->nom }}</span></h5>
                    <span class="badge bg-{{ $type_formation->statut ? 'success' : 'secondary' }}">
                        {{ $type_formation->statut ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-item">
                            <div class="detail-label">Nom Complet</div>
                            <div class="detail-value">{{ $type_formation->nom }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Durée</div>
                            <div class="detail-value">{{ $type_formation->duree ?? 'Non définie' }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Statut</div>
                            <div class="detail-value">
                                <span class="badge bg-{{ $type_formation->statut ? 'success' : 'secondary' }}">
                                    {{ $type_formation->statut ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="detail-item">
                            <div class="detail-label">Description Complète</div>
                            <div class="detail-value" style="min-height: 150px; white-space: pre-wrap;">{{ $type_formation->description ?? 'Aucune description fournie.' }}</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Date de création</div>
                                    <div class="detail-value">
                                        {{ $type_formation->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Dernière modification</div>
                                    <div class="detail-value">
                                        {{ $type_formation->updated_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top mt-4">
                    <a href="{{ route('dashboard.type_formation.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
