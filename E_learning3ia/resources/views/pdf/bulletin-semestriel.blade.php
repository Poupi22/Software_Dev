@php
    $path = public_path('acceuille/assets/images/3iA logo-1.png');
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
@endphp

@php
    $bgPath = public_path('acceuille/assets/images/3iA logo-1.png');
    $bgType = pathinfo($bgPath, PATHINFO_EXTENSION);
    $bgData = file_get_contents($bgPath);
    $bgBase64 = 'data:image/' . $bgType . ';base64,' . base64_encode($bgData);
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Semestriel</title>
    <style>
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.1;
            width: 100%;
            height: 100%;
            background: #ffffff url("{{ $bgBase64 }}") no-repeat center center;
            background-size: cover;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 5px;
            line-height: 1.6;
            background-color: #ffffff;
            color: #333;
        }
        .tete {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: none;
        }
        .tete td {
            width: 33.33%;
            text-align: center;
            vertical-align: middle;
            padding: 2px;
            font-size: 10px;
            word-wrap: break-word;
            border: none;
        }
        .tete .content { text-align: center; line-height: 1.1; }
        .tete .content span {
            display: block;
            font-size: 0.9em;
            white-space: nowrap;
            margin: 0;
            padding: 0;
        }
        .tete .logo img { display: block; margin-left: auto; margin-right: auto; }
        .titre { text-align: center; text-transform: uppercase; text-decoration: underline; }
        .details { width: 100%; border-collapse: collapse; border: none; }
        .details td { vertical-align: middle; border: none; padding: 0; text-align: center; }
        .details_R { width: 100%; text-align: center; padding: 0; }
        .inner-details { width: 100%; border-collapse: collapse; border: none; }
        .inner-details td.column { vertical-align: top; text-align: left; padding-bottom: 10px; }
        .inner-details td.left { width: 45%; padding-left: 10px; }
        .inner-details td.right { width: 55%; padding-left: 5px; }
        .inner-details span {
            display: block;
            font-size: 12px;
            margin: 5px;
            padding: 0;
            line-height: 1.2;
            white-space: normal;
            text-transform: uppercase;
        }

        /* ===== MODIFICATION : photo agrandie 50x50 → 90x110 (format identité) ===== */
        .photo img {
            width: 90px;
            height: 110px;
            display: block;
            margin: auto;
            object-fit: cover;
            border: 1px solid #ccc;
        }
        /* La cellule photo prend la largeur de l'image */
        .photo {
            width: 100px;
        }
        /* ===== FIN MODIFICATION ===== */

        .qr-code img { width: 50px; height: 50px; display: block; margin: auto; }

        @media print {
            body { margin: 0; padding: 0; }
            .details, .details td, .inner-details, .inner-details td { page-break-inside: avoid; }
        }
        table { border-spacing: 0; width: 100%; border-collapse: collapse; margin: 0; }
        th, td { border: 1px solid #ccc; padding: 1px; text-align: center; }
        th { background-color: #d8d1d1; }
        .assiduite-box {
            border: 1px solid #ccc;
            padding: 4px 6px;
            margin: 5px 0;
            font-size: 0.85em;
            background-color: #f9f9f9;
        }
        /* Signatures fixes en bas */
        .signatures {
            position: fixed;
            bottom: 55px;
            left: 0;
            right: 0;
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        .signatures table { border: none; border-collapse: collapse; width: 100%; }
        .signatures td { border: none; text-align: center; font-size: 0.9em; vertical-align: top; padding: 0 10px; }
        .ligne-signature {
            margin-top: 38px;
            border-top: 1px solid #555;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .footer { position: fixed; bottom: 1px; left: 0; right: 0; text-align: center; font-size: 11px; }
        .footer h6 { margin: 0; padding: 0; line-height: 1.3; }
        .footer h6 + h6 { margin-top: 2px; }
    </style>
</head>
<body>

{{-- EN-TÊTE --}}
<table class="tete">
    <tr>
        <td class="info">
            <div class="content">
                <span>République du Cameroun - Paix Travail Patrie</span>
                <span>***************</span>
                <span>Ministère de l'Emploi et la Formation Professionnelle</span>
                <span>***************</span>
                <span>Institut de Formation Professionnelle en Ingénierie Informatique Appliquée</span>
                <span>***************</span>
                <span>BP: Dschang-Cameroun</span>
                <span>(+237) 233451090 / 659218936</span>
                <span>***************</span>
                <span>contact@institut3ia.com</span>
                <span>https://institut3ia.com</span>
            </div>
        </td>
        <td class="logo">
            <img src="{{ $base64 }}" width="130" height="90">
        </td>
        <td class="info">
            <div class="content">
                <span>Republic of Cameroon - Peace Work Fatherland</span>
                <span>***************</span>
                <span>Ministry of Employment and Vocational Training</span>
                <span>***************</span>
                <span>Vocational Training Institute of Applied Computer science Engineering</span>
                <span>***************</span>
                <span>P.O. Box: Dschang - Cameroon</span>
                <span>(+237) 233451090 / 659218936</span>
                <span>***************</span>
                <span>contact@institut3ia.com</span>
                <span>https://institut3ia.com</span>
            </div>
        </td>
    </tr>
</table>

<div style="text-transform: uppercase; color: rgb(0,0,0); text-align: center; font-weight: bold;">
    <span>ARRÊTEE/ORDER N°000366 /MINEFOP/SG/DFOP/SDGSF/CSACD/CBAC du/of 10/06/2025</span>
</div>

<hr style="border: 3px solid rgb(88, 83, 83); margin: 0;">

<div class="titre">
    <h2>Relevé de Notes &mdash; Semestre {{ $bulletinData['session']['semestre'] }}</h2>
</div>

{{-- INFOS ÉTUDIANT --}}
<table class="details">
    <tr>
        <td class="photo">
            @if (!empty($bulletinData['etudiant']['photo']))
                @php
                    $photoPath = public_path('storage/' . $bulletinData['etudiant']['photo']);
                    $photoType = pathinfo($photoPath, PATHINFO_EXTENSION);
                    $photoData = file_get_contents($photoPath);
                    $photoBase64 = 'data:image/' . $photoType . ';base64,' . base64_encode($photoData);
                @endphp
                <img src="{{ $photoBase64 }}" alt="Photo étudiant">
            @else
                @php
                    $avatarPath = public_path('assets/img/icons/spot-illustrations/avatar.png');
                    $avatarData = file_get_contents($avatarPath);
                    $avatarBase64 = 'data:image/png;base64,' . base64_encode($avatarData);
                @endphp
                <img src="{{ $avatarBase64 }}" alt="Photo étudiant">
            @endif
        </td>
        <td class="details_R">
            <table class="inner-details">
                <tr>
                    <td class="column left">
                        <span>NOMS et PRENOMS :
                            <strong>{{ $bulletinData['etudiant']['nom'] }} {{ $bulletinData['etudiant']['prenom'] }}</strong>
                        </span>
                        <span>Né(e) le :
                            <strong>{{ $bulletinData['etudiant']['date_naissance'] ?? '—' }}</strong>
                            à <strong>{{ $bulletinData['etudiant']['lieu_naissance'] ?? '—' }}</strong>
                        </span>
                        <span>Matricule : <strong>{{ $bulletinData['etudiant']['matricule'] }}</strong></span>
                    </td>
                    <td class="column right">
                        <span>Spécialité : <strong>{{ $bulletinData['formation']['nom'] }}</strong></span>
                        <span>Cursus : <strong>{{ $bulletinData['formation']['qualification'] }}</strong></span>
                        <span>Semestre : <strong>{{ $bulletinData['session']['semestre'] }}</strong></span>
                        <span>Année Académique : <strong>{{ $bulletinData['session']['annee_academique'] }}</strong></span>
                    </td>
                </tr>
            </table>
        </td>
        @if($qrBase64)
            <td class="qr-code">
                <img src="{{ $qrBase64 }}" width="50" height="50">
            </td>
        @endif
    </tr>
</table>

{{-- TABLEAU DES NOTES --}}
<table>
    <thead>
        <tr>
            <th style="font-size:0.9em">CODE</th>
            <th style="font-size:0.9em">INTITULÉ DE L'UNITÉ D'ENSEIGNEMENT</th>
            <th style="font-size:0.9em">CRÉDITS</th>
            <th style="font-size:0.9em">NOTE CC /20</th>
            <th style="font-size:0.9em">NOTE EX /20</th>
            <th style="font-size:0.9em">NOTE FINALE /20</th>
            <th style="font-size:0.9em">MENTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bulletinData['notes'] as $note)
            <tr>
                <td style="font-size:0.7em">{{ $note['matiere_code'] }}</td>
                <td style="font-size:0.7em; text-align:left; padding-left:4px;">{{ $note['matiere_nom'] }}</td>
                <td style="font-size:0.7em">{{ $note['credit'] }}</td>
                <td style="font-size:0.7em">
                    {{ $note['note_cc'] !== null ? number_format($note['note_cc'], 2) : '/' }}
                </td>
                <td style="font-size:0.7em">
                    {{ $note['note_normale'] !== null ? number_format($note['note_normale'], 2) : '/' }}
                </td>
                <td style="font-size:0.7em">
                    {{ $note['note_finale'] !== null ? number_format($note['note_finale'], 2) : '/' }}
                </td>
                <td style="font-size:0.7em">
                    @if ($note['note_finale'] !== null)
                        @if ($note['note_finale'] >= 16) Très Bien
                        @elseif ($note['note_finale'] >= 14) Bien
                        @elseif ($note['note_finale'] >= 12) Assez Bien
                        @elseif ($note['note_finale'] >= 10) Passable
                        @else Insuffisant
                        @endif
                    @else /
                    @endif
                </td>
            </tr>
        @endforeach

        {{-- Bilan --}}
        <tr>
            <td colspan="2" style="font-size:0.7em; text-align:left; padding-left:4px;">
                Mention : <strong>{{ $bulletinData['mention'] }}</strong>
            </td>
            <td style="font-size:0.7em">
                @php
                    $creditsCapitalises = collect($bulletinData['notes'])
                        ->filter(fn($n) => $n['note_finale'] !== null && $n['note_finale'] >= 10)
                        ->sum('credit');
                @endphp
                Crédits cap. : <strong>{{ $creditsCapitalises }}</strong>
            </td>
            <td colspan="4" style="font-size:0.7em">
                Moyenne Générale :
                <strong>
                    {{ $bulletinData['moyenne_generale'] !== null
                        ? number_format($bulletinData['moyenne_generale'], 2) . '/20'
                        : '—' }}
                </strong>
            </td>
        </tr>
    </tbody>
</table>

{{-- ASSIDUITÉ --}}
<div class="assiduite-box">
    <strong>ASSIDUITÉ :</strong>
    &nbsp; Présence : <strong>{{ $bulletinData['assiduite']['presence'] }}%</strong>
    &nbsp;|&nbsp;
    Absence : <strong>{{ $bulletinData['assiduite']['absence'] }}%</strong>
</div>

{{-- Date --}}
<div style="text-align: right; font-size: 1em; margin-top: 8px;">
    <p>Fait à Dschang le : <strong>..........................</strong></p>
</div>

{{-- SIGNATURES FIXES EN BAS --}}
<div class="signatures">
    <table style="border: none; border-collapse: collapse; width: 100%;">
        <tr>
            <td style="border: none;">
                <div style="display: inline-block; width: 100%; text-align: justify;">
                    <span style="font-size: 1em;">Directeur des Affaires Académiques</span>
                    <span style="font-size: 1em; float: right;">Le Directeur</span>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- PIED DE PAGE FIXE --}}
<div class="footer">
    <h6>Il n'est établi qu'un seul relevé de notes. Le titulaire peut établir et faire certifier des copies conformes</h6>
    <h6>Only one copy of the transcript is issued. The holder can reproduce and obtain certified copies.</h6>
</div>

</body>
</html>
