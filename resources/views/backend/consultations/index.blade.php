@extends('backend.layouts.app')

@section('title')
    Gestion des consultations
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <i class="fa-regular fa-calendar"></i> Consultation <small class="text-muted">Liste</small>

                <x-slot name="subtitle">
                    Tableau de bord de gestion des consultations
                </x-slot>
                <x-slot name="toolbar">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Rechercher" aria-describedby="addon-wrapping">
                    </div>
                    <select class="form-control select2" id="patient-filter" style="width: 200px;" data-placeholder="Filtrer par patient">
                        <option value="">Tous les patients</option>
                    </select>
                    <button type="button" class="btn btn-primary" id="add-consultation-btn">
                        <i class="fa-solid fa-plus me-2"></i>{{ __('messages.new') }} Consultation
                    </button>
                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table table-striped border table-responsive">
            </table>
        </div>
    </div>

    <!-- Items Modal -->
    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="itemsModalLabel">
                        <i class="fa-regular fa-calendar me-2"></i>Éléments de consultation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div id="items-list" class="p-3">
                        <!-- Items will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultation Form Modal -->
    <div class="modal fade" id="consultationModal" tabindex="-1" aria-labelledby="consultationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consultationModalLabel">
                        <i class="fa-solid fa-user-md me-2"></i>
                        <span id="consultation-modal-title">Ajouter une consultation</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form id="consultation-form">
                    <div class="modal-body">
                        <input type="hidden" id="consultation_id" name="id" value="">
                        
                        <!-- Patient Selection (Hidden in edit mode) -->
                        <div class="form-group mb-3" id="patient-select-container">
                            <label class="form-label">Patient</label>
                            <select 
                                class="form-control" 
                                id="consultation_patient_id" 
                                name="patient_id"
                            >
                                <option value="">Sélectionner un patient (optionnel)</option>
                            </select>
                            <span class="text-danger" id="consultation_patient_id_error"></span>
                        </div>
                        
                        <!-- Patient Display (Shown in edit mode) -->
                        <div class="form-group mb-3" id="patient-display-container" style="display: none;">
                            <label class="form-label">Patient</label>
                            <input 
                                type="text" 
                                id="consultation_patient_display" 
                                class="form-control" 
                                readonly 
                                disabled
                            />
                            <input type="hidden" id="consultation_patient_id_hidden" name="patient_id" value="">
                        </div>

                        <!-- Date Input -->
                        <div class="form-group mb-3">
                            <label class="form-label">Date de consultation <span class="text-danger">*</span></label>
                            <input 
                                type="date" 
                                id="consultation_date" 
                                name="consultation_date" 
                                class="form-control" 
                                required
                            />
                            <span class="text-danger" id="consultation_date_error"></span>
                        </div>

                        <!-- Dynamic Textareas -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label">Éléments de consultation <span class="text-danger">*</span></label>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-primary" 
                                    id="add-consultation-item-btn">
                                    <i class="fas fa-plus"></i> Ajouter un élément
                                </button>
                            </div>
                            
                            <div id="consultation-items-container">
                                <div class="mb-3 consultation-item">
                                    <div class="input-group">
                                        <textarea 
                                            name="items[]" 
                                            class="form-control consultation-item-text" 
                                            rows="3"
                                            placeholder="Élément 1"
                                            required
                                        ></textarea>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-danger remove-item-btn" 
                                            style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <span class="text-danger" id="consultation_items_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="save-consultation-btn">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <!-- DataTables Core and Extensions -->
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" defer>
        const columns = [
            {
                name: 'check',
                data: 'check',
                title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                width: '0%',
                exportable: false,
                orderable: false,
                searchable: false,
            },
            { 
                data: 'consultation_date', 
                name: 'consultation_date', 
                title: 'Date' 
            },
            { 
                data: 'patient_name', 
                name: 'patient_name', 
                title: 'Patient',
                orderable: false,
                searchable: false
            },
            { 
                data: 'items', 
                name: 'items', 
                title: 'Éléments',
                orderable: false,
                searchable: false
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                title: 'Mis à jour le',
                width: '15%',
            },
        ]

        const actionColumn = [
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                title: 'Action' 
            }
        ]

        let finalColumns = [
            ...columns,
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {
            // Initialize patient filter
            $('#patient-filter').select2({
                ajax: {
                    url: '{{ route("backend.users.user_list") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            role: 'user'
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.full_name + (item.email ? ' (' + item.email + ')' : '')
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                placeholder: 'Filtrer par patient',
                allowClear: true
            });

            // Initialize DataTable
            const dataTable = initDatatable({
                url: '{{ route("backend.consultations.index_data") }}',
                finalColumns,
                orderColumn: [[ 4, "desc" ]],
            })

            // Handle patient filter change
            $('#patient-filter').on('change', function() {
                const patientId = $(this).val();
                if (renderedDataTable) {
                    renderedDataTable.ajax.url('{{ route("backend.consultations.index_data") }}?patient_id=' + (patientId || '')).load();
                }
            });

            // Handle view items button click
            $(document).on('click', '.view-items-btn', function() {
                const items = $(this).data('items');
                const consultationId = $(this).data('id');
                const modal = new bootstrap.Modal(document.getElementById('itemsModal'));
                
                let itemsHtml = '';
                if (items && Array.isArray(items) && items.length > 0) {
                    itemsHtml = '<div class="row g-3">';
                    items.forEach((item, index) => {
                        itemsHtml += `
                            <div class="col-12 col-md-6">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-2">
                                            <span class="badge bg-primary rounded-pill me-2 fs-6">${index + 1}</span>
                                            <h6 class="card-title mb-0 text-primary">
                                                <i class="fa-solid fa-file-lines me-2"></i>Élément ${index + 1}
                                            </h6>
                                        </div>
                                        <div class="card-text mt-3">
                                            <p class="mb-0 text-muted" style="white-space: pre-wrap; word-wrap: break-word;">${escapeHtml(item)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    itemsHtml += '</div>';
                } else {
                    itemsHtml = '<div class="alert alert-info mb-0 text-center"><i class="fa-solid fa-info-circle me-2"></i>Aucun élément trouvé.</div>';
                }
                
                $('#items-list').html(itemsHtml);
                $('#itemsModalLabel').html('<i class="fa-regular fa-calendar me-2"></i>Éléments de consultation #' + consultationId);
                modal.show();
            });

            // Helper function to escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Load patients list for select
            function loadPatientsList() {
                $.ajax({
                    url: '{{ route("backend.users.user_list") }}',
                    dataType: 'json',
                    data: {
                        role: 'user'
                    },
                    success: function(data) {
                        const select = $('#consultation_patient_id');
                        // Keep the first option (placeholder)
                        select.find('option:not(:first)').remove();
                        
                        // Add patients to select
                        if (data && data.length > 0) {
                            data.forEach(function(patient) {
                                const option = $('<option></option>')
                                    .attr('value', patient.id)
                                    .text(patient.full_name + (patient.email ? ' (' + patient.email + ')' : ''));
                                select.append(option);
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading patients:', xhr);
                    }
                });
            }

            // Reset consultation form
            function resetConsultationForm() {
                $('#consultation_id').val('');
                $('#consultation_date').val('');
                $('#consultation_date_error').text('');
                $('#consultation_items_error').text('');
                $('#consultation_patient_id_error').text('');
                
                // Reset patient select
                $('#consultation_patient_id').val('');
                $('#consultation_patient_id_hidden').val('');
                $('#consultation_patient_display').val('');
                
                // Show select, hide display (for add mode)
                $('#patient-select-container').show();
                $('#patient-display-container').hide();
                
                // Reset items - keep only one empty item
                $('#consultation-items-container').html(`
                    <div class="mb-3 consultation-item">
                        <div class="input-group">
                            <textarea 
                                name="items[]" 
                                class="form-control consultation-item-text" 
                                rows="3"
                                placeholder="Élément 1"
                                required
                            ></textarea>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-danger remove-item-btn" 
                                style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
                updateRemoveButtons();
            }
            
            // Add consultation item
            function addConsultationItem() {
                const itemCount = $('#consultation-items-container .consultation-item').length + 1;
                const newItem = `
                    <div class="mb-3 consultation-item">
                        <div class="input-group">
                            <textarea 
                                name="items[]" 
                                class="form-control consultation-item-text" 
                                rows="3"
                                placeholder="Élément ${itemCount}"
                                required
                            ></textarea>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-danger remove-item-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#consultation-items-container').append(newItem);
                updateRemoveButtons();
            }
            
            // Update remove buttons visibility
            function updateRemoveButtons() {
                const itemCount = $('#consultation-items-container .consultation-item').length;
                if (itemCount > 1) {
                    $('.remove-item-btn').show();
                } else {
                    $('.remove-item-btn').hide();
                }
            }
            
            // Remove consultation item
            $(document).on('click', '.remove-item-btn', function() {
                if ($('#consultation-items-container .consultation-item').length > 1) {
                    $(this).closest('.consultation-item').remove();
                    updateRemoveButtons();
                }
            });
            
            // Handle add consultation item button
            $(document).on('click', '#add-consultation-item-btn', function() {
                addConsultationItem();
            });
            
            // Handle add consultation button
            $(document).on('click', '#add-consultation-btn', function(e) {
                e.preventDefault();
                resetConsultationForm();
                $('#consultation-modal-title').text('Ajouter une consultation');
                // Load patients list
                loadPatientsList();
                $('#consultationModal').modal('show');
            });
            
            // Load patients list when modal is shown
            $('#consultationModal').on('shown.bs.modal', function() {
                // Only load if select is empty (no patients loaded yet)
                if ($('#consultation_patient_id option').length <= 1) {
                    loadPatientsList();
                }
            });
            
            // Reset form when modal is hidden
            $('#consultationModal').on('hidden.bs.modal', function() {
                resetConsultationForm();
            });
            
            // Handle edit button clicks
            $(document).on('click', '.edit-consultation-btn', function(e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.attr('data-id');
                
                if (!id) {
                    console.error('No consultation ID found');
                    return false;
                }
                
                // Load consultation data
                $.ajax({
                    url: '/app/consultations/' + id + '/edit',
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.status && response.data) {
                            const data = response.data;
                            
                            // Set form values
                            $('#consultation_id').val(data.id);
                            $('#consultation_date').val(data.consultation_date);
                            
                            // Clear and populate items
                            $('#consultation-items-container').empty();
                            if (data.items && Array.isArray(data.items) && data.items.length > 0) {
                                data.items.forEach(function(item, index) {
                                    const itemHtml = `
                                        <div class="mb-3 consultation-item">
                                            <div class="input-group">
                                                <textarea 
                                                    name="items[]" 
                                                    class="form-control consultation-item-text" 
                                                    rows="3"
                                                    placeholder="Élément ${index + 1}"
                                                    required
                                                >${escapeHtml(item)}</textarea>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-sm btn-danger remove-item-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;
                                    $('#consultation-items-container').append(itemHtml);
                                });
                            } else {
                                addConsultationItem();
                            }
                            updateRemoveButtons();
                            
                            // Hide select, show patient display (edit mode)
                            $('#patient-select-container').hide();
                            $('#patient-display-container').show();
                            
                            // Load patient name for display
                            if (data.patient_id) {
                                $.ajax({
                                    url: '{{ route("backend.users.user_list") }}',
                                    data: { role: 'user' },
                                    dataType: 'json',
                                    success: function(users) {
                                        const patient = users.find(u => u.id == data.patient_id);
                                        if (patient) {
                                            $('#consultation_patient_display').val(patient.full_name + (patient.email ? ' (' + patient.email + ')' : ''));
                                            $('#consultation_patient_id_hidden').val(data.patient_id);
                                        }
                                    }
                                });
                            } else {
                                $('#consultation_patient_display').val('Aucun patient');
                                $('#consultation_patient_id_hidden').val('');
                            }
                            
                            // Show modal
                            $('#consultation-modal-title').text('Modifier la consultation');
                            $('#consultationModal').modal('show');
                        } else {
                            window.errorSnackbar('Échec du chargement des données de consultation');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading consultation:', xhr);
                        window.errorSnackbar('Erreur lors du chargement des données de consultation');
                    }
                });
                
                return false;
            });
            
            // Handle form submission
            $('#consultation-form').on('submit', function(e) {
                e.preventDefault();
                
                // Get patient_id from select (add mode) or hidden field (edit mode)
                const patientId = $('#consultation_patient_id').length && $('#patient-select-container').is(':visible') 
                    ? $('#consultation_patient_id').val() 
                    : $('#consultation_patient_id_hidden').val();
                
                const formData = {
                    consultation_date: $('#consultation_date').val(),
                    patient_id: patientId || null,
                    items: []
                };
                
                // Collect items
                $('.consultation-item-text').each(function() {
                    const itemText = $(this).val().trim();
                    if (itemText) {
                        formData.items.push(itemText);
                    }
                });
                
                // Validation
                if (!formData.consultation_date) {
                    $('#consultation_date_error').text('La date de consultation est requise');
                    return false;
                }
                
                if (formData.items.length === 0) {
                    $('#consultation_items_error').text('Au moins un élément de consultation est requis');
                    return false;
                }
                
                // Clear errors
                $('#consultation_date_error').text('');
                $('#consultation_items_error').text('');
                $('#consultation_patient_id_error').text('');
                
                // Disable submit button
                const submitBtn = $('#save-consultation-btn');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Enregistrement...');
                
                const consultationId = $('#consultation_id').val();
                const url = consultationId 
                    ? '/app/consultations/' + consultationId 
                    : '/app/consultations';
                
                // Prepare data for update - Laravel expects _method for PUT
                if (consultationId) {
                    formData._method = 'PUT';
                }
                
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.status) {
                            window.successSnackbar(response.message);
                            $('#consultationModal').modal('hide');
                            resetConsultationForm();
                            
                            // Reload datatable
                            if (renderedDataTable) {
                                renderedDataTable.ajax.reload(null, false);
                            }
                        } else {
                            window.errorSnackbar(response.message || 'Une erreur s\'est produite');
                            
                            // Show validation errors
                            if (response.all_message) {
                                Object.keys(response.all_message).forEach(function(key) {
                                    const errorKey = key.replace('.', '_');
                                    $('#' + errorKey + '_error').text(response.all_message[key][0] || '');
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Une erreur s\'est produite lors de l\'enregistrement';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Show validation errors
                            Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                                const errorKey = key.replace('.', '_');
                                $('#' + errorKey + '_error').text(xhr.responseJSON.errors[key][0] || '');
                            });
                            errorMsg = 'Veuillez corriger les erreurs de validation';
                        }
                        window.errorSnackbar(errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
                
                return false;
            });
            
            // Handle delete button clicks
            $(document).on('click', '.delete-consultation-btn', function(e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.attr('data-id');
                const token = btn.attr('data-token');
                const url = btn.attr('href');
                
                if (confirm('Êtes-vous sûr de vouloir supprimer cette consultation ?')) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        success: function(data) {
                            if (data.status) {
                                window.successSnackbar(data.message);
                                if (renderedDataTable) {
                                    renderedDataTable.ajax.reload(null, false);
                                }
                            } else {
                                window.errorSnackbar(data.message || 'Une erreur s\'est produite');
                            }
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Une erreur s\'est produite lors de la suppression';
                            window.errorSnackbar(errorMsg);
                        }
                    });
                }
                
                return false;
            });
        })
    </script>
@endpush

