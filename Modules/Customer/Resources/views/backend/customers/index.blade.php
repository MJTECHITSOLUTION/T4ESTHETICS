@extends('backend.layouts.app')

@section('title')
{{ __($module_action) }} {{ __($module_title) }}
@endsection


@push('after-styles')
<link rel="stylesheet" href="{{ mix('modules/constant/style.css') }}">
@endpush
@section('content')
<div class="card">
  <div class="card-body">
    <x-backend.section-header>
      <div class="d-flex flex-wrap gap-3">
        @if(auth()->user()->can('edit_customer') || auth()->user()->can('delete_customer'))
        <x-backend.quick-action url='{{route("backend.$module_name.bulk_action")}}'>
          <div class="">
            <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
              <option value="">{{ __('messages.no_action') }}</option>
              @can('edit_customer')
              <option value="change-status">{{ __('messages.status') }}</option>
              @endcan
              @can('delete_customer')
            <option value="delete">{{ __('messages.delete') }}</option>
            @endcan
            </select>
          </div>
          <div class="select-status d-none quick-action-field" id="change-status-action">
            <select name="status" class="form-control select2" id="status" style="width:100%">
              <option value="1">{{ __('messages.active') }}</option>
              <option value="0">{{ __('messages.inactive') }}</option>
            </select>
          </div>
        </x-backend.quick-action>
        @endif
        <div>
          <button type="button" class="btn btn-secondary" data-modal="export">
            <i class="fa-solid fa-download"></i> {{ __('messages.export') }}
          </button>
{{--          <button type="button" class="btn btn-secondary" data-modal="import">--}}
{{--            <i class="fa-solid fa-upload"></i> Import--}}
{{--          </button>--}}
        </div>
      </div>

      <x-slot name="toolbar">
        <div class="input-group flex-nowrap">
          <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search"
            aria-describedby="addon-wrapping">
        </div>
        @hasPermission('add_customer')
          <x-buttons.offcanvas target='#form-offcanvas' title="">{{ __('messages.new') }}</x-buttons.offcanvas>
        @endhasPermission
      </x-slot>
    </x-backend.section-header>
    <table id="datatable" class="table table-striped border table-responsive">
    </table>
  </div>
</div>
<div data-render="app">
  <customer-offcanvas default-image="{{default_user_avatar()}}" create-title="{{ __('messages.new') }} {{ __('customer.singular_title') }}"
    edit-title="{{ __('messages.edit') }} {{ __('customer.singular_title') }}" :customefield="{{ json_encode($customefield) }}">
  </customer-offcanvas>
  <change-password create-title="{{ __('messages.change_password') }}"></change-password>
</div>

<!-- Empty Modal -->
<div class="modal fade" id="emptyModal" tabindex="-1" aria-labelledby="emptyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emptyModalLabel">Modifier patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Intentionally empty for now -->
      </div>
    </div>
  </div>
  <input type="hidden" value="1">
</div>

<input type="hidden" id="customerEditBasePath" value="{{ url('app/customers') }}">
@endsection

@push('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<script src="{{ mix('modules/customer/script.js') }}"></script>
<script src="{{ asset('js/form-offcanvas/index.js') }}" defer></script>
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>

<!-- DataTables Core and Extensions -->
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="text/javascript" defer>
  const columns = [{
        name: 'check',
        data: 'check',
        title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
        width: '0%',
        exportable: false,
        orderable: false,
        searchable: false,
      },
      // {
      //   data: 'image',
      //   name: 'image',
      //   width: '0%',
      //   orderable: false,
      //   searchable: false,
      //   title: "{{ __('customer.lbl_profile_image') }}"
      // },

    {
        data: 'user_id',
        name: 'user_id',
        title: "{{ __('booking.lbl_customer_name') }}"
    },
      
      // {
      //   data: 'first_name',
      //   name: 'first_name',
      //   title: "{{ __('customer.lbl_first_name') }}"
      // },
      // {
      //   data: 'last_name',
      //   name: 'last_name',
      //   title: "{{ __('customer.lbl_last_name') }}"
      // },
      
      {
        data: 'gender',
        name: 'gender',
        title: "{{ __('customer.lbl_gender') }}"
      },
      // {
      //   data: 'email',
      //   name: 'email',
      //   title: "{{ __('customer.lbl_Email') }}"
      // },
      // {
      //   data: 'image',
      //   name: 'image',
      //   width: '0%',
      //   orderable: false,
      //   searchable: false,
      //   title: "{{ __('customer.lbl_profile_image') }}"
      // },
      // {
      //   data: 'first_name',
      //   name: 'first_name',
      //   title: "{{ __('customer.lbl_first_name') }}"
      // },
      // {
      //   data: 'last_name',
      //   name: 'last_name',
      //   title: "{{ __('customer.lbl_last_name') }}"
      // },
      // {
      //   data: 'email',
      //   name: 'email',
      //   title: "{{ __('customer.lbl_Email') }}"
      // },
      {
        data: 'email_verified_at',
        name: 'email_verified_at',
        orderable: false,
        searchable: true,
        title: "{{ __('customer.lbl_verification_status') }}"
      },
      // {
      //   data: 'is_banned',
      //   name: 'is_banned',
      //   orderable: false,
      //   searchable: true,
      //   title: "{{ __('customer.lbl_blocked') }}"
      // },
      {
        data: 'status',
        name: 'status',
        orderable: false,
        searchable: true,
        title: "{{ __('customer.lbl_status') }}"
      },
      {
        data: 'updated_at',
        name: 'updated_at',
        title: "{{ __('customer.lbl_update_at') }}",
        orderable: true,
        visible: false,
       },
    ]

    const actionColumn = [{
      data: 'action',
      name: 'action',
      orderable: false,
      searchable: false,
      title: "{{ __('customer.lbl_action') }}"
    }]

    const customFieldColumns = JSON.parse(@json($columns))

    let finalColumns = [
      ...columns,
      ...customFieldColumns,
      ...actionColumn
    ]

    document.addEventListener('DOMContentLoaded', (event) => {
      initDatatable({
        url: '{{ route("backend.$module_name.index_data") }}',
        finalColumns,
        orderColumn: [[ 6, "desc" ]],

      })
    })

    function resetQuickAction() {
      const actionValue = $('#quick-action-type').val();
      if (actionValue != '') {
        $('#quick-action-apply').removeAttr('disabled');

        if (actionValue == 'change-status') {
          $('.quick-action-field').addClass('d-none');
          $('#change-status-action').removeClass('d-none');
        } else {
          $('.quick-action-field').addClass('d-none');
        }
      } else {
        $('#quick-action-apply').attr('disabled', true);
        $('.quick-action-field').addClass('d-none');
      }
    }

    $('#quick-action-type').change(function() {
      resetQuickAction()
    });

    $(document).on('update_quick_action', function() {
      // resetActionButtons()
    })

    // Removed legacy handler for data-open="modal" to avoid opening a second modal

    // Open empty modal and load edit form via AJAX using the provided id
    $(document).on('click', 'button[data-open="empty-modal"]', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const id = $(this).attr('data-crud-id');
      const base = $('#customerEditBasePath').val();
      const url = id ? (base + '/' + id + '/edit') : null;
      const modalEl = document.getElementById('emptyModal');
      const modalBody = $('#emptyModal .modal-body');
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

      if (url) {
        modalBody.html('<div class="p-4 text-center"><div class="spinner-border" role="status"></div></div>');
        modal.show();

        // Try JSON first; if HTML, fallback to injecting as is
        console.log('[CustomerModal] Fetch edit start', { url });
        fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
          .then(async (res) => {
            const status = res.status;
            const contentType = res.headers.get('content-type') || '';
            console.log('[CustomerModal] Fetch edit response meta', { status, contentType });
            if (contentType.includes('application/json')) {
              const data = await res.json();
              console.log('[CustomerModal] Edit JSON payload', data);
              if (data && data.status && data.data) {
                const user = data.data;
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                // Prefer POST-only route if available, else fallback to RESTful PUT via spoofing
                const updateUrl = base + '/' + user.id + '/update';
                const genderOptions = ['male','female','other'];
                const genderRadios = genderOptions.map(g => `
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="gender_${g}" value="${g}" ${user.gender===g?'checked':''}>
                    <label class="form-check-label" for="gender_${g}">${g.charAt(0).toUpperCase()+g.slice(1)}</label>
                  </div>
                `).join('');

                // Extended options to mirror Vue form
                const languageOptions = [
                  { value: 'fr', label: 'Français' },
                  { value: 'ar', label: 'Arabe' },
                  { value: 'ber', label: 'Berbère' },
                  { value: 'en', label: 'Anglais' }
                ];
                const adressageOptions = [
                  { value: 'medecin', label: 'Médecin' },
                  { value: 'de_passage', label: 'De passage' },
                  { value: 'coiffeur', label: 'Coiffeur' },
                  { value: 'estheticienne', label: 'Esthéticienne' },
                  { value: 'pharmacien', label: 'Pharmacien' },
                  { value: 'collegue', label: 'Collègue' },
                  { value: 'proches', label: 'Proches' }
                ];
                const motifOptions = [
                  { value: 'cosmetique', label: 'Cosmétique' },
                  { value: 'visage', label: 'Visage' },
                  { value: 'cheveux', label: 'Cheveux' },
                  { value: 'intime', label: 'Intime' },
                  { value: 'silhouette', label: 'Silhouette' },
                  { value: 'epilation', label: 'Epilation' }
                ];
                const origineOptions = [
                  { value: 'de_passage', label: 'De passage' },
                  { value: 'site_web', label: 'Site web' },
                  { value: 'parrinage', label: 'Parrinage' },
                  { value: 'les_publicites', label: 'Les publicités' },
                  { value: 'autre', label: 'Autre' }
                ];

                const toArray = (val) => Array.isArray(val) ? val.map(String) : (val ? [String(val)] : []);
                const langueParlee = toArray(user.langue_parlee);
                const motifConsultation = toArray(user.motif_consultation);
                const adressageVal = user.adressage ?? '';
                const originePatientVal = user.origine_patient ?? '';
                const optionHtml = (opts, selectedVals = []) => opts.map(o => `<option value="${o.value}" ${selectedVals.includes(String(o.value)) ? 'selected' : ''}>${o.label}</option>`).join('');

                const isPostOnlyUpdate = updateUrl.endsWith('/update');
                const formHtml = `
                  <form action="${updateUrl}" method="POST" enctype="multipart/form-data" data-customer-id="${user.id}">
                    <input type="hidden" name="_token" value="${token}">
                    ${isPostOnlyUpdate ? '' : '<input type="hidden" name="_method" value="PUT">'}
                    <div class="row g-3">
                      <div class="col-12 text-center">
                        <img src="${user.profile_image || ''}" class="img-fluid avatar avatar-120 avatar-rounded mb-2" alt="profile-image" />
                        <div class="mt-2">
                          <input type="file" class="form-control" name="profile_image" accept=".jpeg,.jpg,.png,.gif">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">First name</label>
                        <input type="text" class="form-control" name="first_name" value="${user.first_name??''}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Last name</label>
                        <input type="text" class="form-control" name="last_name" value="${user.last_name??''}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="${user.email??''}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Mobile</label>
                        <input type="text" class="form-control" name="mobile" value="${user.mobile??''}">
                      </div>
                      <div class="col-md-12">
                        <label class="form-label d-block">Gender</label>
                        ${genderRadios}
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Profession</label>
                        <input type="text" class="form-control" name="profession" value="${user.profession??''}">
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Adresse</label>
                        <textarea class="form-control" rows="2" name="adresse">${user.adresse??''}</textarea>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Langue parlée</label>
                        <select class="form-select" name="langue_parlee[]" multiple>
                          ${optionHtml(languageOptions, langueParlee)}
                        </select>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Adressage</label>
                        <select class="form-select" name="adressage">
                          <option value=""></option>
                          ${optionHtml(adressageOptions, [String(adressageVal)])}
                        </select>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Motif de consultation</label>
                        <select class="form-select" name="motif_consultation[]" multiple>
                          ${optionHtml(motifOptions, motifConsultation)}
                        </select>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Origine du patient</label>
                        <select class="form-select" name="origine_patient">
                          <option value=""></option>
                          ${optionHtml(origineOptions, [String(originePatientVal)])}
                        </select>
                      </div>
                      <div class="col-md-12">
                        <label class="form-label">Remarque interne</label>
                        <textarea class="form-control" rows="2" name="remarque_interne">${user.remarque_interne??''}</textarea>
                      </div>
                      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                      </div>
                    </div>
                  </form>
                `;
                modalBody.html(formHtml);
                // Enhance multi-selects with Select2 for better UX
                try {
                  const $modal = $('#emptyModal');
                  const select2Options = { width: '100%', dropdownParent: $modal, placeholder: '' };
                  $modal.find('select[name="langue_parlee[]"]').select2(select2Options);
                  $modal.find('select[name="motif_consultation[]"]').select2(select2Options);
                  $modal.find('select[name="adressage"]').select2(select2Options);
                  $modal.find('select[name="origine_patient"]').select2(select2Options);
                  console.log('[CustomerModal] Select2 initialized');
                } catch (e) {
                  console.warn('[CustomerModal] Select2 init failed (library missing?)', e);
                }
              } else {
                modalBody.html('<div class="p-4 text-danger">Invalid response.</div>');
              }
            } else {
              // Fallback: response is HTML, inject directly
              const html = await res.text();
              console.log('[CustomerModal] Edit HTML payload length', { length: html?.length || 0 });
              modalBody.html(html);
            }
          })
          .catch((err) => {
            console.error('[CustomerModal] Fetch edit error', err);
            modalBody.html('<div class="p-4 text-danger">Failed to load form.</div>');
          });

        modalEl.addEventListener('hidden.bs.modal', function () {
          modalBody.empty();
        }, { once: true });
      } else {
        // No id: show empty modal for custom content
        modalBody.html('');
        modal.show();
      }
    });

    // Delegated submit for any form loaded inside empty modal (AJAX submit)
    $(document).on('submit', '#emptyModal form', function(e) {
      e.preventDefault();
      const form = this;
      const action = form.getAttribute('action') || form.action;
      const methodInput = form.querySelector('input[name="_method"]');
      const method = methodInput ? methodInput.value : (form.getAttribute('method') || 'POST');
      const formData = new FormData(form);

      // Ensure CSRF header
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      // Disable submit buttons
      const $submitBtns = $(form).find('button[type="submit"], input[type="submit"]');
      $submitBtns.prop('disabled', true);

      // Debug: log outgoing request (omit file binary preview)
      const debugEntries = [];
      formData.forEach((v, k) => {
        if (v instanceof File) {
          debugEntries.push([k, { name: v.name, size: v.size, type: v.type }]);
        } else {
          debugEntries.push([k, v]);
        }
      });
      console.log('[CustomerModal] Submit start', { action, spoofedMethod: method, entries: debugEntries });

      // If targeting the dedicated POST endpoint (.../update), ensure _method is not sent
      if (typeof action === 'string' && action.endsWith('/update')) {
        if (formData.has('_method')) {
          formData.delete('_method');
          console.log('[CustomerModal] Removed _method for POST-only endpoint');
        }
      }

      fetch(action, {
        // Always use POST for Laravel method spoofing; _method carries PUT/PATCH/DELETE
        method: 'POST',
        headers: Object.assign({},
          token ? { 'X-CSRF-TOKEN': token } : {},
          { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        ),
        body: formData,
        credentials: 'same-origin'
      }).then(async (res) => {
        const status = res.status;
        const contentType = res.headers.get('content-type') || '';
        let data;
        try { data = await res.json(); } catch (_) { data = null; }
        console.log('[CustomerModal] Submit response meta', { status, contentType });
        if (data) console.log('[CustomerModal] Submit JSON payload', data);

        if (res.ok && data && (data.status === true || data.success === true)) {
          // Success path when controller returns JSON
          if (window.successSnackbar && data.message) { window.successSnackbar(data.message); }
          if (typeof renderedDataTable !== 'undefined') { renderedDataTable.ajax.reload(null, false); }
          bootstrap.Modal.getOrCreateInstance(document.getElementById('emptyModal')).hide();
          return;
        }

        // If not JSON or not success, fallback: try to reload table and close
        if (res.ok) {
          if (typeof renderedDataTable !== 'undefined') { renderedDataTable.ajax.reload(null, false); }
          bootstrap.Modal.getOrCreateInstance(document.getElementById('emptyModal')).hide();
          return;
        }

        // Error path
        if (data && data.errors) {
          // Simple inline error rendering at top of form
          const errors = Object.values(data.errors).flat().join('<br>');
          const alertHtml = `<div class="alert alert-danger">${errors}</div>`;
          $(form).find('.alert.alert-danger').remove();
          $(form).prepend(alertHtml);
        }
        if (window.errorSnackbar) { window.errorSnackbar(data?.message || 'Update failed'); }
        console.warn('[CustomerModal] Submit error', { status, data });
      }).catch((err) => {
        if (window.errorSnackbar) { window.errorSnackbar('Network error'); }
        console.error('[CustomerModal] Submit network error', err);
      }).finally(() => {
        $submitBtns.prop('disabled', false);
      });
    });
</script>
@endpush
