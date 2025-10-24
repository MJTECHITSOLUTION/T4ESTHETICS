<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consent Form - {{ $customer->first_name }} {{ $customer->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #7f8c8d;
            margin: 10px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .customer-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .customer-info h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .consent-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        
        .consent-item.required {
            border-left: 4px solid #e74c3c;
        }
        
        .consent-item.optional {
            border-left: 4px solid #95a5a6;
        }
        
        .consent-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .consent-description {
            margin-bottom: 10px;
            color: #555;
        }
        
        .consent-content {
            background-color: #f8f9fa;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .consent-status {
            margin-top: 10px;
            padding: 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .consent-status.consented {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .consent-status.not-consented {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .consent-notes {
            margin-top: 10px;
            font-style: italic;
            color: #666;
        }
        
        .signature-section {
            margin-top: 30px;
            padding: 20px;
            border: 2px solid #333;
            border-radius: 5px;
            text-align: center;
        }
        
        .signature-section h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }
        
        .signature-image {
            max-width: 300px;
            max-height: 150px;
            border: 1px solid #ddd;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Consent Form</h1>
        <h2>{{ $customer->first_name }} {{ $customer->last_name }}</h2>
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
    
    <div class="customer-info">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> {{ $customer->first_name }} {{ $customer->last_name }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone_number ?? 'N/A' }}</p>
    </div>
    
    <h3>Consent Preferences</h3>
    
    @foreach($consents as $consent)
    <div class="consent-item {{ $consent->is_required ? 'required' : 'optional' }}">
        <div class="consent-title">
            {{ $consent->name }}
            @if($consent->is_required)
                <span style="color: #e74c3c; font-size: 12px;">(Required)</span>
            @else
                <span style="color: #95a5a6; font-size: 12px;">(Optional)</span>
            @endif
        </div>
        
        @if($consent->description)
            <div class="consent-description">{{ $consent->description }}</div>
        @endif
        
        @if($consent->content)
            <div class="consent-content">{!! $consent->content !!}</div>
        @endif
        
        @if(isset($customerConsents[$consent->id]))
            @php
                $customerConsent = $customerConsents[$consent->id];
                $hasConsented = $customerConsent->has_consented;
            @endphp
            
            <div class="consent-status {{ $hasConsented ? 'consented' : 'not-consented' }}">
                @if($hasConsented)
                    ✓ CONSENTED
                    @if($customerConsent->consented_at)
                        <br><small>Date: {{ \Carbon\Carbon::parse($customerConsent->consented_at)->format('F j, Y \a\t g:i A') }}</small>
                    @endif
                @else
                    ✗ NOT CONSENTED
                    @if($customerConsent->revoked_at)
                        <br><small>Revoked: {{ \Carbon\Carbon::parse($customerConsent->revoked_at)->format('F j, Y \a\t g:i A') }}</small>
                    @endif
                @endif
            </div>
            
            @if($customerConsent->notes)
                <div class="consent-notes">
                    <strong>Notes:</strong> {{ $customerConsent->notes }}
                </div>
            @endif
        @else
            <div class="consent-status not-consented">
                ✗ NO RESPONSE
            </div>
        @endif
    </div>
    @endforeach
    
    @if($signatureData)
    <div class="signature-section">
        <h3>Digital Signature</h3>
        <p>This document has been digitally signed by the customer.</p>
        <img src="{{ $signatureData }}" alt="Customer Signature" class="signature-image">
        <p><strong>Signed on:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
    @endif
    
    <div class="footer">
        <p>This consent form was generated electronically and is legally binding.</p>
        <p>Document ID: {{ $token ?? 'N/A' }} | Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>

