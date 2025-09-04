@extends('admin_site.layouts.app')
@section('title', 'Sessions de Programme')
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
    <div class="main-header"><div class="container"><h1><i class="fas fa-calendar-alt me-2"></i> Sessions de Programme</h1></div></div>
    <div class="container">
        <div class="d-flex justify-content-end mb-3"><a href="{{ route('dashboard.programme_session.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Lancer une session</a></div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead><tr><th>Programme</th><th>Année Académique</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody>
                        @php
                        $actions = [
                            'Planifiée' => ['label' => 'Ouvrir Inscriptions', 'statut' => 'Ouverte aux inscriptions', 'class' => 'success'],
                            'Ouverte aux inscriptions' => ['label' => 'Démarrer les Cours', 'statut' => 'En cours', 'class' => 'primary'],
                            'En cours' => ['label' => 'Terminer la Session', 'statut' => 'Terminée', 'class' => 'warning'],
                            'Terminée' => ['label' => 'Archiver', 'statut' => 'Archivée', 'class' => 'secondary'],
                        ];
                        @endphp

                        @forelse($sessions as $session)
                        <tr>
                            <td>{{ $session->programme->formation->nom }} ({{ $session->programme->qualification->code }})</td>
                            <td>{{ $session->anneeAcademique->libelle }}</td>
                            <td><span class="badge bg-info">{{ $session->statut }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if(isset($actions[$session->statut]))
                                        @php $action = $actions[$session->statut]; @endphp
                                        <form action="{{ route('dashboard.programme_session.change_status', $session->id) }}" method="POST" class="me-2">
                                            @csrf
                                            <input type="hidden" name="statut" value="{{ $action['statut'] }}">
                                            <button type="submit" class="btn btn-sm btn-{{ $action['class'] }}">{{ $action['label'] }}</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('dashboard.programme_session.show', $session->id) }}" class="btn btn-sm btn-outline-info">Gérer</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Aucune session de programme trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
