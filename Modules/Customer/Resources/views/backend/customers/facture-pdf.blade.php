<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->facture_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 50px 20px;
           
        }
        /* Compact header styles - dompdf-friendly */
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222;}
        .pdf-header { width: 100%; margin: 0 0 10px 0; padding: 0; }
        .pdf-header-table { width: 100%; border-collapse: collapse; }
        .pdf-header-table td { vertical-align: middle; border: none; padding: 0; }
        .pdf-logo { height:120px; }
        .pdf-company { text-align: right; line-height: 1.25; }
        .pdf-company .name { font-size: 18px; font-weight: bold; margin: 0; padding: 0; }
        .pdf-company .subtitle { font-size: 14px; color: #444; margin: 2px 0 0 0; padding: 0; }
        .pdf-company .line { font-size: 14px; color: #333; margin: 2px 0 0 0; padding: 0; }

        .section-box-title { text-align: center;  padding: 7px 0 7px 0 ; border-bottom: 1px solid #04035f; background-color: #130046; font-size: 20px; font-weight: bold; color: #cecece; margin-top: 20px }
        .client-info { text-align: left;  padding: 7px 0 7px 0; font-size: 15px; font-weight: bold; }
        .terms { font-size: 13px; line-height: 1.45; color: #222; margin-top: 14px; }
        .terms p { margin: 6px 0; }
        .ack { font-size: 13px; line-height: 1.45; color: #222; margin-top: 12px; }
        .signature-row { width: 100%; margin-top: 20px; }
        .signature-cell { width: 50%; text-align: left; }
        .pdf-footer { margin-top: 14px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 11px; color: #444; line-height: 1.4; }
        .pdf-footer .footer-line { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="pdf-header">
        <table class="pdf-header-table">
            <tr>
                <td style="width:30%;"><img src="{{ public_path('img/logo/logo.png') }}" class="pdf-logo" alt="Logo"></td>
                <td style="width:70%;">
                    <div class="pdf-company">
                        <div class="name">DR. TAIFOR FATIMA</div>
                        <div class="subtitle">Diplôme Inter-Universitaire en Mésothérapie</div>
                        <div class="line">15 Rue de la Beauté, 75001 Paris, France</div>
                        <div class="line">Tél: +33 1 23 45 67 89</div>
                        <div class="line">Email: contact@t4esthetics.com</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-box-title">Facture</div>

    <div class="client-info">
        <b>Nom et prenom :</b> {{ $customer?->full_name }} <b>ne(e) le </b> {{ $customer?->email }}
    </div>

    <div class="section">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#130046; color:#cecece;">
                    <th style="text-align:left; padding:6px 8px; border:1px solid #04035f;" colspan="7">Réalisation et prestations</th>
                </tr>
                <tr>
                    <th style="text-align:left; padding:6px 8px; border:1px solid #ddd;">Type d'acte</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Quantité</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Prix HT</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Remise</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">TVA</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Numéro de Lot</th>
                    <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Total TTC</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($devis?->devisDetails ?? []) as $item)
                <tr>
                    <td style="padding:6px 8px; border:1px solid #ddd;">{{ $item->service_name }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($item->quantity, 0) }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($item->price, 2) }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($item->discount ?? 0, 2) }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($item->tax_amount, 2) }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ $item->number_of_lot ?? '-' }}</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="text-align:left; padding:6px 8px; border:1px solid #ddd;" colspan="5">Totaux</th>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">-</td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;"><strong>{{ number_format($facture->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="pdf-footer">
        <div class="footer-line">Merci pour votre confiance.</div>
    </div>
</body>
</html>





