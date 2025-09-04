<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Financière</title>
    <style>
        @page { margin: 15px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            background : #ffffff url("{{ public_path('assets/img/icons/spot-illustrations/3iA logo-1.png') }}") no-repeat center center;
            background-size: contain;
        }
        .receipt {
            width: 100%;
        }
        .receipt h1 {
            text-align: center;
            font-size: 1.5em;
            margin: 10px 0;
        }
        .receipt table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt td, .receipt th {
            text-align: left;
        }
        .tete {
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
        .tete .content {
            text-align: center;
            line-height: 1.1;
        }
        .tete .content span {
            display: block;
            font-size: 0.6em;
            white-space: nowrap;
            margin: 0;
            padding: 0;
        }
        .tete .logo img {
            display: block;
            margin: auto;
        }
        .order-ref {
            text-transform: uppercase;
            color: rgb(0, 0, 0);
            text-align: center;
            font-weight: bold;
            font-size: 0.9em;
            margin: 5px 0;
        }
        .main-layout {
            width: 100%;
            vertical-align: top;
        }
        .left-column {
            width: 55%;
        }
        .right-column {
            width: 45%;
            padding-left: 20px;
        }
        .info-table td {
            font-size: 0.8em;
            padding: 3px 0;
        }
        .info-table b {
            font-size: 1.1em;
        }
        .signature-space {
            height: 60px;
            border-bottom: 1px dotted #333;
            margin: 10px 0;
        }
        .payment-history-title {
            font-size: 0.9em;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .payment-history table {
            width: 100%;
        }
        .payment-history th, .payment-history td {
            font-size: 0.8em;
            border-bottom: 1px dotted #ccc;
            padding: 2px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <table class="tete">
            <tr>
                <td class="info">
                    <div class="content">
                        <span>République du Cameroun - Paix Travail Patrie</span><span>***************</span>
                        <span>Ministère de l'Emploi et la Formation Professionnelle</span><span>***************</span>
                        <span>Institut de Formation Professionnelle en Ingénierie Informatique Appliquée</span><span>***************</span>
                        <span>BP: Dschang-Cameroun</span><span>(+237) 233451090 / 659218936</span><span>***************</span>
                        <span>contact@institut3ia.com</span><span>https://institut3ia.com</span>
                    </div>
                </td>
                <td class="logo">
                    <img height="90px" src="{{ public_path('assets/img/icons/spot-illustrations/3iA logo-1.png') }}" alt="">
                </td>
                <td class="info">
                    <div class="content">
                        <span>Republic of Cameroon - Peace Work Fatherland</span><span>***************</span>
                        <span>Ministry of Employment and Vocational Training</span><span>***************</span>
                        <span>Vocational Training Institute of Applied Computer science Engineering</span><span>***************</span>
                        <span>P.O. Box: Dschang - Cameroon</span><span>(+237) 233451090 / 659218936</span><span>***************</span>
                        <span>contact@institut3ia.com</span><span>https://institut3ia.com</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="order-ref">
            <span>ARRÊTE/ORDER N°000366 /MINEFOP/SG/DFOP/SDGSF/CSACD/CBAC du/of 10/06/2025</span>
        </div>

        <hr style="border: 2px solid rgb(88, 83, 83); margin: 0;">
        <h1><u>Situation Financière de l'Apprenant</u></h1>

        <table class="main-layout">
            <tr>
                <td class="left-column">
                    <table class="info-table">
                        <tr><td>Type/Formation:<b> {{ $inscription->qualification->nom }} / {{ $inscription->formation->nom }} </b></td></tr>
                        <tr><td>Numéro de document:<b> {{ $inscription->id }} </b></td></tr>
                        <tr><td>Matricule : <b>{{ $inscription->user->matricule }}</b></td></tr>
                        <tr><td>Nom de l'apprenant : <b>{{ $inscription->user->name }} {{ $inscription->user->prenom }}</b></td></tr>
                        <tr><td>Durée : <b>{{ $inscription->duree }}</b></td></tr>
                        <tr><td>Total pension : <b>{{ number_format($inscription->total) }} FCFA</b></td></tr>
                        <tr><td>Montant versé : <b>{{ number_format($inscription->verse) }} FCFA</b></td></tr>
                        <tr><td>Mode de paiement: <b>Espèces / Mobile</b></td></tr>
                        <tr><td>Reste à payer : <b>{{ number_format($inscription->reste) }} FCFA</b></td></tr>
                        <tr><td>Etablie par: <b>Responsable 3IA</b></td></tr>
                        <tr><td><div class="signature-space"></div></td></tr>
                        <tr><td><b>A Dschang, le {{ $dat }}</b></td></tr>
                    </table>
                </td>
                <td class="right-column">
                    @if($inscription->reste != 0)
                        <p style="font-size: 0.8em">Payer la somme restante de <b>{{ number_format($inscription->reste) }} FCFA</b> avant la date des examens finaux.</p>
                    @endif
                    <p style="font-size: 0.8em">
                        Versements possibles : <br>
                        OM: 659218936, NKENFACK Auriol <br>
                        MOMO: 672051100, NKENFACK Auriol
                    </p>

                    <div class="payment-history">
                        <p class="payment-history-title">Historique des Versements</p>
                        <table>
                            <tbody>
                                @forelse($inscription->paiements as $paiement)
                                <tr>
                                    <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                                    <td style="text-align: right;"><b>{{ number_format($paiement->montant) }} FCFA</b></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2">Aucun versement enregistré.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="font-size: 0.8em; padding-top: 20px;">
                    <i><u> NB:</u> Toute falsification de ce document fera l'objet de poursuite judiciaire.</i>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
