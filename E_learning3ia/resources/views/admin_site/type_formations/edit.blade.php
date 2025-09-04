@extends('admin_site.layouts.app')

@section('title', 'Modifier le Type de Formation')

@section('content')
<style>
    /* Styles (identiques au modèle) */
    .form-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
    .card-header { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-bottom: 1px solid #e2e8f0; padding: 1.25rem 1.5rem; }
    .card-header h5 { font-weight: 600; color: #1e293b; margin: 0; }
    .card-body { padding: 1.5rem; }
    .form-label { font-weight: 500; color: #334155; margin-bottom: 0.5rem; }
    .form-control, .form-select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.625rem 1rem; transition: all 0.2s; }
    .form-control:focus, .form-select:focus { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2); }
    .btn { font-weight: 500; padding: 0.625rem 1.25rem; border-radius: 8px; transition: all 0.2s; }
    .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4); }
    .btn-secondary { background: #e2e8f0; border: none; color: #475569; }
    .btn-secondary:hover { background: #cbd5e1; }
    .required-field::after { content: " *"; color: #ef4444; }
</style>

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le Type de Formation
                    </h1>
                    <p class="mb-0 opacity-75 small">Modifiez les informations de ce type de formation</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-layer-group text-primary me-1"></i>
                            ID: {{ $type_formation->id }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-card card">
            <div class="card-header">
                <h5><i class="fas fa-pencil-alt me-2"></i> Formulaire de modification</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0" style="padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.type_formation.update', $type_formation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('admin_site.type_formations.form')

                    <div class="d-flex justify-content-end pt-3 border-top mt-3">
                        <a href="{{ route('dashboard.type_formation.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
