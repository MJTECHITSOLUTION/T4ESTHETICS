<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Booking #{{ (int)$booking->id }}</title>
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
        .section-title { margin: 16px 0 8px 0; font-weight:bold; font-size: 14px; }
        .terms { font-size: 13px; line-height: 1.45; color: #222; margin-top: 14px; }
        .terms p { margin: 6px 0; }
        .ack { font-size: 13px; line-height: 1.45; color: #222; margin-top: 12px; }
        .signature-row { width: 100%; margin-top: 20px; }
        .signature-cell { width: 50%; text-align: left; }
        .pdf-footer { margin-top: 14px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 11px; color: #444; line-height: 1.4; }
        .pdf-footer .footer-line { margin: 5px 0; }
        .right { text-align:right; }
    </style>
    @php
        $customer = $booking->user;
        $created = optional($booking->created_at)?->format('d/m/Y');
        $invoiceNo = optional($booking->created_at)?->format('Ymd') . '-' . (int)$booking->id;
        $serviceSubtotal = ($booking->services ?? collect())->sum('service_price');
        $productSubtotal = ($booking->products ?? collect())->sum(function($p){
            $unit = ($p->discounted_price && $p->discounted_price > 0) ? $p->discounted_price : $p->product_price;
            return $unit * (int) $p->product_qty;
        });
        $packageSubtotal = ($booking->bookingPackages ?? collect())->sum('package_price');
        $subtotal = ($serviceSubtotal + $productSubtotal + $packageSubtotal);
        $taxAmount = 0;
        if (optional($booking->payment)->tax_percentage && is_array($booking->payment->tax_percentage)) {
            foreach ($booking->payment->tax_percentage as $tax) {
                if (($tax['type'] ?? '') === 'percent') {
                    $taxAmount += ($subtotal * (float) ($tax['percent'] ?? 0)) / 100;
                } elseif (($tax['type'] ?? '') === 'fixed') {
                    $taxAmount += (float) ($tax['tax_amount'] ?? 0);
                }
            }
        }
        $total = $subtotal + $taxAmount + (float) (optional($booking->payment)->tip_amount ?? 0) - (float) (optional($booking->payment)->discount_amount ?? 0);
    @endphp
</head>
<body>
    <div class="pdf-header">
        <table class="pdf-header-table">
            <tr>
                <td style="width:30%;">
                    <img src="{{ public_path('img/logo/logo.png') }}" class="pdf-logo" alt="Logo">
                </td>
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

    <div class="section-box-title">
        Facture de Réservation
    </div>

    <div class="client-info">
        <b>Nom et prenom :</b> {{ $customer?->full_name }} <b>ne(e) le </b> {{ $customer?->email }}
    </div>


    @if(($booking->services ?? collect())->count())
        <div class="section">
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#130046; color:#cecece;">
                        <th style="text-align:left; padding:6px 8px; border:1px solid #04035f;" colspan="2">Services</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; padding:6px 8px; border:1px solid #ddd;">Service</th>
                        <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->services as $srv)
                        <tr>
                            <td style="padding:6px 8px; border:1px solid #ddd;">{{ $srv->service_name }}</td>
                            <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($srv->service_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(($booking->products ?? collect())->count())
        <div class="section">
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#130046; color:#cecece;">
                        <th style="text-align:left; padding:6px 8px; border:1px solid #04035f;" colspan="4">Produits</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; padding:6px 8px; border:1px solid #ddd;">Produit</th>
                        <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Quantité</th>
                        <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Prix Unitaire</th>
                        <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->products as $prd)
                        @php $unit = ($prd->discounted_price && $prd->discounted_price > 0) ? $prd->discounted_price : $prd->product_price; @endphp
                        <tr>
                            <td style="padding:6px 8px; border:1px solid #ddd;">{{ $prd->product_name }}</td>
                            <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ (int)$prd->product_qty }}</td>
                            <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($unit, 2) }}</td>
                            <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($unit * (int)$prd->product_qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(($booking->bookingPackages ?? collect())->count())
        <div class="section">
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#130046; color:#cecece;">
                        <th style="text-align:left; padding:6px 8px; border:1px solid #04035f;" colspan="2">Packs</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; padding:6px 8px; border:1px solid #ddd;">Packs</th>
                        <th style="text-align:right; padding:6px 8px; border:1px solid #ddd;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->bookingPackages as $pkg)
                        <tr>
                            <td style="padding:6px 8px; border:1px solid #ddd;">{{ $pkg->name }}</td>
                            <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($pkg->package_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="section">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#130046; color:#cecece;">
                    <th style="text-align:left; padding:6px 8px; border:1px solid #04035f;" colspan="2">Totaux</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;"><b>Sous-total</b></td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;"><b>Taxes</b></td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;">{{ number_format($taxAmount, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;"><b>Total</b></td>
                    <td style="text-align:right; padding:6px 8px; border:1px solid #ddd;"><strong>{{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pdf-footer">
        <div class="footer-line">Merci pour votre confiance.</div>
    </div>
</body>
</html>





