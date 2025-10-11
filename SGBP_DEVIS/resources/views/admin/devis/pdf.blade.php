<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $devis->numero }}</title>
    <style>
        @page {
            margin: 20px 28px 50px 28px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

       body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9.5px;
    color: #111;
    line-height: 1.5;
    padding-left: 24px;
    padding-right: 24px;
    padding-top: 20px;
}

        /* ── Bandes latérales (espace) ── */
        .side-bar-left { display: none; }
        .side-bar-right { display: none; }

        /* ── Filigrane ── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-40deg);
            font-size: 85px;
            font-weight: bold;
            opacity: 0.05;
            letter-spacing: 8px;
            white-space: nowrap;
            z-index: 0;
            color: #6b7280;
        }

        /* ── HEADER : titre + logo ── */
        .header-wrap {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 40%;
            text-align: right;
        }
        .doc-type {
            font-size: 22px;
            font-weight: bold;
            color: #059669;
            line-height: 1.1;
        }
        .doc-numero {
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            margin-top: 3px;
        }
        .doc-date {
            font-size: 10px;
            color: #444;
            margin-top: 1px;
        }
        .doc-titre {
            font-size: 12px;
            font-weight: bold;
            color: #065f46;
            background: #d1fae5;
            margin-top: 8px;
            padding: 6px 10px;
            text-align: center;
            border-radius: 2px;
            width: 100%;
            box-sizing: border-box;
        }
        .company-logo {
            max-height: 55px;
            max-width: 160px;
        }

        /* ── ÉMETTEUR / DESTINATAIRE ── */
        .parties-wrap {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .party-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: 16px;
        }
        .party-col:last-child { padding-right: 0; padding-left: 8px; }

        .party-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            border-bottom: 1.5px solid #059669;
            padding-bottom: 2px;
            margin-bottom: 6px;
        }
        .party-row {
            display: table;
            width: 100%;
            margin-bottom: 1px;
        }
        .party-label {
            display: table-cell;
            color: #666;
            font-size: 9px;
            width: 110px;
            vertical-align: top;
        }
        .party-value {
            display: table-cell;
            font-size: 9px;
            color: #111;
            vertical-align: top;
        }
        .party-value.bold { font-weight: bold; }

        /* ── TABLEAU ARTICLES ── */
        .articles-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .articles-table thead th {
            background: #059669;
            color: #fff;
            padding: 5px 7px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #059669;
            text-align: left;
        }
        .articles-table thead th.text-right { text-align: right; }
        .articles-table thead th.text-center { text-align: center; }

        .articles-table tbody tr:nth-child(even) td { background: #f0fdf4; }
        .articles-table tbody td {
            padding: 5px 7px;
            font-size: 9px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

      .cat-row td {
    background: #d1fae5 !important;
    font-weight: bold;
    font-size: 9.5px;
    color: #065f46;
    text-transform: uppercase;
    padding: 5px 7px;
    border: 1px solid #6ee7b7;
    text-align: center; 
}

        .mo-row td {
            font-style: italic;
            color: #555;
            background: #fffbf0 !important;
            border: 1px solid #ddd;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ── TOTAUX ── */
        .totals-wrap {
            display: table;
            width: 100%;
            margin-top: 0;
        }
        .totals-left {
            display: table-cell;
            vertical-align: bottom;
            width: 48%;
            padding-right: 12px;
        }
        .totals-right {
            display: table-cell;
            vertical-align: top;
            width: 52%;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 8px;
            font-size: 9.5px;
            border: 1px solid #ccc;
        }
        .totals-table .tot-label {
            background: #f3f4f6;
            color: #333;
            font-weight: bold;
            width: 55%;
        }
        .totals-table .tot-value {
            text-align: right;
            font-weight: bold;
        }
        .totals-table .net-row td {
            background: #059669;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 8px 10px;
            text-align: center;
        }

        /* ── MONTANT EN LETTRES ── */
        .montant-lettres {
            margin-top: 8px;
            border: 1px solid #059669;
            padding: 5px 10px;
            font-size: 9px;
            background: #f0fdf4;
            border-radius: 2px;
        }
        .montant-lettres span {
            font-weight: bold;
            color: #065f46;
        }

        /* ── CONDITIONS ── */
        .conditions-section {
            margin-top: 14px;
        }
        .conditions-title {
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            border-bottom: 1.5px solid #059669;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }
        .conditions-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        .conditions-label {
            display: table-cell;
            font-weight: bold;
            font-size: 9px;
            color: #333;
            width: 160px;
        }
        .conditions-value {
            display: table-cell;
            font-size: 9px;
            color: #111;
        }

        /* ── NOTES / INTRO / CONCLUSION ── */
        .intro-block, .conclusion-block {
            font-size: 9px;
            color: #444;
            margin-bottom: 8px;
            font-style: italic;
            padding: 4px 0;
        }

        /* ── SIGNATURE ── */
        .signature-wrap {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .sig-empty {
            display: table-cell;
            width: 50%;
        }
        .sig-col {
            display: table-cell;
            width: 50%;
            text-align: right;
            font-size: 9px;
            color: #444;
            padding: 0;
        }
        .sig-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 9.5px;
        }
        .sig-images {
            margin-bottom: 6px;
        }
        .sig-images img {
            max-height: 50px;
            margin-left: 6px;
        }
        .sig-line {
            border-top: 1px solid #aaa;
            padding-top: 4px;
            font-size: 9px;
            color: #555;
            margin-top: 4px;
        }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 7.5px;
            color: #555;
            border-top: 2px solid #059669;
            padding: 4px 20px;
            line-height: 1.5;
            background: #fff;
        }
        .footer-inner { display: table; width: 100%; }
        .footer-left  { display: table-cell; vertical-align: middle; width: 70%; }
        .footer-right { display: table-cell; vertical-align: middle; text-align: right; width: 30%; font-size: 7.5px; color: #888; }
        .pagenum:after { content: counter(page); }
    </style>
</head>
<body>

{{-- ══════ BANDES LATÉRALES (masquées) ══════ --}}
<div class="side-bar-left"></div>
<div class="side-bar-right"></div>

{{-- ══════ FILIGRANE ══════ --}}
@if($devis->statut === 'accepte')
    <div class="watermark" style="color:#059669;">ACCEPTÉ</div>
@elseif($devis->type === 'provisoire')
    <div class="watermark"></div>
@endif

{{-- ══════ EN-TÊTE ══════ --}}
@php
    $moisFr = [
        1  => 'janvier',  2  => 'février',  3  => 'mars',
        4  => 'avril',    5  => 'mai',       6  => 'juin',
        7  => 'juillet',  8  => 'août',      9  => 'septembre',
        10 => 'octobre',  11 => 'novembre',  12 => 'décembre',
    ];
    $dateFormatee = $devis->created_at->day . ' ' . $moisFr[(int)$devis->created_at->format('n')] . ' ' . $devis->created_at->year;
@endphp
<div class="header-wrap">
    <div class="header-left">
        <div class="doc-type">
            @if($devis->type === 'final' && $devis->statut === 'accepte')
                Devis Qualitatif Estimatif
            @elseif($devis->type === 'provisoire')
                DEVIS QUANTITATIF ET ESTIMATIF
            @else
                Devis Définitif
            @endif
        </div>
        <div class="doc-numero">N° {{ $devis->numero }}</div>
        <div class="doc-date">{{ $dateFormatee }}</div>
    </div>
    <div class="header-right">
        @if(isset($parametre) && $parametre->logo_path && file_exists(public_path('storage/' . $parametre->logo_path)))
            <img src="{{ public_path('storage/' . $parametre->logo_path) }}" class="company-logo" alt="Logo">
        @endif
    </div>
</div>

@if($devis->titre)
<div class="doc-titre">Objet : {{ $devis->titre }}</div>
@endif

{{-- ══════ ÉMETTEUR / DESTINATAIRE ══════ --}}
<div class="parties-wrap">

    {{-- Émetteur --}}
    <div class="party-col">
        <div class="party-title">Émetteur</div>

        @if(isset($parametre))
            {{-- 1. Société --}}
            @if($parametre->nom_entreprise)
            <div class="party-row">
                <div class="party-label">Société :</div>
                <div class="party-value bold">{{ $parametre->nom_entreprise }}</div>
            </div>
            @endif
            {{-- 2. NIF --}}
            @if($parametre->niu ?? null)
            <div class="party-row">
                <div class="party-label">NIF :</div>
                <div class="party-value">{{ $parametre->niu }}</div>
            </div>
            @endif
            {{-- 3. Pays --}}
            @if($parametre->pays ?? null)
            <div class="party-row">
                <div class="party-label">Pays :</div>
                <div class="party-value">{{ $parametre->pays }}</div>
            </div>
            @endif
            {{-- 4. Ville --}}
            @if($parametre->ville ?? null)
            <div class="party-row">
                <div class="party-label">Ville :</div>
                <div class="party-value">{{ $parametre->ville }}</div>
            </div>
            @endif
            {{-- 5. Adresse --}}
            @if($parametre->adresse ?? null)
            <div class="party-row">
                <div class="party-label">Adresse :</div>
                <div class="party-value">{{ $parametre->adresse }}</div>
            </div>
            @endif
            {{-- 6. BP --}}
            @if($parametre->rccm ?? null)
            <div class="party-row">
                <div class="party-label">BP :</div>
                <div class="party-value">{{ $parametre->rccm }}</div>
            </div>
            @endif
            {{-- 7. Téléphone --}}
            @if($parametre->telephone ?? null)
            <div class="party-row">
                <div class="party-label">Téléphone :</div>
                <div class="party-value">{{ $parametre->telephone }}</div>
            </div>
            @endif
            {{-- 8. Email --}}
            @if($parametre->email ?? null)
            <div class="party-row">
                <div class="party-label">Email :</div>
                <div class="party-value">{{ $parametre->email }}</div>
            </div>
            @endif
            {{-- 9. Site web --}}
            @if($parametre->site_web ?? null)
            <div class="party-row">
                <div class="party-label">Site internet :</div>
                <div class="party-value">{{ $parametre->site_web }}</div>
            </div>
            @endif
        @endif
    </div>

    {{-- Destinataire --}}
    <div class="party-col">
        <div class="party-title">Destinataire</div>

        @if($devis->client->type === 'societe')
            {{-- CLIENT SOCIÉTÉ --}}
            {{-- 1. Société --}}
            <div class="party-row">
                <div class="party-label">Société :</div>
                <div class="party-value bold">{{ $devis->client->raison_sociale ?? $devis->client->nom_complet }}</div>
            </div>
            {{-- 2. NIF --}}
            @if($devis->client->nif ?? null)
            <div class="party-row">
                <div class="party-label">NIF :</div>
                <div class="party-value">{{ $devis->client->nif }}</div>
            </div>
            @endif
            {{-- 3. Pays --}}
            @if($devis->client->pays ?? null)
            <div class="party-row">
                <div class="party-label">Pays :</div>
                <div class="party-value">{{ $devis->client->pays }}</div>
            </div>
            @endif
            {{-- 4. Ville --}}
            @if($devis->client->ville ?? null)
            <div class="party-row">
                <div class="party-label">Ville :</div>
                <div class="party-value">{{ $devis->client->ville }}</div>
            </div>
            @endif
            {{-- 5. Adresse --}}
            @if($devis->client->adresse ?? null)
            <div class="party-row">
                <div class="party-label">Adresse :</div>
                <div class="party-value">{{ $devis->client->adresse }}</div>
            </div>
            @endif
            {{-- 6. BP --}}
            @if($devis->client->bp ?? null)
            <div class="party-row">
                <div class="party-label">BP :</div>
                <div class="party-value">{{ $devis->client->bp }}</div>
            </div>
            @endif
            {{-- 7. Téléphone --}}
            @if($devis->client->telephone_principal ?? null)
            <div class="party-row">
                <div class="party-label">Téléphone :</div>
                <div class="party-value">{{ $devis->client->telephone_principal }}</div>
            </div>
            @endif
            {{-- 8. Email --}}
            @if($devis->client->email ?? null)
            <div class="party-row">
                <div class="party-label">Email :</div>
                <div class="party-value">{{ $devis->client->email }}</div>
            </div>
            @endif

        @else
            {{-- CLIENT PARTICULIER --}}
            {{-- 1. Nom --}}
            <div class="party-row">
                <div class="party-label">Nom :</div>
                <div class="party-value bold">{{ $devis->client->nom_complet }}</div>
            </div>
            {{-- 2. Pays --}}
            @if($devis->client->pays ?? null)
            <div class="party-row">
                <div class="party-label">Pays :</div>
                <div class="party-value">{{ $devis->client->pays }}</div>
            </div>
            @endif
            {{-- 3. Ville --}}
            @if($devis->client->ville ?? null)
            <div class="party-row">
                <div class="party-label">Ville :</div>
                <div class="party-value">{{ $devis->client->ville }}</div>
            </div>
            @endif
            {{-- 4. Adresse --}}
            @if($devis->client->adresse ?? null)
            <div class="party-row">
                <div class="party-label">Adresse :</div>
                <div class="party-value">{{ $devis->client->adresse }}</div>
            </div>
            @endif
            {{-- 5. Téléphone --}}
            @if($devis->client->telephone_principal ?? null)
            <div class="party-row">
                <div class="party-label">Téléphone :</div>
                <div class="party-value">{{ $devis->client->telephone_principal }}</div>
            </div>
            @endif
            {{-- 6. Email --}}
            @if($devis->client->email ?? null)
            <div class="party-row">
                <div class="party-label">Email :</div>
                <div class="party-value">{{ $devis->client->email }}</div>
            </div>
            @endif
        @endif
    </div>
</div>

{{-- ══════ INTRODUCTION ══════ --}}
@if($devis->introduction)
    <div class="intro-block">{!! nl2br(e($devis->introduction)) !!}</div>
@endif

{{-- ══════ TABLEAU DES ARTICLES ══════ --}}
<table class="articles-table">
    <thead>
        <tr>
            <th style="width:7%;">Type</th>
            <th style="width:37%;">Description</th>
            <th class="text-center" style="width:7%;">Unité</th>
            <th class="text-right" style="width:14%;">Prix unitaire HT</th>
            <th class="text-center" style="width:8%;">Quantité</th>
            <th class="text-right" style="width:7%;">Remise</th>
            <th class="text-right" style="width:14%;">Total HT</th>
        </tr>
    </thead>
    <tbody>

        {{-- Catégories --}}
        @foreach($devis->categories as $cat)
            <tr class="cat-row">
                <td colspan="7">{{ $cat->nom }}</td>
            </tr>
            @foreach($cat->articles as $art)
            <tr>
                <td class="text-center">
                    @if($art->article && $art->article->type === 'service') Service
                    @else Produit @endif
                </td>
                <td>{{ $art->designation }}</td>
                <td class="text-center">{{ $art->unite ?? '—' }}</td>
                <td class="text-right">{{ number_format($art->prix_unitaire_ht, 2, ',', ' ') }} {{ $devis->devise }}</td>
                <td class="text-center">{{ $art->quantite }}</td>
                <td class="text-center">
                    {{ $art->remise_pourcentage > 0 ? number_format($art->remise_pourcentage, 0).'%' : '' }}
                </td>
                <td class="text-right">{{ number_format($art->total_ht, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            @endforeach
            @if($cat->main_oeuvre > 0)
            <tr class="mo-row">
                <td class="text-center">Service</td>
                <td>Main d'Œuvre — {{ $cat->nom }}</td>
                <td class="text-center">—</td>
                <td class="text-right">{{ number_format($cat->main_oeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
                <td class="text-center">1</td>
                <td></td>
                <td class="text-right">{{ number_format($cat->main_oeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            @endif
        @endforeach

        {{-- Articles sans catégorie --}}
        @php $sansCategorie = $devis->articles()->whereNull('devis_category_id')->get(); @endphp
        @if($sansCategorie->count() > 0)
            @if($devis->categories->count() > 0)
            <tr class="cat-row"><td colspan="7">Autres articles</td></tr>
            @endif
            @foreach($sansCategorie as $art)
            <tr>
                <td class="text-center">
                    @if($art->article && $art->article->type === 'service') Service
                    @else Produit @endif
                </td>
                <td>{{ $art->designation }}</td>
                <td class="text-center">{{ $art->unite ?? '—' }}</td>
                <td class="text-right">{{ number_format($art->prix_unitaire_ht, 2, ',', ' ') }} {{ $devis->devise }}</td>
                <td class="text-center">{{ $art->quantite }}</td>
                <td class="text-center">
                    {{ $art->remise_pourcentage > 0 ? number_format($art->remise_pourcentage, 0).'%' : '' }}
                </td>
                <td class="text-right">{{ number_format($art->total_ht, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            @endforeach
        @endif

        {{-- Main d'œuvre globale --}}
        @if($devis->main_oeuvre > 0)
        <tr class="mo-row">
            <td class="text-center">Service</td>
            <td>Main d'Œuvre</td>
            <td class="text-center">—</td>
            <td class="text-right">{{ number_format($devis->main_oeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
            <td class="text-center">1</td>
            <td></td>
            <td class="text-right">{{ number_format($devis->main_oeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
        </tr>
        @endif

    </tbody>
</table>

{{-- ══════ TOTAUX ══════ --}}
@php
    $totalMainOeuvre = $devis->main_oeuvre + $devis->categories->sum('main_oeuvre');
@endphp
<div class="totals-wrap">
    <div class="totals-left">
        <div class="montant-lettres">
            Arrêté le présent devis à la somme de :<br>
            <span>{{ \App\Helpers\NombreEnLettres::convertir($devis->total_ttc, $devis->devise) }}</span>
        </div>
    </div>
    <div class="totals-right">
        <table class="totals-table">
            <tr>
                <td class="tot-label">Total HT</td>
                <td class="tot-value">{{ number_format($devis->total_ht, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            @if($totalMainOeuvre > 0)
            <tr>
                <td class="tot-label">Main d'Œuvre (HT)</td>
                <td class="tot-value">{{ number_format($totalMainOeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            <tr>
                <td class="tot-label" style="color:#059669;">Sous-total HT</td>
                <td class="tot-value" style="color:#059669;">{{ number_format($devis->total_ht + $totalMainOeuvre, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            @endif
            <tr>
                <td class="tot-label">TPS {{ number_format($devis->taux_tps, 2) }}% (sur M.O.)</td>
                <td class="tot-value">{{ number_format($devis->total_tps, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            <tr>
                <td class="tot-label">CSS {{ number_format($devis->taux_css, 2) }}% (sur HT)</td>
                <td class="tot-value">{{ number_format($devis->total_css, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
            <tr class="net-row">
                <td>Total TTC</td>
                <td>{{ number_format($devis->total_ttc, 2, ',', ' ') }} {{ $devis->devise }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- ══════ CONDITIONS ══════ --}}
<div class="conditions-section">
    <div class="conditions-title">Conditions</div>
    <div class="conditions-row">
        <div class="conditions-label">Conditions de règlement :</div>
        <div class="conditions-value">À réception</div>
    </div>
    <div class="conditions-row">
        <div class="conditions-label">Validité du devis :</div>
        <div class="conditions-value">{{ $devis->validite_mois }} mois à compter de la date d'émission</div>
    </div>
    <div class="conditions-row">
        <div class="conditions-label">Devise :</div>
        <div class="conditions-value">{{ $devis->devise }}</div>
    </div>
</div>

{{-- ══════ CONCLUSION ══════ --}}
@if($devis->conclusion)
    <div class="conclusion-block" style="margin-top:8px;">{!! nl2br(e($devis->conclusion)) !!}</div>
@endif

{{-- ══════ SIGNATURE (entreprise uniquement) ══════ --}}
<div class="signature-wrap">
    <div class="sig-empty"></div>
    <div class="sig-col">
        <div class="sig-label">Pour l'entreprise</div>
        <div class="sig-images">
            @if(isset($parametre) && ($parametre->signature_path ?? null) && file_exists(public_path('storage/' . $parametre->signature_path)))
                <img src="{{ public_path('storage/' . $parametre->signature_path) }}" alt="Signature">
            @endif
            @if(isset($parametre) && ($parametre->cachet_path ?? null) && file_exists(public_path('storage/' . $parametre->cachet_path)))
                <img src="{{ public_path('storage/' . $parametre->cachet_path) }}" alt="Cachet">
            @endif
        </div>
        <div class="sig-line">{{ $parametre->signataire_nom ?? ($parametre->nom_entreprise ?? '') }}</div>
        @if(isset($parametre) && ($parametre->signataire_fonction ?? null))
            <div style="font-size:8px; color:#666;">{{ $parametre->signataire_fonction }}</div>
        @endif
    </div>
</div>

{{-- ══════ PIED DE PAGE ══════ --}}
<div class="footer">
    <div class="footer-inner">
        <div class="footer-left">
            @if(isset($parametre))
                <strong>{{ $parametre->nom_entreprise ?? '' }}</strong>
                @if($parametre->adresse ?? null) — {{ $parametre->adresse }}@if($parametre->ville), {{ $parametre->ville }}@endif @endif
                @if($parametre->telephone ?? null) — Tél : {{ $parametre->telephone }}@endif
                @if($parametre->email ?? null) — {{ $parametre->email }}@endif
                @if($parametre->rccm ?? null) — BP : {{ $parametre->rccm }}@endif
            @endif
        </div>
        <div class="footer-right">
            Devis N° {{ $devis->numero }} — Page <span class="pagenum"></span>
        </div>
    </div>
</div>

</body>
</html>