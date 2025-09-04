<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du Relevé de Notes — {{ $student->name }} {{ $student->prenom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }

        /* Couleurs institutionnelles */
        :root {
            --bleu-3ia: #1e3a8a;
            --vert-minefop: #166534;
            --or: #b45309;
        }

        /* En-tête officiel */
        .header-officiel {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #166534 100%);
        }

        /* Badge de vérification */
        .badge-verifie {
            background: linear-gradient(135deg, #16a34a, #15803d);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid rgba(209,213,219,0.4);
        }

        /* Section title */
        .section-title {
            position: relative;
            padding-left: 1rem;
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: 0; top: 0;
            height: 100%; width: 4px;
            background: linear-gradient(180deg, #1e3a8a, #166534);
            border-radius: 2px;
        }

        /* Tableau des notes */
        .table-notes thead th {
            background-color: #1e3a8a;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
        }
        .table-notes tbody tr:nth-child(even) { background-color: #f8fafc; }
        .table-notes tbody tr:hover { background-color: #eff6ff; }
        .table-notes tbody td { padding: 0.65rem 1rem; font-size: 0.875rem; border-bottom: 1px solid #e5e7eb; }

        /* Badges statut */
        .badge-admis    { background:#dcfce7; color:#166534; }
        .badge-rattrapage { background:#fef9c3; color:#854d0e; }
        .badge-ajourne  { background:#fee2e2; color:#991b1b; }
        .badge-nonevalue { background:#f3f4f6; color:#6b7280; }

        /* Carte MINEFOP */
        .minefop-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #166534;
        }
        .cqp-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #1e3a8a;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 6rem;
            font-weight: 900;
            color: rgba(30, 58, 138, 0.04);
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        /* Print */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card { box-shadow: none; border: 1px solid #e5e7eb; }
            .watermark { color: rgba(30,58,138,0.06); }
        }

        /* Stat cards */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        /* Séparateur décoratif */
        .divider-ornament {
            display: flex; align-items: center; gap: 1rem;
        }
        .divider-ornament::before, .divider-ornament::after {
            content: ''; flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #1e3a8a, transparent);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Filigrane -->
    <div class="watermark">OFFICIEL</div>

    <!-- ═══════════════════════════════════════════════════════════
         EN-TÊTE OFFICIEL
    ═══════════════════════════════════════════════════════════ -->
    <header class="header-officiel text-white shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                <!-- Logo 3iA + Titre -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg p-1">
                        <img src="{{ asset('acceuille/assets/images/3ia logo-01 1.png') }}"
                             alt="Logo 3iA" class="w-full h-full object-contain"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div style="display:none" class="w-full h-full items-center justify-center">
                            <span class="text-blue-800 font-black text-lg">3iA</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-wide">Institut 3iA</h1>
                        <p class="text-blue-200 text-sm">Ingénierie Informatique Appliquée</p>
                        <p class="text-blue-300 text-xs">Agréé par le MINEFOP</p>
                    </div>
                </div>

                <!-- Titre central -->
                <div class="text-center">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-xl px-6 py-3 border border-white/20">
                        <i class="fas fa-shield-check text-green-300 text-xl"></i>
                        <div>
                            <p class="text-xs text-blue-200 uppercase tracking-widest font-semibold">Document Officiel</p>
                            <h2 class="text-lg font-black">RELEVÉ DE NOTES</h2>
                            <p class="text-xs text-blue-200">Vérification par QR Code</p>
                        </div>
                    </div>
                </div>

                <!-- Logo MINEFOP + Badge -->
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="inline-flex items-center gap-2 bg-green-800/50 rounded-xl px-4 py-2 border border-green-400/30">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-landmark text-white text-lg"></i>
                            </div>
                            <div>
                                <p class="text-green-200 text-xs font-bold uppercase">MINEFOP</p>
                                <p class="text-white text-xs">Cameroun</p>
                            </div>
                        </div>
                    </div>
                    <!-- Badge vérifié -->
                    <div class="badge-verifie rounded-full px-4 py-2 flex items-center gap-2 shadow-lg">
                        <i class="fas fa-check-circle text-white"></i>
                        <span class="text-white text-sm font-bold">VÉRIFIÉ</span>
                    </div>
                </div>
            </div>

            <!-- Barre d'info de vérification -->
            <div class="mt-4 bg-white/10 rounded-lg px-4 py-2 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm border border-white/10">
                <div class="flex items-center gap-2 text-blue-100">
                    <i class="fas fa-qrcode text-green-300"></i>
                    <span>Document consulté via QR Code le <strong>{{ now()->format('d/m/Y à H:i') }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-blue-100">
                    <i class="fas fa-link text-blue-300"></i>
                    <span class="text-xs font-mono truncate max-w-xs">{{ $pageUrl ?? request()->url() }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">

        @if(isset($error))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            <p class="text-red-700">{{ $error }}</p>
        </div>
        @endif

        <!-- ═══════════════════════════════════════════════════════════
             SECTION 1 — PROFIL ÉTUDIANT
        ═══════════════════════════════════════════════════════════ -->
        <div class="card p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Avatar + Identité -->
                <div class="flex items-start gap-5 flex-1">
                    <div class="relative flex-shrink-0">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center shadow-md border-4 border-white">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <i class="fas fa-user-graduate text-4xl text-blue-600"></i>
                            @endif
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-7 h-7 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap mb-1">
                            <h2 class="text-2xl font-black text-gray-900">
                                {{ strtoupper($student->name) }} {{ $student->prenom }}
                            </h2>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-id-card mr-1"></i>{{ $student->matricule ?? 'N/A' }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm mb-3">
                            <i class="fas fa-graduation-cap mr-1 text-blue-500"></i>
                            {{ $student->inscriptions->first()?->programmeSession?->programme?->formation?->nom ?? 'Formation non définie' }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <p class="text-xs text-gray-500">Qualification</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $student->inscriptions->first()?->programmeSession?->programme?->qualification?->nom ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <p class="text-xs text-gray-500">Année Académique</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $student->inscriptions->first()?->programmeSession?->anneeAcademique?->libelle ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-2 text-center">
                                <p class="text-xs text-gray-500">Durée</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $student->inscriptions->first()?->programmeSession?->programme?->duree ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="flex flex-col items-center justify-center border-l border-gray-100 pl-6 flex-shrink-0">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" alt="QR Code de vérification" class="w-28 h-28 rounded-lg shadow-md border-2 border-gray-200">
                    @else
                        <div class="w-28 h-28 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                            <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    <p class="text-xs text-gray-400 mt-2 text-center">Scanner pour vérifier</p>
                    <p class="text-xs text-gray-500 font-semibold text-center">{{ $student->matricule }}</p>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════
             SECTION 2 — INFORMATIONS PERSONNELLES & ACADÉMIQUES
        ═══════════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- Infos personnelles -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 section-title">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Informations Personnelles
                </h3>
                <div class="space-y-3">
                    @php
                        $infosPerso = [
                            ['label' => 'Date de Naissance',  'icon' => 'fa-calendar',    'value' => $student->date_naissance ?? null],
                            ['label' => 'Lieu de Naissance',  'icon' => 'fa-map-marker-alt','value' => $student->lieu_naissance ?? null],
                            ['label' => 'Sexe',               'icon' => 'fa-venus-mars',   'value' => $student->sexe ?? null],
                            ['label' => 'Nationalité',        'icon' => 'fa-flag',         'value' => $student->nationalite ?? null],
                            ['label' => 'Téléphone',          'icon' => 'fa-phone',        'value' => $student->tel1 ?? null],
                            ['label' => 'Email',              'icon' => 'fa-envelope',     'value' => $student->email ?? null],
                            ['label' => 'Ville',              'icon' => 'fa-city',         'value' => $student->ville ?? null],
                        ];
                    @endphp
                    @foreach($infosPerso as $info)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                        <span class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fas {{ $info['icon'] }} w-4 text-blue-400"></i>
                            {{ $info['label'] }}
                        </span>
                        <span class="font-medium text-gray-800 text-sm text-right">
                            {{ $info['value'] ?? '<span class="text-gray-400 italic text-xs">Non renseigné</span>' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Infos académiques + Stats -->
            <div class="space-y-4">
                <!-- Infos académiques -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 section-title">
                        <i class="fas fa-university mr-2 text-blue-600"></i>Informations Académiques
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500 text-sm flex items-center gap-2"><i class="fas fa-book w-4 text-blue-400"></i>Formation</span>
                            <span class="font-semibold text-gray-800 text-sm text-right">{{ $student->inscriptions->first()?->programmeSession?->programme?->formation?->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500 text-sm flex items-center gap-2"><i class="fas fa-certificate w-4 text-blue-400"></i>Qualification</span>
                            <span class="font-semibold text-gray-800 text-sm text-right">{{ $student->inscriptions->first()?->programmeSession?->programme?->qualification?->nom ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500 text-sm flex items-center gap-2"><i class="fas fa-calendar-alt w-4 text-blue-400"></i>Année Académique</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ $student->inscriptions->first()?->programmeSession?->anneeAcademique?->libelle ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500 text-sm flex items-center gap-2"><i class="fas fa-clock w-4 text-blue-400"></i>Durée de la formation</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ $student->inscriptions->first()?->programmeSession?->programme?->duree ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistiques globales -->
                @if(isset($stats))
                <div class="card p-5">
                    <h3 class="text-base font-bold text-gray-800 mb-3 section-title">
                        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Résultats Globaux
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-3 text-center border border-blue-200">
                            <p class="text-2xl font-black text-blue-800">
                                {{ $stats['moyenne_generale'] !== null ? $stats['moyenne_generale'] . '/20' : 'N/A' }}
                            </p>
                            <p class="text-xs text-blue-600 font-medium">Moyenne Générale</p>
                            @if($stats['moyenne_generale'] !== null)
                            <span class="text-xs font-bold text-blue-700 bg-blue-200 px-2 py-0.5 rounded-full">
                                {{ $stats['mention_generale'] }}
                            </span>
                            @endif
                        </div>
                        <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-3 text-center border border-green-200">
                            <p class="text-2xl font-black text-green-800">{{ $stats['credits_obtenus'] }}/{{ $stats['total_credits'] }}</p>
                            <p class="text-xs text-green-600 font-medium">Crédits Obtenus</p>
                        </div>
                        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-3 text-center border border-purple-200">
                            <p class="text-2xl font-black text-purple-800">{{ $stats['matieres_admises'] }}/{{ $stats['matieres_evaluees'] }}</p>
                            <p class="text-xs text-purple-600 font-medium">Matières Admises</p>
                        </div>
                        <div class="stat-card bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-3 text-center border border-amber-200">
                            @php
                                $pct = $stats['total_credits'] > 0 ? round(($stats['credits_obtenus'] / $stats['total_credits']) * 100) : 0;
                            @endphp
                            <p class="text-2xl font-black text-amber-800">{{ $pct }}%</p>
                            <p class="text-xs text-amber-600 font-medium">Taux de Réussite</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════
             SECTION 3 — TABLEAU DES NOTES PAR TRIMESTRE
        ═══════════════════════════════════════════════════════════ -->
        <div class="card mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex items-center justify-between">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-table"></i> Relevé de Notes Détaillé
                </h3>
                <span class="text-blue-200 text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Notes sur 20 — Pondération : {{ config('bulletin.include_quiz_online') ? '56% Exam + 24% CC + 20% Quiz' : '70% Exam + 30% CC' }}
                </span>
            </div>

            @if(empty($matieres))
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune note disponible pour cet étudiant.</p>
                </div>
            @else
                @foreach($matieres as $trimestre => $matieresListe)
                <div class="mb-0">
                    <!-- En-tête trimestre -->
                    <div class="bg-blue-50 border-b border-blue-200 px-6 py-3 flex items-center justify-between">
                        <h4 class="font-bold text-blue-900 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-600"></i>
                            Trimestre {{ $trimestre }}
                        </h4>
                        @php
                            $moyTrimestre = collect($matieresListe)->whereNotNull('moyenne')->avg('moyenne');
                        @endphp
                        @if($moyTrimestre)
                        <span class="text-sm font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                            Moyenne du trimestre : {{ round($moyTrimestre, 2) }}/20
                        </span>
                        @endif
                    </div>

                    <!-- Tableau -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-notes">
                            <thead>
                                <tr>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Matière</th>
                                    <th class="text-left">Formateur</th>
                                    <th class="text-center">Crédits</th>
                                    <th class="text-center">Note CC</th>
                                    <th class="text-center">Note Exam</th>
                                    @if(config('bulletin.include_quiz_online'))
                                    <th class="text-center">Quiz</th>
                                    @endif
                                    <th class="text-center">Moyenne</th>
                                    <th class="text-center">Mention</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matieresListe as $matiere)
                                <tr>
                                    <td class="font-mono text-xs text-gray-500">{{ $matiere['code'] ?: '—' }}</td>
                                    <td class="font-semibold text-gray-800">{{ $matiere['nom'] }}</td>
                                    <td class="text-gray-600 text-xs">{{ $matiere['formateur'] }}</td>
                                    <td class="text-center">
                                        <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2 py-0.5 rounded-full">
                                            {{ $matiere['credit'] }}
                                        </span>
                                    </td>
                                    <td class="text-center font-medium">
                                        {{ $matiere['note_cc'] !== null ? number_format($matiere['note_cc'], 2) : '—' }}
                                    </td>
                                    <td class="text-center font-medium">
                                        {{ $matiere['note_normale'] !== null ? number_format($matiere['note_normale'], 2) : '—' }}
                                    </td>
                                    @if(config('bulletin.include_quiz_online'))
                                    <td class="text-center font-medium">
                                        {{ $matiere['note_quiz'] !== null ? number_format($matiere['note_quiz'], 2) : '—' }}
                                    </td>
                                    @endif
                                    <td class="text-center">
                                        @if($matiere['moyenne'] !== null)
                                            <span class="font-black text-lg {{ $matiere['moyenne'] >= 10 ? 'text-green-700' : ($matiere['moyenne'] >= 8 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ number_format($matiere['moyenne'], 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                            {{ $matiere['mention'] === 'Très Bien' ? 'bg-blue-100 text-blue-800' :
                                               ($matiere['mention'] === 'Bien' ? 'bg-green-100 text-green-800' :
                                               ($matiere['mention'] === 'Assez Bien' ? 'bg-teal-100 text-teal-800' :
                                               ($matiere['mention'] === 'Passable' ? 'bg-yellow-100 text-yellow-800' :
                                               ($matiere['mention'] === '-' ? 'bg-gray-100 text-gray-500' :
                                               'bg-red-100 text-red-800')))) }}">
                                            {{ $matiere['mention'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-xs font-bold px-2 py-1 rounded-full
                                            {{ $matiere['statut'] === 'Admis' ? 'badge-admis' :
                                               ($matiere['statut'] === 'Rattrapage' ? 'badge-rattrapage' :
                                               ($matiere['statut'] === 'Non évalué' ? 'badge-nonevalue' :
                                               'badge-ajourne')) }}">
                                            {{ $matiere['statut'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                <!-- Récapitulatif final -->
                @if(isset($stats) && $stats['moyenne_generale'] !== null)
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-3 text-white">
                        <i class="fas fa-trophy text-yellow-400 text-xl"></i>
                        <div>
                            <p class="text-blue-200 text-xs uppercase tracking-wider">Résultat Final</p>
                            <p class="font-black text-xl">Moyenne Générale : {{ $stats['moyenne_generale'] }}/20</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-yellow-400 text-yellow-900 font-black px-4 py-2 rounded-xl text-lg shadow">
                            {{ $stats['mention_generale'] }}
                        </span>
                        <span class="bg-white/20 text-white font-semibold px-4 py-2 rounded-xl text-sm">
                            {{ $stats['credits_obtenus'] }}/{{ $stats['total_credits'] }} crédits
                        </span>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <!-- ═══════════════════════════════════════════════════════════
             SECTION 4 — DOCUMENTATION CQP & MINEFOP
        ═══════════════════════════════════════════════════════════ -->
        <div class="divider-ornament my-8">
            <span class="text-gray-500 text-sm font-semibold uppercase tracking-widest px-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Informations Officielles
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- CQP -->
            <div class="cqp-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 bg-blue-800 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-certificate text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-blue-900">CQP</h3>
                        <p class="text-blue-700 text-sm font-medium">Certificat de Qualification Professionnelle</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white/70 rounded-xl p-4 border border-blue-200">
                        <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-question-circle text-blue-600"></i> Qu'est-ce que le CQP ?
                        </h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            Le <strong>Certificat de Qualification Professionnelle (CQP)</strong> est une certification
                            professionnelle reconnue par l'État camerounais, délivrée sous l'autorité du
                            <strong>Ministère de l'Emploi et de la Formation Professionnelle (MINEFOP)</strong>.
                            Il atteste de la maîtrise d'un ensemble de compétences techniques et pratiques
                            correspondant à un métier ou une activité professionnelle précise.
                        </p>
                    </div>

                    <div class="bg-white/70 rounded-xl p-4 border border-blue-200">
                        <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-star text-yellow-500"></i> Valeur & Reconnaissance
                        </h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                Certification <strong>officielle reconnue</strong> par l'État du Cameroun
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                Valeur sur le <strong>marché du travail national et sous-régional</strong>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                Atteste de compétences <strong>pratiques et opérationnelles</strong>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                Facilite l'<strong>insertion professionnelle</strong> et l'entrepreneuriat
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white/70 rounded-xl p-4 border border-blue-200">
                        <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-blue-600"></i> Conditions d'Obtention
                        </h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-arrow-right text-blue-500 mt-0.5 flex-shrink-0"></i>
                                Avoir suivi l'intégralité de la formation agréée
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-arrow-right text-blue-500 mt-0.5 flex-shrink-0"></i>
                                Obtenir une <strong>moyenne générale ≥ 10/20</strong>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-arrow-right text-blue-500 mt-0.5 flex-shrink-0"></i>
                                Satisfaire aux exigences d'<strong>assiduité</strong> (≥ 80%)
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-arrow-right text-blue-500 mt-0.5 flex-shrink-0"></i>
                                Réussir les évaluations pratiques et théoriques
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- MINEFOP -->
            <div class="minefop-card rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 bg-green-800 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-landmark text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-green-900">MINEFOP</h3>
                        <p class="text-green-700 text-sm font-medium">Ministère de l'Emploi et de la Formation Professionnelle</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white/70 rounded-xl p-4 border border-green-200">
                        <h4 class="font-bold text-green-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-building-columns text-green-600"></i> Présentation
                        </h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            Le <strong>Ministère de l'Emploi et de la Formation Professionnelle (MINEFOP)</strong>
                            est l'institution gouvernementale camerounaise chargée de la définition, de la mise en
                            œuvre et du suivi de la politique nationale en matière d'emploi et de formation
                            professionnelle. Il est le garant de la qualité et de la reconnaissance des certifications
                            professionnelles sur le territoire national.
                        </p>
                    </div>

                    <div class="bg-white/70 rounded-xl p-4 border border-green-200">
                        <h4 class="font-bold text-green-900 mb-2 flex items-center gap-2">
                            <i class="fas fa-tasks text-green-600"></i> Missions Principales
                        </h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <strong>Agrément</strong> des centres de formation professionnelle
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <strong>Délivrance</strong> et homologation des certifications professionnelles
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <strong>Promotion</strong> de l'emploi et insertion des jeunes
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <strong>Contrôle</strong> de la qualité des formations dispensées
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                <strong>Lutte</strong> contre le chômage et développement des compétences
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white/70 rounded-xl p-4 border border-green-200">
                        <h4 class="font-bold text-green-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-address-card text-green-600"></i> Coordonnées Officielles
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-green-600 w-4"></i>
                                <span class="text-gray-700">Yaoundé, Cameroun — Quartier Bastos</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-green-600 w-4"></i>
                                <span class="text-gray-700">+237 222 22 30 38</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-green-600 w-4"></i>
                                <span class="text-gray-700">contact@minefop.cm</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-globe text-green-600 w-4"></i>
                                <a href="https://www.minefop.cm" target="_blank"
                                   class="text-green-700 font-semibold hover:underline">
                                    www.minefop.cm
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Accréditation 3iA -->
                    <div class="bg-green-800 rounded-xl p-4 text-white">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-award text-yellow-400 text-2xl flex-shrink-0"></i>
                            <div>
                                <p class="font-bold text-sm">Accréditation de l'Institut 3iA</p>
                                <p class="text-green-200 text-xs mt-1">
                                    L'Institut 3iA — Ingénierie Informatique Appliquée est un centre de formation
                                    professionnelle <strong class="text-white">agréé par le MINEFOP</strong>,
                                    habilité à délivrer des Certificats de Qualification Professionnelle (CQP)
                                    dans le domaine des technologies de l'information et de la communication.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════
             SECTION 5 — BANDEAU DE VÉRIFICATION OFFICIELLE
        ═══════════════════════════════════════════════════════════ -->
        <div class="bg-gradient-to-r from-green-800 to-green-700 rounded-2xl p-6 shadow-xl mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-shield-check text-green-700 text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <h4 class="font-black text-lg">Document Authentique et Vérifié</h4>
                        <p class="text-green-200 text-sm">
                            Ce relevé de notes a été généré automatiquement suite au scan du QR Code
                            imprimé sur le document officiel de l'étudiant.
                        </p>
                        <p class="text-green-300 text-xs mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            Consulté le {{ now()->format('d/m/Y à H:i:s') }} (UTC+1)
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" alt="QR Code" class="w-20 h-20 rounded-lg border-2 border-white/30 shadow">
                    @endif
                    <p class="text-green-200 text-xs text-center">QR Code de vérification</p>
                    <p class="text-white text-xs font-mono font-bold">{{ $student->matricule }}</p>
                </div>
            </div>

            <!-- URL de vérification -->
            <div class="mt-4 bg-white/10 rounded-xl px-4 py-3 border border-white/20">
                <p class="text-green-200 text-xs mb-1 uppercase tracking-wider font-semibold">
                    <i class="fas fa-link mr-1"></i>URL de vérification officielle
                </p>
                <p class="text-white font-mono text-sm break-all">{{ $pageUrl ?? request()->url() }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-400 text-xs py-4 no-print">
            <p>
                <i class="fas fa-copyright mr-1"></i>
                {{ date('Y') }} Institut 3iA — Ingénierie Informatique Appliquée |
                Agréé par le MINEFOP — Cameroun
            </p>
            <p class="mt-1">
                <a href="{{ route('acceuil.index') }}" class="text-blue-400 hover:text-blue-600">
                    <i class="fas fa-home mr-1"></i>Retour à l'accueil
                </a>
            </p>
        </div>

    </main>

    <!-- Bouton Imprimer -->
    <div class="fixed bottom-6 right-6 no-print z-50">
        <button onclick="window.print()"
                class="bg-blue-800 hover:bg-blue-900 text-white font-bold px-5 py-3 rounded-full shadow-2xl flex items-center gap-2 transition-all hover:scale-105">
            <i class="fas fa-print"></i>
            <span class="hidden sm:inline">Imprimer</span>
        </button>
    </div>

</body>
</html>
