@extends('admin_site.layouts.app')
@section('title', 'Détails du Programme')
@section('content')
<style>
    /* Base Styles */
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 0;
        margin-bottom: 1rem;
        width: 100%;
    }

    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
    }

    .btn {
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        border: none;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .required-field::after {
        content: " *";
        color: #ef4444;
    }

    /* File Input Customization */
    .form-control[type="file"] {
        padding: 0.375rem;
    }

    /* Layout Adjustments */
    .main-content {
        margin-left: 250px; /* Adjust based on your sidebar width */
        transition: all 0.3s;
    }

    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0;
            padding-top: 70px; /* For fixed header */
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .card-body {
            padding: 1rem;
        }

        .form-label {
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 767.98px) {
        .card-header {
            padding: 1rem;
        }

        .card-header h5 {
            font-size: 1.1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .main-header h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 575.98px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .row > div {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem;
        }

        .d-flex.justify-content-end .btn {
            width: 100%;
        }

        .main-header h1 {
            font-size: 1.25rem;
        }
    }
</style>
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-eye me-2"></i> Détails du Programme</h1></div></div>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $programme->formation->nom }} - {{ $programme->qualification->code }}</h5>
                <a href="{{ route('dashboard.programme.edit', $programme->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i> Modifier</a>
            </div>
            <div class="card-body">
                <p><b>Prix:</b> {{ number_format($programme->prix) }} FCFA | <b>Durée:</b> {{ $programme->duree }}</p>
                <hr><h6>Matières par trimestre</h6>
                @forelse($programme->matieres->groupBy('pivot.trimestre')->sortKeys() as $trimestre => $matieres)
                    <p class="mt-3"><b>Trimestre {{ $trimestre }}</b></p>
                    <ul class="list-group">
                    @foreach($matieres as $matiere)
                        <li class="list-group-item">{{ $matiere->nom }}</li>
                    @endforeach
                    </ul>
                @empty
                    <p>Aucune matière n'est associée à ce programme.</p>
                @endforelse
            </div>
            <div class="card-footer d-flex justify-content-end"><a href="{{ route('dashboard.programme.index') }}" class="btn btn-secondary">Retour</a></div>
        </div>
    </div>
</div>
@endsection
