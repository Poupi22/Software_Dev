@extends('admin_site.layouts.app')
@section('title', 'Gestion de la Matière : ' . $matiere->nom)
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
                <h1><i class="fas fa-book-open me-2"></i> Matière : {{ $matiere->nom }}</h1>
                <p class="text-white-50">Gérez les chapitres, leçons et évaluations de cette matière.</p>
            </div>
        </div>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('dashboard.matiere.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                    Retour</a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chapitreModal"><i
                        class="fas fa-plus"></i> Ajouter un Chapitre</button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quiz Final de la Matière</h5>
                    <div class="btn-group btn-group-sm">
                        @if ($matiere->quiz)
                            <a href="{{ route('dashboard.quiz.edit', $matiere->quiz->id) }}"
                                class="btn btn-outline-primary"><i class="fas fa-edit"></i> Modifier</a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteQuiz{{ $matiere->quiz->id }}"><i class="fas fa-trash"></i></button>
                        @else
                            <a href="{{ route('dashboard.quiz.create', ['quizzable_type' => 'Matiere', 'quizzable_id' => $matiere->id]) }}"
                                class="btn btn-info"><i class="fas fa-plus"></i> Créer le Quiz Final</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Dans admin_site/matieres/show.blade.php --}}
            <div class="accordion" id="chapitresAccordion">
                @forelse($matiere->chapitres as $chapitre)
                    <div class="accordion-item">
                        <h2 class="accordion-header d-flex align-items-center" id="heading{{ $chapitre->id }}">
                            {{-- Le bouton qui déplie l'accordéon --}}
                            <button class="accordion-button collapsed w-100" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $chapitre->id }}">
                                <i class="fas fa-folder me-2"></i> {{ $chapitre->nom }}
                            </button>

                            {{-- NOUVEAU : Groupe de boutons d'action pour le chapitre --}}
                            <div class="btn-group flex-shrink-0 me-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Modifier le chapitre"
                                    data-bs-toggle="modal" data-bs-target="#editChapitreModal"
                                    data-action-url="{{ route('dashboard.chapitre.update', $chapitre->id) }}"
                                    data-chapitre-nom="{{ $chapitre->nom }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer le chapitre"
                                    data-bs-toggle="modal" data-bs-target="#deleteChapitre{{ $chapitre->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </h2>
                        <div id="collapse{{ $chapitre->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#chapitresAccordion">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-end mb-2">
                                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#leconModal" data-chapitre-id="{{ $chapitre->id }}">
                                        <i class="fas fa-plus"></i> Ajouter une Leçon
                                    </button>
                                </div>
                                <ul class="list-group">
                                    @forelse($chapitre->lecons as $lecon)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div><i class="fas fa-file-alt me-2"></i> {{ $lecon->titre }}</div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('dashboard.lecon.edit', $lecon->id) }}"
                                                    class="btn btn-outline-primary" title="Gérer les ressources"><i
                                                        class="fas fa-cogs"></i></a>

                                                {{-- NOUVEAU : Bouton pour modifier le titre de la leçon via une modale --}}
                                                <button type="button" class="btn btn-outline-secondary"
                                                    title="Modifier la leçon" data-bs-toggle="modal"
                                                    data-bs-target="#editLeconModal"
                                                    data-action-url="{{ route('dashboard.lecon.update', $lecon->id) }}"
                                                    data-lecon-titre="{{ $lecon->titre }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @if ($lecon->quiz)
                                                    <a href="{{ route('dashboard.quiz.edit', $lecon->quiz->id) }}"
                                                        class="btn btn-outline-info" title="Modifier le quiz"><i
                                                            class="fas fa-question-circle"></i></a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Supprimer le quiz" data-bs-toggle="modal"
                                                        data-bs-target="#deleteQuiz{{ $lecon->quiz->id }}"><i
                                                            class="fas fa-trash"></i></button>
                                                @else
                                                    <a href="{{ route('dashboard.quiz.create', ['quizzable_type' => 'Lecon', 'quizzable_id' => $lecon->id]) }}"
                                                        class="btn btn-outline-success" title="Ajouter un quiz"><i
                                                            class="fas fa-plus-circle"></i></a>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteLecon{{ $lecon->id }}"
                                                    title="Supprimer la leçon"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">Aucune leçon dans ce chapitre.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card card-body text-center">
                        <p>Aucun chapitre créé pour cette matière.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('admin_site.matieres.modals_chapitre_lecon', ['matiere' => $matiere])

    @if ($matiere->quiz)
        @include('admin_site.global.delete-modal', [
            'id' => 'Quiz' . $matiere->quiz->id,
            'itemName' => 'le quiz "' . $matiere->quiz->titre . '"',
            'url' => route('dashboard.quiz.destroy', $matiere->quiz->id),
        ])
    @endif
    @foreach ($matiere->chapitres as $chapitre)
        @foreach ($chapitre->lecons as $lecon)
            @if ($lecon->quiz)
                @include('admin_site.global.delete-modal', [
                    'id' => 'Quiz' . $lecon->quiz->id,
                    'itemName' => 'le quiz "' . $lecon->quiz->titre . '"',
                    'url' => route('dashboard.quiz.destroy', $lecon->quiz->id),
                ])
            @endif
            @include('admin_site.global.delete-modal', [
                'id' => 'Lecon' . $lecon->id,
                'itemName' => 'la leçon "' . $lecon->titre . '"',
                'url' => route('dashboard.lecon.destroy', $lecon->id),
            ])
        @endforeach
    @endforeach

    @foreach($matiere->chapitres as $chapitre)
    {{-- NOUVEAU : Modale de suppression pour chaque chapitre --}}
    @include('admin_site.global.delete-modal', ['id' => 'Chapitre' . $chapitre->id, 'itemName' => 'le chapitre "' . $chapitre->nom . '"', 'url' => route('dashboard.chapitre.destroy', $chapitre->id)])

    @foreach($chapitre->lecons as $lecon)
        @if($lecon->quiz)
            @include('admin_site.global.delete-modal', ['id' => 'Quiz' . $lecon->quiz->id, 'itemName' => 'le quiz "' . $lecon->quiz->titre . '"', 'url' => route('dashboard.quiz.destroy', $lecon->quiz->id)])
        @endif
        @include('admin_site.global.delete-modal', ['id' => 'Lecon' . $lecon->id, 'itemName' => 'la leçon "' . $lecon->titre . '"', 'url' => route('dashboard.lecon.destroy', $lecon->id)])
    @endforeach
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gère la modale pour l'ajout de leçons
    var leconModal = document.getElementById('leconModal');
    if(leconModal) {
        leconModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var chapitreId = button.getAttribute('data-chapitre-id');
            var modalChapitreIdField = leconModal.querySelector('#chapitreIdField');
            modalChapitreIdField.value = chapitreId;
        });
    }

    // Gère la modale pour la modification de chapitres
    var editChapitreModal = document.getElementById('editChapitreModal');
    if(editChapitreModal) {
        editChapitreModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var actionUrl = button.getAttribute('data-action-url');
            var chapitreNom = button.getAttribute('data-chapitre-nom');

            var form = editChapitreModal.querySelector('#editChapitreForm');
            var nomField = editChapitreModal.querySelector('#chapitreNomField');

            form.action = actionUrl;
            nomField.value = chapitreNom;
        });
    }

    // NOUVEAU : Gère la modale pour la modification de leçons
    var editLeconModal = document.getElementById('editLeconModal');
    if(editLeconModal) {
        editLeconModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var actionUrl = button.getAttribute('data-action-url');
            var leconTitre = button.getAttribute('data-lecon-titre');

            var form = editLeconModal.querySelector('#editLeconForm');
            var titreField = editLeconModal.querySelector('#leconTitreField');

            form.action = actionUrl;
            titreField.value = leconTitre;
        });
    }
});
</script>
@endpush
