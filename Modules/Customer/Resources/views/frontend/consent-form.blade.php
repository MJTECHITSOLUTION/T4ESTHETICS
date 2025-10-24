 .<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulaire de Consentement - {{ $customer->first_name }} {{ $customer->last_name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .consent-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .consent-item {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
        }
        .consent-required {
            border-left: 4px solid #dc3545;
        }
        .consent-optional {
            border-left: 4px solid #6c757d;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        .customer-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Customer Information -->
                <div class="customer-info text-center">
                    <h2 class="mb-3">
                        <i class="fa-solid fa-shield-halved me-2"></i>
                        Gestion des Consentements
                    </h2>
                    <h4 class="mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                    <p class="mb-0 opacity-75">{{ $customer->email }}</p>
                </div>

                <!-- Consent Form -->
                <div class="card consent-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-clipboard-list me-2"></i>
                            Préférences de Consentement
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="consentForm">
                            @csrf
                            <div class="row">
                                @foreach($consents as $consent)
                                <div class="col-12">
                                    <div class="consent-item {{ $consent->is_required ? 'consent-required' : 'consent-optional' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    {{ $consent->name }}
                                                    @if($consent->is_required)
                                                        <span class="badge bg-danger ms-2">Obligatoire</span>
                                                    @else
                                                        <span class="badge bg-secondary ms-2">Optionnel</span>
                                                    @endif
                                                </h6>
                                                @if($consent->description)
                                                    <p class="text-muted mb-2">{{ $consent->description }}</p>
                                                @endif
                                                @if($consent->content)
                                                    <div class="consent-content bg-light p-3 rounded">
                                                        {!! $consent->content !!}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="generateConsentPDF({{ $consent->id }}, '{{ $consent->name }}')">
                                                    <i class="fa-solid fa-file-pdf me-1"></i>
                                                    PDF
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="consents[{{ $consent->id }}][has_consented]" 
                                                           id="consent_yes_{{ $consent->id }}" 
                                                           value="1"
                                                           {{ isset($customerConsents[$consent->id]) && $customerConsents[$consent->id]->has_consented ? 'checked' : '' }}>
                                                    <label class="form-check-label text-success fw-bold" for="consent_yes_{{ $consent->id }}">
                                                        <i class="fa-solid fa-check-circle me-1"></i>
                                                        J'accepte
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="consents[{{ $consent->id }}][has_consented]" 
                                                           id="consent_no_{{ $consent->id }}" 
                                                           value="0"
                                                           {{ isset($customerConsents[$consent->id]) && !$customerConsents[$consent->id]->has_consented ? 'checked' : '' }}>
                                                    <label class="form-check-label text-danger fw-bold" for="consent_no_{{ $consent->id }}">
                                                        <i class="fa-solid fa-times-circle me-1"></i>
                                                        Je refuse
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <label for="notes_{{ $consent->id }}" class="form-label">
                                                <i class="fa-solid fa-comment me-1"></i>
                                                Notes Supplémentaires (Optionnel)
                                            </label>
                                            <textarea class="form-control" 
                                                      id="notes_{{ $consent->id }}" 
                                                      name="consents[{{ $consent->id }}][notes]" 
                                                      rows="2" 
                                                      placeholder="Ajoutez des notes ou commentaires supplémentaires...">{{ isset($customerConsents[$consent->id]) ? $customerConsents[$consent->id]->notes : '' }}</textarea>
                                        </div>
                                        
                                        <input type="hidden" name="consents[{{ $consent->id }}][consent_id]" value="{{ $consent->id }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Section Signature Numérique -->
                            <div class="signature-section mt-5">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fa-solid fa-signature me-2"></i>
                                            Signature Numérique
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Signez ici :</label>
                                                <div class="signature-pad-container">
                                                    <canvas id="signaturePad" width="400" height="200" style="border: 1px solid #ddd; border-radius: 5px; cursor: crosshair;"></canvas>
                                                </div>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                                        <i class="fa-solid fa-eraser me-1"></i>
                                                        Effacer
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="signature-preview">
                                                    <label class="form-label fw-bold">Aperçu de la Signature :</label>
                                                    <div id="signaturePreview" class="border p-3 bg-light" style="min-height: 100px;">
                                                        <p class="text-muted text-center">Votre signature apparaîtra ici</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-outline-primary me-2" onclick="generateConsentPDF()">
                                    <i class="fa-solid fa-file-pdf me-2"></i>
                                    Générer PDF
                                </button>
                                <button type="submit" class="btn btn-submit text-white">
                                    <i class="fa-solid fa-save me-2"></i>
                                    Enregistrer les Préférences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Success/Error Messages -->
                <div id="messageContainer" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

    <script>
        let signaturePad;
        
        $(document).ready(function() {
            // Initialize signature pad
            initSignaturePad();
            // Form submission
            $('#consentForm').on('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = $('button[type="submit"]');
                const originalText = submitBtn.html();
                
                // Show loading state
                submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Enregistrement...');
                
                // Prepare form data
                const formData = new FormData(this);
                const consents = [];
                
                // Convert form data to the expected format
                $('input[name^="consents["]').each(function() {
                    const name = $(this).attr('name');
                    const consentId = name.match(/consents\[(\d+)\]/)[1];
                    const field = name.match(/\[([^\]]+)\]$/)[1];
                    
                    if (!consents.find(c => c.consent_id == consentId)) {
                        consents.push({
                            consent_id: parseInt(consentId),
                            has_consented: null,
                            notes: ''
                        });
                    }
                    
                    const consent = consents.find(c => c.consent_id == consentId);
                    if (field === 'has_consented') {
                        if ($(this).is(':checked')) {
                            consent.has_consented = $(this).val() === '1';
                        }
                    } else if (field === 'notes') {
                        consent.notes = $(this).val();
                    }
                });
                
                // Add signature data if available
                let signatureData = null;
                if (signaturePad && !signaturePad.isEmpty()) {
                    signatureData = signaturePad.toDataURL();
                }
                
                // Submit the form
                $.ajax({
                    url: '{{ route("customer.consent.process", $token) }}',
                    method: 'POST',
                    data: {
                        consents: consents,
                        signature: signatureData,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            showMessage('success', 'Préférences de consentement mises à jour avec succès !');
                            
                            // Disable form after successful submission
                            $('#consentForm input, #consentForm textarea').prop('disabled', true);
                            submitBtn.html('<i class="fa-solid fa-check me-2"></i>Enregistré avec Succès');
                        } else {
                            showMessage('danger', response.message || 'Échec de la mise à jour des préférences de consentement.');
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Une erreur s\'est produite lors de l\'enregistrement de vos préférences.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showMessage('danger', errorMessage);
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
            
            function showMessage(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                
                const messageHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fa-solid ${iconClass} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                $('#messageContainer').html(messageHtml);
            }
        });
        
        // Initialize signature pad
        function initSignaturePad() {
            const canvas = document.getElementById('signaturePad');
            if (canvas) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(0, 0, 0)'
                });
                
                // Update preview on signature change
                signaturePad.addEventListener('endStroke', function() {
                    updateSignaturePreview();
                });
            }
        }
        
        // Clear signature
        function clearSignature() {
            if (signaturePad) {
                signaturePad.clear();
                updateSignaturePreview();
            }
        }
        
        // Update signature preview
        function updateSignaturePreview() {
            if (signaturePad && !signaturePad.isEmpty()) {
                const dataURL = signaturePad.toDataURL();
                $('#signaturePreview').html(`<img src="${dataURL}" style="max-width: 100%; height: auto;" />`);
            } else {
                $('#signaturePreview').html('<p class="text-muted text-center">Votre signature apparaîtra ici</p>');
            }
        }
        
        // Generate individual consent PDF
        function generateConsentPDF(consentId, consentName) {
            // Show loading state
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Génération...';
            btn.disabled = true;
            
            // Create a simple dummy PDF using jsPDF
            const { jsPDF } = window.jspdf || {};
            
            if (typeof jsPDF !== 'undefined') {
                // Create new PDF document
                const doc = new jsPDF();
                
                // Add title
                doc.setFontSize(20);
                doc.text('Formulaire de Consentement', 105, 20, { align: 'center' });
                
                // Add line
                doc.line(20, 25, 190, 25);
                
                // Add client info
                doc.setFontSize(12);
                doc.text('Client: {{ $customer->first_name }} {{ $customer->last_name }}', 20, 40);
                doc.text('Email: {{ $customer->email }}', 20, 50);
                doc.text('Date: ' + new Date().toLocaleDateString('fr-FR'), 20, 60);
                
                // Add line
                doc.line(20, 65, 190, 65);
                
                // Add consent info
                doc.setFontSize(14);
                doc.text('Consentement: ' + consentName, 20, 80);
                doc.setFontSize(12);
                doc.text('ID du Consentement: ' + consentId, 20, 90);
                doc.text('Statut: Formulaire de consentement généré', 20, 100);
                
                // Add line
                doc.line(20, 105, 190, 105);
                
                // Add signature area
                doc.setFontSize(12);
                doc.text('Signature du Client:', 20, 120);
                doc.rect(20, 125, 170, 40);
                doc.text('Signature ici', 25, 145);
                
                // Add date/time
                doc.text('Date: ' + new Date().toLocaleDateString('fr-FR') + ' - ' + new Date().toLocaleTimeString('fr-FR'), 20, 180);
                
                // Save the PDF
                const fileName = 'consentement-' + consentName.replace(/[^a-zA-Z0-9]/g, '-') + '-' + new Date().toISOString().split('T')[0] + '.pdf';
                doc.save(fileName);
                
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
            } else {
                // Fallback: Create a simple downloadable HTML file
                const htmlContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Consentement - ${consentName}</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            .header { text-align: center; margin-bottom: 30px; }
                            .section { margin: 20px 0; }
                            .signature-area { border: 1px solid #ccc; height: 100px; margin: 20px 0; }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h1>Formulaire de Consentement</h1>
                        </div>
                        <div class="section">
                            <h3>Client: {{ $customer->first_name }} {{ $customer->last_name }}</h3>
                            <p><strong>Email:</strong> {{ $customer->email }}</p>
                            <p><strong>Date:</strong> ${new Date().toLocaleDateString('fr-FR')}</p>
                        </div>
                        <div class="section">
                            <h3>Consentement: ${consentName}</h3>
                            <p><strong>ID du Consentement:</strong> ${consentId}</p>
                            <p><strong>Statut:</strong> Formulaire de consentement généré</p>
                        </div>
                        <div class="section">
                            <h3>Signature du Client:</h3>
                            <div class="signature-area"></div>
                            <p><strong>Date:</strong> ${new Date().toLocaleDateString('fr-FR')} - ${new Date().toLocaleTimeString('fr-FR')}</p>
                        </div>
                    </body>
                    </html>
                `;
                
                // Create and download the HTML file
                const blob = new Blob([htmlContent], { type: 'text/html' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'consentement-' + consentName.replace(/[^a-zA-Z0-9]/g, '-') + '-' + new Date().toISOString().split('T')[0] + '.html';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                // Reset button
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
        
        // Generate full consent PDF
        function generateConsentPDF() {
            if (signaturePad && !signaturePad.isEmpty()) {
                const signatureData = signaturePad.toDataURL();
                
                // Collect form data
                const formData = new FormData();
                formData.append('signature', signatureData);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                // Add consent data
                const consents = [];
                $('input[name^="consents["]').each(function() {
                    const name = $(this).attr('name');
                    const consentId = name.match(/consents\[(\d+)\]/)[1];
                    const field = name.match(/\[([^\]]+)\]$/)[1];
                    
                    if (!consents.find(c => c.consent_id == consentId)) {
                        consents.push({
                            consent_id: consentId,
                            has_consented: null,
                            notes: ''
                        });
                    }
                    
                    const consent = consents.find(c => c.consent_id == consentId);
                    if (field === 'has_consented') {
                        if ($(this).is(':checked')) {
                            consent.has_consented = $(this).val() == '1';
                        }
                    } else if (field === 'notes') {
                        consent.notes = $(this).val();
                    }
                });
                
                formData.append('consents', JSON.stringify(consents));
                
                // Generate PDF
                $.ajax({
                    url: '{{ route("customer.consent.pdf", $token) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        const blob = new Blob([data], { type: 'application/pdf' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'consent-form-{{ $customer->first_name }}-{{ $customer->last_name }}-' + new Date().toISOString().split('T')[0] + '.pdf';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    },
                    error: function(xhr) {
                        alert('Erreur lors de la génération du PDF');
                    }
                });
            } else {
                alert('Veuillez signer le formulaire avant de générer le PDF');
            }
        }
    </script>
</body>
</html>
