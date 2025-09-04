@extends('admin_site.layouts.app')
@section('title', 'Dossier d\'Inscription')
@section('content')
    <style>
        .detail-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .detail-item {
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #1e293b;
            padding: 0.5rem 0;
        }
    </style>
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

        .form-control,
        .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.625rem 1rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
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
            margin-left: 250px;
            /* Adjust based on your sidebar width */
            transition: all 0.3s;
        }

        @media (max-width: 1199.98px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
                /* For fixed header */
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

            .form-control,
            .form-select {
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

            .row>div {
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
        <div class="main-header">
            <div class="container">
                <h1><i class="fas fa-folder-open me-2"></i> Dossier de {{ $inscription->user->name }}</h1>
            </div>
        </div>
        <div class="container">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations sur l'Inscription</h5>
                </div>
                <div class="card-body">
                    <p><strong>Programme:</strong> {{ $inscription->programmeSession->programme->formation->nom }} -
                        {{ $inscription->programmeSession->programme->qualification->nom }}</p>
                    <p><strong>Année Académique:</strong> {{ $inscription->programmeSession->anneeAcademique->libelle }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Résumé Financier</h5>
                </div>
                <div class="card-body">
                    <p><strong>Coût Total :</strong> {{ number_format($inscription->programmeSession->programme->prix) }}
                        FCFA</p>
                    <p><strong>Total Versé :</strong> {{ number_format($inscription->verse) }} FCFA</p>
                    <p><strong>Reste à payer :</strong> <span
                            class="fw-bold text-danger">{{ number_format($inscription->reste) }} FCFA</span></p>
                </div>
            </div>

            <!-- Section Notes et Bulletins -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Notes et Bulletins</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-edit me-2"></i>Saisie des Notes</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('dashboard.bulletin.saisie-notes', ['programme_session_id' => $inscription->programme_session_id, 'semestre' => 1]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-pen me-1"></i> Semestre 1
                                </a>
                                <a href="{{ route('dashboard.bulletin.saisie-notes', ['programme_session_id' => $inscription->programme_session_id, 'semestre' => 2]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-pen me-1"></i> Semestre 2
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-calendar-check me-2"></i>Saisie Assiduité</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('dashboard.bulletin.saisie-assiduite', ['programme_session_id' => $inscription->programme_session_id, 'semestre' => 1]) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-user-check me-1"></i> Semestre 1
                                </a>
                                <a href="{{ route('dashboard.bulletin.saisie-assiduite', ['programme_session_id' => $inscription->programme_session_id, 'semestre' => 2]) }}"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-user-check me-1"></i> Semestre 2
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6><i class="fas fa-eye me-2"></i>Prévisualiser les Bulletins de {{ $inscription->user->name }}</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('dashboard.bulletin.preview', ['user_id' => $inscription->user_id, 'programme_session_id' => $inscription->programme_session_id, 'semestre' => 1]) }}"
                            class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-alt me-1"></i> Bulletin S1
                        </a>
                        <a href="{{ route('dashboard.bulletin.preview', ['user_id' => $inscription->user_id, 'programme_session_id' => $inscription->programme_session_id, 'semestre' => 2]) }}"
                            class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-alt me-1"></i> Bulletin S2
                        </a>
                        <a href="{{ route('dashboard.bulletin.preview', ['user_id' => $inscription->user_id, 'programme_session_id' => $inscription->programme_session_id, 'type' => 'final']) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-graduation-cap me-1"></i> Bulletin Final
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
