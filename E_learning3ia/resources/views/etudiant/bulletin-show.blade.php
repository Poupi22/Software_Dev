@extends('etudiant.layouts.app')

@section('title', 'Mon Bulletin - Semestre ' . $bulletinData['session']['semestre'])

@section('content')
    <style>
        .bulletin-container {
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 0 auto;
        }

        .bulletin-header {
            background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%);
            color: white;
        }

        .student-photo-large {
            width: 120px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .signature-box {
            border-top: 1px solid #000;
            min-width: 150px;
            text-align: center;
            padding-top: 5px;
        }
    </style>

    <div class="container py-4">
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('etudiant.bulletin.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('etudiant.bulletin.download-pdf', ['programme_session_id' => request('programme_session_id'), 'semestre' => $bulletinData['session']['semestre'], 'type' => 'semestriel']) }}"
                class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i> Télécharger PDF
            </a>
        </div>

        <!-- Bulletin -->
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
                        <h2 class="mb-0">PERFORMANCE SEMESTRIEL</h2>
                        <p class="mb-0">Performance théorique et pratique</p>
                    </div>
                    <div class="col-md-3 text-end">
                        <p class="mb-0"><strong>Année :</strong> {{ $bulletinData['session']['annee_academique'] }}</p>
                        <p class="mb-0"><strong>Semestre :</strong> {{ $bulletinData['session']['semestre'] }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="row">
                    <!-- Informations étudiant -->
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            @if ($bulletinData['etudiant']['photo'])
                                <img src="{{ asset('storage/' . $bulletinData['etudiant']['photo']) }}" alt="Photo"
                                    class="student-photo-large">
                            @else
                                <div class="student-photo-large bg-secondary d-flex align-items-center justify-content-center text-white mx-auto"
                                    style="font-size: 3rem;">
                                    {{ strtoupper(substr($bulletinData['etudiant']['prenom'], 0, 1)) }}{{ strtoupper(substr($bulletinData['etudiant']['nom'], 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="bg-light p-3 rounded">
                            <h6 class="border-bottom pb-2">Mes Informations</h6>
                            <p class="mb-1"><strong>Nom :</strong> {{ $bulletinData['etudiant']['nom'] }}</p>
                            <p class="mb-1"><strong>Prénom :</strong> {{ $bulletinData['etudiant']['prenom'] }}</p>
                            <p class="mb-1"><strong>Date de naissance :</strong>
                                {{ $bulletinData['etudiant']['date_naissance'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Lieu de naissance :</strong>
                                {{ $bulletinData['etudiant']['lieu_naissance'] ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Spécialité :</strong> {{ $bulletinData['formation']['nom'] }}</p>
                            <p class="mb-0"><strong>Matricule :</strong> {{ $bulletinData['etudiant']['matricule'] }}</p>
                        </div>
                    </div>

                    <!-- Tableau des notes -->
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="fas fa-table me-2"></i>Relevé de Notes</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Matière</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Crédit</th>
                                        <th class="text-center">CC ({{ $bulletinData['ponderation']['cc'] }}%)</th>
                                        <th class="text-center">Norm. ({{ $bulletinData['ponderation']['normale'] }}%)</th>
                                        @if ($bulletinData['ponderation']['phase'] == 2)
                                            <th class="text-center">Quiz ({{ $bulletinData['ponderation']['quiz'] }}%)</th>
                                        @endif
                                        <th class="text-center">Moy.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bulletinData['notes'] as $note)
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
                                            @if ($bulletinData['ponderation']['phase'] == 2)
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
                                        <th colspan="{{ $bulletinData['ponderation']['phase'] == 2 ? 6 : 5 }}"
                                            class="text-end">Moyenne Générale :</th>
                                        <th
                                            class="text-center fs-5 {{ $bulletinData['moyenne_generale'] !== null && $bulletinData['moyenne_generale'] >= 10 ? 'text-success' : 'text-danger' }}">
                                            {{ $bulletinData['moyenne_generale'] !== null ? number_format($bulletinData['moyenne_generale'], 2) : '-' }}/20
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="{{ $bulletinData['ponderation']['phase'] == 2 ? 6 : 5 }}"
                                            class="text-end">Mention :</th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-{{ $bulletinData['moyenne_generale'] >= 16 ? 'success' : ($bulletinData['moyenne_generale'] >= 14 ? 'info' : ($bulletinData['moyenne_generale'] >= 12 ? 'primary' : ($bulletinData['moyenne_generale'] >= 10 ? 'warning' : 'danger'))) }} fs-6">
                                                {{ $bulletinData['mention'] }}
                                            </span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Graphiques -->
                        <div class="row mt-4">
                            <div class="col-md-7">
                                <h6>Progression par matière</h6>
                                <canvas id="barChart" height="200"></canvas>
                            </div>
                            <div class="col-md-5">
                                <h6>Mon Assiduité</h6>
                                <canvas id="pieChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h6>Observations de la Direction des Affaires Académiques :</h6>
                            <div style="min-height: 60px; border-bottom: 1px dashed #ccc;"></div>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="row mt-4">
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature du Formateur Principal</p>
                        <div class="signature-box mx-auto"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature du Parent / Tuteur</p>
                        <div class="signature-box mx-auto"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-5">Signature de la DAC</p>
                        <div class="signature-box mx-auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique à barres
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($graphiqueBarres['labels']),
                datasets: @json($graphiqueBarres['datasets'])
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20
                    }
                }
            }
        });

        // Camembert
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($camembertAssiduite['labels']),
                datasets: @json($camembertAssiduite['datasets'])
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
