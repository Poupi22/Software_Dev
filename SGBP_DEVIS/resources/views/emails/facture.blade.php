<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            color: #1f2937;
            background: #f3f4f6;
        }

        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
        }

        .header {
            background: #059669;
            padding: 25px 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0;
            letter-spacing: 1px;
        }

        .header p {
            color: #a7f3d0;
            font-size: 13px;
            margin: 5px 0 0;
        }

        .body {
            padding: 30px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .message-text {
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 20px;
        }

        .total-box {
            background: #059669;
            color: #ffffff;
            border-radius: 8px;
            padding: 15px 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        .total-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .total-amount {
            font-size: 28px;
            font-weight: bold;
            margin-top: 3px;
        }

        .note {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 20px;
            padding: 12px;
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            border-radius: 4px;
        }

        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            font-size: 11px;
            color: #9ca3af;
            margin: 3px 0;
            line-height: 1.4;
        }

        .footer .company {
            font-weight: bold;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        {{-- ── Header ── --}}
        <div class="header">
            <h1>{{ $parametre->nom_entreprise ?? config('app.name') }}</h1>
            <p>Facture N° {{ $facture->numero }}</p>
        </div>

        {{-- ── Body ── --}}
        <div class="body">
            <p class="greeting">Bonjour <strong>{{ $facture->client->nom_complet }}</strong>,</p>

            @if ($customMessage)
                <div class="message-text">{!! nl2br(e($customMessage)) !!}</div>
            @else
                <p class="message-text">
                    Veuillez trouver ci-joint la facture <strong>{{ $facture->numero }}</strong> relative à :
                    <em>{{ $facture->titre }}</em>.<br>
                    N'hésitez pas à nous contacter pour toute question ou clarification.
                </p>
            @endif

            {{-- Infos de la facture --}}
            <div class="info-box">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Numéro</td>
                        <td
                            style="padding: 6px 0; text-align: right; font-weight: bold; border-bottom: 1px solid #f3f4f6;">
                            {{ $facture->numero }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Type</td>
                        <td
                            style="padding: 6px 0; text-align: right; font-weight: bold; border-bottom: 1px solid #f3f4f6;">
                            {{ ucfirst($facture->type) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Date d'émission
                        </td>
                        <td
                            style="padding: 6px 0; text-align: right; font-weight: bold; border-bottom: 1px solid #f3f4f6;">
                            {{ $facture->date_emission->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Échéance</td>
                        <td
                            style="padding: 6px 0; text-align: right; font-weight: bold; border-bottom: 1px solid #f3f4f6;">
                            {{ $facture->date_echeance->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #6b7280;">Devise</td>
                        <td style="padding: 6px 0; text-align: right; font-weight: bold;">{{ $facture->devise }}</td>
                    </tr>
                </table>
            </div>

            {{-- Total --}}
            <div class="total-box">
                <div class="total-label">Montant Total TTC</div>
                <div class="total-amount">{{ number_format($facture->total_ttc, 0, ',', ' ') }} {{ $facture->devise }}
                </div>
            </div>

            <div class="note">
                <strong>📎 Pièce jointe :</strong> La facture complète en PDF est jointe à cet email.<br>
                Date d'échéance : <strong>{{ $facture->date_echeance->format('d/m/Y') }}</strong>.
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="footer">
            <p class="company">{{ $parametre->nom_entreprise ?? config('app.name') }}</p>
            @if ($parametre->adresse ?? null)
                <p>{{ $parametre->adresse }}@if ($parametre->ville)
                        , {{ $parametre->ville }}
                    @endif
                </p>
            @endif
            @if ($parametre->telephone ?? null)
                <p>Tél : {{ $parametre->telephone }}</p>
            @endif
            @if ($parametre->email ?? null)
                <p>{{ $parametre->email }}</p>
            @endif
            <p style="margin-top: 10px;">&copy; {{ date('Y') }} — Tous droits réservés</p>
        </div>
    </div>
</body>

</html>
