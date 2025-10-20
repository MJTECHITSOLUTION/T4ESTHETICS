<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentement - {{ $consent->name }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #000;
            font-size: 12pt;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #a7398b;
            padding-bottom: 15pt;
            margin-bottom: 20pt;
            page-break-after: avoid;
        }
        
        .header h1 {
            color: #a7398b;
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header h2 {
            color: #333;
            margin: 8pt 0 0 0;
            font-size: 14pt;
            font-weight: normal;
        }
        
        .content {
            margin-bottom: 20pt;
        }
        
        .consent-info {
            background-color: #f8f9fa;
            padding: 12pt;
            border-left: 4pt solid #a7398b;
            margin-bottom: 15pt;
            page-break-inside: avoid;
        }
        
        .consent-info h3 {
            margin-top: 0;
            color: #a7398b;
            font-size: 14pt;
            font-weight: bold;
        }
        
        .consent-content {
            background-color: #fff;
            border: 1pt solid #ddd;
            padding: 15pt;
            margin-bottom: 15pt;
            page-break-inside: avoid;
        }
        
        .consent-content h3 {
            font-size: 14pt;
            font-weight: bold;
            color: #a7398b;
            margin-top: 0;
        }
        
        .customer-info {
            background-color: #f5f5f5;
            padding: 12pt;
            border: 1pt solid #ddd;
            margin-bottom: 15pt;
            page-break-inside: avoid;
        }
        
        .customer-info h3 {
            font-size: 14pt;
            font-weight: bold;
            color: #a7398b;
            margin-top: 0;
        }
        
        .signature-section {
            margin-top: 30pt;
            border-top: 2pt solid #a7398b;
            padding-top: 15pt;
            background-color: #f8f9fa;
            padding: 15pt;
            page-break-inside: avoid;
        }
        
        .signature-section h3 {
            font-size: 14pt;
            font-weight: bold;
            color: #a7398b;
            margin-top: 0;
        }
        
        .signature-area {
            margin: 15pt 0;
        }
        
        .signature-box {
            border: 2pt solid #a7398b;
            height: 80pt;
            margin: 10pt 0;
            position: relative;
            background-color: white;
        }
        
        .signature-label {
            position: absolute;
            bottom: -20pt;
            left: 0;
            font-size: 10pt;
            color: #666;
            font-weight: bold;
        }
        
        .signature-details {
            margin-top: 20pt;
            padding-top: 10pt;
            border-top: 1pt solid #ddd;
        }
        
        .date-box {
            border-bottom: 1pt solid #333;
            width: 150pt;
            display: inline-block;
            margin-left: 8pt;
        }
        
        .signature-section ul {
            margin: 10pt 0;
            padding-left: 15pt;
        }
        
        .signature-section li {
            margin-bottom: 3pt;
            color: #555;
            font-size: 11pt;
        }
        
        .footer {
            margin-top: 30pt;
            text-align: center;
            font-size: 10pt;
            color: #666;
            border-top: 1pt solid #ddd;
            padding-top: 15pt;
            page-break-before: avoid;
        }
        
        .required-badge {
            background-color: #dc3545;
            color: white;
            padding: 2pt 6pt;
            font-size: 10pt;
            font-weight: bold;
        }
        
        .optional-badge {
            background-color: #6c757d;
            color: white;
            padding: 2pt 6pt;
            font-size: 10pt;
        }
        
        p {
            margin: 8pt 0;
            text-align: justify;
        }
        
        strong {
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10pt 0;
        }
        
        .info-table td {
            padding: 5pt;
            border: 1pt solid #ddd;
            vertical-align: top;
        }
        
        .info-table .label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 30%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONSENTEMENT</h1>
        <h2>{{ $consent->name }}</h2>
    </div>

    <div class="content">
        <div class="consent-info">
            <h3>Informations du consentement</h3>
            <table class="info-table">
                <tr>
                    <td class="label">Type:</td>
                    <td>{{ $consent->name }}</td>
                </tr>
                @if($consent->description)
                <tr>
                    <td class="label">Description:</td>
                    <td>{{ $consent->description }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Statut:</td>
                    <td>
                        @if($consent->is_required)
                            <span class="required-badge">REQUIS</span>
                        @else
                            <span class="optional-badge">OPTIONNEL</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Généré le:</td>
                    <td>{{ $generated_at }}</td>
                </tr>
            </table>
        </div>

        @if($customer)
        <div class="customer-info">
            <h3>Informations du client</h3>
            <table class="info-table">
                <tr>
                    <td class="label">Nom:</td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td>{{ $customer->email }}</td>
                </tr>
                @if($customer->phone)
                <tr>
                    <td class="label">Téléphone:</td>
                    <td>{{ $customer->phone }}</td>
                </tr>
                @endif
                @if($customerConsent && $customerConsent->consented_at)
                <tr>
                    <td class="label">Consentement donné le:</td>
                    <td>{{ $customerConsent->consented_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
        @endif

        <div class="consent-content">
            <h3>Contenu du consentement</h3>
            @if($consent->content)
                {!! nl2br(e($consent->content)) !!}
            @else
                <div style="background-color: #f8f9fa; padding: 15pt; border-left: 4pt solid #a7398b; margin: 10pt 0;">
                    <h4 style="color: #a7398b; margin-top: 0; font-size: 13pt; font-weight: bold;">Contenu type du consentement</h4>
                    
                    <p style="margin: 8pt 0 5pt 0; font-weight: bold;">Article 1 - Objet</p>
                    <p style="margin: 0 0 10pt 0;">Le présent consentement a pour objet de recueillir votre accord libre et éclairé concernant les traitements de données personnelles et les soins esthétiques qui vous seront dispensés.</p>
                    
                    <p style="margin: 8pt 0 5pt 0; font-weight: bold;">Article 2 - Données collectées</p>
                    <p style="margin: 0 0 10pt 0;">Nous collectons les données suivantes : nom, prénom, adresse, téléphone, email, antécédents médicaux, photos avant/après traitement, et autres informations nécessaires à votre suivi.</p>
                    
                    <p style="margin: 8pt 0 5pt 0; font-weight: bold;">Article 3 - Utilisation des données</p>
                    <p style="margin: 0 0 10pt 0;">Vos données sont utilisées pour : la gestion de votre dossier client, le suivi de vos traitements, la planification de vos rendez-vous, et l'amélioration de nos services.</p>
                    
                    <p style="margin: 8pt 0 5pt 0; font-weight: bold;">Article 4 - Conservation</p>
                    <p style="margin: 0 0 10pt 0;">Vos données sont conservées pendant une durée de 10 ans conformément à la réglementation en vigueur, puis archivées ou supprimées.</p>
                    
                    <p style="margin: 8pt 0 5pt 0; font-weight: bold;">Article 5 - Droits</p>
                    <p style="margin: 0 0 10pt 0;">Vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition concernant vos données personnelles.</p>
                </div>
            @endif
        </div>

        <div class="signature-section">
            <h3>Signature du client</h3>
            
            <div style="background-color: #fff3cd; border: 1pt solid #ffeaa7; padding: 12pt; margin: 15pt 0;">
                <p style="margin: 0 0 5pt 0; color: #856404; font-weight: bold;">Déclaration du client :</p>
                <p style="margin: 0 0 8pt 0; color: #856404;">En signant ce document, je confirme que :</p>
                <ul style="margin: 0; padding-left: 15pt; color: #856404;">
                    <li style="margin-bottom: 3pt;">J'ai lu et compris le contenu de ce consentement</li>
                    <li style="margin-bottom: 3pt;">Je donne mon accord libre et éclairé</li>
                    <li style="margin-bottom: 3pt;">J'accepte les conditions décrites ci-dessus</li>
                    <li style="margin-bottom: 3pt;">Je comprends que je peux retirer mon consentement à tout moment</li>
                </ul>
            </div>
            
            <div class="signature-area">
                <div style="margin: 15pt 0;">
                    <p style="margin: 0 0 8pt 0; font-weight: bold; color: #a7398b; font-size: 12pt;">Zone de signature :</p>
                    <div class="signature-box">
                        @if($customer && $customerConsent && $customerConsent->hasSignature())
                        WW
                            <img src="{{ $customerConsent->getSignatureUrl() }}" alt="Signature" style="max-width: 100%; max-height: 100%;">
                        @else
                            <div style="height: 60pt; border: 2pt dashed #a7398b; display: flex; align-items: center; justify-content: center; color: #a7398b; background-color: #f8f9fa; font-size: 11pt;">
                                <em>Signature du client</em>
                            </div>
                        @endif
                    </div>
                    <div class="signature-label">Signature du client</div>
                </div>
            </div>

            <div class="signature-details">
                <div style="display: flex; justify-content: space-between; margin: 15pt 0;">
                    <div style="width: 45%;">
                        <p style="margin: 0 0 5pt 0; font-weight: bold;">Nom du client:</p>
                        <div style="border-bottom: 1pt solid #333; padding: 3pt 0; margin-top: 3pt;">
                            @if($customer)
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            @else
                                _________________________
                            @endif
                        </div>
                    </div>
                    <div style="width: 45%;">
                        <p style="margin: 0 0 5pt 0; font-weight: bold;">Date de signature:</p>
                        <div style="border-bottom: 1pt solid #333; padding: 3pt 0; margin-top: 3pt;">
                            @if($customer && $customerConsent && $customerConsent->signed_at)
                                {{ $customerConsent->signed_at->format('d/m/Y H:i') }}
                            @else
                                _________________________
                            @endif
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 20pt; padding: 12pt; background-color: #f8f9fa; border-left: 4pt solid #a7398b;">
                    <p style="margin: 0; font-size: 10pt; color: #666;">
                        <strong>Note :</strong> Ce document a été généré automatiquement et constitue une preuve légale de votre consentement. 
                        Il est conservé dans nos archives conformément à la réglementation en vigueur.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div style="text-align: center; margin-top: 30pt; padding-top: 15pt; border-top: 2pt solid #a7398b;">
            <p style="margin: 3pt 0; font-size: 11pt; color: #666;">
                <strong>Centre Esthétique T4</strong><br>
                Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}
            </p>
            <p style="margin: 3pt 0; font-size: 9pt; color: #999;">
                Ce document constitue une preuve légale de consentement et est conservé conformément à la réglementation RGPD
            </p>
            <p style="margin: 3pt 0; font-size: 8pt; color: #999;">
                © {{ now()->format('Y') }} T4 Esthetics - Tous droits réservés
            </p>
        </div>
    </div>
</body>
</html>
