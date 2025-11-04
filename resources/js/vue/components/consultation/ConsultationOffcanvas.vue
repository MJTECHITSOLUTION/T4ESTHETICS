<template>
  <form @submit="formSubmit">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
      <FormHeader :currentId="currentId" :editTitle="editTitle" :createTitle="createTitle"></FormHeader>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-12">
            <!-- Patient Selection -->
            <div class="form-group mb-3">
              <label class="form-label">Patient</label>
              <select 
                class="form-control patient-select" 
                v-model="patient_id"
                style="width: 100%;"
              >
                <option value="">Select Patient (optional)</option>
              </select>
              <span v-if="errorMessages['patient_id']" class="text-danger">
                {{ errorMessages['patient_id'] }}
              </span>
            </div>

            <!-- Date Input -->
            <div class="form-group mb-3">
              <label class="form-label">Consultation Date <span class="text-danger">*</span></label>
              <input 
                type="date" 
                v-model="consultation_date" 
                class="form-control"
                :class="{ 'is-invalid': errors['consultation_date'] }"
                required
              />
              <span class="text-danger">{{ errors['consultation_date'] }}</span>
              <span v-if="errorMessages['consultation_date']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['consultation_date']" :key="err">{{ err }}</li>
                </ul>
              </span>
            </div>

            <!-- Dynamic Textareas -->
            <div class="form-group mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label">Consultation Items <span class="text-danger">*</span></label>
                <button 
                  type="button" 
                  class="btn btn-sm btn-primary" 
                  @click="addTextarea">
                  <i class="fas fa-plus"></i> Add Item
                </button>
              </div>
              
              <div v-for="(item, index) in items" :key="index" class="mb-3 position-relative">
                <div class="input-group">
                  <textarea 
                    v-model="items[index]" 
                    class="form-control" 
                    rows="3"
                    :placeholder="`Item ${index + 1}`"
                    :class="{ 'is-invalid': errorMessages['items.' + index] }"
                  ></textarea>
                  <button 
                    v-if="items.length > 1"
                    type="button" 
                    class="btn btn-sm btn-danger" 
                    @click="removeTextarea(index)">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <span v-if="errorMessages['items.' + index]" class="text-danger">
                  {{ errorMessages['items.' + index] }}
                </span>
              </div>
              
              <span v-if="errorMessages['items']" class="text-danger">
                <ul>
                  <li v-for="err in errorMessages['items']" :key="err">{{ err }}</li>
                </ul>
              </span>
            </div>
          </div>
        </div>
      </div>
      <FormFooter :IS_SUBMITED="IS_SUBMITED"></FormFooter>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useField, useForm } from 'vee-validate'
import { useModuleId, useRequest, useOnOffcanvasHide } from '@/helpers/hooks/useCrudOpration'
import * as yup from 'yup'
import FormHeader from '@/vue/components/form-elements/FormHeader.vue'
import FormFooter from '@/vue/components/form-elements/FormFooter.vue'

// Constants
const MODULE = 'consultations'
const EDIT_URL = (id) => {
  return { path: `${MODULE}/${id}/edit`, method: 'GET' }
}
const STORE_URL = () => {
  return { path: `${MODULE}`, method: 'POST' }
}
const UPDATE_URL = (id) => {
  return { path: `${MODULE}/${id}`, method: 'POST' }
}

// Props
const props = defineProps({
  createTitle: { type: String, default: '' },
  editTitle: { type: String, default: '' }
})

const { getRequest, storeRequest, updateRequest } = useRequest()

// Edit Form Or Create Form
const currentId = useModuleId(() => {
  if (currentId.value > 0) {
    getRequest({ url: EDIT_URL(currentId.value), id: currentId.value }).then((res) => {
      if (res.status && res.data) {
        setFormData(res.data)
        // Initialize Select2 after data is loaded
        nextTick(() => {
          initPatientSelect()
        })
      }
    })
  } else {
    setFormData(defaultData())
  }
})

// Default FORM DATA
const defaultData = () => {
  errorMessages.value = {}
  return {
    id: '',
    consultation_date: '',
    patient_id: null,
    items: ['']
  }
}

// Reset Form
const setFormData = (data) => {
  const formItems = data.items && Array.isArray(data.items) && data.items.length > 0 
    ? [...data.items] 
    : ['']
  
  items.value = formItems
  patient_id.value = data.patient_id || null
  
  resetForm({
    values: {
      id: data.id || '',
      consultation_date: data.consultation_date || '',
      items: formItems
    }
  })
  
  // Reset Select2
  if (typeof $ !== 'undefined' && $.fn.select2) {
    nextTick(() => {
      $('.patient-select').val(null).trigger('change')
      if (data.patient_id) {
        loadPatientForEdit(data.patient_id)
      }
    })
  }
}

// Validation Schema
const validationSchema = yup.object({
  consultation_date: yup.string().required('Consultation date is required')
})

const { handleSubmit, errors, resetForm } = useForm({ validationSchema })
const { value: id } = useField('id')
const { value: consultation_date } = useField('consultation_date')
const patient_id = ref(null)
const items = ref([''])

const errorMessages = ref({})
const IS_SUBMITED = ref(false)

// Initialize Select2 for patient selection
const initPatientSelect = () => {
  nextTick(() => {
    if (typeof $ !== 'undefined' && $.fn.select2) {
      // Destroy existing instance if any
      if ($('.patient-select').hasClass('select2-hidden-accessible')) {
        $('.patient-select').select2('destroy')
      }
      
      $('.patient-select').select2({
        ajax: {
          url: '/app/users/user-list',
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
              results: data.map(item => ({
                id: item.id,
                text: item.full_name + (item.email ? ' (' + item.email + ')' : '')
              }))
            };
          },
          cache: true
        },
        minimumInputLength: 0,
        placeholder: 'Select Patient (optional)',
        allowClear: true
      }).on('change', function() {
        patient_id.value = $(this).val() || null
      });
    }
  });
}

// Load patient when editing
const loadPatientForEdit = (patientId) => {
  if (patientId && typeof $ !== 'undefined' && $.fn.select2) {
    $.ajax({
      url: '/app/users/user-list',
      data: { role: 'user' },
      dataType: 'json'
    }).done(function(data) {
      const patient = data.find(item => item.id == patientId);
      if (patient) {
        const option = new Option(patient.full_name, patient.id, true, true);
        $('.patient-select').append(option).trigger('change');
      }
    });
  }
}

onMounted(() => {
  setFormData(defaultData())
  
  // Initialize Select2 when offcanvas is shown
  const offcanvasEl = document.getElementById('form-offcanvas')
  if (offcanvasEl) {
    offcanvasEl.addEventListener('shown.bs.offcanvas', () => {
      initPatientSelect()
    })
    
    // Also initialize on mount if offcanvas is already visible
    setTimeout(() => {
      if ($(offcanvasEl).hasClass('show')) {
        initPatientSelect()
      }
    }, 100)
  }
})

// Add Textarea
const addTextarea = () => {
  items.value.push('')
}

// Remove Textarea
const removeTextarea = (index) => {
  if (items.value.length > 1) {
    items.value.splice(index, 1)
  }
}

// Reload Datatable, SnackBar Message, Alert, Offcanvas Close
const reset_datatable_close_offcanvas = (res) => {
  IS_SUBMITED.value = false
  if (res.status) {
    window.successSnackbar(res.message)
    renderedDataTable.ajax.reload(null, false)
    bootstrap.Offcanvas.getInstance('#form-offcanvas').hide()
    setFormData(defaultData())
  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message || {}
  }
}

// Form Submit
const formSubmit = handleSubmit((values) => {
  if (IS_SUBMITED.value) return false
  IS_SUBMITED.value = true
  
  // Filter out empty items
  const filteredItems = items.value.filter(item => item && item.trim().length > 0)
  
  if (filteredItems.length === 0) {
    errorMessages.value = { items: ['At least one consultation item is required.'] }
    IS_SUBMITED.value = false
    return
  }
  
  values.items = filteredItems
  values.patient_id = patient_id.value || null
  
  if (currentId.value > 0) {
    updateRequest({ url: UPDATE_URL(currentId.value), id: currentId.value, body: values }).then((res) => reset_datatable_close_offcanvas(res))
  } else {
    storeRequest({ url: STORE_URL(), body: values }).then((res) => reset_datatable_close_offcanvas(res))
  }
})

useOnOffcanvasHide('form-offcanvas', () => {
  setFormData(defaultData())
  // Destroy existing Select2 instance if any
  if (typeof $ !== 'undefined' && $.fn.select2) {
    $('.patient-select').select2('destroy')
  }
})
</script>

<style scoped>
@media only screen and (min-width: 768px) {
  .offcanvas {
    width: 60%;
  }
}

@media only screen and (min-width: 1280px) {
  .offcanvas {
    width: 50%;
  }
}
</style>

