@extends('backend.layouts.app')

@section('title')
    Consultation Management
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <i class="fa-regular fa-calendar"></i> Consultation <small class="text-muted">List</small>

                <x-slot name="subtitle">
                    Consultation Management Dashboard
                </x-slot>
                <x-slot name="toolbar">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search" aria-describedby="addon-wrapping">
                    </div>
                    <select class="form-control select2" id="patient-filter" style="width: 200px;" data-placeholder="Filter by Patient">
                        <option value="">All Patients</option>
                    </select>
                    <x-buttons.create route="{{ route('backend.consultations.create') }}" title="">{{ __('messages.new') }} Consultation</x-buttons.create>
                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table table-striped border table-responsive">
            </table>
        </div>
    </div>

    <!-- Items Modal -->
    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">
                        <i class="fa-regular fa-calendar me-2"></i>Consultation Items
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="items-list">
                        <!-- Items will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div data-render="app">
        <consultation-offcanvas
            create-title="{{ __('messages.new') }} Consultation"
            edit-title="{{ __('messages.edit') }} Consultation">
        </consultation-offcanvas>
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
                data: 'id', 
                name: 'id', 
                title: 'ID' 
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
                title: 'Items',
                orderable: false,
                searchable: false
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                title: 'Updated At',
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
                placeholder: 'Filter by Patient',
                allowClear: true
            });

            // Initialize DataTable
            const dataTable = initDatatable({
                url: '{{ route("backend.consultations.index_data") }}',
                finalColumns,
                orderColumn: [[ 5, "desc" ]],
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
                    itemsHtml = '<div class="list-group">';
                    items.forEach((item, index) => {
                        itemsHtml += `
                            <div class="list-group-item">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-pill me-3 mt-1">${index + 1}</span>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">${escapeHtml(item)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    itemsHtml += '</div>';
                } else {
                    itemsHtml = '<div class="alert alert-info mb-0">No items found.</div>';
                }
                
                $('#items-list').html(itemsHtml);
                $('#itemsModalLabel').html('<i class="fa-regular fa-calendar me-2"></i>Consultation Items #' + consultationId);
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
        })
    </script>
    <script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
    <script src="{{ mix('js/vue.min.js') }}" defer></script>
    <script src="{{ mix('js/consultation.js') }}" defer></script>
@endpush

