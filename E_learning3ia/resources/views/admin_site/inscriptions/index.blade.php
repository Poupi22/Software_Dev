@extends('admin_site.layouts.app')

@section('title', 'Liste des Inscriptions')

@section('content')
    <style>
        .main-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 1rem;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .payment-history {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
    <div class="main-content">
        <div class="main-header">
            <div class="container">
                <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-list-ul me-2"></i> Liste des Inscriptions</h1>
            </div>
        </div>

        <div class="container">
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('dashboard.bulletin.index') }}" class="btn btn-success">
                    <i class="fas fa-file-invoice me-2"></i> Gestion des Bulletins
                </a>
                <a href="{{ route('dashboard.inscription.create') }}" class="btn btn-primary"><i
                        class="fas fa-plus me-1"></i>
                    Nouvelle Inscription</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center mb-3" role="alert"><i
                        class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center mb-3" role="alert"><i
                        class="fas fa-times-circle me-2"></i>{{ session('error') }}</div>
            @endif

            <div class="table-container card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Programme / Année</th>
                                <th>Reste à Payer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscriptions as $inscription)
                                <tr>
                                    <td>
                                        <div>{{ $inscription->user->name }} {{ $inscription->user->prenom }}</div>
                                        <small class="text-muted">{{ $inscription->user->matricule }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $inscription->programmeSession->programme->qualification->code }} /
                                            {{ $inscription->programmeSession->programme->formation->nom }}</div>
                                        <small
                                            class="text-muted">{{ $inscription->programmeSession->anneeAcademique->libelle }}</small>
                                    </td>
                                    <td class="fw-bold {{ $inscription->reste > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($inscription->reste) }} FCFA
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('dashboard.inscription.show', $inscription->id) }}"
                                                class="btn btn-outline-info" title="Voir le dossier"><i
                                                    class="fas fa-eye"></i></a>
                                            <button type="button" class="btn btn-outline-success"
                                                title="Gérer les paiements" data-bs-toggle="modal"
                                                data-bs-target="#paymentModal{{ $inscription->id }}">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                            <a href="{{ route('dashboard.inscription.situation_financiere', $inscription->id) }}"
                                                target="_blank" class="btn btn-outline-secondary"
                                                title="Imprimer la situation financière">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('dashboard.bulletin.liste-etudiants', ['programme_session_id' => $inscription->programme_session_id, 'semestre' => 1]) }}"
                                                class="btn btn-outline-warning" title="Bulletins de la session">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            <a href="{{ route('dashboard.inscription.edit', $inscription->id) }}"
                                                class="btn btn-outline-primary" title="Modifier l'inscription"><i
                                                    class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-outline-danger" title="Supprimer"
                                                data-bs-toggle="modal" data-bs-target="#delete{{ $inscription->id }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune inscription trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($inscriptions as $inscription)
        @include('admin_site.global.delete-modal', [
            'id' => $inscription->id,
            'itemName' => "l'inscription de " . $inscription->user->name,
            'url' => route('dashboard.inscription.destroy', $inscription->id),
        ])

        @include('admin_site.inscriptions.solde', ['inscription' => $inscription])
    @endforeach
@endsection

@push('scripts')
    <script>
        function disableSubmitButton(form) {
            var button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...';
            }
        }
    </script>
@endpush
