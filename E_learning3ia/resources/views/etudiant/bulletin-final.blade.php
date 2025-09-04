@extends('etudiant.layouts.app')

@section('title', 'Bulletin Final')

@section('content')
    <style>
        .bulletin-container {
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 0 auto;
        }

        .bulletin-header {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
        }

        .semester-section {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .semester-header {
            background: #e3f2fd;
            padding: 10px 15px;
            font-weight: bold;
        }
    </style>

    <div class="container py-4">
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('etudiant.bulletin.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('etudiant.bulletin.download-pdf', ['programme_session_id' => request('programme_session_id'), 'type' => 'final']) }}"
                class="btn btn-success">
                <i class="fas fa-file-pdf me-2"></i> Télécharger PDF Final
            </a>
        </div>

        <!-- Bulletin Final -->
        <div class="bulletin-container mb-4">
            <!-- En-tête -->
            <div class="bulletin-header p-3">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @if ($bulletinData['institution']['logo'])
                            <img src="{{ asset($bulletinData['institution']['logo']) }}" alt="Logo"
                                style="max-height: 80px;">
                        @else
                            <div class="bg-white text-dark p-2 rounded">
                                <strong>{{ $bulletinData['institution']['nom'] }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 text-center">
                        <h2 class="mb-0">BULLETIN FINAL</h2>
                        <p class="mb-0">Récapitulatif de l'année académique</p>
                    </div>
                    <div class="col-md-3 text-end">
                        <p class="mb-0"><strong>Année :</strong> {{ $bulletinData['session']['annee_academique'] }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <!-- Informations étudiant -->
                <div class="bg-light p-3 rounded mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nom :</strong> {{ $bulletinData['etudiant']['nom'] }}</p>
                            <p class="mb-1"><strong>Prénom :</strong> {{ $bulletinData['etudiant']['prenom'] }}</p>
                            <p class="mb-1"><strong>Date de naissance :</strong>
                                {{ $bulletinData['etudiant']['date_naissance'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Lieu de naissance :</strong>
                                {{ $bulletinData['etudiant']['lieu_naissance'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Spécialité :</strong> {{ $bulletinData['formation']['nom'] }}</p>
                            <p class="mb-0"><strong>Matricule :</strong> {{ $bulletinData['etudiant']['matricule'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tableaux par semestre -->
                @foreach ($bulletinData['bulletins_semestres'] as $semestre => $bulletinSemestre)
                    <div class="semester-section">
                        <div class="semester-header">
                            <i class="fas fa-book me-2"></i>Semestre {{ $semestre }}
                        </div>
                        <div class="p-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Matière</th>
                                            <th class="text-center">Code</th>
                                            <th class="text-center">Crédit</th>
                                            <th class="text-center">CC</th>
                                            <th class="text-center">Normale</th>
                                            @if ($bulletinSemestre['ponderation']['phase'] == 2)
                                                <th class="text-center">Quiz</th>
                                            @endif
                                            <th class="text-center">Moy.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bulletinSemestre['notes'] as $note)
                                            <tr>
                                                <td>{{ $note['matiere_nom'] }}</td>
                                                <td class="text-center">{{ $note['matiere_code'] }}</td>
                                                <td class="text-center">{{ $note['credit'] }}</td>
                                                <td class="text-center">
                                                    {{ $note['note_cc'] !== null ? number_format($note['note_cc'], 2) : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $note['note_normale'] !== null ? number_format($note['note_normale'], 2) : '-' }}
                                                </td>
                                                @if ($bulletinSemestre['ponderation']['phase'] == 2)
                                                    <td class="text-center">
                                                        {{ $note['note_quiz'] !== null ? number_format($note['note_quiz'], 2) : '-' }}
                                                    </td>
                                                @endif
                                                <td
                                                    class="text-center fw-bold {{ $note['note_finale'] !== null && $note['note_finale'] >= 10 ? 'text-success' : 'text-danger' }}">
                                                    {{ $note['note_finale'] !== null ? number_format($note['note_finale'], 2) : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-warning">
                                            <th colspan="{{ $bulletinSemestre['ponderation']['phase'] == 2 ? 6 : 5 }}"
                                                class="text-end">
                                                Moyenne Semestre {{ $semestre }} :
                                            </th>
                                            <th
                                                class="text-center {{ $bulletinSemestre['moyenne_generale'] >= 10 ? 'text-success' : 'text-danger' }}">
                                                {{ $bulletinSemestre['moyenne_generale'] !== null ? number_format($bulletinSemestre['moyenne_generale'], 2) : '-' }}/20
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="{{ $bulletinSemestre['ponderation']['phase'] == 2 ? 6 : 5 }}"
                                                class="text-end">Assiduité :</td>
                                            <td class="text-center">
                                                {{ number_format($bulletinSemestre['assiduite']['presence'], 1) }}%</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Résultat final -->
                <div class="card border-success mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Résultat Final</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-center">
                                <h4>Moyenne Annuelle</h4>
                                <h1 class="{{ $bulletinData['moyenne_annuelle'] >= 10 ? 'text-success' : 'text-danger' }}">
                                    {{ $bulletinData['moyenne_annuelle'] !== null ? number_format($bulletinData['moyenne_annuelle'], 2) : '-' }}/20
                                </h1>
                            </div>
                            <div class="col-md-6 text-center">
                                <h4>Mention</h4>
                                <span
                                    class="badge fs-4 bg-{{ $bulletinData['moyenne_annuelle'] >= 16 ? 'success' : ($bulletinData['moyenne_annuelle'] >= 14 ? 'info' : ($bulletinData['moyenne_annuelle'] >= 12 ? 'primary' : ($bulletinData['moyenne_annuelle'] >= 10 ? 'warning' : 'danger'))) }}">
                                    {{ $bulletinData['mention_finale'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                <div class="border rounded p-3 mt-4">
                    <h6>Observations de la Direction des Affaires Académiques :</h6>
                    <div style="min-height: 60px; border-bottom: 1px dashed #ccc;"></div>
                </div>

                <!-- Signatures -->
                <div class="row mt-4">
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature du Formateur Principal</p>
                        <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto;"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature du Parent / Tuteur</p>
                        <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto;"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature de la DAC</p>
                        <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
