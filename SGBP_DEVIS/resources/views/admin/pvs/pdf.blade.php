<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $pv->numero }}</title>
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
        .watermark-brouillon {
            color: #dc2626;
            opacity: 0.10;
            font-size: 90px;
        }

        /* ── HEADER ── */
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

        /* ── TITRE CENTRAL ── */
        .title-box {
            border: 2px solid #059669;
            text-align: center;
            padding: 8px 15px;
            margin: 14px 40px;
            background: #f0fdf4;
        }
        .title-box h1 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #065f46;
            margin: 0;
        }
        .pv-numero {
            text-align: center;
            font-size: 10px;
            margin-top: 4px;
            color: #059669;
            font-weight: bold;
        }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            border-bottom: 1.5px solid #059669;
            padding-bottom: 2px;
            margin: 12px 0 6px 0;
        }

        /* ── CORPS DU TEXTE ── */
        .body-text {
            font-size: 9.5px;
            line-height: 1.7;
            margin: 8px 0;
            text-align: justify;
        }
        .body-text .bold { font-weight: bold; }
        .body-text .underline { text-decoration: underline; }
        .indent { padding-left: 20px; }

        /* ── RÉFÉRENCES ── */
        .refs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .refs-table td {
            padding: 4px 8px;
            font-size: 9px;
            border: 1px solid #ccc;
        }
        .refs-table .ref-label {
            background: #f3f4f6;
            font-weight: bold;
            color: #333;
            width: 35%;
        }
        .refs-table .ref-value {
            color: #111;
        }
        .refs-table .ref-value.bold { font-weight: bold; }

        /* ── CHECKBOXES ── */
        .checkbox-section { margin: 10px 0; }
        .checkbox-line {
            margin: 8px 0;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            border-radius: 2px;
            background: #fafafa;
        }
        .checkbox-line.checked {
            background: #f0fdf4;
            border-color: #6ee7b7;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1.5px solid #333;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 6px;
        }
        .checkbox-checked {
            background-color: #059669;
            color: #fff;
            border-color: #059669;
        }

        /* ── OBSERVATIONS ── */
        .observations-block {
            font-size: 9px;
            color: #444;
            margin: 8px 0;
            font-style: italic;
            padding: 6px 10px;
            border-left: 3px solid #059669;
            background: #f0fdf4;
        }

        /* ── FAIT À ── */
        .fait-a {
            margin-top: 14px;
            font-size: 9.5px;
        }
        .exemplaires {
            margin-top: 3px;
            font-size: 9px;
            color: #555;
        }

        /* ── SIGNATURES ── */
        .signature-wrap {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .sig-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 9px;
            color: #444;
            padding: 0 8px;
            vertical-align: top;
        }
        .sig-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 9.5px;
            color: #059669;
            border-bottom: 1px solid #059669;
            padding-bottom: 3px;
        }
        .sig-images { margin-bottom: 6px; min-height: 60px; }
        .sig-images img { max-height: 50px; max-width: 120px; }
        .sig-name {
            font-size: 9px;
            font-weight: bold;
            margin-top: 4px;
        }
        .sig-fonction {
            font-size: 8px;
            color: #555;
        }
        .sig-date {
            font-size: 8px;
            color: #777;
            margin-top: 3px;
        }

        /* ── SÉPARATEUR ── */
        .separator {
            border: none;
            border-top: 1px solid #d1fae5;
            margin: 10px 0;
        }

        /* ── MENTION LÉGALE ── */
        .mention-legale {
            font-size: 8.5px;
            color: #555;
            font-style: italic;
            padding: 5px 8px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            margin-top: 10px;
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

    {{-- ══════ FILIGRANE ══════ --}}
    @if($pv->statut === 'brouillon')
        <div class="watermark watermark-brouillon">BROUILLON</div>
    @elseif($pv->statut === 'archive')
        <div class="watermark">ARCHIVÉ</div>
    @endif

    {{-- ══════ EN-TÊTE ══════ --}}
    @php
        $moisFr = [
            1  => 'janvier',  2  => 'février',  3  => 'mars',
            4  => 'avril',    5  => 'mai',       6  => 'juin',
            7  => 'juillet',  8  => 'août',      9  => 'septembre',
            10 => 'octobre',  11 => 'novembre',  12 => 'décembre',
        ];
        $dateRef = $pv->date_reception ?? $pv->created_at;
        $dateFormatee = $dateRef->day . ' ' . $moisFr[(int)$dateRef->format('n')] . ' ' . $dateRef->year;
    @endphp
    <div class="header-wrap">
        <div class="header-left">
            @if($pv->client && $pv->client->logo_path && file_exists(public_path('storage/' . $pv->client->logo_path)))
                <img src="{{ public_path('storage/' . $pv->client->logo_path) }}" class="company-logo" alt="Client">
            @elseif($pv->client)
                <div class="doc-type" style="font-size:13px;">{{ $pv->client->nom_complet ?? $pv->client->entreprise ?? '' }}</div>
            @endif
        </div>
        <div class="header-right">
            @if($parametre->logo_path && file_exists(public_path('storage/' . $parametre->logo_path)))
                <img src="{{ public_path('storage/' . $parametre->logo_path) }}" class="company-logo" alt="Logo">
            @else
                <div class="doc-type" style="font-size:13px;">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</div>
            @endif
        </div>
    </div>

    {{-- ══════ TITRE ══════ --}}
    <div class="title-box">
        <h1>Procès-Verbal de Réception des Travaux</h1>
    </div>
    <div class="pv-numero">N° {{ $pv->numero }}</div>

    {{-- ══════ ÉMETTEUR / CLIENT ══════ --}}
    <div class="parties-wrap" style="margin-top:14px;">
        <div class="party-col">
            <div class="party-title">Émetteur</div>
            @if($parametre->nom_entreprise)
            <div class="party-row">
                <div class="party-label">Société :</div>
                <div class="party-value bold">{{ $parametre->nom_entreprise }}</div>
            </div>
            @endif
            @if($parametre->niu ?? null)
            <div class="party-row">
                <div class="party-label">NIF :</div>
                <div class="party-value">{{ $parametre->niu }}</div>
            </div>
            @endif
            @if($parametre->ville ?? null)
            <div class="party-row">
                <div class="party-label">Ville :</div>
                <div class="party-value">{{ $parametre->ville }}</div>
            </div>
            @endif
            @if($parametre->adresse ?? null)
            <div class="party-row">
                <div class="party-label">Adresse :</div>
                <div class="party-value">{{ $parametre->adresse }}</div>
            </div>
            @endif
            @if($parametre->telephone ?? null)
            <div class="party-row">
                <div class="party-label">Téléphone :</div>
                <div class="party-value">{{ $parametre->telephone }}</div>
            </div>
            @endif
            @if($parametre->email ?? null)
            <div class="party-row">
                <div class="party-label">Email :</div>
                <div class="party-value">{{ $parametre->email }}</div>
            </div>
            @endif
            @if($parametre->signataire_nom ?? null)
            <div class="party-row">
                <div class="party-label">Signataire :</div>
                <div class="party-value">{{ $parametre->signataire_nom }}@if($parametre->signataire_fonction), {{ $parametre->signataire_fonction }}@endif</div>
            </div>
            @endif
        </div>

        <div class="party-col">
            <div class="party-title">Maître de l'ouvrage (Client)</div>
            <div class="party-row">
                <div class="party-label">Nom / Société :</div>
                <div class="party-value bold">{{ $pv->client->nom_complet ?? $pv->client->entreprise ?? '—' }}</div>
            </div>
            @if($pv->client->nif ?? null)
            <div class="party-row">
                <div class="party-label">NIF :</div>
                <div class="party-value">{{ $pv->client->nif }}</div>
            </div>
            @endif
            @if($pv->client->ville ?? null)
            <div class="party-row">
                <div class="party-label">Ville :</div>
                <div class="party-value">{{ $pv->client->ville }}</div>
            </div>
            @endif
            @if($pv->client->adresse ?? null)
            <div class="party-row">
                <div class="party-label">Adresse :</div>
                <div class="party-value">{{ $pv->client->adresse }}</div>
            </div>
            @endif
            @if($pv->client->telephone_principal ?? null)
            <div class="party-row">
                <div class="party-label">Téléphone :</div>
                <div class="party-value">{{ $pv->client->telephone_principal }}</div>
            </div>
            @endif
            @if($pv->client->email ?? null)
            <div class="party-row">
                <div class="party-label">Email :</div>
                <div class="party-value">{{ $pv->client->email }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════ RÉFÉRENCES ══════ --}}
    <div class="section-title">Références</div>
    <table class="refs-table">
        @if($pv->facture && $pv->facture->devis)
        <tr>
            <td class="ref-label">Devis N°</td>
            <td class="ref-value bold">{{ $pv->facture->devis->numero }}</td>
        </tr>
        @endif
        @if($pv->facture?->devis?->bonCommande?->numero)
        <tr>
            <td class="ref-label">Bon de commande N°</td>
            <td class="ref-value bold">{{ $pv->facture->devis->bonCommande->numero }}</td>
        </tr>
        @elseif($pv->facture)
        <tr>
            <td class="ref-label">Facture N°</td>
            <td class="ref-value bold">{{ $pv->facture->numero }}</td>
        </tr>
        @endif
        @if($pv->facture && $pv->facture->commande_service)
        <tr>
            <td class="ref-label">Commande de service</td>
            <td class="ref-value">{{ $pv->facture->commande_service }}</td>
        </tr>
        @endif
        <tr>
            <td class="ref-label">Objet des travaux</td>
            <td class="ref-value bold">{{ $pv->titre ?? $pv->description_travaux ?? '—' }}</td>
        </tr>
        @if($pv->description_travaux && $pv->titre)
        <tr>
            <td class="ref-label">Description</td>
            <td class="ref-value">{{ $pv->description_travaux }}</td>
        </tr>
        @endif
        <tr>
            <td class="ref-label">Date de réception</td>
            <td class="ref-value bold">{{ $pv->date_reception ? $pv->date_reception->format('d/m/Y') : '—' }}</td>
        </tr>
        <tr>
            <td class="ref-label">Lieu de réception</td>
            <td class="ref-value">{{ $pv->lieu_reception ?? $parametre->ville ?? '—' }}</td>
        </tr>
    </table>

    {{-- ══════ DÉCLARATION ══════ --}}
    <div class="section-title">Déclaration de réception</div>
    <div class="body-text">
        Je soussigné <span class="bold underline">{{ $parametre->signataire_nom ?? '.............................' }}</span>@if($parametre->signataire_fonction), {{ $parametre->signataire_fonction }}@endif,
        certifie avoir procédé à la livraison des travaux ci-dessus référencés, en présence du représentant de
        <span class="bold underline">{{ $pv->client->nom_complet ?? $pv->client->entreprise ?? '.............................' }}</span>.
    </div>

    @php
        $sansReserve = in_array($pv->etat_travaux, ['conforme']);
        $avecReserve = in_array($pv->etat_travaux, ['reserve_mineure', 'reserve_majeure']);
        $refuse      = in_array($pv->etat_travaux, ['non_conforme']);
    @endphp

    <div class="checkbox-section">
        {{-- Option 1 : Sans réserve --}}
        <div class="checkbox-line {{ $sansReserve ? 'checked' : '' }}">
            <span class="checkbox {{ $sansReserve ? 'checkbox-checked' : '' }}">{{ $sansReserve ? '✓' : '' }}</span>
            La réception est prononcée <span class="bold">sans réserve</span>, avec effet à la date du :
            <span class="bold underline">{{ $pv->date_reception ? $pv->date_reception->format('d/m/Y') : '__ /__ /____' }}</span>
        </div>

        {{-- Option 2 : Avec réserves --}}
        <div class="checkbox-line {{ $avecReserve ? 'checked' : '' }}">
            <span class="checkbox {{ $avecReserve ? 'checkbox-checked' : '' }}">{{ $avecReserve ? '✓' : '' }}</span>
            La réception est prononcée <span class="bold">avec réserves</span> :
            @if($avecReserve && $pv->reserves)
                <div class="indent" style="font-size:9px; margin-top:3px; font-style:italic;">{{ $pv->reserves }}</div>
            @endif
        </div>

        {{-- Option 3 : Refusée --}}
        <div class="checkbox-line {{ $refuse ? 'checked' : '' }}">
            <span class="checkbox {{ $refuse ? 'checkbox-checked' : '' }}">{{ $refuse ? '✓' : '' }}</span>
            La réception est <span class="bold">refusée – différée</span> pour les motifs suivants :
            @if($refuse && $pv->reserves)
                <div class="indent" style="font-size:9px; margin-top:3px; font-style:italic;">{{ $pv->reserves }}</div>
            @endif
        </div>
    </div>

    {{-- ══════ OBSERVATIONS ══════ --}}
    @if($pv->observations)
        <div class="section-title">Observations</div>
        <div class="observations-block">{{ $pv->observations }}</div>
    @endif

    {{-- ══════ MENTION LÉGALE ══════ --}}
    <div class="mention-legale">
        La présente réception emporte transfert de possession des travaux réalisés au profit du maître d'ouvrage,
        qui en assume désormais la garde et les risques à compter de la date de réception.
    </div>

    {{-- ══════ FAIT À ══════ --}}
    <div class="fait-a">
        Fait à <span class="bold underline">{{ $pv->lieu_reception ?? $parametre->ville ?? '.............' }}</span>
        le <span class="bold underline">{{ $pv->date_reception ? $pv->date_reception->format('d/m/Y') : '__ /__ /____' }}</span>
    </div>
    <div class="exemplaires">En <span class="bold">.02.</span> Exemplaires</div>

    {{-- ══════ SIGNATURES ══════ --}}
    <div class="signature-wrap">
        {{-- Signature Entreprise --}}
        <div class="sig-col">
            <div class="sig-label">Signature de {{ $parametre->nom_entreprise ?? "l'entreprise" }}</div>
            <div class="sig-images">
                @if($parametre->signature_path && file_exists(public_path('storage/' . $parametre->signature_path)))
                    <img src="{{ public_path('storage/' . $parametre->signature_path) }}" alt="Signature">
                @endif
                @if($parametre->cachet_path && file_exists(public_path('storage/' . $parametre->cachet_path)))
                    <img src="{{ public_path('storage/' . $parametre->cachet_path) }}" alt="Cachet">
                @endif
            </div>
            @if($parametre->signataire_nom)
                <div class="sig-name">{{ $parametre->signataire_nom }}</div>
            @endif
            @if($parametre->signataire_fonction)
                <div class="sig-fonction" style="margin-bottom:4px;">{{ $parametre->signataire_fonction }}</div>
            @endif
            @if($pv->date_signature_entreprise)
                <div class="sig-date">Signé le {{ $pv->date_signature_entreprise->format('d/m/Y à H:i') }}</div>
            @endif
        </div>

        {{-- Signature Client --}}
        <div class="sig-col">
            <div class="sig-label">Signature du maître de l'ouvrage (CLIENT)</div>
            <div class="sig-name" style="margin-bottom:4px;">{{ $pv->client->nom_complet ?? $pv->client->entreprise ?? '' }}</div>
            <div class="sig-images">
                @if($pv->signature_client_path && file_exists(public_path('storage/' . $pv->signature_client_path)))
                    <img src="{{ public_path('storage/' . $pv->signature_client_path) }}" alt="Signature Client">
                @endif
            </div>
            @if($pv->date_signature_client)
                <div class="sig-date">Signé le {{ $pv->date_signature_client->format('d/m/Y à H:i') }}</div>
            @endif
        </div>
    </div>

    {{-- ══════ FOOTER ══════ --}}
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
                PV N° {{ $pv->numero }} — Page <span class="pagenum"></span>
            </div>
        </div>
    </div>

</body>
</html>