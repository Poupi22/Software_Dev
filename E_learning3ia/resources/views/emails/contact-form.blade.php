<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #374151;
            line-height: 1.6;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .email-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .email-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .email-body {
            padding: 40px;
        }
        
        .field-group {
            margin-bottom: 24px;
            border-left: 4px solid #3b82f6;
            padding-left: 16px;
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        
        .field-label {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
            display: block;
        }
        
        .field-value {
            color: #374151;
            font-size: 16px;
            word-wrap: break-word;
        }
        
        .message-field {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 8px;
        }
        
        .email-footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .email-footer p {
            color: #6b7280;
            font-size: 12px;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .email-header {
                padding: 20px;
            }
            
            .email-header h2 {
                font-size: 20px;
            }
            
            .email-body {
                padding: 20px;
            }
            
            .contact-info {
                grid-template-columns: 1fr;
            }
            
            .field-group {
                margin-bottom: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>📧 Nouveau message de contact</h2>
            <p>Vous avez reçu un nouveau message via le formulaire de contact</p>
        </div>
        
        <div class="email-body">
            <div class="contact-info">
                <div class="field-group">
                    <span class="field-label">👤 Nom</span>
                    <div class="field-value">{{ $nom ?? 'Non renseigné' }}</div>
                </div>
                
                <div class="field-group">
                    <span class="field-label">👤 Prénom</span>
                    <div class="field-value">{{ $prenom ?? 'Non renseigné' }}</div>
                </div>
            </div>
            
            <div class="field-group">
                <span class="field-label">📧 Email</span>
                <div class="field-value">{{ $email ?? 'Non renseigné' }}</div>
            </div>
            
            @if(isset($tel) && $tel)
            <div class="field-group">
                <span class="field-label">📋 Telephone</span>
                <div class="field-value">{{ $tel ?? 'Non renseigné' }}</div>
            </div>
            @endif
            
            <div class="field-group">
                <span class="field-label">💬 Message</span>
                <div class="message-field">
                    <div class="field-value">
                        {!! nl2br(e($message ?? 'Non renseigné')) !!}
                    </div>
                </div>
            </div>
            
            @if(isset($fichier) && $fichier)
            <div class="field-group" style="border-left-color: #f59e0b; background-color: #fffbeb;">
                <span class="field-label" style="color: #b45309;">📎 Pièce Jointe</span>
                <div class="field-value">
                    Un fichier nommé "<strong>{{ $fichier->getClientOriginalName() }}</strong>" a été joint à cet email.
                </div>
            </div>
            @endif
        </div>
        
        <div class="email-footer">
            <p>Recu du site.</p>
            <p style="margin-top: 8px; font-size: 11px; opacity: 0.7;">
                📅 Reçu le {{ now()->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>