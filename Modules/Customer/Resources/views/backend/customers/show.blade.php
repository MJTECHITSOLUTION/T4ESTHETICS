@extends('backend.layouts.app')

@section('title')
{{ __($module_action) }} {{ __($module_title) }}
@endsection

@push('after-styles')
<link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
<style>
    .customer-profile-header {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 2rem;
        margin-bottom: 0;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .nav-tabs-custom {
        border: none;
        background: #f8f9fa;
        border-radius: 0 0 15px 15px;
        padding: 0 2rem;
    }
    
    .nav-tabs-custom .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        border-radius: 0;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-tabs-custom .nav-link:hover {
        color: #495057;
        background: transparent;
    }
    
    .nav-tabs-custom .nav-link.active {
        color: #667eea;
        background: white;
        border: none;
        border-bottom: 3px solid #667eea;
    }
    
    .tab-content-custom {
        background: white;
        border-radius: 0 0 15px 15px;
        padding: 2rem;
        min-height: 400px;
    }
    
    .info-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: #495057;
        font-weight: 500;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .custom-fields-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .empty-tab {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-tab i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Modal Styling */
    .devis-step {
        min-height: 300px;
    }
    
    .service-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef !important;
        transition: all 0.3s ease;
    }
    
    .service-item:hover {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .service-item .form-control-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
    }
    
    .service-item .form-label.small {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .modal-xl {
        max-width: 1200px;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .step-indicator .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        font-weight: 600;
        position: relative;
    }
    
    .step-indicator .step.active {
        background: #667eea;
        color: white;
    }
    
    .step-indicator .step.completed {
        background: #28a745;
        color: white;
    }
    
    .step-indicator .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 100%;
        width: 20px;
        height: 2px;
        background: #e9ecef;
        transform: translateY(-50%);
    }
    
    .step-indicator .step.completed:not(:last-child)::after {
        background: #28a745;
    }
    
    /* Devis List Styling */
    #devis-list-table {
        font-size: 0.9rem;
    }
    
    #devis-list-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    #devis-list-table td {
        vertical-align: middle;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    /* Print Devis Styles */
    .print-devis {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        background: white;
        color: black;
    }
    
    .print-header {
        border-bottom: 2px solid #333;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .company-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .company-logo {
        width: 120px;
        height: 80px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #666;
    }
    
    .company-details {
        text-align: right;
        font-size: 14px;
    }
    
    .devis-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin: 20px 0;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .client-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
    
    .print-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    
    .print-table th {
        background: #333;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: bold;
        border: 1px solid #333;
    }
    
    .print-table td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        vertical-align: top;
    }
    
    .print-table tr:nth-child(even) {
        background: #f9f9f9;
    }
    
    .service-description {
        font-size: 12px;
        line-height: 1.4;
    }
    
    .quantity-list {
        text-align: center;
        font-weight: bold;
    }
    
    .print-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
        font-size: 12px;
        color: #666;
    }
    
    @media print {
        .print-devis {
            margin: 0;
            max-width: none;
        }
        
        .modal-header,
        .modal-footer {
            display: none !important;
        }
        
        .modal-body {
            padding: 0 !important;
        }
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Searchable Dropdown Styles */
    #package_dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        background: white;
    }
    
    .package-option {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.15s ease-in-out;
    }
    
    .package-option.highlighted {
        background-color: #e9ecef;
    }
    
    .package-option:last-child {
        border-bottom: none;
    }
    
    #package_search:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <!-- Profile Header -->
    <div class="customer-profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="profile-image-container">
                    @if(!empty($data->profile_image))
                        @php
                            // Fix profile image URL if it contains duplicate domain or storage path
                            $profileImageUrl = $data->profile_image;
                            
                            // Remove duplicate domain and storage path if present
                            // Example: http://t4esthetics/storage/http://t4esthetics/storage/profile_image/...
                            $pattern = '#(https?://[^/]+/)?storage/(https?://[^/]+/)?storage/#i';
                            $replacement = 'storage/';
                            $profileImageUrl = preg_replace($pattern, $replacement, $profileImageUrl);

                            // If still contains double http(s), keep only the last occurrence
                            if (preg_match('#(https?://[^/]+/)(https?://[^/]+/)#', $profileImageUrl)) {
                                $profileImageUrl = preg_replace('#^(https?://[^/]+/)(https?://[^/]+/)#', '$2', $profileImageUrl);
                            }

                            // If the URL does not start with http, prepend asset() helper
                            if (!preg_match('#^https?://#', $profileImageUrl)) {
                                $profileImageUrl = asset($profileImageUrl);
                            }
                        @endphp
                        <img src="{{ $profileImageUrl }}" 
                             alt="{{ $data->full_name }}" 
                             class="img-fluid rounded-circle profile-avatar">
                    @else
                        <img src="{{ default_user_avatar() }}" 
                             alt="{{ $data->full_name }}" 
                             class="img-fluid rounded-circle profile-avatar">
                    @endif
                </div>
            </div>
            <div class="col">
                <h2 class="mb-2">{{ $data->full_name ?? 'N/A' }}</h2>
                <p class="mb-3 opacity-75">{{ $data->email ?? 'N/A' }}</p>
                <div class="d-flex gap-3">
                    @if($data->email_verified_at)
                        <span class="badge bg-light text-success px-3 py-2">
                            <i class="fa-solid fa-check-circle me-1"></i>
                            {{ __('customer.msg_verified') }}
                        </span>
                    @else
                        <span class="badge bg-light text-danger px-3 py-2">
                            <i class="fa-solid fa-times-circle me-1"></i>
                            {{ __('customer.msg_unverified') }}
                        </span>
                    @endif
                    
                    @if($data->status)
                        <span class="badge bg-light text-success px-3 py-2">
                            <i class="fa-solid fa-user-check me-1"></i>
                            {{ __('messages.active') }}
                        </span>
                    @else
                        <span class="badge bg-light text-danger px-3 py-2">
                            <i class="fa-solid fa-user-times me-1"></i>
                            {{ __('messages.inactive') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary btn-lg me-2" data-bs-toggle="modal" data-bs-target="#preconsultationModal">
                    <i class="fa-solid fa-file-alt me-2"></i> Pré-consultation
                </button>
                <a href="#" id="whatsappPreBtn" class="btn btn-success btn-lg me-2">
                    <i class="fa-brands fa-whatsapp me-2"></i> Envoyer lien WhatsApp
                </a>
                <a href="{{ route('backend.customers.index') }}" class="btn btn-light btn-lg">
                    <i class="fa-solid fa-arrow-left me-2"></i> {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs nav-tabs-custom" id="customerTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                <i class="fa-solid fa-user me-2"></i>
                {{ __('Informations') }}
            </button>
        </li>
      
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="medical-records-tab" data-bs-toggle="tab" data-bs-target="#medical-records" type="button" role="tab" aria-controls="medical-records" aria-selected="false">
                <i class="fa-solid fa-notes-medical me-2"></i>

                Examens
            </button>
        </li>
      
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="consentement-tab" data-bs-toggle="tab" data-bs-target="#consentement" type="button" role="tab" aria-controls="consentement" aria-selected="false">
                <i class="fa-solid fa-file-signature me-2"></i>
                Consentements
            </button>
        </li> 
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="phototheque-tab" data-bs-toggle="tab" data-bs-target="#phototheque" type="button" role="tab" aria-controls="phototheque" aria-selected="false">
                <i class="fa-solid fa-photo-film me-2"></i>
                Photothèques
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="consultations-tab" data-bs-toggle="tab" data-bs-target="#consultations" type="button" role="tab" aria-controls="consultations" aria-selected="false">
                <i class="fa-solid fa-user-md me-2"></i>
                Consultations
            </button>
        </li>
        
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="listes-actes-tab" data-bs-toggle="tab" data-bs-target="#listes-actes" type="button" role="tab" aria-controls="listes-actes" aria-selected="false">
                <i class="fa-solid fa-list-ul me-2"></i>
                Actes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="devis-tab" data-bs-toggle="tab" data-bs-target="#devis" type="button" role="tab" aria-controls="devis" aria-selected="false">
                <i class="fa-solid fa-file-invoice me-2"></i>
                {{ __('messages.devis') }}
            </button>
        </li>
        {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="devis-list-tab" data-bs-toggle="tab" data-bs-target="#devis-list" type="button" role="tab" aria-controls="devis-list" aria-selected="false">
                <i class="fa-solid fa-list me-2"></i>
                {{ __('messages.devis_list') }}
            </button>
        </li> --}}
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="devis-history-tab" data-bs-toggle="tab" data-bs-target="#devis-history" type="button" role="tab" aria-controls="devis-history" aria-selected="false">
                <i class="fa-solid fa-history me-2"></i>
                Listes devis
            </button>
        </li>
       
     
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice" type="button" role="tab" aria-controls="invoice" aria-selected="false">
                <i class="fa-solid fa-file-invoice me-2"></i>
                Factures
            </button>
        </li>


       

    

 

    </ul>

    <!-- Tab Content -->
    <div class="tab-content tab-content-custom" id="customerTabsContent">
        <!-- Info Tab -->
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
            <div class="row">
                <!-- Personal Information -->
                <div class="col-lg-6">
                    <h5 class="mb-4 text-primary">
                        <i class="fa-solid fa-id-card me-2"></i>
                        {{ __('messages.personal_information') }}
                    </h5>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_first_name') }}</div>
                        <div class="info-value">{{ $data->first_name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_last_name') }}</div>
                        <div class="info-value">{{ $data->last_name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_Email') }}</div>
                        <div class="info-value">{{ $data->email ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_phone_number') }}</div>
                        <div class="info-value">{{ $data->phone_number ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_gender') }}</div>
                        <div class="info-value">{{ ucfirst($data->gender ?? 'N/A') }}</div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="col-lg-6">
                    <h5 class="mb-4 text-primary">
                        <i class="fa-solid fa-shield-alt me-2"></i>
                        {{ __('messages.account_status') }}
                    </h5>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_verification_status') }}</div>
                        <div class="info-value">
                            @if($data->email_verified_at)
                                <span class="status-badge bg-success text-white">
                                    <i class="fa-solid fa-check-circle me-1"></i>
                                    {{ __('customer.msg_verified') }}
                                </span>
                            @else
                                <span class="status-badge bg-danger text-white">
                                    <i class="fa-solid fa-times-circle me-1"></i>
                                    {{ __('customer.msg_unverified') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_status') }}</div>
                        <div class="info-value">
                            @if($data->status)
                                <span class="status-badge bg-success text-white">
                                    <i class="fa-solid fa-user-check me-1"></i>
                                    {{ __('messages.active') }}
                                </span>
                            @else
                                <span class="status-badge bg-danger text-white">
                                    <i class="fa-solid fa-user-times me-1"></i>
                                    {{ __('messages.inactive') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('customer.lbl_blocked') }}</div>
                        <div class="info-value">
                            @if($data->is_banned)
                                <span class="status-badge bg-danger text-white">
                                    <i class="fa-solid fa-ban me-1"></i>
                                    {{ __('messages.yes') }}
                                </span>
                            @else
                                <span class="status-badge bg-success text-white">
                                    <i class="fa-solid fa-check me-1"></i>
                                    {{ __('messages.no') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('messages.created_at') }}</div>
                        <div class="info-value">{{ $data->created_at ? $data->created_at->format('M d, Y H:i A') : 'N/A' }}</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">{{ __('messages.updated_at') }}</div>
                        <div class="info-value">{{ $data->updated_at ? $data->updated_at->format('M d, Y H:i A') : 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Custom Fields -->
            @if(isset($data->custom_field_data) && !empty($data->custom_field_data))
            <div class="custom-fields-section">
                <h5 class="mb-4 text-primary">
                    <i class="fa-solid fa-list me-2"></i>
                    {{ __('messages.custom_fields') }}
                </h5>
                <div class="row">
                    @foreach($data->custom_field_data as $field => $value)
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">{{ ucwords(str_replace('_', ' ', $field)) }}</div>
                            <div class="info-value">{{ $value ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Invoices Tab -->
        <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-file-invoice me-2"></i>
                    {{ __('messages.invoices') }}
                </h5>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.services') }}</th>
                                    <th>{{ __('messages.products') }}</th>
                                    <th>{{ __('messages.packages') }}</th>
                                    <th class="text-end">{{ __('messages.subtotal') }}</th>
                                    <th class="text-end">{{ __('messages.tax') }}</th>
                                    <th class="text-end">{{ __('messages.total') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasInvoices = isset($bookings) && $bookings->count() > 0;
                                @endphp
                                @forelse(($bookings ?? []) as $booking)
                                    @php
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
                                        $statusText = optional($booking->payment)->payment_status ? __('messages.paid') : __('messages.unpaid');
                                    @endphp
                                    <tr>
                                        <td>{{ optional($booking->created_at)->format('Y-m-d') }}</td>
                                        <td>
                                            @if(($booking->services ?? collect())->count())
                                                <ul class="mb-0 ps-3">
                                                    @foreach($booking->services as $srv)
                                                        <li>{{ $srv->service_name }} ({{ \Currency::format($srv->service_price) }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($booking->products ?? collect())->count())
                                                <ul class="mb-0 ps-3">
                                                    @foreach($booking->products as $prd)
                                                        @php $unit = ($prd->discounted_price && $prd->discounted_price > 0) ? $prd->discounted_price : $prd->product_price; @endphp
                                                        <li>{{ $prd->product_name }} × {{ (int)$prd->product_qty }} ({{ \Currency::format($unit) }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($booking->bookingPackages ?? collect())->count())
                                                <ul class="mb-0 ps-3">
                                                    @foreach($booking->bookingPackages as $pkg)
                                                        <li>{{ $pkg->name }} ({{ \Currency::format($pkg->package_price) }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ \Currency::format($subtotal) }}</td>
                                        <td class="text-end">{{ \Currency::format($taxAmount) }}</td>
                                        <td class="text-end">{{ \Currency::format($total) }}</td>
                                        <td>
                                            <span class="badge {{ optional($booking->payment)->payment_status ? 'bg-success' : 'bg-warning' }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('backend.customers.print_booking_invoice', $booking->id) }}" title="Imprimer la facture (booking)">
                                                    <i class="fa-solid fa-print"></i>
                                                </a>
                                                @if(!optional($booking->payment)->payment_status)
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $booking->id }}" title="Ajouter un paiement">
                                                        <i class="fa-solid fa-credit-card"></i>
                                                    </button>
                                                @else
                                                    <div class="btn-group" role="group">
                                                        <span class="badge bg-success me-2">Payé</span>
                                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#paymentDetailsModal{{ $booking->id }}" title="Voir les détails du paiement">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fa-solid fa-file-invoice fa-2x text-muted d-block mb-2"></i>
                                            <span class="text-muted">{{ __('messages.no_invoices_found') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Payment Modals for each booking --}}
            @foreach(($bookings ?? []) as $booking)
                @if(!optional($booking->payment)->payment_status)
                    @php
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
                    <!-- Payment Modal for Booking {{ $booking->id }} -->
                    <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentModalLabel{{ $booking->id }}">
                                        <i class="fa-solid fa-credit-card me-2"></i>
                                        Paiement - Facture #{{ $booking->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('backend.customers.add-payment', $booking->id) }}" method="POST" id="paymentForm{{ $booking->id }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Détails de la facture</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td><strong>Sous-total:</strong></td>
                                                            <td class="text-end">{{ \Currency::format($subtotal) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Taxes:</strong></td>
                                                            <td class="text-end">{{ \Currency::format($taxAmount) }}</td>
                                                        </tr>
                                                        <tr class="table-primary">
                                                            <td><strong>Total:</strong></td>
                                                            <td class="text-end"><strong>{{ \Currency::format($total) }}</strong></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Informations de paiement</h6>
                                                
                                                <div class="mb-3">
                                                    <label for="payment_method{{ $booking->id }}" class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="payment_method{{ $booking->id }}" name="payment_method" required onchange="togglePaymentFields({{ $booking->id }})">
                                                        <option value="">Sélectionner une méthode</option>
                                                        <option value="cash">Espèces</option>
                                                        <option value="card">Carte bancaire</option>
                                                        <option value="cheque">Chèque</option>
                                                        <option value="transfer">Virement</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="notes{{ $booking->id }}" class="form-label">Notes</label>
                                                    <textarea class="form-control" id="notes{{ $booking->id }}" name="notes" rows="3" placeholder="Notes additionnelles..."></textarea>
                                                </div>

                                                <!-- Chèque specific fields -->
                                                <div id="cheque_fields{{ $booking->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails du chèque</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="cheque_number{{ $booking->id }}" class="form-label">Numéro du chèque</label>
                                                                <input type="text" class="form-control" id="cheque_number{{ $booking->id }}" name="cheque_number" placeholder="Ex: 123456">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="cheque_date{{ $booking->id }}" class="form-label">Date du chèque</label>
                                                                <input type="date" class="form-control" id="cheque_date{{ $booking->id }}" name="cheque_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="bank_name{{ $booking->id }}" class="form-label">Nom de la banque</label>
                                                        <input type="text" class="form-control" id="bank_name{{ $booking->id }}" name="bank_name" placeholder="Ex: BNP Paribas">
                                                    </div>
                                                </div>

                                                <!-- Virement specific fields -->
                                                <div id="transfer_fields{{ $booking->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails du virement</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="transfer_reference{{ $booking->id }}" class="form-label">Référence du virement</label>
                                                                <input type="text" class="form-control" id="transfer_reference{{ $booking->id }}" name="transfer_reference" placeholder="Ex: VIR2025001">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="transfer_date{{ $booking->id }}" class="form-label">Date du virement</label>
                                                                <input type="date" class="form-control" id="transfer_date{{ $booking->id }}" name="transfer_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Carte bancaire specific fields -->
                                                <div id="card_fields{{ $booking->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails de la carte</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="card_last_four{{ $booking->id }}" class="form-label">4 derniers chiffres</label>
                                                                <input type="text" class="form-control" id="card_last_four{{ $booking->id }}" name="card_last_four" placeholder="Ex: 1234" maxlength="4">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="card_type{{ $booking->id }}" class="form-label">Type de carte</label>
                                                                <select class="form-select" id="card_type{{ $booking->id }}" name="card_type">
                                                                    <option value="">Sélectionner</option>
                                                                    <option value="visa">Visa</option>
                                                                    <option value="mastercard">Mastercard</option>
                                                                    <option value="amex">American Express</option>
                                                                    <option value="other">Autre</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="transaction_reference{{ $booking->id }}" class="form-label">Référence de transaction</label>
                                                        <input type="text" class="form-control" id="transaction_reference{{ $booking->id }}" name="transaction_reference" placeholder="Ex: TXN2025001">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="external_transaction_id{{ $booking->id }}" class="form-label">ID Transaction externe</label>
                                                    <input type="text" class="form-control" id="external_transaction_id{{ $booking->id }}" name="external_transaction_id" placeholder="Optionnel">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-check me-2"></i>
                                            Enregistrer le paiement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Payment Details Modals for each paid booking --}}
            @foreach(($bookings ?? []) as $booking)
                @if(optional($booking->payment)->payment_status)
                    @php
                        $payment = $booking->payment;
                        $serviceSubtotal = ($booking->services ?? collect())->sum('service_price');
                        $productSubtotal = ($booking->products ?? collect())->sum(function($p){
                            $unit = ($p->discounted_price && $p->discounted_price > 0) ? $p->discounted_price : $p->product_price;
                            return $unit * (int) $p->product_qty;
                        });
                        $packageSubtotal = ($booking->bookingPackages ?? collect())->sum('package_price');
                        $subtotal = ($serviceSubtotal + $productSubtotal + $packageSubtotal);
                        $taxAmount = 0;
                        if ($payment && $payment->tax_percentage && is_array($payment->tax_percentage)) {
                            foreach ($payment->tax_percentage as $tax) {
                                if (($tax['type'] ?? '') === 'percent') {
                                    $taxAmount += ($subtotal * (float) ($tax['percent'] ?? 0)) / 100;
                                } elseif (($tax['type'] ?? '') === 'fixed') {
                                    $taxAmount += (float) ($tax['tax_amount'] ?? 0);
                                }
                            }
                        }
                        $total = $subtotal + $taxAmount + (float) ($payment->tip_amount ?? 0);
                    @endphp
                    <!-- Payment Details Modal for Booking {{ $booking->id }} -->
                    <div class="modal fade" id="paymentDetailsModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentDetailsModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentDetailsModalLabel{{ $booking->id }}">
                                        <i class="fa-solid fa-receipt me-2"></i>
                                        Détails du paiement - Facture #{{ $booking->id }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Détails de la facture</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Date de la facture:</strong></td>
                                                        <td>{{ optional($booking->created_at)->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sous-total:</strong></td>
                                                        <td class="text-end">{{ \Currency::format($subtotal) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Taxes:</strong></td>
                                                        <td class="text-end">{{ \Currency::format($taxAmount) }}</td>
                                                    </tr>
                                                   
                                                    <tr class="table-primary">
                                                        <td><strong>Total:</strong></td>
                                                        <td class="text-end"><strong>{{ \Currency::format($total) }}</strong></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Informations de paiement</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Méthode de paiement:</strong></td>
                                                        <td>
                                                            @switch($payment->transaction_type)
                                                                @case('cash')
                                                                    <span class="badge bg-success">Espèces</span>
                                                                    @break
                                                                @case('card')
                                                                    <span class="badge bg-primary">Carte bancaire</span>
                                                                    @break
                                                                @case('cheque')
                                                                    <span class="badge bg-warning">Chèque</span>
                                                                    @break
                                                                @case('transfer')
                                                                    <span class="badge bg-info">Virement</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ $payment->transaction_type }}</span>
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>État du paiement:</strong></td>
                                                        <td>
                                                            @if($payment->payment_status)
                                                                <span class="badge bg-success">Paiement complet</span>
                                                            @else
                                                                <span class="badge bg-warning">Paiement partiel</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date du paiement:</strong></td>
                                                        <td>{{ optional($payment->created_at)->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    @if($payment->external_transaction_id)
                                                        <tr>
                                                            <td><strong>ID Transaction externe:</strong></td>
                                                            <td>{{ $payment->external_transaction_id }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($payment->notes)
                                                        <tr>
                                                            <td><strong>Notes:</strong></td>
                                                            <td>{{ $payment->notes }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Payment Method Specific Details --}}
                                    @if($payment->transaction_type === 'cheque' && ($payment->cheque_number || $payment->cheque_date || $payment->bank_name))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails du chèque</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($payment->cheque_number)
                                                            <tr>
                                                                <td><strong>Numéro du chèque:</strong></td>
                                                                <td>{{ $payment->cheque_number }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($payment->cheque_date)
                                                            <tr>
                                                                <td><strong>Date du chèque:</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($payment->cheque_date)->format('d/m/Y') }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($payment->bank_name)
                                                            <tr>
                                                                <td><strong>Nom de la banque:</strong></td>
                                                                <td>{{ $payment->bank_name }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($payment->transaction_type === 'transfer' && ($payment->transfer_reference || $payment->transfer_date))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails du virement</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($payment->transfer_reference)
                                                            <tr>
                                                                <td><strong>Référence du virement:</strong></td>
                                                                <td>{{ $payment->transfer_reference }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($payment->transfer_date)
                                                            <tr>
                                                                <td><strong>Date du virement:</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($payment->transfer_date)->format('d/m/Y') }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($payment->transaction_type === 'card' && ($payment->card_last_four || $payment->card_type || $payment->transaction_reference))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails de la carte</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($payment->card_last_four)
                                                            <tr>
                                                                <td><strong>4 derniers chiffres:</strong></td>
                                                                <td>**** **** **** {{ $payment->card_last_four }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($payment->card_type)
                                                            <tr>
                                                                <td><strong>Type de carte:</strong></td>
                                                                <td>
                                                                    @switch($payment->card_type)
                                                                        @case('visa')
                                                                            <span class="badge bg-primary">Visa</span>
                                                                            @break
                                                                        @case('mastercard')
                                                                            <span class="badge bg-danger">Mastercard</span>
                                                                            @break
                                                                        @case('amex')
                                                                            <span class="badge bg-info">American Express</span>
                                                                            @break
                                                                        @default
                                                                            <span class="badge bg-secondary">{{ ucfirst($payment->card_type) }}</span>
                                                                    @endswitch
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if($payment->transaction_reference)
                                                            <tr>
                                                                <td><strong>Référence de transaction:</strong></td>
                                                                <td>{{ $payment->transaction_reference }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" onclick="removePayment({{ $booking->id }}, 'booking')" title="Supprimer le paiement">
                                        <i class="fa-solid fa-trash me-2"></i>
                                        Supprimer le paiement
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Payment Modals for Devis Factures --}}
            @foreach(($devisFactures ?? []) as $facture)
                @if($facture->id && $facture->status !== 'paid')
                    <!-- Payment Modal for Devis Facture {{ $facture->id }} -->
                    <div class="modal fade" id="devisFacturePaymentModal{{ $facture->id }}" tabindex="-1" aria-labelledby="devisFacturePaymentModalLabel{{ $facture->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="devisFacturePaymentModalLabel{{ $facture->id }}">
                                        <i class="fa-solid fa-credit-card me-2"></i>
                                        Paiement - Facture Devis #{{ $facture->facture_number }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('backend.customers.add-devis-facture-payment', $facture->id) }}" method="POST" id="devisFacturePaymentForm{{ $facture->id }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Détails de la facture</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <td><strong>N° Facture:</strong></td>
                                                            <td>{{ $facture->facture_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>N° Devis:</strong></td>
                                                            <td>{{ $facture->devis?->devis_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Sous-total:</strong></td>
                                                            <td class="text-end">{{ \Currency::format($facture->subtotal) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Taxes:</strong></td>
                                                            <td class="text-end">{{ \Currency::format($facture->tax_amount) }}</td>
                                                        </tr>
                                                        <tr class="table-primary">
                                                            <td><strong>Total:</strong></td>
                                                            <td class="text-end"><strong>{{ \Currency::format($facture->total_amount) }}</strong></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Informations de paiement</h6>
                                                
                                                <div class="mb-3">
                                                    <label for="payment_method_facture{{ $facture->id }}" class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="payment_method_facture{{ $facture->id }}" name="payment_method" required onchange="togglePaymentFieldsFacture({{ $facture->id }})">
                                                        <option value="">Sélectionner une méthode</option>
                                                        <option value="cash">Espèces</option>
                                                        <option value="card">Carte bancaire</option>
                                                        <option value="cheque">Chèque</option>
                                                        <option value="transfer">Virement</option>
                                                    </select>
                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label for="payment_state_facture{{ $facture->id }}" class="form-label">État du paiement <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="payment_state_facture{{ $facture->id }}" name="payment_state" required>
                                                        <option value="full">Paiement complet</option>
                                                        <option value="partial">Paiement partiel</option>
                                                    </select>
                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="notes_facture{{ $facture->id }}" class="form-label">Notes</label>
                                                    <textarea class="form-control" id="notes_facture{{ $facture->id }}" name="notes" rows="3" placeholder="Notes additionnelles..."></textarea>
                                                </div>

                                                <!-- Chèque specific fields -->
                                                <div id="cheque_fields_facture{{ $facture->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails du chèque</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="cheque_number_facture{{ $facture->id }}" class="form-label">Numéro du chèque</label>
                                                                <input type="text" class="form-control" id="cheque_number_facture{{ $facture->id }}" name="cheque_number" placeholder="Ex: 123456">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="cheque_date_facture{{ $facture->id }}" class="form-label">Date du chèque</label>
                                                                <input type="date" class="form-control" id="cheque_date_facture{{ $facture->id }}" name="cheque_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="bank_name_facture{{ $facture->id }}" class="form-label">Nom de la banque</label>
                                                        <input type="text" class="form-control" id="bank_name_facture{{ $facture->id }}" name="bank_name" placeholder="Ex: BNP Paribas">
                                                    </div>
                                                </div>

                                                <!-- Virement specific fields -->
                                                <div id="transfer_fields_facture{{ $facture->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails du virement</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="transfer_reference_facture{{ $facture->id }}" class="form-label">Référence du virement</label>
                                                                <input type="text" class="form-control" id="transfer_reference_facture{{ $facture->id }}" name="transfer_reference" placeholder="Ex: VIR2025001">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="transfer_date_facture{{ $facture->id }}" class="form-label">Date du virement</label>
                                                                <input type="date" class="form-control" id="transfer_date_facture{{ $facture->id }}" name="transfer_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Carte bancaire specific fields -->
                                                <div id="card_fields_facture{{ $facture->id }}" class="payment-method-fields" style="display: none;">
                                                    <h6 class="text-primary mb-3">Détails de la carte</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="card_last_four_facture{{ $facture->id }}" class="form-label">4 derniers chiffres</label>
                                                                <input type="text" class="form-control" id="card_last_four_facture{{ $facture->id }}" name="card_last_four" placeholder="Ex: 1234" maxlength="4">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="card_type_facture{{ $facture->id }}" class="form-label">Type de carte</label>
                                                                <select class="form-select" id="card_type_facture{{ $facture->id }}" name="card_type">
                                                                    <option value="">Sélectionner</option>
                                                                    <option value="visa">Visa</option>
                                                                    <option value="mastercard">Mastercard</option>
                                                                    <option value="amex">American Express</option>
                                                                    <option value="other">Autre</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="transaction_reference_facture{{ $facture->id }}" class="form-label">Référence de transaction</label>
                                                        <input type="text" class="form-control" id="transaction_reference_facture{{ $facture->id }}" name="transaction_reference" placeholder="Ex: TXN2025001">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="external_transaction_id_facture{{ $facture->id }}" class="form-label">ID Transaction externe</label>
                                                    <input type="text" class="form-control" id="external_transaction_id_facture{{ $facture->id }}" name="external_transaction_id" placeholder="Optionnel">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-check me-2"></i>
                                            Enregistrer le paiement
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Payment Details Modals for Devis Factures --}}
            @foreach(($devisFactures ?? []) as $facture)
                @if($facture->id && $facture->status === 'paid')
                    <!-- Payment Details Modal for Devis Facture {{ $facture->id }} -->
                    <div class="modal fade" id="devisFacturePaymentDetailsModal{{ $facture->id }}" tabindex="-1" aria-labelledby="devisFacturePaymentDetailsModalLabel{{ $facture->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="devisFacturePaymentDetailsModalLabel{{ $facture->id }}">
                                        <i class="fa-solid fa-receipt me-2"></i>
                                        Détails du paiement - Facture Devis #{{ $facture->facture_number }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Détails de la facture</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>N° Facture:</strong></td>
                                                        <td>{{ $facture->facture_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>N° Devis:</strong></td>
                                                        <td>{{ $facture->devis?->devis_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date de la facture:</strong></td>
                                                        <td>{{ optional($facture->issued_at)->format('d/m/Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sous-total:</strong></td>
                                                        <td class="text-end">{{ \Currency::format($facture->subtotal) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Taxes:</strong></td>
                                                        <td class="text-end">{{ \Currency::format($facture->tax_amount) }}</td>
                                                    </tr>
                                                    <tr class="table-primary">
                                                        <td><strong>Total:</strong></td>
                                                        <td class="text-end"><strong>{{ \Currency::format($facture->total_amount) }}</strong></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">Informations de paiement</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Méthode de paiement:</strong></td>
                                                        <td>
                                                            @if($facture->payment)
                                                                @switch($facture->payment->transaction_type)
                                                                    @case('cash')
                                                                        <span class="badge bg-success">Espèces</span>
                                                                        @break
                                                                    @case('card')
                                                                        <span class="badge bg-primary">Carte bancaire</span>
                                                                        @break
                                                                    @case('cheque')
                                                                        <span class="badge bg-warning">Chèque</span>
                                                                        @break
                                                                    @case('transfer')
                                                                        <span class="badge bg-info">Virement</span>
                                                                        @break
                                                                    @default
                                                                        <span class="badge bg-secondary">{{ $facture->payment->transaction_type }}</span>
                                                                @endswitch
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>État du paiement:</strong></td>
                                                        <td>
                                                            <span class="badge bg-success">Paiement complet</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date du paiement:</strong></td>
                                                        <td>{{ optional($facture->payment?->created_at)->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    @if($facture->payment?->external_transaction_id)
                                                        <tr>
                                                            <td><strong>ID Transaction externe:</strong></td>
                                                            <td>{{ $facture->payment->external_transaction_id }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($facture->payment?->notes)
                                                        <tr>
                                                            <td><strong>Notes:</strong></td>
                                                            <td>{{ $facture->payment->notes }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Payment Method Specific Details --}}
                                    @if($facture->payment && $facture->payment->transaction_type === 'cheque' && ($facture->payment->cheque_number || $facture->payment->cheque_date || $facture->payment->bank_name))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails du chèque</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($facture->payment->cheque_number)
                                                            <tr>
                                                                <td><strong>Numéro du chèque:</strong></td>
                                                                <td>{{ $facture->payment->cheque_number }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($facture->payment->cheque_date)
                                                            <tr>
                                                                <td><strong>Date du chèque:</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($facture->payment->cheque_date)->format('d/m/Y') }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($facture->payment->bank_name)
                                                            <tr>
                                                                <td><strong>Nom de la banque:</strong></td>
                                                                <td>{{ $facture->payment->bank_name }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($facture->payment && $facture->payment->transaction_type === 'transfer' && ($facture->payment->transfer_reference || $facture->payment->transfer_date))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails du virement</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($facture->payment->transfer_reference)
                                                            <tr>
                                                                <td><strong>Référence du virement:</strong></td>
                                                                <td>{{ $facture->payment->transfer_reference }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($facture->payment->transfer_date)
                                                            <tr>
                                                                <td><strong>Date du virement:</strong></td>
                                                                <td>{{ \Carbon\Carbon::parse($facture->payment->transfer_date)->format('d/m/Y') }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($facture->payment && $facture->payment->transaction_type === 'card' && ($facture->payment->card_last_four || $facture->payment->card_type || $facture->payment->transaction_reference))
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Détails de la carte</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        @if($facture->payment->card_last_four)
                                                            <tr>
                                                                <td><strong>4 derniers chiffres:</strong></td>
                                                                <td>**** **** **** {{ $facture->payment->card_last_four }}</td>
                                                            </tr>
                                                        @endif
                                                        @if($facture->payment->card_type)
                                                            <tr>
                                                                <td><strong>Type de carte:</strong></td>
                                                                <td>
                                                                    @switch($facture->payment->card_type)
                                                                        @case('visa')
                                                                            <span class="badge bg-primary">Visa</span>
                                                                            @break
                                                                        @case('mastercard')
                                                                            <span class="badge bg-danger">Mastercard</span>
                                                                            @break
                                                                        @case('amex')
                                                                            <span class="badge bg-info">American Express</span>
                                                                            @break
                                                                        @default
                                                                            <span class="badge bg-secondary">{{ ucfirst($facture->payment->card_type) }}</span>
                                                                    @endswitch
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if($facture->payment->transaction_reference)
                                                            <tr>
                                                                <td><strong>Référence de transaction:</strong></td>
                                                                <td>{{ $facture->payment->transaction_reference }}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" onclick="removePayment({{ $facture->id }}, 'facture')" title="Supprimer le paiement">
                                        <i class="fa-solid fa-trash me-2"></i>
                                        Supprimer le paiement
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Converted Devis to Factures --}}
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-primary">
                            <i class="fa-solid fa-file-invoice-dollar me-2"></i>
                            {{ __('messages.invoices') }} - Devis convertis
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Facture</th>
                                    <th>N° Devis</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th class="text-end">{{ __('messages.subtotal') }}</th>
                                    <th class="text-end">{{ __('messages.tax') }}</th>
                                    <th class="text-end">{{ __('messages.total') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($devisFactures ?? []) as $facture)
                                    <tr>
                                        <td>{{ $facture->facture_number }}</td>
                                        <td>{{ $facture->devis?->devis_number }}</td>
                                        <td>{{ optional($facture->issued_at)->format('Y-m-d') }}</td>
                                        <td class="text-end">{{ \Currency::format($facture->subtotal) }}</td>
                                        <td class="text-end">{{ \Currency::format($facture->tax_amount) }}</td>
                                        <td class="text-end">{{ \Currency::format($facture->total_amount) }}</td>
                                        <td>
                                            <span class="badge {{ $facture->status === 'paid' ? 'bg-success' : ($facture->status === 'cancelled' ? 'bg-secondary' : 'bg-warning') }}">{{ ucfirst($facture->status) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if(!$facture->devis?->facture)
                                                    <form method="POST" action="{{ route('backend.customers.convert_devis', $facture->devis?->id) }}" onsubmit="return confirm('Convertir ce devis en facture ?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fa-solid fa-right-left me-1"></i> Convertir
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($facture->id)
                                                    <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('backend.customers.print_facture_pdf', $facture->id) }}" title="Imprimer la facture PDF">
                                                        <i class="fa-solid fa-print"></i>
                                                    </a>
                                                    
                                                    @if($facture->status !== 'paid')
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#devisFacturePaymentModal{{ $facture->id }}" title="Ajouter un paiement">
                                                            <i class="fa-solid fa-credit-card"></i>
                                                        </button>
                                                    @else
                                                        <div class="btn-group" role="group">
                                                            <span class="badge bg-success me-2">Payé</span>
                                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#devisFacturePaymentDetailsModal{{ $facture->id }}" title="Voir les détails du paiement">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fa-solid fa-file-circle-question fa-2x text-muted d-block mb-2"></i>
                                            <span class="text-muted">Aucune facture convertie depuis un devis</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devis Tab -->
        <!-- Actes Tab -->
        <div class="tab-pane fade" id="listes-actes" role="tabpanel" aria-labelledby="listes-actes-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-list-ul me-2"></i>
                    Actes
                </h5>
                <button type="button" class="btn btn-primary" id="openActModalBtn" data-bs-toggle="modal" data-bs-target="#actModal">
                    <i class="fa-solid fa-plus me-2"></i>
                    Ajouter un acte
                </button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="acts-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Employé</th>
                                    <th>Branche</th>
                                    <th>Prix</th>
                                    <th>Durée (min)</th>
                                    <th>Note</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="acts-tbody"></tbody>
                        </table>
                    </div>
                    <div id="acts-loading" class="text-center py-4" style="display: none;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">{{ __('customer::show.loading') }}</p>
                    </div>
                    <div id="acts-empty" class="text-center py-5" style="display: none;">
                        <i class="fa-solid fa-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun acte trouvé</h5>
                        <p class="text-muted">Ajouter le premier acte pour ce patient.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#actModal">
                            <i class="fa-solid fa-plus me-2"></i>
                            Ajouter un acte
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photothèques Tab -->
        <div class="tab-pane fade" id="phototheque" role="tabpanel" aria-labelledby="phototheque-tab">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-4">
                <div class="flex-grow-1" style="max-width:520px;">
                    <label for="actSelect" class="form-label mb-1">Sélectionner un acte</label>
                    <select id="actSelect" class="form-select">
                        <option value="">-- Choisir un acte --</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="refreshGalleryBtn">
                        <i class="fa-solid fa-rotate me-2"></i>Actualiser
                    </button>
                    <button type="button" class="btn btn-primary" id="openGallerySessionModalBtn" data-bs-toggle="modal" data-bs-target="#gallerySessionModal" disabled>
                        <i class="fa-solid fa-plus me-2"></i>Nouvelle session
                    </button>
                </div>
            </div>

            <div id="gallery-loading" class="text-center py-4" style="display:none;">
                <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2">Chargement...</p>
            </div>

            <div id="gallery-empty" class="text-center py-5" style="display:none;">
                <i class="fa-solid fa-photo-film fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucune session trouvée</h5>
                <p class="text-muted">Créez une session "Avant" ou "Après" pour commencer.</p>
            </div>

            <div id="gallery-sessions" class="row g-3"></div>
        </div>

        <!-- Devis Tab -->
        <div class="tab-pane fade" id="devis" role="tabpanel" aria-labelledby="devis-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-file-invoice me-2"></i>
                    {{ __('messages.devis') }}
                </h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDevisModal">
                    <i class="fa-solid fa-plus me-2"></i>
                    {{ __('messages.create_devis') }}
                </button>
            </div>
            
            <!-- Devis List -->
            <div id="devis-list">
                <div class="empty-tab" id="empty-devis">
                    <i class="fa-solid fa-file-invoice"></i>
                    <h4>{{ __('messages.no_devis_available') }}</h4>
                    <p>{{ __('messages.click_create_devis_to_start') }}</p>
                </div>
                
                <!-- Services Table -->
                <div id="services-table-container" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-list me-2"></i>
                                {{ __('messages.added_services') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="services-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.service_name') }}</th>
                                            <th>{{ __('messages.quantity') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.discount') }}</th>
                                            <th>{{ __('messages.subtotal') }}</th>
                                            <th>TVA %</th>
                                            <th>{{ __('messages.tax') }}</th>
                                            <th>{{ __('messages.total') }}</th>
                                            <th>Lot #</th>
                                            <th>{{ __('messages.remarks') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="services-table-body">
                                        <!-- Services will be added here -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Summary -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <strong>{{ __('messages.subtotal') }}:</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    $<span id="subtotal-amount">0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <strong>{{ __('messages.tax') }}:</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    $<span id="tax-amount">20.00</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-6">
                                                    <strong>{{ __('messages.total_amount') }}:</strong>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <strong>$<span id="total-amount">0.00</span></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-success btn-lg" id="saveDevisBtn">
                                            <i class="fa-solid fa-save me-2"></i>
                                            {{ __('messages.save_devis') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="printDevisBtn">
                                            <i class="fa-solid fa-print me-2"></i>
                                            {{ __('messages.print_devis') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devis List Tab -->
        <div class="tab-pane fade" id="devis-list" role="tabpanel" aria-labelledby="devis-list-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-list me-2"></i>
                    {{ __('messages.devis_list') }}
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="refreshDevisList">
                        <i class="fa-solid fa-refresh me-2"></i>
                        {{ __('messages.refresh') }}
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDevisModal">
                        <i class="fa-solid fa-plus me-2"></i>
                        {{ __('messages.create_devis') }}
                    </button>
                </div>
            </div>
            
            <!-- Devis List Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="devis-list-table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.devis_number') }}</th>
                                    <th>{{ __('messages.package') }}</th>
                                    <th>{{ __('messages.subtotal') }}</th>
                                    <th>{{ __('messages.tax') }}</th>
                                    <th>{{ __('messages.total') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.valid_until') }}</th>
                                    <th>Number of Lot</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="devis-list-tbody">
                                <!-- Devis list will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Loading and Empty States -->
                    <div id="devis-list-loading" class="text-center py-4" style="display: none;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">{{ __('messages.loading') }}...</p>
                    </div>
                    
                    <div id="devis-list-empty" class="text-center py-5" style="display: none;">
                        <i class="fa-solid fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('messages.no_devis_found') }}</h5>
                        <p class="text-muted">{{ __('messages.create_first_devis') }}</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDevisModal">
                            <i class="fa-solid fa-plus me-2"></i>
                            {{ __('messages.create_devis') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devis History Tab -->
        <div class="tab-pane fade" id="devis-history" role="tabpanel" aria-labelledby="devis-history-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-history me-2"></i>
                    {{ __('customer::show.devis_history') }}
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="refreshDevisHistory">
                        <i class="fa-solid fa-refresh me-2"></i>
                        {{ __('customer::show.loading') }}
                    </button>
                </div>
            </div>
            
            <!-- Devis History Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="devis-history-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('messages.devis_number') }}</th>
                                    <th>{{ __('messages.package') }}</th>
                                    <th>{{ __('messages.subtotal') }}</th>
                                    <th>{{ __('messages.tax') }}</th>
                                    <th>{{ __('messages.total') }}</th>
                                    {{-- <th>{{ __('messages.status') }}</th> --}}
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.valid_until') }}</th>
                                    <th>{{ __('messages.number_of_lot') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="devis-history-tbody">
                                <!-- Devis history will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Loading and Empty States -->
                    <div id="devis-history-loading" class="text-center py-4" style="display: none;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">{{ __('customer::show.loading') }}</p>
                    </div>
                    
                    <div id="devis-history-empty" class="text-center py-5" style="display: none;">
                        <i class="fa-solid fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('customer::show.no_devis_available') }}</h5>
                        <p class="text-muted">{{ __('customer::show.no_services_in_package') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Records Tab -->
        <div class="tab-pane fade" id="medical-records" role="tabpanel" aria-labelledby="medical-records-tab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-notes-medical me-2"></i>
                    Dossiers Médicaux
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="refreshMedicalRecords">
                        <i class="fa-solid fa-refresh me-2"></i>
                        {{ __('messages.refresh') }}
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicalRecordModal">
                        <i class="fa-solid fa-file-circle-plus me-2"></i>
                        Ajouter un document
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="medical-records-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Document</th>
                                    <th>Title</th>
                                    <th>Note</th>
                                    <th>Créé à / Modifié à</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="medical-records-tbody">
                                <!-- Records will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <div id="medical-records-loading" class="text-center py-4" style="display: none;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">{{ __('customer::show.loading') }}</p>
                    </div>

                    <div id="medical-records-empty" class="text-center py-5" style="display: none;">
                        <i class="fa-solid fa-file-medical fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun dossier médical trouvé</h5>
                        <p class="text-muted">Ajouter le premier dossier médical pour ce patient.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicalRecordModal">
                            <i class="fa-solid fa-file-circle-plus me-2"></i>
                            Add Document
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical History Tab -->
        <div class="tab-pane fade" id="medical-history" role="tabpanel" aria-labelledby="medical-history-tab">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-primary">
                    <i class="fa-solid fa-briefcase-medical me-2"></i>
                    Antécédents médicaux
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="refreshMedicalHistory">
                        <i class="fa-solid fa-refresh me-2"></i> {{ __('messages.refresh') }}
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicalHistoryModal">
                        <i class="fa-solid fa-plus me-2"></i> Ajouter un antécédent
                    </button>
                </div>
            </div>

            <div id="mh-list">
                <div id="mh-loading" class="text-center py-4" style="display:none;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2">{{ __('customer::show.loading') }}</p>
                </div>
                <div id="mh-empty" class="text-center py-5" style="display:none;">
                    <i class="fa-solid fa-heart-pulse fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun antécédent médical trouvé</h5>
                    <p class="text-muted">Ajouter le premier antécédent pour ce patient.</p>
                </div>
                <div id="mh-items" class="vstack gap-2"></div>
            </div>
        </div>

    <div id="devis-tab-content"></div>
    <div id="devis-history-tab-content"></div>
    <div id="invoice-tab-content"></div>
    <div id="consultations-tab-content"></div>
    <div id="consentement-tab-content"></div>
    <div id="listes-actes-tab-content"></div>
    <div id="phototheque-tab-content"></div>
    </div>
</div>

<!-- Pré-consultation Modal -->
<div class="modal fade" id="preconsultationModal" tabindex="-1" aria-labelledby="preconsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preconsultationModalLabel">Formulaire d'Information Patient (Pré-consultation)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="preconsultationForm">
                    <input type="hidden" name="customer_id" value="{{ $data->id }}">

                    <!-- Stepper indicator -->
                    <div class="step-indicator mb-3">
                        <div class="step active" data-step="1">1</div>
                        <div class="step" data-step="2">2</div>
                        <div class="step" data-step="3">3</div>
                        <div class="step" data-step="4">4</div>
                        <div class="step" data-step="5">5</div>
                        <div class="step" data-step="6">6</div>
                        <div class="step" data-step="7">7</div>
                        <div class="step" data-step="8">8</div>
                    </div>

                    <!-- Step 1: Identité -->
                    <div class="devis-step" data-step-panel="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" name="identite_nom" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="identite_prenom" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de Naissance</label>
                                <input type="date" name="identite_date_naissance" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="identite_email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="identite_telephone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adresse</label>
                                <input type="text" name="identite_adresse" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profession</label>
                                <input type="text" name="identite_profession" class="form-control">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="identite_newsletter" name="identite_newsletter" value="1">
                                    <label class="form-check-label" for="identite_newsletter">Souhaitez-vous recevoir nos nouveautés par email ?</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Antécédents Médicaux et Chirurgicaux -->
                    <div class="devis-step" data-step-panel="2" style="display:none;">
                        <label class="form-label">Antécédents Médicaux et Chirurgicaux</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="aucuns" id="a1"><label class="form-check-label" for="a1">Aucuns</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="herpes" id="a2"><label class="form-check-label" for="a2">Herpès</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="diabete" id="a3"><label class="form-check-label" for="a3">Diabète</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="epilepsie" id="a4"><label class="form-check-label" for="a4">Épilepsie</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="cancer" id="a5"><label class="form-check-label" for="a5">Cancer</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="maladie_virale" id="a6"><label class="form-check-label" for="a6">Maladie virale (Hépatite, HIV, Sida)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="maladie_bacterienne" id="a7"><label class="form-check-label" for="a7">Maladie bactérienne</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="glaucome" id="a8"><label class="form-check-label" for="a8">Glaucome</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="depression" id="a9"><label class="form-check-label" for="a9">Dépression / Troubles psychiatriques</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="troubles_alimentaires" id="a10"><label class="form-check-label" for="a10">Anorexie / Boulimie</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="antecedents[]" value="addictions" id="a11"><label class="form-check-label" for="a11">Addictions (Toxico, Alcool)</label></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Maladies auto-immunes -->
                    <div class="devis-step" data-step-panel="3" style="display:none;">
                        <label class="form-label">Avez-vous des maladies auto-immunes suivantes ?</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="aucune" id="m1"><label class="form-check-label" for="m1">Aucune</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="crohn" id="m2"><label class="form-check-label" for="m2">Maladie de Crohn</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="lupus" id="m3"><label class="form-check-label" for="m3">Lupus</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="thyroidite" id="m4"><label class="form-check-label" for="m4">Thyroïdite</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="polyarthrite_rhumatoide" id="m5"><label class="form-check-label" for="m5">Polyarthrite rhumatoïde</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="sclerodermie" id="m6"><label class="form-check-label" for="m6">Sclérodermie</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="autoimmunes[]" value="sep" id="m7"><label class="form-check-label" for="m7">SEP (Sclérose en plaques)</label></div>
                                <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="autoimmunes_autre"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Réactions allergiques post-acte -->
                    <div class="devis-step" data-step-panel="4" style="display:none;">
                        <label class="form-label">Avez-vous eu des réactions allergiques suite à un acte esthétique ?</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="aucune" id="r1"><label class="form-check-label" for="r1">Aucune</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="urticaire" id="r2"><label class="form-check-label" for="r2">Urticaire</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="reaction_nodulaire" id="r3"><label class="form-check-label" for="r3">Réaction nodulaire</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="allergies[]" value="taches" id="r4"><label class="form-check-label" for="r4">Taches</label></div>
                                <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="allergies_autre"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Traitements médicamenteux -->
                    <div class="devis-step" data-step-panel="5" style="display:none;">
                        <label class="form-label">Vos traitements médicamenteux</label>
                        <textarea name="traitements" class="form-control" rows="4" placeholder="Listez vos traitements et compléments actuels"></textarea>
                    </div>

                    <!-- Step 6: Antécédents esthétiques -->
                    <div class="devis-step" data-step-panel="6" style="display:none;">
                        <label class="form-label">Antécédents Esthétiques</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="comblement" id="e1"><label class="form-check-label" for="e1">Injection de comblement</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="toxine_botulique" id="e2"><label class="form-check-label" for="e2">Toxine botulique</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="peeling" id="e3"><label class="form-check-label" for="e3">Peeling</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="laser_visage" id="e4"><label class="form-check-label" for="e4">Laser visage</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="chirurgie_visage" id="e5"><label class="form-check-label" for="e5">Chirurgie esthétique du visage</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="chirurgie_silhouette" id="e6"><label class="form-check-label" for="e6">Chirurgie esthétique de la silhouette</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="laser_epilation" id="e7"><label class="form-check-label" for="e7">Laser épilation</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="cryolipolyse" id="e8"><label class="form-check-label" for="e8">Cryolipolyse</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="esthetiques[]" value="emsculpt" id="e9"><label class="form-check-label" for="e9">EMsculpt</label></div>
                                <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="esthetiques_autre"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7: Motif principal -->
                    <div class="devis-step" data-step-panel="7" style="display:none;">
                        <label class="form-label">Votre motif principal de consultation ce jour</label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="troubles_rides" id="mcf1"><label class="form-check-label" for="mcf1">Troubles des rides</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="volumetrie_visage" id="mcf2"><label class="form-check-label" for="mcf2">Volumétrie du visage</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="fils_tenseurs" id="mcf3"><label class="form-check-label" for="mcf3">Fils tenseurs</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="rhinoplastie_medicale" id="mcf4"><label class="form-check-label" for="mcf4">Rhinoplastie médicale</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="peeling" id="mcf5"><label class="form-check-label" for="mcf5">Peeling</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="hydrafacial" id="mcf6"><label class="form-check-label" for="mcf6">Hydrafacial</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="nettoyage_peau" id="mcf7"><label class="form-check-label" for="mcf7">Nettoyage de peau dermatologique</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="laser_peau" id="mcf8"><label class="form-check-label" for="mcf8">Laser de peau (HIFU, Frax, Plexr)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="coup_eclat" id="mcf9"><label class="form-check-label" for="mcf9">Coup d'éclat / Réhydratation</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="cosmetologie" id="mcf10"><label class="form-check-label" for="mcf10">Cosmétologie sur mesure</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="cheveu" id="mcf11"><label class="form-check-label" for="mcf11">Traitement du cheveu</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="mains" id="mcf12"><label class="form-check-label" for="mcf12">Traitement des mains</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="silhouette_emsculpt" id="mcf13"><label class="form-check-label" for="mcf13">Traitement de la silhouette (EMsculpt)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="led" id="mcf14"><label class="form-check-label" for="mcf14">LED (visage, cheveux, intime)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="epilation" id="mcf15"><label class="form-check-label" for="mcf15">Épilation (laser, électrique)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox" name="motifs[]" value="micronutrition" id="mcf16"><label class="form-check-label" for="mcf16">Micronutrition</label></div>
                                <div class="mt-2"><label class="form-label">Autre</label><input type="text" class="form-control" name="motifs_autre"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 8: Parrainage & Déclaration -->
                    <div class="devis-step" data-step-panel="8" style="display:none;">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="parrainage" name="parrainage" value="1">
                            <label class="form-check-label" for="parrainage">Souhaitez-vous participer à un programme de parrainage ?</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="declaration_exactitude" name="declaration_exactitude" value="1">
                            <label class="form-check-label" for="declaration_exactitude">À ma connaissance, j'atteste l'exactitude de ces informations...</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-outline-primary" id="preconsultationPrev" style="display:none;">
                    <i class="fa-solid fa-arrow-left me-2"></i> Précédent
                </button>
                <button type="button" class="btn btn-primary" id="preconsultationNext">
                    Suivant <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
                <button type="button" class="btn btn-success" id="preconsultationSubmit" style="display:none;">
                    <i class="fa-solid fa-save me-2"></i> Enregistrer
                </button>
                <a href="{{ route('backend.customers.preconsultation.pdf', ['customerId' => $data->id]) }}" target="_blank" class="btn btn-outline-danger">
                    <i class="fa-solid fa-file-pdf me-2"></i> PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu</h5>
                <div class="ms-auto small text-muted" id="previewCounter">1 / 1</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center align-items-center" style="min-height:60vh;background:#111;">
                    <img id="previewImage" src="" alt="Preview" style="max-width:100%;max-height:80vh;object-fit:contain;cursor:zoom-in;">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-light" id="prevImageBtn"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-outline-light" id="nextImageBtn"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
    </div>

<div data-render="app">
    <change-password create-title="{{ __('messages.change_password') }}"></change-password>
</div>

<!-- Medical Record Create Modal -->
<div class="modal fade" id="medicalRecordModal" tabindex="-1" aria-labelledby="medicalRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicalRecordModalLabel">
                    <i class="fa-solid fa-file-circle-plus me-2"></i>
                    Ajouter un document médical
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="medicalRecordForm" enctype="multipart/form-data">
                    <input type="hidden" name="customer_id" value="{{ $data->id }}">
                    <div class="mb-3">
                        <label for="mr_title" class="form-label">Titre</label>
                        <input type="text" id="mr_title" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mr_note" class="form-label">Note</label>
                        <textarea id="mr_note" name="note" class="form-control" rows="3" placeholder="Optional"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="mr_file" class="form-label">Document</label>
                        <input type="file" id="mr_file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" required>
                        <div class="form-text">Allowed: PDF, Images, DOC, XLS</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveMedicalRecordBtn">
                    <i class="fa-solid fa-save me-2"></i>
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
 </div>

<!-- Medical History Create Modal -->
<div class="modal fade" id="medicalHistoryModal" tabindex="-1" aria-labelledby="medicalHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="medicalHistoryModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>
                    Ajouter un antécédent
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="medicalHistoryForm">
                    <input type="hidden" name="customer_id" value="{{ $data->id }}">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" id="mh_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0">Type</label>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="mhAddTypeBtn">
                                <i class="fa-solid fa-plus"></i> Nouveau Type
                            </button>
                        </div>
                        <select class="form-select mt-2" id="mh_type_id" name="type_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Détail</label>
                        <textarea name="detail" id="mh_detail" class="form-control" rows="2" placeholder="Optional"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Médicament / traitement</label>
                        <input type="text" name="medication" id="mh_medication" class="form-control" placeholder="Optional">
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Date de début</label>
                            <input type="date" name="start_date" id="mh_start" class="form-control">
                        </div>
                        <div class="col">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="end_date" id="mh_end" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveMedicalHistoryBtn">
                    <i class="fa-solid fa-save me-2"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Act Create/Edit Modal -->
<div class="modal fade" id="actModal" tabindex="-1" aria-labelledby="actModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actModalLabel">
                    <i class="fa-solid fa-list-ul me-2"></i>
                    Ajouter / Modifier un acte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="actForm">
                    <input type="hidden" id="act_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select id="act_service_id" class="form-select select2" required style="width: 100%"></select>
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Date</label>
                            <input type="datetime-local" id="act_date" class="form-control">
                        </div>
                        <div class="col">
                            <label class="form-label">Statut</label>
                            <select id="act_status" class="form-select"></select>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col">
                            <label class="form-label">Employé</label>
                            <select id="act_employee_id" class="form-select"></select>
                        </div>
                        <div class="col">
                            <label class="form-label">Branche</label>
                            <select id="act_branch_id" class="form-select"></select>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col">
                            <label class="form-label">Prix</label>
                            <input type="number" step="0.01" id="act_service_price" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col">
                            <label class="form-label">Durée (min)</label>
                            <input type="number" id="act_duration_min" class="form-control" placeholder="0">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Note</label>
                        <textarea id="act_note" class="form-control" rows="2" placeholder="Optional"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="saveActBtn">
                    <i class="fa-solid fa-save me-2"></i>
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
 </div>

<script>
    (function() {
        const customerId = {{ $data->id }};
        const routes = {
            list: "{{ route('backend.customers.acts.index', ['customerId' => $data->id]) }}",
            store: "{{ route('backend.customers.acts.store', ['customerId' => $data->id]) }}",
            options: "{{ route('backend.customers.acts.options') }}",
            update: function(id){ return "{{ route('backend.customers.acts.update', ['actId' => 'ACT_ID']) }}".replace('ACT_ID', id); },
            delete: function(id){ return "{{ route('backend.customers.acts.delete', ['actId' => 'ACT_ID']) }}".replace('ACT_ID', id); },
        };

        let actOptionsCache = null;

        function fmtDate(dtStr){
            if(!dtStr) return '';
            try { return new Date(dtStr).toLocaleString(); } catch(e){ return dtStr; }
        }

        function loadOptions(){
            if (actOptionsCache) return Promise.resolve(actOptionsCache);
            return fetch(routes.options, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(r => r.json())
                .then(json => {
                    actOptionsCache = json;
                    const serviceSel = document.getElementById('act_service_id');
                    const empSel = document.getElementById('act_employee_id');
                    const brSel = document.getElementById('act_branch_id');
                    const stSel = document.getElementById('act_status');

                    const serviceOptions = (json.services||[]).map(s => ({ id: s.id, name: s.name, price: s.default_price||0, duration: s.duration_min||0 }));
                    serviceSel.innerHTML = '<option value="">Sélectionner</option>' + serviceOptions.map(s => `<option value="${s.id}" data-price="${s.price}" data-duration="${s.duration}">${s.name}</option>`).join('');

                    if (window.$ && $.fn.select2) {
                        const $serviceSel = $(serviceSel);
                        $serviceSel.select2({ width: '100%', dropdownParent: $('#actModal') });
                    }
                    empSel.innerHTML = '<option value="">Sélectionner</option>' + (json.employees||[]).map(u => `<option value="${u.id}">${u.full_name}</option>`).join('');
                    brSel.innerHTML = '<option value="">Sélectionner</option>' + (json.branches||[]).map(b => `<option value="${b.id}">${b.name}</option>`).join('');
                    stSel.innerHTML = (json.status_list||[]).map(s => `<option value="${s.value}">${s.label}</option>`).join('');

                    // Autofill price/duration when service changes
                    function onServiceChange(){
                        const opt = serviceSel.options[serviceSel.selectedIndex];
                        if(!opt) return;
                        const price = opt.getAttribute('data-price') || '';
                        const duration = opt.getAttribute('data-duration') || '';
                        document.getElementById('act_service_price').value = price;
                        document.getElementById('act_duration_min').value = duration;

                        const serviceId = serviceSel.value;
                        if(!serviceId) return;
                        const url = "{{ route('backend.customers.acts.service_meta', ['serviceId' => 'SERVICE_ID']) }}".replace('SERVICE_ID', serviceId);
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                            .then(r => r.json())
                            .then(json => {
                                const empSel = document.getElementById('act_employee_id');
                                const brSel = document.getElementById('act_branch_id');

                                empSel.innerHTML = '<option value="">Sélectionner</option>' + (json.employees||[]).map(u => `<option value="${u.id}">${u.name}</option>`).join('');
                                brSel.innerHTML = '<option value="">Sélectionner</option>' + (json.branches||[]).map(b => `<option value="${b.id}" data-price="${b.service_price||''}" data-duration="${b.duration_min||''}">${b.name}</option>`).join('');

                                if(json.service){
                                    if(!price) document.getElementById('act_service_price').value = json.service.default_price ?? '';
                                    if(!duration) document.getElementById('act_duration_min').value = json.service.duration_min ?? '';
                                }
                            });
                    }
                    serviceSel.addEventListener('change', onServiceChange);
                    if (window.$ && $.fn.select2) {
                        $(serviceSel).on('select2:select', onServiceChange);
                    }

                    // On branch change, adopt branch-specific price/duration when available
                    document.getElementById('act_branch_id').addEventListener('change', function(){
                        const opt = this.options[this.selectedIndex];
                        if(!opt) return;
                        const bPrice = opt.getAttribute('data-price');
                        const bDur = opt.getAttribute('data-duration');
                        if(bPrice) document.getElementById('act_service_price').value = bPrice;
                        if(bDur) document.getElementById('act_duration_min').value = bDur;
                    });

                    return json;
                });
        }

        function renderActs(list){
            const tbody = document.getElementById('acts-tbody');
            tbody.innerHTML = '';
            list.forEach(act => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${act.service?.name ?? ''}</td>
                    <td>${fmtDate(act.act_date)}</td>
                    <td><span class="badge bg-secondary">${act.status ?? ''}</span></td>
                    <td>${act.employee ? (act.employee.first_name ?? '') + ' ' + (act.employee.last_name ?? '') : ''}</td>
                    <td>${act.branch?.name ?? ''}</td>
                    <td>${(act.service_price ?? 0).toFixed ? (act.service_price).toFixed(2) : act.service_price ?? ''}</td>
                    <td>${act.duration_min ?? ''}</td>
                    <td style="max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="${act.note ?? ''}">${act.note ?? ''}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary act-edit" data-id="${act.id}"><i class="fa-solid fa-pen"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger act-delete" data-id="${act.id}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>`;
                tbody.appendChild(tr);
            });

            document.querySelectorAll('.act-edit').forEach(btn => btn.addEventListener('click', onEditAct));
            document.querySelectorAll('.act-delete').forEach(btn => btn.addEventListener('click', onDeleteAct));

            document.getElementById('acts-empty').style.display = list.length ? 'none' : '';
        }

        function loadActs(){
            document.getElementById('acts-loading').style.display = '';
            document.getElementById('acts-empty').style.display = 'none';
            return fetch(routes.list, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(r => r.json())
                .then(json => {
                    renderActs(json.data || []);
                })
                .finally(() => {
                    document.getElementById('acts-loading').style.display = 'none';
                });
        }

        function resetActForm(){
            document.getElementById('act_id').value = '';
            document.getElementById('actForm').reset();
        }

        function collectActForm(){
            return {
                service_id: document.getElementById('act_service_id').value || null,
                act_date: document.getElementById('act_date').value || null,
                status: document.getElementById('act_status').value || null,
                employee_id: document.getElementById('act_employee_id').value || null,
                branch_id: document.getElementById('act_branch_id').value || null,
                note: document.getElementById('act_note').value || null,
                service_price: document.getElementById('act_service_price').value || null,
                duration_min: document.getElementById('act_duration_min').value || null,
            };
        }

        function onEditAct(e){
            const id = e.currentTarget.getAttribute('data-id');
            const row = e.currentTarget.closest('tr');
            if(!id || !row) return;
            // Extract basic values from row
            const serviceName = row.children[0].innerText;
            const dateTxt = row.children[1].innerText;
            const statusTxt = row.children[2].innerText.trim();
            const empTxt = row.children[3].innerText.trim();
            const branchTxt = row.children[4].innerText.trim();
            const priceTxt = row.children[5].innerText.trim();
            const durationTxt = row.children[6].innerText.trim();
            const noteTxt = row.children[7].getAttribute('title') || '';

            loadOptions().then(() => {
                document.getElementById('act_id').value = id;
                // Try to select by text
                const serviceSel = document.getElementById('act_service_id');
                [...serviceSel.options].forEach(o => { if(o.text === serviceName) serviceSel.value = o.value; });
                document.getElementById('act_date').value = '';
                const statusSel = document.getElementById('act_status');
                [...statusSel.options].forEach(o => { if(o.text === statusTxt || o.value === statusTxt.toLowerCase()) statusSel.value = o.value; });
                const empSel = document.getElementById('act_employee_id');
                [...empSel.options].forEach(o => { if(o.text.trim() === empTxt) empSel.value = o.value; });
                const brSel = document.getElementById('act_branch_id');
                [...brSel.options].forEach(o => { if(o.text.trim() === branchTxt) brSel.value = o.value; });
                document.getElementById('act_service_price').value = (priceTxt||'').replace(/[^0-9.,-]/g,'').replace(',','.');
                document.getElementById('act_duration_min').value = durationTxt;
                document.getElementById('act_note').value = noteTxt;

                const modal = new bootstrap.Modal(document.getElementById('actModal'));
                modal.show();
            });
        }

        function onDeleteAct(e){
            const id = e.currentTarget.getAttribute('data-id');
            if(!id) return;
            if(!confirm('Supprimer cet acte ?')) return;
            fetch(routes.delete(id), { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
                .then(r => r.json())
                .then(() => loadActs());
        }

        document.getElementById('saveActBtn').addEventListener('click', function(){
            const id = document.getElementById('act_id').value;
            const payload = collectActForm();
            const method = id ? 'PUT' : 'POST';
            const url = id ? routes.update(id) : routes.store;
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(json => {
                if(json.status){
                    const modalEl = document.getElementById('actModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                    resetActForm();
                    loadActs();
                } else {
                    alert(json.message || 'Erreur');
                }
            });
        });

        // Load when tab shown
        document.getElementById('listes-actes-tab').addEventListener('shown.bs.tab', function(){
            loadOptions().then(loadActs);
        });
        // Also preload options early
        loadOptions();

        // Reset form when opening create
        document.getElementById('openActModalBtn').addEventListener('click', function(){
            resetActForm();
        });
    })();
</script>

<!-- Create Devis Modal -->
<div class="modal fade" id="createDevisModal" tabindex="-1" aria-labelledby="createDevisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDevisModalLabel">
                    <i class="fa-solid fa-file-invoice me-2"></i>
                    {{ __('messages.create_devis') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="devisForm">
                    <input type="hidden" id="customer_id" value="{{ $data->id }}">
                    
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">1</div>
                        <div class="step" data-step="2">2</div>
                        <div class="step" data-step="3">3</div>
                    </div>
                    
                    <!-- Step 1: Package Selection -->
                    <div id="step1" class="devis-step">
                        <h6 class="mb-3 text-primary">
                            <i class="fa-solid fa-box me-2"></i>
                            {{ __('messages.select_package') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="package_id" class="form-label">{{ __('messages.package') }}</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="package_search" placeholder="Search packages..." autocomplete="off">
                                    <select class="form-select" id="package_id" name="package_id" required style="display: none;">
                                        <option value="">{{ __('messages.select_package') }}</option>
                                    </select>
                                    <div id="package_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;">
                                        <!-- Package options will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Package Details -->
                    <div id="step2" class="devis-step" style="display: none;">
                        <h6 class="mb-3 text-primary">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            {{ __('messages.package_information') }}
                        </h6>
                        
                        <!-- Package Info Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('messages.package_details') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>{{ __('messages.package_name') }}:</strong>
                                        <span id="package_name_display"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>{{ __('messages.package_price') }}:</strong>
                                        <span id="package_price_display"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>{{ __('messages.validity') }}:</strong>
                                        <span id="package_validity_display"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>{{ __('messages.branch') }}:</strong>
                                        <span id="package_branch_display"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <strong>{{ __('messages.description') }}:</strong>
                                        <p id="package_description_display" class="mt-1"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Service Customization -->
                    <div id="step3" class="devis-step" style="display: none;">
                        <h6 class="mb-3 text-primary">
                            <i class="fa-solid fa-cogs me-2"></i>
                            {{ __('messages.customize_services') }}
                        </h6>
                        
                        <!-- Services List -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('messages.package_services') }}</h6>
                            </div>
                            <div class="card-body">
                                <div id="services_list">
                                    <!-- Services will be loaded here -->
                                </div>
                                
                                <!-- Add Service Button -->
                               
                                
                                <!-- Grand Total -->
                                <div class="row mt-4">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-end">
                                                <h5 class="mb-0">
                                                    <strong>{{ __('messages.grand_total') }}: $<span id="grand_total">0.00</span></strong>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <!-- Client Signature -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fa-solid fa-signature me-2"></i>
                                Client Signature
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 align-items-start">
                                <div class="col-12">
                                    <div class="border rounded" style="height: 220px; position: relative; background: #fff;">
                                        <canvas id="signature-canvas" style="width: 100%; height: 100%;"></canvas>
                                    </div>
                                    <div class="form-text mt-2">Please sign inside the box above.</div>
                                    <div class="mt-2">
                                        <span id="signature-status" class="badge bg-secondary">Empty</span>
                                    </div>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="signature-undo">Undo</button>
                                    <button type="button" class="btn btn-outline-danger" id="signature-clear">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>

                     
                        
                        <!-- Remarks Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fa-solid fa-comment me-2"></i>
                                    {{ __('messages.remarks') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="devis_received_at" class="form-label">Devis reçu avant exécution (date)</label>
                                        <input type="date" class="form-control" id="devis_received_at" name="devis_received_at">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="devis_accepted_at" class="form-label">Devis accepté après réflexion (date)</label>
                                        <input type="date" class="form-control" id="devis_accepted_at" name="devis_accepted_at">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="devis_remarks" class="form-label">{{ __('messages.remarks') }}</label>
                                    <textarea class="form-control" id="devis_remarks" name="devis_remarks" rows="3" 
                                              placeholder="{{ __('messages.enter_remarks_here') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('messages.cancel') }}
                </button>
                <button type="button" class="btn btn-outline-primary" id="prevStep" style="display: none;">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    {{ __('messages.previous') }}
                </button>
                <button type="button" class="btn btn-primary" id="nextStep">
                    {{ __('messages.next') }}
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
                <button type="button" class="btn btn-success" id="createDevis" style="display: none;">
                    <i class="fa-solid fa-save me-2"></i>
                    {{ __('messages.create_devis') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Devis Details Modal -->
<div class="modal fade" id="viewDevisModal" tabindex="-1" aria-labelledby="viewDevisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDevisModalLabel">
                    <i class="fa-solid fa-eye me-2"></i>
                    Devis Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Devis Header Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Devis Information</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Devis Number:</strong> <span id="view_devis_number"></span></p>
                                <p><strong>Status:</strong> <span id="view_devis_status"></span></p>
                                <p><strong>Created Date:</strong> <span id="view_devis_created"></span></p>
                                <p><strong>Valid Until:</strong> <span id="view_devis_valid_until"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Package Information</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Package:</strong> <span id="view_package_name"></span></p>
                                <p><strong>Subtotal:</strong> $<span id="view_devis_subtotal"></span></p>
                                <p><strong>Tax Amount:</strong> $<span id="view_devis_tax"></span></p>
                                <p><strong>Total Amount:</strong> $<span id="view_devis_total"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Details -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Services Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="view-services-table">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>TVA %</th>
                                        <th>Subtotal</th>
                                        <th>Tax Amount</th>
                                        <th>Total</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="view-services-tbody">
                                    <!-- Services will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Remarks</h6>
                    </div>
                    <div class="card-body">
                        <p id="view_devis_remarks" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printDevisBtn">
                    <i class="fa-solid fa-print me-2"></i>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Devis Modal -->
<div class="modal fade" id="printDevisModal" tabindex="-1" aria-labelledby="printDevisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printDevisModalLabel">
                    <i class="fa-solid fa-print me-2"></i>
                    Print Devis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="print-content" class="p-4">
                    <!-- Print content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="actualPrintBtn">
                    <i class="fa-solid fa-print me-2"></i>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Session Modal -->
<div class="modal fade" id="gallerySessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="gallerySessionForm">
                    <div class="mb-3">
                        <label class="form-label">Phase</label>
                        <select name="phase" id="gallery_phase" class="form-select" required>
                            <option value="before">Avant</option>
                            <option value="after">Après</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" name="session_date" id="gallery_session_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="gallery_note" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Images</label>
                        <input type="file" name="images[]" id="gallery_images" class="form-control" accept="image/*" multiple required>
                        <small class="text-muted">JPEG, PNG, WebP jusqu'à 10MB par image.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="saveGallerySessionBtn" class="btn btn-primary">
                    <i class="fa-solid fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
    </div>

<!-- Append Images Modal -->
<div class="modal fade" id="appendImagesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter des images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="appendImagesForm">
                    <input type="hidden" id="append_gallery_id" value="">
                    <div class="mb-3">
                        <label class="form-label">Images</label>
                        <input type="file" name="images[]" id="append_images" class="form-control" accept="image/*" multiple required>
                        <small class="text-muted">Sélectionnez une ou plusieurs images.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="appendImagesBtn" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Ajouter
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script src="{{ mix('modules/customer/script.js') }}"></script>
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>

<script>
$(document).ready(function() {
    let currentStep = 1;
    let selectedPackage = null;
    let packageServices = [];
    let addedServices = []; // Array to store all added services
    let serviceCounter = 0; // Counter for unique service IDs
    let taxRate = 0; // Current tax rate
    let availableTaxes = []; // Available tax options
    let signaturePad = null; // Signature pad instance
    let signatureImage = null; // Base64 of signature
    let capturedSignatureImage = null; // Persisted signature captured from modal

    // Load packages on modal open
    $('#createDevisModal').on('show.bs.modal', function() {
        loadPackages();
        loadTaxes();
        resetForm();
        initSignaturePad();
    });

    // Load packages
    function loadPackages() {
        $.ajax({
            url: '{{ route("backend.customers.get_packages") }}',
            method: 'GET',
            success: function(response) {
                console.log('Package API Response:', response);
                if (response.status) {
                    // Store packages globally for search functionality
                    window.availablePackages = response.data || [];
                    
                    // Populate the hidden select for form submission
                    const packageSelect = $('#package_id');
                    packageSelect.empty().append('<option value="">{{ __("messages.select_package") }}</option>');
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(package) {
                            packageSelect.append(`<option value="${package.id}">${package.name} - ${package.package_price} DH</option>`);
                        });
                        
                        // Populate the searchable dropdown
                        populatePackageDropdown(response.data);
                    } else {
                        packageSelect.append('<option value="" disabled>No packages available</option>');
                        $('#package_dropdown').html('<div class="dropdown-item text-muted">No packages available</div>');
                    }
                } else {
                    showAlert(response.message || 'Error loading packages', 'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error('Package API Error:', xhr.responseText);
                showAlert('Error loading packages: ' + error, 'danger');
            }
        });
    }

    // Populate package dropdown for search
    function populatePackageDropdown(packages) {
        const dropdown = $('#package_dropdown');
        dropdown.empty();
        
        if (packages && packages.length > 0) {
            packages.forEach(function(package) {
                const option = $(`
                    <div class="dropdown-item package-option" data-package-id="${package.id}" style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${package.name}</strong>
                                <br>
                                <small class="text-muted">${package.package_price} DH</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">${package.branch_name || 'N/A'}</small>
                            </div>
                        </div>
                    </div>
                `);
                dropdown.append(option);
            });
        } else {
            dropdown.html('<div class="dropdown-item text-muted">No packages available</div>');
        }
    }

    // Filter packages based on search
    function filterPackages(searchTerm) {
        const dropdown = $('#package_dropdown');
        const options = dropdown.find('.package-option');
        
        if (!searchTerm || searchTerm.trim() === '') {
            options.show();
            return;
        }
        
        const term = searchTerm.toLowerCase();
        options.each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(term)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Show "No results" if no options are visible
        const visibleOptions = dropdown.find('.package-option:visible');
        if (visibleOptions.length === 0) {
            dropdown.html('<div class="dropdown-item text-muted">No packages found</div>');
        }
    }

    // Package change event
    $('#package_id').on('change', function() {
        selectedPackage = $(this).val();
        if (selectedPackage) {
            loadPackageDetails(selectedPackage);
        }
    });

    // Package search functionality
    $('#package_search').on('focus', function() {
        $('#package_dropdown').show();
        filterPackages($(this).val());
    });

    $('#package_search').on('input', function() {
        const searchTerm = $(this).val();
        filterPackages(searchTerm);
        $('#package_dropdown').show();
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#package_search, #package_dropdown').length) {
            $('#package_dropdown').hide();
        }
    });

    // Handle package selection from dropdown
    $(document).on('click', '.package-option', function() {
        const packageId = $(this).data('package-id');
        const packageText = $(this).find('strong').text();
        
        // Update the search input
        $('#package_search').val(packageText);
        
        // Update the hidden select
        $('#package_id').val(packageId);
        
        // Hide dropdown
        $('#package_dropdown').hide();
        
        // Load package details
        selectedPackage = packageId;
        if (selectedPackage) {
            loadPackageDetails(selectedPackage);
        }
    });

    // Handle keyboard navigation
    $('#package_search').on('keydown', function(e) {
        const dropdown = $('#package_dropdown');
        const options = dropdown.find('.package-option:visible');
        const current = dropdown.find('.package-option.highlighted');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (current.length === 0) {
                options.first().addClass('highlighted');
            } else {
                current.removeClass('highlighted');
                current.next('.package-option:visible').addClass('highlighted');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (current.length === 0) {
                options.last().addClass('highlighted');
            } else {
                current.removeClass('highlighted');
                current.prev('.package-option:visible').addClass('highlighted');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (current.length > 0) {
                current.click();
            }
        } else if (e.key === 'Escape') {
            dropdown.hide();
        }
    });

    // Load package details and services
    function loadPackageDetails(packageId) {
        console.log('Loading package details for ID:', packageId);
        
        $.ajax({
            url: '{{ route("backend.customers.get_package_details") }}',
            method: 'GET',
            data: { package_id: packageId },
            success: function(response) {
                console.log('Package Details API Response:', response);
                if (response.status && response.data && response.data.length > 0) {
                    const package = response.data[0];
                    displayPackageInfo(package);
                    displayServices(package.services || []);
                } else {
                    console.error('No package data received:', response);
                    showAlert('No package data received', 'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error('Package Details API Error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);
                
                let errorMessage = 'Error loading package details: ' + error;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert(errorMessage, 'danger');
            }
        });
    }

    // Display package information
    function displayPackageInfo(package) {
        $('#package_name_display').text(package.name || 'N/A');
        $('#package_price_display').text(package.package_price ? '$' + package.package_price : 'N/A');
        $('#package_validity_display').text(package.end_date ? new Date(package.end_date).toLocaleDateString() : 'N/A');
        $('#package_branch_display').text(package.branch_name || 'N/A');
        $('#package_description_display').text(package.description || 'No description available');
    }

    // Load package services (now handled in loadPackageDetails)
    function loadPackageServices(packageId) {
        // This function is now handled in loadPackageDetails
        // Keeping for compatibility but not used
    }

    // Display services with customization options
    function displayServices(services) {
        let html = '';
        if (services.length === 0) {
            html = '<div class="text-center text-muted"><i class="fa-solid fa-exclamation-circle me-2"></i>No services found in this package</div>';
        } else {
            services.forEach(function(service, index) {
                const serviceId = `service_${serviceCounter++}`;
                html += `
                    <div class="service-item border rounded p-3 mb-3" data-service-id="${serviceId}" data-service-name="${service.service_name || 'N/A'}">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h6 class="mb-1">${service.service_name || 'N/A'}</h6>
                                <small class="text-muted">Duration: ${service.duration_min || 0} min</small>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small">Quantity</label>
                                <input type="number" class="form-control form-control-sm service-qty" 
                                       value="${service.quantity || 1}" 
                                       min="0" max="100">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small">Price</label>
                                <input type="number" class="form-control form-control-sm service-price" 
                                       value="${service.service_price || 0}" 
                                       step="0.01" min="0">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small">Discount</label>
                                <input type="number" class="form-control form-control-sm service-discount" 
                                       value="${service.discount_price || 0}" 
                                       step="0.01" min="0">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small">TVA %</label>
                                <input type="number" class="form-control form-control-sm service-tva" 
                                       value="20" step="0.01" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Lot #</label>
                                <input type="text" class="form-control form-control-sm service-lot" 
                                       placeholder="Lot #">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Total</label>
                                <input type="text" class="form-control form-control-sm service-total" 
                                       readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-service" title="Remove Service">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label small">Service Remarks</label>
                                <input type="text" class="form-control form-control-sm service-remarks" 
                                       placeholder="Enter service remarks...">
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        $('#services_list').html(html);
        
        // Add event listeners for calculations
        $('.service-qty, .service-price, .service-discount, .service-tva').on('input', calculateServiceTotal);
        $('.remove-service').on('click', removeService);
        calculateAllTotals();
    }

    // Load taxes
    function loadTaxes() {
        $.ajax({
            url: '{{ route("backend.customers.get_taxes") }}',
            method: 'GET',
            success: function(response) {
                console.log('Tax Response:', response);
                if (response.status) {
                    const taxSelect = $('#tax_id');
                    taxSelect.empty().append('<option value="">{{ __('messages.no_tax') }}</option>');
                    
                    if (response.data && response.data.length > 0) {
                        availableTaxes = response.data;
                        response.data.forEach(function(tax) {
                            taxSelect.append(`<option value="${tax.id}" data-type="${tax.type}" data-value="${tax.value}">${tax.title} (${tax.value}${tax.type === 'percent' ? '%' : '$'})</option>`);
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Tax Loading Error:', xhr.responseText);
                showAlert('Error loading taxes: ' + error, 'danger');
            }
        });
    }

    // Tax selection change
    $('#tax_id').on('change', function() {
        const selectedTax = $(this).find('option:selected');
        if (selectedTax.val()) {
            const type = selectedTax.data('type');
            const value = parseFloat(selectedTax.data('value'));
            taxRate = type === 'percent' ? value : (value / 100) * 100; // Convert fixed to percentage
            $('#custom_tax_rate').val(taxRate);
        } else {
            taxRate = 0;
            $('#custom_tax_rate').val('');
        }
        updateAllTotals();
    });

    // Custom tax rate change
    $('#custom_tax_rate').on('input', function() {
        taxRate = parseFloat($(this).val()) || 0;
        updateAllTotals();
    });

    // Calculate individual service total
    function calculateServiceTotal() {
        const qty = parseFloat($(this).closest('.service-item').find('.service-qty').val()) || 0;
        const price = parseFloat($(this).closest('.service-item').find('.service-price').val()) || 0;
        const discount = parseFloat($(this).closest('.service-item').find('.service-discount').val()) || 0;
        const tvaRate = parseFloat($(this).closest('.service-item').find('.service-tva').val()) || 0;
        
        const subtotal = qty * (price - discount);
        const taxAmount = (subtotal * tvaRate) / 100;
        const total = subtotal + taxAmount;
        
        $(this).closest('.service-item').find('.service-total').val(total.toFixed(2));
        updateAllTotals();
    }

    // Calculate grand total
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.service-total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        
        // Update grand total display
        $('#grand_total').text(grandTotal.toFixed(2));
    }

    // Calculate all totals
    function calculateAllTotals() {
        $('.service-item').each(function() {
            const qty = parseFloat($(this).find('.service-qty').val()) || 0;
            const price = parseFloat($(this).find('.service-price').val()) || 0;
            const discount = parseFloat($(this).find('.service-discount').val()) || 0;
            const total = qty * (price - discount);
            $(this).find('.service-total').val(total.toFixed(2));
        });
        calculateGrandTotal();
    }

    // Next step button
    $('#nextStep').on('click', function() {
        if (currentStep === 1) {
            if (!$('#package_id').val()) {
                showAlert('Please select a package', 'warning');
                return;
            }
            currentStep = 2;
            updateStepIndicator(2);
            $('#step1').hide();
            $('#step2').show();
            $('#prevStep').show();
        } else if (currentStep === 2) {
            currentStep = 3;
            updateStepIndicator(3);
            $('#step2').hide();
            $('#step3').show();
            $('#nextStep').hide();
            $('#createDevis').show();
            // Ensure signature canvas is properly sized after becoming visible
            setTimeout(function() {
                if (typeof refreshSignatureCanvasSize === 'function') {
                    refreshSignatureCanvasSize();
                } else if (typeof initSignaturePad === 'function') {
                    initSignaturePad();
                }
            }, 150);
        }
    });

    // Previous step button
    $('#prevStep').on('click', function() {
        if (currentStep === 2) {
            currentStep = 1;
            updateStepIndicator(1);
            $('#step2').hide();
            $('#step1').show();
            $('#prevStep').hide();
        } else if (currentStep === 3) {
            currentStep = 2;
            updateStepIndicator(2);
            $('#step3').hide();
            $('#step2').show();
            $('#nextStep').show();
            $('#createDevis').hide();
        }
    });

    // Create devis button
    $('#createDevis').on('click', function() {
        createDevis();
    });

    // Add service button click
    $('#addServiceBtn').on('click', function() {
        addNewService();
    });

    // Add new service function
    function addNewService() {
        const serviceId = `service_${serviceCounter++}`;
        const html = `
            <div class="service-item border rounded p-3 mb-3" data-service-id="${serviceId}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small">Service Name</label>
                        <input type="text" class="form-control form-control-sm service-name" 
                               placeholder="Enter service name...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Quantity</label>
                        <input type="number" class="form-control form-control-sm service-qty" 
                               value="1" min="0" max="100">
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex align-items-end gap-2">
                            <div style="width: 20%">
                                <label class="form-label small">Quantity</label>
                                <input type="number" class="form-control form-control-sm service-qty" value="1" min="0" max="100">
                            </div>
                            <div style="width: 20%">
                                <label class="form-label small">Price</label>
                                <input type="number" class="form-control form-control-sm service-price" value="0" step="0.01" min="0">
                            </div>
                            <div style="width: 20%">
                                <label class="form-label small">Discount</label>
                                <input type="number" class="form-control form-control-sm service-discount" value="0" step="0.01" min="0">
                            </div>
                            <div style="width: 20%">
                                <label class="form-label small">TVA %</label>
                                <input type="number" class="form-control form-control-sm service-tva" value="20" step="0.01" min="0" max="100">
                            </div>
                            <div style="width: 20%">
                                <label class="form-label small">Lot #</label>
                                <input type="text" class="form-control form-control-sm service-lot" placeholder="Lot #">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Total</label>
                        <input type="text" class="form-control form-control-sm service-total" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-service" title="Remove Service">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="form-label small">Service Remarks</label>
                        <input type="text" class="form-control form-control-sm service-remarks" 
                               placeholder="Enter service remarks...">
                    </div>
                </div>
            </div>
        `;
        
        $('#services_list').append(html);
        
        // Add event listeners for the new service
        $(`[data-service-id="${serviceId}"] .service-qty, [data-service-id="${serviceId}"] .service-price, [data-service-id="${serviceId}"] .service-discount, [data-service-id="${serviceId}"] .service-tva`).on('input', calculateServiceTotal);
        $(`[data-service-id="${serviceId}"] .remove-service`).on('click', removeService);
        
        calculateAllTotals();
    }

    // Remove service function
    function removeService() {
        $(this).closest('.service-item').remove();
        calculateAllTotals();
    }

    // Create devis function - Only add to table, don't save to database
    function createDevis() {
        const packageId = $('#package_id').val();
        const remarks = $('#devis_remarks').val();
        
        // Collect service data
        const services = [];
        $('.service-item').each(function() {
            // Try to get service name from input field first, then from data attribute, then from display text
            let serviceName = $(this).find('.service-name').val();
            if (!serviceName) {
                // Try data attribute first
                serviceName = $(this).data('service-name');
                if (!serviceName) {
                    // For package services, get the name from the h6 element
                    serviceName = $(this).find('h6').text().trim() || 'Custom Service';
                }
            }
            
            const qty = parseInt($(this).find('.service-qty').val()) || 0;
            const price = parseFloat($(this).find('.service-price').val()) || 0;
            const discount = parseFloat($(this).find('.service-discount').val()) || 0;
            const tvaRate = parseFloat($(this).find('.service-tva').val()) || 0;
            const serviceRemarks = $(this).find('.service-remarks').val() || '';
            const numberOfLot = $(this).find('.service-lot').val() || '';
            
            if (qty > 0 && price > 0) {
                const serviceData = {
                    id: `temp_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`, // Generate unique temp ID
                    service_name: serviceName,
                    quantity: qty,
                    price: price,
                    discount: discount,
                    tva_rate: tvaRate,
                    remarks: serviceRemarks,
                    number_of_lot: numberOfLot,
                    package_id: packageId,
                    modal_remarks: remarks
                };
                services.push(serviceData);
            }
        });

        if (services.length === 0) {
            showAlert('Please add at least one service with quantity and price', 'warning');
            return;
        }

        // Capture signature image now (modal will hide next)
        if (signaturePad && !signaturePad.isEmpty()) {
            capturedSignatureImage = signaturePad.toDataURL('image/png');
        } else {
            capturedSignatureImage = null;
        }

        // Add services to the table (don't save to database yet)
        addServicesToTable(services);
        
        // Show success message
        showAlert('Services added to table successfully!', 'success');
        
        // Hide modal and reset form
        $('#createDevisModal').modal('hide');
        resetForm();
    }

    // Add services to table
    function addServicesToTable(services) {
        // Hide empty state and show table
        $('#empty-devis').hide();
        $('#services-table-container').show();
        
        // Add services to addedServices array
        services.forEach(function(service) {
            addedServices.push(service);
        });
        
        // Add services to table body
        const tbody = $('#services-table-body');
        services.forEach(function(service) {
            const subtotal = service.quantity * (service.price - service.discount);
            const tvaRate = service.tva_rate || 0;
            const taxAmount = (subtotal * tvaRate) / 100;
            const total = subtotal + taxAmount;
            
            const row = `
                <tr data-service-id="${service.id}">
                    <td>${service.service_name}</td>
                    <td>${service.quantity}</td>
                    <td>${service.price.toFixed(2)} DH</td>
                    <td>${service.discount.toFixed(2)} DH</td>
                    <td>${subtotal.toFixed(2)} DH</td>
                    <td>${tvaRate.toFixed(2)}%</td>
                    <td>${taxAmount.toFixed(2)} DH</td>
                    <td>${total.toFixed(2)} DH</td>
                    <td>${service.number_of_lot || '-'}</td>
                    <td>${service.remarks || '-'}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary edit-service" title="Edit Service">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-table-service" title="Remove Service">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        
        // Add event listeners for table actions
        $('.edit-service').on('click', editService);
        $('.remove-table-service').on('click', removeTableService);
        
        // Update total amount
        updateTotalAmount();
    }

    // Edit service function
    function editService() {
        const row = $(this).closest('tr');
        const serviceId = row.data('service-id');
        
        // Find the service in addedServices array
        const serviceIndex = addedServices.findIndex(s => s.id === serviceId);
        if (serviceIndex !== -1) {
            const service = addedServices[serviceIndex];
            
            // Open modal with service data for editing
            $('#createDevisModal').modal('show');
            
            // Pre-fill the form with service data
            $('#package_id').val(selectedPackage);
            // You can add more pre-filling logic here
            
            showAlert('Edit functionality will be implemented', 'info');
        }
    }

    // Remove service from table
    function removeTableService() {
        const row = $(this).closest('tr');
        const serviceId = row.data('service-id');
        
        // Remove from addedServices array
        addedServices = addedServices.filter(s => s.id !== serviceId);
        
        // Remove from table
        row.remove();
        
        // Update total amount
        updateTotalAmount();
        
        // Show empty state if no services left
        if (addedServices.length === 0) {
            $('#empty-devis').show();
            $('#services-table-container').hide();
        }
        
        showAlert('Service removed successfully', 'success');
    }

    // Save devis button click
    $('#saveDevisBtn').on('click', function() {
        saveDevis();
    });

    // Print devis button click
    $('#printDevisBtn').on('click', function() {
        saveDevis(true);
    });

    // Save devis function - Save all services from table to database
    function saveDevis(shouldPrint = false) {
        if (addedServices.length === 0) {
            showAlert('No services to save', 'warning');
            return;
        }

        // Optional signature: attach if present; prefer captured image from modal
        signatureImage = capturedSignatureImage || ((signaturePad && !signaturePad.isEmpty()) ? signaturePad.toDataURL('image/png') : null);

        // Get package ID from the first service (if available) or use selected package
        const packageId = addedServices[0].package_id || selectedPackage;
        
        // Get remarks from the first service (if available) or use empty
        const remarks = addedServices[0].modal_remarks || '';

        const devisData = {
            customer_id: {{ $data->id }},
            package_id: packageId,
            services: addedServices.map(service => ({
                service_name: service.service_name,
                quantity: service.quantity,
                price: service.price,
                discount: service.discount,
                tva_rate: service.tva_rate || 0,
                remarks: service.remarks,
                number_of_lot: service.number_of_lot || ''
            })),
            tax_rate: taxRate,
            remarks: remarks,
            received_at: $('#devis_received_at').val() || null,
            accepted_at: $('#devis_accepted_at').val() || null,
            signature_image: signatureImage
        };

        console.log('Saving Devis:', devisData);
        
        // Show loading state
        const originalSaveHtml = $('#saveDevisBtn').html();
        const originalPrintHtml = $('#printDevisBtn').html();
        $('#saveDevisBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...');
        $('#printDevisBtn').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: '{{ route("backend.customers.save_devis") }}',
            method: 'POST',
            data: devisData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Save Devis Response:', response);
                if (response.status) {
                    showAlert(response.message || 'Devis saved successfully!', 'success');
                    
                    // Clear the table and reset everything
                    clearTable();
                    
                    // Refresh devis list if the devis list tab is active
                    if ($('#devis-list-tab').hasClass('active')) {
                        loadDevisList();
                    }
                    
                    // If triggered by print button, open PDF directly
                    if (shouldPrint && response.data && response.data.devis_id) {
                        const printUrl = `{{ route('backend.customers.print_devis_pdf', '') }}/${response.data.devis_id}`;
                        window.open(printUrl, '_blank');
                    }
                } else {
                    showAlert(response.message || 'Error saving devis', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('Save Devis Error:', xhr.responseText);
                let errorMessage = 'Error saving devis';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                }
                
                showAlert(errorMessage, 'danger');
            },
            complete: function() {
                // Reset button state
                $('#saveDevisBtn').prop('disabled', false).html(originalSaveHtml);
                $('#printDevisBtn').prop('disabled', false).html(originalPrintHtml);
            }
        });
    }

    // Clear table function
    function clearTable() {
        // Clear the table body
        $('#services-table-body').empty();
        
        // Clear addedServices array
        addedServices = [];
        
        // Reset tax rate
        taxRate = 0;
        $('#tax_id').val('');
        $('#custom_tax_rate').val('');
        
        // Show empty state
        $('#empty-devis').show();
        $('#services-table-container').hide();
        
        // Reset totals
        $('#subtotal-amount').text('0.00');
        $('#tax-amount').text('0.00');
        $('#total-amount').text('0.00');

        // Clear signature
        if (signaturePad) {
            signaturePad.clear();
        }
        signatureImage = null;
    }

    // Generate devis content for printing
    function generateDevisContent() {
        let content = `
            <div class="header">
                <h1>Devis</h1>
                <p>Date: ${new Date().toLocaleDateString()}</p>
            </div>
            
            <div class="customer-info">
                <h3>Customer Information</h3>
                <p><strong>Name:</strong> {{ $data->full_name }}</p>
                <p><strong>Email:</strong> {{ $data->email }}</p>
                <p><strong>Phone:</strong> {{ $data->phone_number || 'N/A' }}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        addedServices.forEach(function(service) {
            const subtotal = service.quantity * (service.price - service.discount);
            const taxAmount = (subtotal * taxRate) / 100;
            const total = subtotal + taxAmount;
            
            content += `
                <tr>
                    <td>${service.service_name}</td>
                    <td>${service.quantity}</td>
                    <td>${service.price.toFixed(2)} DH  </td>
                    <td>${service.discount.toFixed(2)} DH</td>
                    <td>${subtotal.toFixed(2)} DH</td>
                    <td>${taxAmount.toFixed(2)} DH</td>
                    <td>${total.toFixed(2)} DH</td>
                </tr>
            `;
        });
        
        content += `
                </tbody>
            </table>
            
            <div class="total-section">
                <p>Subtotal: ${calculateSubtotal().toFixed(2)} DH</p>
                <p>Tax (${taxRate}%): ${calculateTaxAmount().toFixed(2)} DH</p>
                <p class="total-row">Total: ${calculateTotalAmount().toFixed(2)} DH</p>
            </div>
        `;
        
        return content;
    }

    // Calculate subtotal
    function calculateSubtotal() {
        let subtotal = 0;
        addedServices.forEach(function(service) {
            subtotal += service.quantity * (service.price - service.discount);
        });
        return subtotal;
    }

    // Calculate tax amount
    function calculateTaxAmount() {
        return (calculateSubtotal() * taxRate) / 100;
    }

    // Calculate total amount
    function calculateTotalAmount() {
        return calculateSubtotal() + calculateTaxAmount();
    }

    // Update all totals
    function updateAllTotals() {
        updateTotalAmount();
    }

    // Update total amount
    function updateTotalAmount() {
        // Calculate totals from addedServices array
        let subtotal = 0;
        let totalTaxAmount = 0;
        let total = 0;
        
        addedServices.forEach(function(service) {
            const serviceSubtotal = service.quantity * (service.price - service.discount);
            const serviceTvaRate = service.tva_rate || 0;
            const serviceTaxAmount = (serviceSubtotal * serviceTvaRate) / 100;
            
            subtotal += serviceSubtotal;
            totalTaxAmount += serviceTaxAmount;
        });
        
        total = subtotal + totalTaxAmount;
        
        // Update display
        $('#subtotal-amount').text(subtotal.toFixed(2));
        $('#tax-amount').text(totalTaxAmount.toFixed(2));
        $('#total-amount').text(total.toFixed(2));
    }

    // Calculate grand total amount
    function calculateGrandTotalAmount() {
        let total = 0;
        $('.service-total').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }

    // Update step indicator
    function updateStepIndicator(step) {
        $('.step').removeClass('active completed');
        for (let i = 1; i <= step; i++) {
            if (i < step) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            } else {
                $(`.step[data-step="${i}"]`).addClass('active');
            }
        }
    }

    // Reset form
    function resetForm() {
        currentStep = 1;
        selectedPackage = null;
        packageServices = [];
        taxRate = 0;
        
        updateStepIndicator(1);
        $('#step1').show();
        $('#step2').hide();
        $('#step3').hide();
        $('#prevStep').hide();
        $('#nextStep').show();
        $('#createDevis').hide();
        
        $('#package_id').val('');
        $('#services_list').empty();
        $('#devis_remarks').val('');
        $('#tax_id').val('');
        $('#custom_tax_rate').val('');

        // Clear signature area if initialized
        if (signaturePad) {
            signaturePad.clear();
        }
        signatureImage = null;
    }

    // Initialize signature pad
    function initSignaturePad() {
        const canvas = document.getElementById('signature-canvas');
        if (!canvas) return;

        const resizeCanvasPreserve = () => {
            const data = signaturePad ? signaturePad.toData() : null;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return; // not visible yet
            canvas.width = rect.width * ratio;
            canvas.height = rect.height * ratio;
            const context = canvas.getContext('2d');
            context.scale(ratio, ratio);
            if (signaturePad && data && data.length) {
                signaturePad.clear();
                signaturePad.fromData(data);
            }
        };

        if (!signaturePad) {
            // First-time init
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255,255,255,1)',
                penColor: 'rgb(0, 0, 0)'
            });
            // Initial size
            resizeCanvasPreserve();
            // Resize on window changes
            window.addEventListener('resize', resizeCanvasPreserve);

            // Buttons
            $('#signature-clear').off('click').on('click', function() {
                if (signaturePad) signaturePad.clear();
                updateSignatureStatus();
            });
            $('#signature-undo').off('click').on('click', function() {
                if (!signaturePad) return;
                const data = signaturePad.toData();
                if (data && data.length) {
                    data.pop();
                    signaturePad.fromData(data);
                }
                updateSignatureStatus();
            });
            // Update status on draw
            signaturePad.onBegin = updateSignatureStatus;
            signaturePad.onEnd = updateSignatureStatus;
        } else {
            // Already exists: just ensure correct sizing without clearing
            resizeCanvasPreserve();
        }
    }

    // Public helper to refresh canvas size without losing signature
    function refreshSignatureCanvasSize() {
        if (signaturePad) {
            initSignaturePad();
        } else {
            initSignaturePad();
        }
    }

    function updateSignatureStatus() {
        const $badge = $('#signature-status');
        if (!$badge.length) return;
        if (signaturePad && !signaturePad.isEmpty()) {
            $badge.removeClass('bg-secondary').addClass('bg-success').text('Signed');
        } else {
            $badge.removeClass('bg-success').addClass('bg-secondary').text('Empty');
        }
    }

    // Show alert function
    function showAlert(message, type) {
        // You can implement your preferred alert system here
        alert(message);
    }

    // Load devis list when tab is clicked
    $('#devis-list-tab').on('click', function() {
        console.log('Devis List tab clicked');
        
        // Wait for tab to be fully activated
        setTimeout(function() {
            // Add a test row first to verify table works
            $('#devis-list-tbody').html(`
                <tr>
                    <td><strong>TEST001</strong></td>
                    <td>Test Package</td>
                    <td>$100.00</td>
                    <td>$10.00</td>
                    <td><strong>$110.00</strong></td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>${new Date().toLocaleDateString()}</td>
                    <td>${new Date().toLocaleDateString()}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" title="{{   __('messages.view_details') }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
            
            // Force table to be visible
            $('#devis-list-table').css({
                'display': 'table',
                'background-color': 'red',
                'border': '3px solid blue'
            }).show();
            $('#devis-list-loading').hide();
            $('#devis-list-empty').hide();
            
            console.log('Test row added, table should be visible');
            console.log('Table element:', $('#devis-list-table'));
            console.log('Table is visible:', $('#devis-list-table').is(':visible'));
            console.log('Table display style:', $('#devis-list-table').css('display'));
            console.log('Tab content visible:', $('#devis-list').is(':visible'));
            console.log('Tab has active class:', $('#devis-list').hasClass('active'));
            console.log('Table parent visible:', $('#devis-list-table').parent().is(':visible'));
            
            // Then load real data
            loadDevisList();
        }, 100);
    });

    // Refresh devis list button
    $('#refreshDevisList').on('click', function() {
        loadDevisList();
    });

    // Load devis history when tab is clicked
    $('#devis-history-tab').on('click', function() {
        console.log('Devis History tab clicked');
        loadDevisHistory();
    });

    // Load medical records when tab is clicked
    $('#medical-records-tab').on('click', function() {
        console.log('Medical Records tab clicked');
        loadMedicalRecords();
    });

    // Load medical history when tab is clicked
    $('#medical-history-tab').on('click', function() {
        console.log('Medical History tab clicked');
        loadMedicalHistory();
    });

    // Refresh devis history button
    $('#refreshDevisHistory').on('click', function() {
        loadDevisHistory();
    });

    // Refresh medical records button
    $('#refreshMedicalRecords').on('click', function() {
        loadMedicalRecords();
    });

    // Refresh medical history button
    $('#refreshMedicalHistory').on('click', function() {
        loadMedicalHistory();
    });

    // Save medical record
    $('#saveMedicalRecordBtn').on('click', function() {
        submitMedicalRecord();
    });

    // Create medical history
    $('#saveMedicalHistoryBtn').on('click', function() {
        submitMedicalHistory();
    });

    // Add new type
    $('#mhAddTypeBtn').on('click', function() {
        const name = prompt('Enter new type name');
        if (!name) return;
        $.ajax({
            url: `{{ route('backend.customers.medical_history.types') }}`,
            method: 'POST',
            data: { name: name },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp) {
                if (resp.status && resp.data) {
                    loadMedicalHistoryTypes(resp.data.id);
                } else {
                    showAlert(resp.message || 'Error creating type', 'danger');
                }
            },
            error: function(xhr) {
                showAlert('Error creating type', 'danger');
            }
        });
    });

    // Load devis list function
    function loadDevisList() {
        $('#devis-list-loading').show();
        $('#devis-list-empty').hide();
        $('#devis-list-table').hide();
        
        $.ajax({
            url: '{{ route("backend.customers.customer_devis", $data->id) }}',
            method: 'GET',
            success: function(response) {
                console.log('Devis List Response:', response);
                $('#devis-list-loading').hide();
                
                if (response.status && response.data && response.data.length > 0) {
                    displayDevisList(response.data);
                    $('#devis-list-table').show();
                    console.log('Table should be visible now');
                } else {
                    $('#devis-list-empty').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Devis List Error:', xhr.responseText);
                $('#devis-list-loading').hide();
                $('#devis-list-empty').show();
                showAlert('Error loading devis list: ' + error, 'danger');
            }
        });
    }

    // Load medical history types
    function loadMedicalHistoryTypes(selectId = null) {
        $.ajax({
            url: `{{ route('backend.customers.medical_history.types') }}`,
            method: 'GET',
            success: function(resp) {
                const $sel = $('#mh_type_id');
                $sel.empty();
                if (resp.status && Array.isArray(resp.data) && resp.data.length) {
                    resp.data.forEach(function(t){
                        $sel.append(`<option value="${t.id}">${$('<div>').text(t.name).html()}</option>`);
                    });
                    if (selectId) $sel.val(selectId);
                } else {
                    $sel.append('<option value="" disabled>No types</option>');
                }
            }
        });
    }

    // Load medical history list
    function loadMedicalHistory() {
        $('#mh-loading').show();
        $('#mh-empty').hide();
        $('#mh-items').empty();

        // Ensure types available when opening modal later
        loadMedicalHistoryTypes();

        $.ajax({
            url: `{{ route('backend.customers.medical_history.index', $data->id) }}`,
            method: 'GET',
            success: function(resp) {
                $('#mh-loading').hide();
                let items = [];
                if (Array.isArray(resp)) items = resp;
                else if (resp && Array.isArray(resp.data)) items = resp.data;

                if (!items.length) {
                    $('#mh-empty').show();
                    return;
                }

                renderMedicalHistory(items);
            },
            error: function() {
                $('#mh-loading').hide();
                $('#mh-empty').show();
            }
        });
    }

    function renderMedicalHistory(items) {
        const $wrap = $('#mh-items');
        $wrap.empty();
        items.forEach(function(h) {
            const typeName = h.type && h.type.name ? h.type.name : '—';
            const period = (h.start_date ? new Date(h.start_date).toLocaleDateString() : '-') +
                           ' → ' + (h.end_date ? new Date(h.end_date).toLocaleDateString() : '-');
            const detail = h.detail ? $('<div>').text(h.detail).html() : '';
            const medication = h.medication ? $('<div>').text(h.medication).html() : '';
            const created = h.created_at ? new Date(h.created_at).toLocaleString() : '';
            const html = `
                <div class="alert alert-secondary d-flex justify-content-between align-items-start mb-2" role="alert">
                    <div>
                        <div class="fw-semibold">${$('<div>').text(h.title || '').html()}</div>
                        <div class="small text-muted">Type: ${$('<div>').text(typeName).html()}</div>
                        ${detail ? `<div class="mt-1">${detail}</div>` : ''}
                        ${medication ? `<div class="mt-1"><strong>Médicament:</strong> ${medication}</div>` : ''}
                        <div class="small mt-1"><strong>Période:</strong> ${period}</div>
                        ${created ? `<div class="small text-muted">Ajouté: ${created}</div>` : ''}
                    </div>
                    <div class="ms-3">
                        <button type="button" class="btn btn-sm btn-outline-danger mh-delete" data-id="${h.id}"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `;
            $wrap.append(html);
        });

        $('.mh-delete').off('click').on('click', function(){
            const id = $(this).data('id');
            if (!confirm('Delete this history item?')) return;
            const btn = $(this);
            const orig = btn.html();
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
            $.ajax({
                url: `{{ route('backend.customers.medical_history.delete', '') }}/${id}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(resp){
                    if (resp.status) loadMedicalHistory();
                    else showAlert(resp.message || 'Error deleting', 'danger');
                },
                error: function(){ showAlert('Error deleting', 'danger'); },
                complete: function(){ btn.prop('disabled', false).html(orig); }
            });
        });
    }

    function submitMedicalHistory() {
        const form = document.getElementById('medicalHistoryForm');
        if (!form) return;
        const fd = new FormData(form);
        const btn = $('#saveMedicalHistoryBtn');
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: `{{ route('backend.customers.medical_history.store', $data->id) }}`,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp){
                if (resp.status) {
                    $('#medicalHistoryModal').modal('hide');
                    form.reset();
                    loadMedicalHistory();
                    showAlert('Antecedent added', 'success');
                } else {
                    showAlert(resp.message || 'Error saving history', 'danger');
                }
            },
            error: function(xhr){
                let msg = 'Error saving history';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showAlert(msg, 'danger');
            },
            complete: function(){ btn.prop('disabled', false).html(original); }
        });
    }

    // Display devis list in table
    function displayDevisList(devisList) {
        console.log('Displaying devis list:', devisList);
        const tbody = $('#devis-list-tbody');
        console.log('Found tbody element:', tbody.length);
        tbody.empty();
        
        devisList.forEach(function(devis, index) {
            console.log(`Processing devis ${index + 1}:`, devis);
            const statusBadge = getStatusBadge(devis.status);
            const createdDate = new Date(devis.created_at).toLocaleDateString();
            const validUntil = devis.valid_until ? new Date(devis.valid_until).toLocaleDateString() : 'N/A';
            
            const row = `
                <tr>
                    <td>
                        <strong>${devis.devis_number}</strong>
                    </td>
                    <td>
                        ${devis.package ? devis.package.name : 'N/A'}
                    </td>
                    <td>${parseFloat(devis.subtotal).toFixed(2)} DH</td>
                    <td>${parseFloat(devis.tax_amount).toFixed(2)} DH</td>
                    <td>
                        <strong>${parseFloat(devis.total_amount).toFixed(2)} DH</strong>
                    </td>
                    <td>${createdDate}</td>
                    <td>${validUntil}</td>
                    <td>${devis.number_of_lot || '-'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary view-devis" 
                                    data-devis-id="${devis.id}" title="{{ __('messages.view_details') }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success print-devis" 
                                    data-devis-id="${devis.id}" title="{{ __('messages.print') }}">
                                <i class="fa-solid fa-print"></i>   
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning edit-devis" 
                                    data-devis-id="${devis.id}" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-devis" 
                                    data-devis-id="${devis.id}" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            console.log(`Adding row ${index + 1} to table`);
            tbody.append(row);
        });
        
        console.log('Total rows added:', tbody.find('tr').length);
        
        // Add event listeners for action buttons
        $('.view-devis').on('click', viewDevisDetails);
        $('.print-devis').on('click', printDevisDetails);
        $('.edit-devis').on('click', editDevisDetails);
        $('.delete-devis').on('click', deleteDevisDetails);
    }

    // Load devis history function
    function loadDevisHistory() {
        console.log('Loading devis history...');
        $('#devis-history-loading').show();
        $('#devis-history-empty').hide();
        $('#devis-history-table').hide();
        
        $.ajax({
            url: '{{ route("backend.customers.customer_devis", $data->id) }}',
            method: 'GET',
            success: function(response) {
                console.log('Devis History Response:', response);
                $('#devis-history-loading').hide();
                
                if (response.status && response.data && response.data.length > 0) {
                    displayDevisHistory(response.data);
                    $('#devis-history-table').show();
                } else {
                    $('#devis-history-empty').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Devis History Error:', xhr.responseText);
                $('#devis-history-loading').hide();
                $('#devis-history-empty').show();
                showAlert('Error loading devis history: ' + error, 'danger');
            }
        });
    }

    // Display devis history in table
    function displayDevisHistory(devisList) {
        console.log('Displaying devis history:', devisList);
        const tbody = $('#devis-history-tbody');
        tbody.empty();
        
        devisList.forEach(function(devis, index) {
            console.log(`Processing devis ${index + 1}:`, devis);
            const statusBadge = getStatusBadge(devis.status);
            const createdDate = new Date(devis.created_at).toLocaleDateString();
            const validUntil = devis.valid_until ? new Date(devis.valid_until).toLocaleDateString() : 'N/A';
            const numberOfLot = devis.number_of_lot || '-';
            const row = `
                <tr>
                    <td>
                        <strong>${devis.devis_number}</strong>
                    </td>
                    <td>
                        ${devis.package ? devis.package.name : 'N/A'}
                    </td>
                    <td>${parseFloat(devis.subtotal).toFixed(2)} DH</td>
                    <td>${parseFloat(devis.tax_amount).toFixed(2)} DH</td>
                    <td>
                        <strong>${parseFloat(devis.total_amount).toFixed(2)} DH</strong>
                    </td>
                    <td>${createdDate}</td>
                    <td>${validUntil}</td>
                    <td>${devis.number_of_lot || '-'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary view-devis-history" 
                                    data-devis-id="${devis.id}" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success print-devis-history" 
                                    data-devis-id="${devis.id}" title="Print">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary convert-devis-to-facture" 
                                    data-devis-id="${devis.id}" title="Convertir en facture">
                                <i class="fa-solid fa-right-left"></i>
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-outline-danger delete-devis-history" 
                                    data-devis-id="${devis.id}" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            
            tbody.append(row);
        });
        
        console.log('Total rows added to history table:', tbody.find('tr').length);
        
        // Add event listeners for action buttons
        $('.view-devis-history').on('click', viewDevisDetails);
        $('.print-devis-history').on('click', printDevisDetails);
        $('.convert-devis-to-facture').on('click', convertDevisToFacture);
        $('.edit-devis-history').on('click', editDevisDetails);
        $('.delete-devis-history').on('click', deleteDevisDetails);
    }

    // Get status badge HTML
    function getStatusBadge(status) {
        const statusMap = {
            'draft': '<span class="badge bg-secondary">Draft</span>',
            'sent': '<span class="badge bg-info">Sent</span>',
            'accepted': '<span class="badge bg-success">Accepted</span>',
            'rejected': '<span class="badge bg-danger">Rejected</span>',
            'expired': '<span class="badge bg-warning">Expired</span>'
        };
        return statusMap[status] || '<span class="badge bg-light text-dark">Unknown</span>';
    }

    // Load medical records
    function loadMedicalRecords() {
        $('#medical-records-loading').show();
        $('#medical-records-empty').hide();
        $('#medical-records-table').hide();

        $.ajax({
            url: `{{ route('backend.customers.medical_records.index', $data->id) }}`,
            method: 'GET',
            success: function(response) {
                console.log('Medical Records Response:', response);
                $('#medical-records-loading').hide();

                // Normalize records array regardless of wrapper
                let records = [];
                if (Array.isArray(response)) {
                    records = response;
                } else if (response && Array.isArray(response.data)) {
                    records = response.data;
                } else if (response && response.data && Array.isArray(response.data.data)) {
                    records = response.data.data; // handle paginator shape
                }

                if (records.length > 0) {
                    displayMedicalRecords(records);
                    $('#medical-records-table').show();
                } else {
                    $('#medical-records-empty').show();
                    $('#medical-records-table').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Medical Records Error:', xhr.responseText);
                $('#medical-records-loading').hide();
                $('#medical-records-empty').show();
                showAlert('Error loading medical records: ' + error, 'danger');
            }
        });
    }

    // Render medical records
    function displayMedicalRecords(records) {
        const tbody = $('#medical-records-tbody');
        tbody.empty();

        if (!Array.isArray(records) || records.length === 0) {
            $('#medical-records-empty').show();
            $('#medical-records-table').hide();
            return;
        }

        const getFilePreviewHTML = function(rec) {
            const url = rec.file_url || rec.url || rec.download_url || '#';
            const name = rec.file_name || 'Document';
            const mime = (rec.mime_type || '').toLowerCase();
            const ext = (name.split('.').pop() || '').toLowerCase();

            const isImage = mime.startsWith('image/') || ['jpg','jpeg','png','gif','webp','bmp','svg'].includes(ext);
            const isPdf = mime === 'application/pdf' || ext === 'pdf';
            const isWord = mime.includes('word') || ['doc','docx'].includes(ext);
            const isExcel = mime.includes('sheet') || ['xls','xlsx','csv'].includes(ext);

            if (url === '#' || !url) {
                return '<span class="text-muted">-</span>';
            }

            if (isImage) {
                return `
                    <a href="${url}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                        <img src="${url}" alt="${$('<div>').text(name).html()}" style="height:40px;width:auto;border-radius:4px;border:1px solid #eee;object-fit:cover;margin-right:8px;"/>
                        <span class="text-truncate" style="max-width:220px;" title="${$('<div>').text(name).html()}">${$('<div>').text(name).html()}</span>
                    </a>
                `;
            }

            if (isPdf) {
                return `
                    <a href="${url}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                        <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                        <span class="text-truncate" style="max-width:240px;" title="${$('<div>').text(name).html()}">${$('<div>').text(name).html()}</span>
                    </a>
                `;
            }

            if (isWord) {
                return `
                    <a href="${url}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                        <i class="fa-solid fa-file-word text-primary me-2"></i>
                        <span class="text-truncate" style="max-width:240px;" title="${$('<div>').text(name).html()}">${$('<div>').text(name).html()}</span>
                    </a>
                `;
            }

            if (isExcel) {
                return `
                    <a href="${url}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                        <i class="fa-solid fa-file-excel text-success me-2"></i>
                        <span class="text-truncate" style="max-width:240px;" title="${$('<div>').text(name).html()}">${$('<div>').text(name).html()}</span>
                    </a>
                `;
            }

            return `
                <a href="${url}" target="_blank" class="d-inline-flex align-items-center text-decoration-none">
                    <i class="fa-solid fa-file-lines text-secondary me-2"></i>
                    <span class="text-truncate" style="max-width:240px;" title="${$('<div>').text(name).html()}">${$('<div>').text(name).html()}</span>
                </a>
            `;
        };

        records.forEach(function(rec) {
            const createdAt = rec.created_at ? new Date(rec.created_at).toLocaleString() : 'N/A';
            const updatedAt = rec.updated_at ? new Date(rec.updated_at).toLocaleString() : 'N/A';
            const previewHtml = getFilePreviewHTML(rec);
            const fileUrl = rec.file_url || rec.url || rec.download_url || '#';

            const row = `
                <tr>
                    <td>${previewHtml}</td>
                    <td>${rec.title || '-'}</td>
                    <td>${rec.note ? $('<div>').text(rec.note).html() : '-'}</td>
                    <td>
                        <div><small class="text-muted">Créé:</small> ${createdAt}</div>
                        <div><small class="text-muted">Modifié:</small> ${updatedAt}</div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary" title="View / Download">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-medical-record" data-record-id="${rec.id}" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Bind delete handlers
        $('.delete-medical-record').off('click').on('click', deleteMedicalRecord);

        $('#medical-records-empty').hide();
        $('#medical-records-table').show();
    }

    // Submit new medical record
    function submitMedicalRecord() {
        const form = document.getElementById('medicalRecordForm');
        if (!form) return;

        const fd = new FormData(form);

        const btn = $('#saveMedicalRecordBtn');
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: `{{ route('backend.customers.medical_records.store', $data->id) }}`,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Medical Record Save Response:', response);
                if (response.status) {
                    showAlert(response.message || 'Document saved successfully', 'success');
                    $('#medicalRecordModal').modal('hide');
                    resetMedicalRecordForm();
                    loadMedicalRecords();
                } else {
                    showAlert(response.message || 'Error saving document', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('Medical Record Save Error:', xhr.responseText);
                let msg = 'Error saving document';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showAlert(msg, 'danger');
            },
            complete: function() {
                btn.prop('disabled', false).html(original);
            }
        });
    }

    function resetMedicalRecordForm() {
        $('#medicalRecordForm')[0].reset();
    }

    function deleteMedicalRecord() {
        const recordId = $(this).data('record-id');
        if (!recordId) return;

        if (!confirm('Are you sure you want to delete this document?')) return;

        const btn = $(this);
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: `{{ url('app/customers/medical-records') }}/${recordId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    showAlert('Document deleted', 'success');
                    loadMedicalRecords();
                } else {
                    showAlert(response.message || 'Error deleting document', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('Medical Record Delete Error:', xhr.responseText);
                showAlert('Error deleting document: ' + error, 'danger');
            },
            complete: function() {
                btn.prop('disabled', false).html(original);
            }
        });
    }

    // Photothèque logic
    const photothequeTab = document.getElementById('phototheque-tab');
    if (photothequeTab) {
        photothequeTab.addEventListener('shown.bs.tab', function () {
            loadActsForGallery();
        });
    }

    $('#refreshGalleryBtn').on('click', function() {
        loadActsForGallery();
    });

    $('#actSelect').on('change', function() {
        const actId = $(this).val();
        $('#openGallerySessionModalBtn').prop('disabled', !actId);
        if (actId) loadGallerySessions(actId);
    });

    $('#openGallerySessionModalBtn').on('click', function() {
        $('#gallerySessionForm')[0].reset();
    });

    // Build route URL helpers for galleries
    const GALLERIES_INDEX_TMPL = `{{ route('backend.customers.acts.galleries.index', ['actId' => 'ACT_ID_PLACEHOLDER']) }}`;
    const GALLERIES_STORE_TMPL = `{{ route('backend.customers.acts.galleries.store', ['actId' => 'ACT_ID_PLACEHOLDER']) }}`;
    const GALLERIES_ADD_IMAGES_TMPL = `{{ route('backend.customers.acts.galleries.images.add', ['galleryId' => 'GALLERY_ID_PLACEHOLDER']) }}`;
    const GALLERIES_DELETE_IMAGE_TMPL = `{{ route('backend.customers.acts.galleries.images.delete', ['galleryId' => 'GALLERY_ID_PLACEHOLDER', 'mediaId' => 'MEDIA_ID_PLACEHOLDER']) }}`;
    const GALLERIES_DELETE_TMPL = `{{ route('backend.customers.acts.galleries.delete', ['galleryId' => 'GALLERY_ID_PLACEHOLDER']) }}`;

    $('#saveGallerySessionBtn').on('click', function() {
        const actId = $('#actSelect').val();
        if (!actId) return;
        const fd = new FormData(document.getElementById('gallerySessionForm'));
        const btn = $(this);
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Enregistrement...');
        $.ajax({
            url: GALLERIES_STORE_TMPL.replace('ACT_ID_PLACEHOLDER', actId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp){
                if (resp.status) {
                    $('#gallerySessionModal').modal('hide');
                    loadGallerySessions(actId);
                    showAlert('Session créée', 'success');
                } else {
                    showAlert(resp.message || 'Erreur lors de la création', 'danger');
                }
            },
            error: function(xhr){
                showAlert(xhr.responseJSON?.message || 'Erreur serveur', 'danger');
            },
            complete: function(){ btn.prop('disabled', false).html(original); }
        });
    });

    $('#appendImagesBtn').on('click', function() {
        const galleryId = $('#append_gallery_id').val();
        if (!galleryId) return;
        const fd = new FormData(document.getElementById('appendImagesForm'));
        const btn = $(this);
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Ajout...');
        $.ajax({
            url: GALLERIES_ADD_IMAGES_TMPL.replace('GALLERY_ID_PLACEHOLDER', galleryId),
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp){
                if (resp.status) {
                    $('#appendImagesModal').modal('hide');
                    const actId = $('#actSelect').val();
                    loadGallerySessions(actId);
                    showAlert('Images ajoutées', 'success');
                } else {
                    showAlert(resp.message || 'Erreur lors de l\'ajout', 'danger');
                }
            },
            error: function(xhr){
                showAlert(xhr.responseJSON?.message || 'Erreur serveur', 'danger');
            },
            complete: function(){ btn.prop('disabled', false).html(original); }
        });
    });

    function loadActsForGallery() {
        $('#gallery-loading').show();
        $('#gallery-empty').hide();
        $('#gallery-sessions').empty();
        $('#actSelect').prop('disabled', true).empty().append('<option value="">Chargement...</option>');
        $.ajax({
            url: `{{ route('backend.customers.acts.index', $data->id) }}`,
            method: 'GET',
            success: function(resp){
                const acts = Array.isArray(resp) ? resp : resp.data || [];
                const select = $('#actSelect');
                select.empty().append('<option value="">-- Choisir un acte --</option>');
                acts.forEach(function(a){
                    const label = `${a.service?.name || 'Service #'+a.service_id} • ${a.act_date ? (new Date(a.act_date)).toLocaleDateString() : ''} • ${a.status || ''}`;
                    select.append(`<option value="${a.id}">${label}</option>`);
                });
                select.prop('disabled', false);
                $('#gallery-loading').hide();
                if (acts.length === 0) {
                    $('#gallery-empty').show();
                }
            },
            error: function(){
                $('#gallery-loading').hide();
                showAlert('Erreur lors du chargement des actes', 'danger');
            }
        });
    }

    function loadGallerySessions(actId) {
        $('#gallery-loading').show();
        $('#gallery-empty').hide();
        $('#gallery-sessions').empty();
        $.ajax({
            url: GALLERIES_INDEX_TMPL.replace('ACT_ID_PLACEHOLDER', actId),
            method: 'GET',
            success: function(resp){
                const sessions = Array.isArray(resp) ? resp : resp.data || [];
                renderGallerySessions(sessions);
            },
            error: function(){
                showAlert('Erreur lors du chargement des sessions', 'danger');
            },
            complete: function(){ $('#gallery-loading').hide(); }
        });
    }

    function renderGallerySessions(sessions) {
        const container = $('#gallery-sessions');
        container.empty();
        if (!sessions.length) {
            $('#gallery-empty').show();
            return;
        }
        $('#gallery-empty').hide();
        sessions.forEach(function(s){
            const chips = (s.images || []).map(function(img, idx){
                return `
                    <div class=\"position-relative\">
                        <img src=\"${img.url}\" class=\"img-fluid rounded gallery-thumb\" data-gallery-id=\"${s.id}\" data-index=\"${idx}\" style=\"max-height:160px;object-fit:cover;cursor:zoom-in;\">
                        <button type=\"button\" class=\"btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-gallery-image\" data-gallery-id=\"${s.id}\" data-media-id=\"${img.id}\">&times;</button>
                    </div>
                `;
            }).join('');

            const card = `
                <div class=\"col-md-6 col-lg-4\">
                    <div class=\"card h-100\">
                        <div class=\"card-header d-flex justify-content-between align-items-center\">
                            <div>
                                <span class=\"badge ${s.phase === 'before' ? 'bg-warning text-dark' : 'bg-success'}\">${s.phase === 'before' ? 'Avant' : 'Après'}</span>
                                <small class=\"text-muted ms-2\">${s.session_date ? (new Date(s.session_date)).toLocaleString() : ''}</small>
                            </div>
                            <div class=\"btn-group btn-group-sm\">
                                <button class=\"btn btn-outline-primary append-images\" data-gallery-id=\"${s.id}\"><i class=\"fa-solid fa-plus\"></i></button>
                                <button class=\"btn btn-outline-danger delete-gallery\" data-gallery-id=\"${s.id}\"><i class=\"fa-solid fa-trash\"></i></button>
                            </div>
                        </div>
                        <div class=\"card-body\">
                            <div class=\"mb-2 small text-muted\">${s.note ? $('<div>').text(s.note).html() : ''}</div>
                            <div class=\"row g-2\">${chips || '<div class=\"text-muted\">Aucune image</div>'}</div>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });

        // bind actions
        $('.append-images').off('click').on('click', function(){
            const id = $(this).data('gallery-id');
            $('#append_gallery_id').val(id);
            $('#appendImagesForm')[0].reset();
            $('#appendImagesModal').modal('show');
        });

        $('.delete-gallery-image').off('click').on('click', function(){
            const galleryId = $(this).data('gallery-id');
            const mediaId = $(this).data('media-id');
            if (!confirm('Supprimer cette image ?')) return;
            $.ajax({
                url: GALLERIES_DELETE_IMAGE_TMPL.replace('GALLERY_ID_PLACEHOLDER', galleryId).replace('MEDIA_ID_PLACEHOLDER', mediaId),
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: (resp) => {
                    if (resp.status) {
                        const actId = $('#actSelect').val();
                        loadGallerySessions(actId);
                    } else {
                        showAlert(resp.message || 'Erreur lors de la suppression', 'danger');
                    }
                },
                error: () => showAlert('Erreur serveur', 'danger')
            });
        });

        $('.delete-gallery').off('click').on('click', function(){
            const galleryId = $(this).data('gallery-id');
            if (!confirm('Supprimer cette session et toutes ses images ?')) return;
            $.ajax({
                url: GALLERIES_DELETE_TMPL.replace('GALLERY_ID_PLACEHOLDER', galleryId),
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: (resp) => {
                    if (resp.status) {
                        const actId = $('#actSelect').val();
                        loadGallerySessions(actId);
                    } else {
                        showAlert(resp.message || 'Erreur lors de la suppression', 'danger');
                    }
                },
                error: () => showAlert('Erreur serveur', 'danger')
            });
        });
        // preview modal open
        $('.gallery-thumb').off('click').on('click', function(){
            const galleryId = $(this).data('gallery-id');
            const startIndex = parseInt($(this).data('index')) || 0;
            // store session images in window for navigation
            const session = sessions.find(x => x.id === galleryId);
            if (!session || !session.images || !session.images.length) return;
            window.__galleryImages = session.images;
            window.__galleryIndex = startIndex;
            updatePreviewModal();
            $('#imagePreviewModal').modal('show');
        });
    }

    // Image Preview Modal updater
    function updatePreviewModal() {
        const list = (window.__galleryImages || []);
        let idx = window.__galleryIndex || 0;
        if (!list.length) return;
        if (idx < 0) idx = 0; if (idx >= list.length) idx = list.length - 1;
        window.__galleryIndex = idx;
        const img = list[idx];
        const src = img.url;
        $('#previewImage').attr('src', src);
        $('#previewCounter').text((idx+1) + ' / ' + list.length);
    }

    // next/prev handlers
    $('#nextImageBtn').on('click', function(){
        if (!Array.isArray(window.__galleryImages)) return;
        window.__galleryIndex = (window.__galleryIndex + 1) % window.__galleryImages.length;
        updatePreviewModal();
    });
    $('#prevImageBtn').on('click', function(){
        if (!Array.isArray(window.__galleryImages)) return;
        window.__galleryIndex = (window.__galleryIndex - 1 + window.__galleryImages.length) % window.__galleryImages.length;
        updatePreviewModal();
    });

    // simple click-to-zoom toggle
    let zoomed = false;
    $('#imagePreviewModal').on('click', '#previewImage', function(){
        zoomed = !zoomed;
        $(this).css('cursor', zoomed ? 'zoom-out' : 'zoom-in');
        $(this).css('transform', zoomed ? 'scale(1.5)' : 'scale(1)');
        $(this).css('transition', 'transform .2s ease');
    });

    // View devis details
    function viewDevisDetails() {
        const devisId = $(this).data('devis-id');
        console.log('Viewing devis details for ID:', devisId);
        
        // Show loading state
        $('#viewDevisModal .modal-body').html(`
            <div class="text-center py-4">
                <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2">Loading devis details...</p>
            </div>
        `);
        
        // Show the modal
        $('#viewDevisModal').modal('show');
        
        // Load devis details
        $.ajax({
            url: '{{ route("backend.customers.customer_devis", $data->id) }}',
            method: 'GET',
            success: function(response) {
                console.log('Devis details response:', response);
                
                if (response.status && response.data) {
                    // Find the specific devis
                    const devis = response.data.find(d => d.id == devisId);
                    
                    if (devis) {
                        console.log('Found devis, calling populateDevisModal...');
                        console.log('populateDevisModal function exists:', typeof window.populateDevisModal);
                        if (typeof window.populateDevisModal === 'function') {
                            window.populateDevisModal(devis);
                        } else {
                            console.error('populateDevisModal is not a function!');
                            $('#viewDevisModal .modal-body').html(`
                                <div class="alert alert-danger">
                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                    Function error: populateDevisModal not found
                                </div>
                            `);
                        }
                    } else {
                        $('#viewDevisModal .modal-body').html(`
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                Devis not found!
                            </div>
                        `);
                    }
                } else {
                    $('#viewDevisModal .modal-body').html(`
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            Error loading devis details!
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading devis details:', xhr.responseText);
                $('#viewDevisModal .modal-body').html(`
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Error loading devis details: ${error}
                    </div>
                `);
            }
        });
    }

    // Populate devis modal with data - moved here for proper scope
    window.populateDevisModal = function(devis) {
        console.log('Populating modal with devis:', devis);
        
        // Restore the original modal body structure
        $('#viewDevisModal .modal-body').html(`
            <!-- Devis Header Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Devis Information</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Devis Number:</strong> <span id="view_devis_number"></span></p>
                            <p><strong>Status:</strong> <span id="view_devis_status"></span></p>
                            <p><strong>Created Date:</strong> <span id="view_devis_created"></span></p>
                            <p><strong>Valid Until:</strong> <span id="view_devis_valid_until"></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Package Information</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Package:</strong> <span id="view_package_name"></span></p>
                            <p><strong>Subtotal:</strong> $<span id="view_devis_subtotal"></span></p>
                            <p><strong>Tax Amount:</strong> $<span id="view_devis_tax"></span></p>
                            <p><strong>Total Amount:</strong> $<span id="view_devis_total"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Details -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Services Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="view-services-table">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>TVA %</th>
                                    <th>Subtotal</th>
                                    <th>Tax Amount</th>
                                    <th>Total</th>
                                    <th>Lot #</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="view-services-tbody">
                                <!-- Services will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Remarks</h6>
                </div>
                <div class="card-body">
                    <p id="view_devis_remarks" class="mb-0"></p>
                </div>
            </div>
        `);
        
        // Populate basic devis information
        $('#view_devis_number').text(devis.devis_number);
        $('#view_devis_status').html(getStatusBadge(devis.status));
        $('#view_devis_created').text(new Date(devis.created_at).toLocaleDateString());
        $('#view_devis_valid_until').text(devis.valid_until ? new Date(devis.valid_until).toLocaleDateString() : 'N/A');
        $('#view_package_name').text(devis.package ? devis.package.name : 'N/A');
        $('#view_devis_subtotal').text(parseFloat(devis.subtotal).toFixed(2));
        $('#view_devis_tax').text(parseFloat(devis.tax_amount).toFixed(2));
        $('#view_devis_total').text(parseFloat(devis.total_amount).toFixed(2));
        $('#view_devis_remarks').text(devis.remarks || 'No remarks');
        
        // Populate services table
        const tbody = $('#view-services-tbody');
        tbody.empty();
        
        if (devis.devis_details && devis.devis_details.length > 0) {
            devis.devis_details.forEach(function(service) {
                const row = `
                    <tr>
                        <td>${service.service_name}</td>
                        <td>${service.quantity}</td>
                        <td>${parseFloat(service.price).toFixed(2)} DH</td>
                        <td>${parseFloat(service.discount).toFixed(2)} DH</td>
                        <td>${service.tva_rate || 0}%</td>
                        <td>${parseFloat(service.subtotal).toFixed(2)} DH</td>
                        <td>${parseFloat(service.tax_amount).toFixed(2)} DH</td>
                        <td>${parseFloat(service.total).toFixed(2)} DH</td>
                        <td>${service.number_of_lot || '-'}</td>
                        <td>${service.remarks || '-'}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        } else {
            tbody.append('<tr><td colspan="10" class="text-center text-muted">No services found</td></tr>');
        }

        // Append signature card if present
        const existingSignatureCard = $('#view_devis_signature_card');
        if (existingSignatureCard.length) existingSignatureCard.remove();
        const signatureSrc = devis.signature_image_url || devis.signature_image || null;
        if (signatureSrc) {
            const signatureCard = `
                <div class="card mt-4" id="view_devis_signature_card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-signature me-2"></i>Client Signature</h6>
                    </div>
                    <div class="card-body">
                        <img src="${signatureSrc}" alt="Client Signature" class="img-fluid" style="max-height:240px;">
                    </div>
                </div>
            `;
            $('#viewDevisModal .modal-body').append(signatureCard);
        }
    };

    // Print devis details
    function printDevisDetails() {
        const devisId = $(this).data('devis-id');
        console.log('Printing devis ID:', devisId);
        
        // Open the PDF template in a new window/tab
        const printUrl = `{{ route('backend.customers.print_devis_pdf', '') }}/${devisId}`;
        window.open(printUrl, '_blank');
    }

    // Convert devis to PDF (opens PDF in new tab)
    function convertDevisToFacture() {
        const devisId = $(this).data('devis-id');
        if (!devisId) return;
        if (!confirm('Convertir ce devis en facture ?')) return;

        const btn = $(this);
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        const CONVERT_URL_TMPL = `{{ route('backend.customers.convert_devis', ['devisId' => 'DEVIS_ID_PLACEHOLDER']) }}`;
        const url = CONVERT_URL_TMPL.replace('DEVIS_ID_PLACEHOLDER', devisId);

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp){
                if (resp && resp.status) {
                    // Reload both tables: history and invoices tab converted list
                    try { loadDevisHistory(); } catch(e) {}
                    // If invoice tab is active, force reload by refreshing page section
                    location.reload();
                } else {
                    alert((resp && resp.message) || 'Echec de conversion');
                }
            },
            error: function(xhr){
                alert(xhr.responseJSON?.message || 'Erreur serveur');
            },
            complete: function(){
                btn.prop('disabled', false).html(original);
            }
        });
    }

    // Populate print modal with professional layout
    window.populatePrintModal = function(devis) {
        console.log('Populating print modal with devis:', devis);
        
        const createdDate = new Date(devis.created_at).toLocaleDateString('fr-FR');
        const validUntil = devis.valid_until ? new Date(devis.valid_until).toLocaleDateString('fr-FR') : 'N/A';
        
        // Generate quantity list (1-2-3-4-5)
        const quantityList = '1 - 2 - 3 - 4 - 5';
        
        // Build services table rows
        let servicesRows = '';
        if (devis.devis_details && devis.devis_details.length > 0) {
            devis.devis_details.forEach(function(service, index) {
                const tvaRate = service.tva_rate || 0;
                const tvaAmount = parseFloat(service.tax_amount).toFixed(2);
                const totalWithTax = parseFloat(service.total).toFixed(2);
                
                servicesRows += `
                    <tr>
                        <td class="service-description">
                            ${service.service_name}
                            ${service.remarks ? '<br><small class="text-muted">' + service.remarks + '</small>' : ''}
                        </td>
                        <td class="text-end">${parseFloat(service.subtotal).toFixed(2)} €</td>
                        <td class="text-end">${tvaRate}%<br><small>${tvaAmount} €</small></td>
                        <td class="text-end"><strong>${totalWithTax} €</strong></td>
                        <td class="quantity-list">${quantityList}</td>
                        <td class="text-center">${service.number_of_lot || '-'}</td>
                    </tr>
                `;
            });
        } else {
            servicesRows = '<tr><td colspan="6" class="text-center text-muted">Aucun service trouvé</td></tr>';
        }
        
        // Calculate totals
        const subtotal = parseFloat(devis.subtotal).toFixed(2);
        const totalTax = parseFloat(devis.tax_amount).toFixed(2);
        const grandTotal = parseFloat(devis.total_amount).toFixed(2);
        
        const printContent = `
            <div class="print-devis">
                <!-- Header with Company Info -->
                <div class="print-header">
                    <div class="company-info">
                        <div class="company-logo">
                            LOGO<br>COMPANY
                        </div>
                        <div class="company-details">
                            <strong>T4 ESTHETICS</strong><br>
                            123 Rue de la Beauté<br>
                            75001 Paris, France<br>
                            Tél: +33 1 23 45 67 89<br>
                            Email: contact@t4esthetics.com<br>
                            SIRET: 123 456 789 00012
                        </div>
                    </div>
                </div>
                
                <!-- Devis Title -->
                <div class="devis-title">DEVIS N° ${devis.devis_number}</div>
                
                <!-- Client Information -->
                <div class="client-info">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Client:</strong><br>
                            ${$('#customer_name').text() || 'Nom du Client'}<br>
                            ${$('#customer_email').text() || 'email@client.com'}<br>
                            ${$('#customer_phone').text() || 'Téléphone client'}
                        </div>
                        <div class="col-md-6">
                            <strong>Date de création:</strong> ${createdDate}<br>
                            <strong>Valide jusqu'au:</strong> ${validUntil}<br>
                            <strong>Statut:</strong> ${getStatusText(devis.status)}
                        </div>
                    </div>
                </div>
                
                <!-- Services Table -->
                <table class="print-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">TYPE D'ACTE</th>
                            <th style="width: 12%;">HONORAIRES HT</th>
                            <th style="width: 12%;">TVA 20%</th>
                            <th style="width: 12%;">HONORAIRES TTC</th>
                            <th style="width: 12%;">QUANTITÉ</th>
                            <th style="width: 17%;">NUMÉRO DE LOT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="service-description">
                                <strong>Incluant la réalisation du dossier médical, l'analyse, la prise de photographies, l'environnement des soins et la réalisation des actes.</strong>
                            </td>
                            <td class="text-end">-</td>
                            <td class="text-end">-</td>
                            <td class="text-end">-</td>
                            <td class="quantity-list">-</td>
                            <td class="text-center">-</td>
                        </tr>
                        ${servicesRows}
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td class="text-end"><strong>TOTAL</strong></td>
                            <td class="text-end"><strong>${subtotal} €</strong></td>
                            <td class="text-end"><strong>${totalTax} €</strong></td>
                            <td class="text-end"><strong>${grandTotal} €</strong></td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        </tr>
                    </tfoot>
                </table>
                
                <!-- Footer -->
                <div class="print-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Conditions de paiement:</strong><br>
                            Paiement à la commande ou selon accord commercial.<br>
                            TVA en sus si applicable.
                        </div>
                        <div class="col-md-6">
                            <strong>Validité:</strong><br>
                            Ce devis est valable 30 jours à compter de sa date d'émission.<br>
                            Signature du client obligatoire pour validation.
                        </div>
                    </div>
                    <hr>
                    <div class="text-center mt-3">
                        <p><strong>Merci pour votre confiance</strong></p>
                        <p>T4 ESTHETICS - Votre partenaire beauté et bien-être</p>
                    </div>
                </div>
            </div>
        `;
        
        $('#print-content').html(printContent);
    };

    // Helper function to get status text in French
    function getStatusText(status) {
        const statusMap = {
            'draft': 'Brouillon',
            'sent': 'Envoyé',
            'accepted': 'Accepté',
            'rejected': 'Rejeté',
            'expired': 'Expiré'
        };
        return statusMap[status] || status;
    }

    // Edit devis details
    function editDevisDetails() {
        const devisId = $(this).data('devis-id');
        showAlert('Edit devis functionality will be implemented', 'info');
    }

    // Print button functionality
    $('#actualPrintBtn').on('click', function() {
        console.log('Print button clicked');
        
        // Hide modal header and footer for printing
        $('#printDevisModal .modal-header').hide();
        $('#printDevisModal .modal-footer').hide();
        
        // Trigger print
        window.print();
        
        // Show modal header and footer again after printing
        setTimeout(function() {
            $('#printDevisModal .modal-header').show();
            $('#printDevisModal .modal-footer').show();
        }, 1000);
    });

    // Delete devis details
    function deleteDevisDetails() {
        const devisId = $(this).data('devis-id');
        const devisNumber = $(this).closest('tr').find('td:first strong').text();
        
        if (confirm(`Are you sure you want to delete devis ${devisNumber}? This action cannot be undone.`)) {
            console.log('Deleting devis ID:', devisId);
            
            // Show loading state
            const deleteBtn = $(this);
            const originalHtml = deleteBtn.html();
            deleteBtn.html('<i class="fa-solid fa-spinner fa-spin"></i>');
            deleteBtn.prop('disabled', true);
            
            $.ajax({
                url: `{{ url('app/customers/delete-devis') }}/${devisId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    if (response.status) {
                        showAlert('Devis deleted successfully!', 'success');
                        
                        // Remove the row from the table
                        deleteBtn.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if table is empty
                            const remainingRows = $('#devis-history-tbody tr').length;
                            if (remainingRows === 0) {
                                $('#devis-history-empty').show();
                                $('#devis-history-table').hide();
                            }
                        });
                    } else {
                        showAlert(response.message || 'Error deleting devis', 'danger');
                        deleteBtn.html(originalHtml);
                        deleteBtn.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr.responseText);
                    showAlert('Error deleting devis: ' + error, 'danger');
                    deleteBtn.html(originalHtml);
                    deleteBtn.prop('disabled', false);
                }
            });
        }
    }
});
</script>

<script>
// WhatsApp share for public preconsultation link
$(function(){
    const customerId = {{ $data->id }};
    const signedUrl = `{{ URL::signedRoute('preconsultation.public', ['customerId' => $data->id]) }}`;
    $('#whatsappPreBtn').on('click', function(e){
        e.preventDefault();
        // Copy link to clipboard
        const copyToClipboard = async (text) => {
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                    return true;
                }
            } catch (e) {}
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            let ok = false;
            try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
            document.body.removeChild(ta);
            return ok;
        };

        copyToClipboard(signedUrl).then(function(){
            alert('Lien de Pré-consultation copié dans le presse-papiers.');
            window.open(signedUrl, '_blank');
        }).catch(function(){
            window.open(signedUrl, '_blank');
        });
    });
});
</script>

<script>
// Pré-consultation stepper + submit (lightweight, AJAX placeholder)
$(function(){
    let preStep = 1;
    const totalSteps = 8;

    function updatePreStepIndicator(step){
        $('.step-indicator .step').removeClass('active completed');
        for(let i=1;i<=totalSteps;i++){
            if(i < step) $(`.step-indicator .step[data-step="${i}"]`).addClass('completed');
            else if(i === step) $(`.step-indicator .step[data-step="${i}"]`).addClass('active');
        }
        $('[data-step-panel]').hide();
        $(`[data-step-panel="${step}"]`).show();
        $('#preconsultationPrev').toggle(step > 1);
        $('#preconsultationNext').toggle(step < totalSteps);
        $('#preconsultationSubmit').toggle(step === totalSteps);
    }

    $('#preconsultationModal').on('shown.bs.modal', function(){
        preStep = 1;
        updatePreStepIndicator(preStep);
        // Prefill from API
        const customerId = {{ $data->id }};
        $.ajax({
            url: `{{ route('backend.customers.preconsultation.show', '') }}/${customerId}`,
            method: 'GET',
            success: function(resp){
                if(resp && resp.data){
                    const d = resp.data;
                    // Simple fields
                    $('[name="identite_nom"]').val(d.identite_nom||'');
                    $('[name="identite_prenom"]').val(d.identite_prenom||'');
                    if(d.identite_date_naissance){ $('[name="identite_date_naissance"]').val(String(d.identite_date_naissance).substring(0,10)); }
                    $('[name="identite_email"]').val(d.identite_email||'');
                    $('[name="identite_telephone"]').val(d.identite_telephone||'');
                    $('[name="identite_adresse"]').val(d.identite_adresse||'');
                    $('[name="identite_profession"]').val(d.identite_profession||'');
                    $('#identite_newsletter').prop('checked', !!d.identite_newsletter);

                    // Arrays: antecedents, autoimmunes, allergies, esthetiques, motifs
                    function checkArray(name, arr){
                        if(!Array.isArray(arr)) return;
                        arr.forEach(function(val){
                            $(`input[name="${name}[]"][value="${val}"]`).prop('checked', true);
                        });
                    }
                    checkArray('antecedents', d.antecedents);
                    checkArray('autoimmunes', d.autoimmunes);
                    $('[name="autoimmunes_autre"]').val(d.autoimmunes_autre||'');
                    checkArray('allergies', d.allergies);
                    $('[name="allergies_autre"]').val(d.allergies_autre||'');
                    $('[name="traitements"]').val(d.traitements||'');
                    checkArray('esthetiques', d.esthetiques);
                    $('[name="esthetiques_autre"]').val(d.esthetiques_autre||'');
                    checkArray('motifs', d.motifs);
                    $('[name="motifs_autre"]').val(d.motifs_autre||'');
                    $('#parrainage').prop('checked', !!d.parrainage);
                    $('#declaration_exactitude').prop('checked', !!d.declaration_exactitude);
                } else {
                    // no data: keep empty
                }
            }
        });
    });

    $('#preconsultationPrev').on('click', function(){
        if(preStep > 1){ preStep--; updatePreStepIndicator(preStep); }
    });
    $('#preconsultationNext').on('click', function(){
        if(preStep < totalSteps){ preStep++; updatePreStepIndicator(preStep); }
    });

    $('#preconsultationSubmit').on('click', function(){
        const $btn = $(this);
        const original = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Enregistrement...');

        // Build payload
        const form = document.getElementById('preconsultationForm');
        const fd = new FormData(form);

        // Convert checkboxes arrays to plain arrays for JSON endpoint
        const payload = {};
        fd.forEach((v,k)=>{
            if(k.endsWith('[]')){
                const key = k.replace('[]','');
                if(!payload[key]) payload[key] = [];
                payload[key].push(v);
            } else if(payload[k] !== undefined){
                if(!Array.isArray(payload[k])) payload[k] = [payload[k]];
                payload[k].push(v);
            } else {
                payload[k] = v;
            }
        });

        $.ajax({
            url: '{{ url('app/customers/preconsultation') }}', // TODO: point to your store route
            method: 'POST',
            data: payload,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(resp){
                alert(resp.message || 'Pré-consultation enregistrée');
                $('#preconsultationModal').modal('hide');
                form.reset();
            },
            error: function(xhr){
                let msg = 'Erreur lors de l\'enregistrement';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            },
            complete: function(){
                $btn.prop('disabled', false).html(original);
            }
        });
    });
});

// Function to toggle payment method specific fields
function togglePaymentFields(bookingId) {
    const paymentMethod = document.getElementById('payment_method' + bookingId).value;
    
    // Hide all payment method fields first
    const allFields = document.querySelectorAll('#paymentModal' + bookingId + ' .payment-method-fields');
    allFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Show relevant fields based on payment method
    switch(paymentMethod) {
        case 'cheque':
            document.getElementById('cheque_fields' + bookingId).style.display = 'block';
            break;
        case 'transfer':
            document.getElementById('transfer_fields' + bookingId).style.display = 'block';
            break;
        case 'card':
            document.getElementById('card_fields' + bookingId).style.display = 'block';
            break;
        default:
            // For 'cash' or empty, no additional fields needed
            break;
    }
}

// Function to toggle payment method specific fields for devis factures
function togglePaymentFieldsFacture(factureId) {
    const paymentMethod = document.getElementById('payment_method_facture' + factureId).value;
    
    // Hide all payment method fields first
    const allFields = document.querySelectorAll('#devisFacturePaymentModal' + factureId + ' .payment-method-fields');
    allFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Show relevant fields based on payment method
    switch(paymentMethod) {
        case 'cheque':
            document.getElementById('cheque_fields_facture' + factureId).style.display = 'block';
            break;
        case 'transfer':
            document.getElementById('transfer_fields_facture' + factureId).style.display = 'block';
            break;
        case 'card':
            document.getElementById('card_fields_facture' + factureId).style.display = 'block';
            break;
        default:
            // For 'cash' or empty, no additional fields needed
            break;
    }
}

// Function to remove payment
function removePayment(id, type) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action ne peut pas être annulée.')) {
        const url = type === 'booking' 
            ? `{{ url('app/customers/remove-payment') }}/${id}`
            : `{{ url('app/customers/remove-devis-facture-payment') }}/${id}`;
        
        // Close the modal first
        const modal = document.querySelector('.modal.show');
        if (modal) {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);
                // Reload the page to update the UI
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression du paiement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression du paiement');
        });
    }
}
</script>
@endpush
