@extends('backend.layouts.app')

@section('title')
    {{ __('messages.new') }} Consultation
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-backend.section-header>
            <i class="fa-regular fa-calendar"></i> Consultation <small class="text-muted">Create</small>

            <x-slot name="subtitle">
                Consultation Management Dashboard
            </x-slot>
            <x-slot name="toolbar">
                <x-backend.buttons.return-back />
                <a href="{{ route('backend.consultations.index') }}" class="btn btn-secondary ms-1" data-bs-toggle="tooltip" title="Consultation List">
                    <i class="fas fa-list-ul"></i> List
                </a>
            </x-slot>
        </x-backend.section-header>

        <hr>

        <div class="row mt-4">
            <div class="col">
                <form action="{{ route('backend.consultations.store') }}" method="POST" id="consultation-form">
                    @csrf
                    
                    <!-- Patient Selection -->
                    <div class="form-group mb-3">
                        <label for="patient_id" class="form-label">
                            Patient
                        </label>
                        <div class="d-flex gap-2 align-items-start">
                            <select 
                                class="form-control select2" 
                                id="patient_id" 
                                name="patient_id"
                                data-placeholder="Select Patient (optional)"
                                style="width: 100%;"
                            >
                                <option value="">{{ old('patient_id') ? '' : 'Select Patient (optional)' }}</option>
                                @if(old('patient_id'))
                                    @php
                                        $oldPatient = \App\Models\User::find(old('patient_id'));
                                    @endphp
                                    @if($oldPatient)
                                        <option value="{{ $oldPatient->id }}" selected>{{ $oldPatient->first_name }} {{ $oldPatient->last_name }}</option>
                                    @endif
                                @endif
                            </select>
                            <button 
                                type="button" 
                                class="btn btn-primary" 
                                id="select-patient-btn"
                                title="Select Patient">
                                <i class="fas fa-user"></i> Select Patient
                            </button>
                        </div>
                        @error('patient_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Input -->
                    <div class="form-group mb-3">
                        <label for="consultation_date" class="form-label">
                            Consultation Date <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="date" 
                            class="form-control @error('consultation_date') is-invalid @enderror" 
                            id="consultation_date" 
                            name="consultation_date" 
                            value="{{ old('consultation_date') }}" 
                            required
                        >
                        @error('consultation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dynamic Textareas Section -->
                    <div class="form-group mb-3">
                        <label class="form-label">
                            Consultation Items <span class="text-danger">*</span>
                        </label>
                        
                        <div id="items-container">
                            <!-- Initial textarea -->
                            <div class="item-row mb-3" data-index="0">
                                <div class="d-flex gap-2">
                                    <textarea 
                                        class="form-control item-textarea" 
                                        name="items[]" 
                                        rows="3" 
                                        placeholder="Enter consultation item..."></textarea>
                                    <button 
                                        type="button" 
                                        class="btn btn-danger remove-item-btn align-self-start" 
                                        style="display: none;"
                                        title="Remove item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            class="btn btn-success btn-sm mt-2" 
                            id="add-item-btn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>

                        @error('items')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        @error('items.*')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Create Consultation
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="float-end">
                                <div class="form-group">
                                    <a href="{{ route('backend.consultations.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
</style>
@endpush

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for patient selection
    $('#patient_id').select2({
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
        placeholder: 'Select Patient (optional)',
        allowClear: true
    });

    // Button to trigger Select2 dropdown
    $('#select-patient-btn').on('click', function() {
        $('#patient_id').select2('open');
    });

    // Load initial value if exists
    @if(old('patient_id'))
        var oldPatientId = {{ old('patient_id') }};
        $.ajax({
            url: '{{ route("backend.users.user_list") }}',
            data: { role: 'user' },
            dataType: 'json'
        }).done(function(data) {
            var patient = data.find(function(item) {
                return item.id == oldPatientId;
            });
            if (patient) {
                var option = new Option(patient.full_name, patient.id, true, true);
                $('#patient_id').append(option).trigger('change');
            }
        });
    @endif
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item-btn');
    let itemIndex = 1;

    // Add new textarea
    addItemBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'item-row mb-3';
        newItem.setAttribute('data-index', itemIndex);
        
        newItem.innerHTML = `
            <div class="d-flex gap-2">
                <textarea 
                    class="form-control item-textarea" 
                    name="items[]" 
                    rows="3" 
                    placeholder="Enter consultation item..."></textarea>
                <button 
                    type="button" 
                    class="btn btn-danger remove-item-btn align-self-start" 
                    title="Remove item">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        
        // Show remove buttons if there's more than one item
        updateRemoveButtons();
        
        // Focus on the new textarea
        const newTextarea = newItem.querySelector('.item-textarea');
        newTextarea.focus();
    });

    // Remove textarea
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            const itemRow = e.target.closest('.item-row');
            itemRow.remove();
            updateRemoveButtons();
        }
    });

    // Update remove button visibility
    function updateRemoveButtons() {
        const itemRows = itemsContainer.querySelectorAll('.item-row');
        const removeButtons = itemsContainer.querySelectorAll('.remove-item-btn');
        
        if (itemRows.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }

    // Form validation
    document.getElementById('consultation-form').addEventListener('submit', function(e) {
        const textareas = itemsContainer.querySelectorAll('.item-textarea');
        const hasContent = Array.from(textareas).some(textarea => {
            return textarea.value.trim().length > 0;
        });

        if (!hasContent) {
            e.preventDefault();
            alert('Please add at least one consultation item.');
            return false;
        }
    });
});
</script>
@endpush

