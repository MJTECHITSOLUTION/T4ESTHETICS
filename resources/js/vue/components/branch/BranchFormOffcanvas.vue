<template>
  <form @submit="formSubmit">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
      <FormHeader :currentId="currentId" :editTitle="editTitle" :createTitle="createTitle"></FormHeader>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-12 row">
            <div class="col-12 row">
              <div class="col-md-6">
                <InputField class="col-md-12" :is-required="true" :label="$t('branch.lbl_branch_name')" :placeholder="$t('branch.branch_name')" v-model="name" :error-message="errors.name" :error-messages="errorMessages['name']"></InputField>
                <div class="form-group col-md-12">
                  <label class="form-label">{{ $t('branch.lbl_branch_for') }}</label>
                  <div class="btn-group w-100" role="group" aria-label="Basic example">
                    <template v-for="(item, index) in BRANCH_FOR_OPTIONS" :key="index">
                      <input type="radio" class="btn-check" name="branch_for" :id="`${item.id}-for`" :value="item.id" autocomplete="off" v-model="branch_for" :checked="branch_for == item.id" />
                      <label class="btn btn-outline-primary" :for="`${item.id}-for`">{{ item.text }}</label>
                    </template>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <div class="text-center">
                  <img :src="ImageViewer || defaultImage" alt="feature-image" class="img-fluid mb-2 avatar-140 avatar-rounded" />
                  <div v-if="validationMessage" class="text-danger mb-2">{{ validationMessage }}</div>
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="file" ref="profileInputRef" class="form-control d-none" id="feature_image" name="feature_image" @change="fileUpload" accept=".jpeg, .jpg, .png, .gif" />
                    <label class="btn btn-info" for="feature_image">{{ $t('messages.upload') }}</label>
                    <input type="button" class="btn btn-danger" name="remove" :value="$t('messages.remove')" @click="removeLogo()" v-if="ImageViewer" />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group col-md-12">
              <div class="d-flex justify-content-between">
                <label for="manager_id">{{ $t('branch.lbl_select_manager') }} <span class="text-danger">*</span></label>
                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm text-primary"><i class="fa-solid fa-plus"></i> {{ $t('messages.create') }} {{ $t('messages.new') }}</button>
              </div>
              <Multiselect v-model="manager_id" :value="manager_id" :placeholder="$t('branch.assign_manager')" :options="manager.options" v-bind="singleSelectOption" id="manager_id"></Multiselect>
               <span v-if="errorMessages['manager_id']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['manager_id']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors.manager_id }}</span>
            </div>

            <div class="form-group col-md-12">
              <label class="form-label" for="services">{{ $t('branch.lbl_select_service') }}</label>
              <Multiselect v-model="service_id" :value="service_id" :options="service.options" :placeholder="$t('branch.select_service')" v-bind="multiselectOption" id="services"></Multiselect>
            </div>
           
         

            <div class="form-group col-md-12">
              <label class="form-label" for="description">{{$t('branch.lbl_description')}}</label>
              <textarea class="form-control" v-model="description" :placeholder="$t('branch.enter_decription')" id="description"></textarea>
              <span v-if="errorMessages['description']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['description']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors.description }}</span>
            </div>

            <div v-for="field in customefield" :key="field.id">
              <FormElement v-model="custom_fields_data" :name="field.name" :label="field.label" :type="field.type" :required="field.required" :options="field.value"  :field_id="field.id"  ></FormElement>            </div>

            <div class="form-group col-md-2 ">
              <div class="d-flex gap-3">
                <label class="form-label" for="category-status">{{$t('branch.lbl_status')}}</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" :value="status" :checked="status" name="status" id="category-status" type="checkbox" v-model="status" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <FormFooter :IS_SUBMITED="IS_SUBMITED"></FormFooter>
    </div>
  </form>
  <EmployeeCreate @submit="updateManagerDetail"></EmployeeCreate>
</template>
<script setup>
import { ref, reactive, onMounted } from 'vue'
import { EDIT_URL, STORE_URL, UPDATE_URL, SERVICE_LIST, EMPLOYEE_LIST, COUNTRY_URL, STATE_URL, CITY_URL} from '../../constants/branch'
import { useField, useForm } from 'vee-validate'
import { readFile } from '@/helpers/utilities'
import { useModuleId, useRequest,useOnOffcanvasHide } from '@/helpers/hooks/useCrudOpration'
import { VueTelInput } from 'vue3-tel-input'
import * as yup from 'yup'
import { useSelect } from '@/helpers/hooks/useSelect'
import FormHeader from '@/vue/components/form-elements/FormHeader.vue'
import FormFooter from '@/vue/components/form-elements/FormFooter.vue'
import InputField from '@/vue/components/form-elements/InputField.vue'
// Modals
import EmployeeCreate from '@/vue/components/Modal/EmployeeCreate.vue'
import FormElement from '@/helpers/custom-field/FormElement.vue'

// props
const props = defineProps({
  createTitle: { type: String, default: '' },
  editTitle: { type: String, default: '' },
  selectData: { type: String },
  customefield: { type: Array, default: () => [] },
  defaultImage: { type: String, default: 'https://dummyimage.com/600x300/cfcfcf/000000.png' }
})

const parseSelectData = JSON.parse(props.selectData)

const { getRequest, storeRequest, updateRequest } = useRequest()

const BRANCH_FOR_OPTIONS = reactive(parseSelectData['BRANCH_FOR'] || [])

const PAYMENT_METHODS_OPTIONS = reactive(parseSelectData['PAYMENT_METHODS'])

const singleSelectOption = ref({
  closeOnSelect: true,
  searchable: true
})
const multiselectOption = ref({
  mode: 'tags',
  searchable: true
})

const service = ref({ options: [], list: [] })
const manager = ref({ options: [], list: [] })

const getServiceList = () => {
  return useSelect({ url: SERVICE_LIST }, { value: 'id', label: 'name' }).then((data) => (service.value = data))
}

const getManagerList = () => {
  return useSelect({ url: EMPLOYEE_LIST, data: { role: 'manager' } }, { value: 'id', label: 'name' }).then((data) => {
    manager.value = data
    return data
  })
}

useOnOffcanvasHide('form-offcanvas', () => setFormData(defaultData()))

onMounted(() => {
  getCountry()
  setFormData(defaultData())
})

const numberRegex = /^\d+$/;

/*
 * Form Data & Validation & Handeling
 */

 const EMAIL_REGX = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;

const validationSchema = yup.object({
  name: yup.string()
    .required('Branch Name is a required field')
    .test('is-string', 'Only strings are allowed', (value) => {
      const specialCharsRegex = /[!@#$%^&*,.?":{}|<>\-_;'\/+=\[\]\\]/;
      return !specialCharsRegex.test(value) && !numberRegex.test(value);
    }),
  branch_for: yup.string().nullable(),
  // manager_id is optional server-side; keep client optional
  manager_id: yup.mixed().nullable(),
  contact_number: yup.string().nullable(),
  contact_email: yup.string().nullable().email('Must be a valid email'),
  payment_method: yup.array().nullable(),
  address: yup.object({
    address_line_1: yup.string().nullable(),
    address_line_2: yup.string().nullable(),
    city: yup.string().nullable(),
    state: yup.string().nullable(),
    country: yup.string().nullable(),
    postal_code: yup.string().nullable(),
  })
})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema,
  initialValues: {
    payment_method: ['cash']
  }
})

const { value: name } = useField('name')
const { value: status } = useField('status')
const { value: branch_for } = useField('branch_for')
const { value: manager_id } = useField('manager_id')
const { value: contact_email } = useField('contact_email')
const { value: contact_number } = useField('contact_number')
const { value: payment_method } = useField('payment_method')
const { value: address } = useField('address')
const { value: feature_image } = useField('feature_image')

const updateManagerDetail = (e) => {
  switch (e.type) {
    case 'create_manager':
      getManagerList(() => manager_id.value = e.value)
        break;

    default:
      break;
  }
}

branch_for.value = 'both'

// Default FORM DATA, Error Message
const errorMessages = ref({})
const validationMessage = ref('');

// File Upload Function
const ImageViewer = ref(null)
const profileInputRef = ref(null)
const fileUpload = async (e) => {
  let file = e.target.files[0];
  const maxSizeInMB = 2;
  const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

  if (file) {
    if (file.size > maxSizeInBytes) {
      // File is too large
      validationMessage.value = `File size exceeds ${maxSizeInMB} MB. Please upload a smaller file.`;
      // Clear the file input
      profileInputRef.value.value = '';
      return;
    }

    await readFile(file, (fileB64) => {
      ImageViewer.value = fileB64;
      profileInputRef.value.value = '';
      validationMessage.value = ''; 
    });
    feature_image.value = file;
  } else {
    validationMessage.value = '';
  }
};

// Function to delete Images
const removeImage = ({ imageViewerBS64, changeFile }) => {
  imageViewerBS64.value = null
  changeFile.value = null
}

const removeLogo = () => removeImage({ imageViewerBS64: ImageViewer, changeFile: feature_image })

const defaultData = () => {
  ImageViewer.value = props.defaultImage
  errorMessages.value = {}
  return {
    name: '',
    contact_email: '',
    contact_number: '',
    feature_image: null,
    status: true,
    branch_for: 'both',
    manager_id: null,
    payment_method: ['cash'],
    address: {
      address_line_1: '',
      address_line_2: '',
      city: '',
      state: '',
      country: '',
      postal_code: ''
    }
  }
}

//  Reset Form
const setFormData = (data) => {
  ImageViewer.value = data.feature_image || props.defaultImage
  resetForm({
    values: {
      name: data.name,
      contact_email: data.contact_email,
      contact_number: data.contact_number,
      feature_image: data.feature_image,
      status: data.status,
      branch_for: data.branch_for,
      manager_id: data.manager_id,
      payment_method: data.payment_method ?? ['cash'],
      address: data.address ?? {
        address_line_1: '', address_line_2: '', city: '', state: '', country: '', postal_code: ''
      },
    }
  })
  // Ensure selected services exist in options so labels show in multiselect
  if (Array.isArray(data.service_id) && service.value?.options) {
    const optionIds = new Set(service.value.options.map(o => o.value))
    const missing = data.service_id.filter(id => !optionIds.has(id))
    if (missing.length) {
      service.value.options = service.value.options.concat(missing.map(id => ({ value: id, label: String(id) })))
    }
  }
}

// Edit Form Or Create Form
const currentId = useModuleId(async () => {
  if (currentId.value > 0) {
    const [_, __, editRes] = await Promise.all([
      getServiceList(),
      getManagerList(),
      getRequest({ url: EDIT_URL, id: currentId.value })
    ])
    if (editRes?.status) {
      manager_id.value = editRes.data.manager_id
      setFormData(editRes.data)
    }
  } else {
    await Promise.all([getServiceList(), getManagerList()])
    setFormData(defaultData())
  }
})

const IS_SUBMITED = ref(false)
const formSubmit = handleSubmit((values) => {
  if(IS_SUBMITED.value) return false

  // Ensure address is stringified per backend expectation
  if (values.address && typeof values.address === 'object') {
    values.address = JSON.stringify(values.address)
  }

  if (currentId.value > 0) {
    updateRequest({ url: UPDATE_URL, id: currentId.value, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  } else {
    storeRequest({ url: STORE_URL, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  }
})

// Reload Datatable, SnackBar Message, Alert, Offcanvas Close
const reset_datatable_close_offcanvas = (res) => {
  IS_SUBMITED.value = false
  if (res.status) {
    window.successSnackbar(res.message)
    renderedDataTable.ajax.reload(null, false)
    bootstrap.Offcanvas.getInstance('#form-offcanvas').hide()
    setFormData(defaultData())
    removeImage({ imageViewerBS64: ImageViewer, changeFile: feature_image })
  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message
  }
}
</script>

<style scoped>
@media only screen and (min-width: 768px) {
  .offcanvas {
    width: 80%;
  }
}

@media only screen and (min-width: 1280px) {
  .offcanvas {
    width: 60%;
  }
}
</style>
