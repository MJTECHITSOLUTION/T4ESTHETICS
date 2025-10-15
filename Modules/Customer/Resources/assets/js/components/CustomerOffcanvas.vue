<template>
  <form @submit.prevent="formSubmit">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
      <FormHeader :currentId="currentId" :editTitle="editTitle" :createTitle="createTitle"></FormHeader>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-md-12 row">
            <div class="col-md-12 text-center">
              <img :src="ImageViewer || defaultImage" class="img-fluid avatar avatar-120 avatar-rounded mb-2" alt="profile-image" />
              <div v-if="validationMessage" class="text-danger mb-2">{{ validationMessage }}</div>
              <div class="d-flex align-items-center justify-content-center gap-2">
                <input type="file" ref="profileInputRef" class="form-control d-none" id="logo" name="profile_image" accept=".jpeg, .jpg, .png, .gif" @change="fileUpload" />
                <label class="btn btn-info mb-3" for="logo">{{ $t('messages.upload') }}</label>
                <input type="button" class="btn btn-danger mb-3" name="remove" :value="$t('messages.remove')" @click="removeLogo()" v-if="ImageViewer" />
              </div>
              <span class="text-danger">{{ errors.profile_image }}</span>
            </div>

            <InputField :is-required="true" :label="$t('customer.lbl_first_name')" :placeholder="$t('customer.first_name')" v-model="first_name" :error-message="errors.first_name" :error-messages="errorMessages['first_name']"></InputField>
            <InputField :is-required="true" :label="$t('customer.lbl_last_name')" :placeholder="$t('customer.last_name')" v-model="last_name" :error-message="errors['last_name']" :error-messages="errorMessages['last_name']"></InputField>

            <InputField :is-required="true" :label="$t('customer.lbl_Email')" :placeholder="$t('customer.email_address')" v-model="email" :error-message="errors['email']" :error-messages="errorMessages['email']"></InputField>
            <div class="form-group">
              <label class="form-label">{{ $t('customer.lbl_phone_number') }}<span class="text-danger">*</span> </label>
              <vue-tel-input :value="mobile" @input="handleInput" v-bind="{ mode: 'international', maxLen: 15 }"></vue-tel-input>
              <span class="text-danger">{{ errors['mobile'] }}</span>
            </div>

            <div class="row" v-if="currentId === 0">
              <InputField type="password" class="col-md-12" :is-required="true" :autocomplete="newpassword" :label="$t('employee.lbl_password')"
               :placeholder="$t('customer.password')" v-model="password" :error-message="errors['password']"
                :error-messages="errorMessages['password']"></InputField>

              <InputField type="password" class="col-md-12" :is-required="true" :label="$t('employee.lbl_confirm_password')"
                :placeholder="$t('customer.confirm_password')" v-model="confirm_password" :error-message="errors['confirm_password']"
                :error-messages="errorMessages['confirm_password']"></InputField>
            </div>

            <div class="form-group col-md-4">
              <label for="" class="w-100">{{ $t('employee.lbl_gender') }}</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" v-model="gender" id="male" value="male"
                  :checked="gender == 'male'" />
                <label class="form-check-label" for="male"> Male </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" v-model="gender" id="female" value="female"
                  :checked="gender == 'female'" />
                <label class="form-check-label" for="female"> Female </label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" v-model="gender" id="other" value="other"
                  :checked="gender == 'other'" />
                <label class="form-check-label" for="other"> Intersex </label>
              </div>
              <p class="mb-0 text-danger">{{ errors.gender }}</p>
            </div>

            <!-- Additional Fields -->
            <div class="col-md-12">
              <InputField :is-required="false" :label="'Profession'" :placeholder="'Profession'" v-model="profession"
                :error-message="errors['profession']" :error-messages="errorMessages['profession']"></InputField>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Adresse</label>
                <textarea class="form-control" rows="2" v-model="adresse" placeholder="Adresse"></textarea>
                <span class="text-danger">{{ errors['adresse'] }}</span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Langue parlée</label>
                <Multiselect
                  v-model="langue_parlee"
                  :options="languageOptions"
                  mode="multiple"
                  :searchable="true"
                  :closeOnSelect="false"
                  placeholder="Sélectionner les langues"
                />
                <span class="text-danger">{{ errors['langue_parlee'] }}</span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Adressage</label>
                <Multiselect
                  v-model="adressage"
                  :options="adressageOptions"
                  :searchable="true"
                  placeholder="Sélectionner une source"
                />
                <span class="text-danger">{{ errors['adressage'] }}</span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Motif de consultation</label>
                <Multiselect
                  v-model="motif_consultation"
                  :options="motifOptions"
                  mode="multiple"
                  :searchable="true"
                  :closeOnSelect="false"
                  placeholder="Sélectionner des motifs"
                />
                <span class="text-danger">{{ errors['motif_consultation'] }}</span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Origine du patient</label>
                <Multiselect
                  v-model="origine_patient"
                  :options="origineOptions"
                  :searchable="true"
                  placeholder="Origine du patient"
                />
                <span class="text-danger">{{ errors['origine_patient'] }}</span>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label class="form-label">Remarque interne</label>
                <textarea class="form-control" rows="2" v-model="remarque_interne" placeholder="Remarque interne"></textarea>
                <span class="text-danger">{{ errors['remarque_interne'] }}</span>
              </div>
            </div>

            <div v-for="field in customefield" :key="field.id">
              <FormElement v-model="custom_fields_data" :name="field.name" :label="field.label" :type="field.type" :required="field.required" :options="field.value" :field_id="field.id"></FormElement>
            </div>
          </div>
        </div>
      </div>
    <FormFooter :IS_SUBMITED="IS_SUBMITED"></FormFooter>
    </div>
  </form>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { EDIT_URL, STORE_URL, UPDATE_URL,EMAIL_UNIQUE_CHECK } from '../constant/customer'
import { useField, useForm } from 'vee-validate'

import { VueTelInput } from 'vue3-tel-input'

import { useModuleId, useRequest, useOnOffcanvasHide } from '@/helpers/hooks/useCrudOpration'
import * as yup from 'yup'

import { readFile } from '@/helpers/utilities'
import FormHeader from '@/vue/components/form-elements/FormHeader.vue'
import FormFooter from '@/vue/components/form-elements/FormFooter.vue'
import InputField from '@/vue/components/form-elements/InputField.vue'
import FormElement from '@/helpers/custom-field/FormElement.vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'
const ISREADONLY = ref(false)
// props
defineProps({
  createTitle: { type: String, default: '' },
  editTitle: { type: String, default: '' },
  defaultImage: { type: String, default: 'https://dummyimage.com/600x300/cfcfcf/000000.png' },
  customefield: { type: Array, default: () => [] }
})

const { getRequest, storeRequest, updateRequest, listingRequest } = useRequest()

/*
 * Form Data & Validation & Handeling
 */
const currentId = useModuleId(() => {
  if (currentId.value > 0) {
    getRequest({ url: EDIT_URL, id: currentId.value }).then((res) => res.status && setFormData(res.data))
  } else {
    setFormData(defaultData())
  }
})

// File Upload Function
const validationMessage = ref('')
const ImageViewer = ref(null)
const profileInputRef = ref(null)
const fileUpload = async (e) => {
  let file = e.target.files[0];
  const maxSizeInMB = 2;
  const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

  if (file) {
    if (file.size > maxSizeInBytes) {
      validationMessage.value = `File size exceeds ${maxSizeInMB} MB. Please upload a smaller file.`;
      profileInputRef.value.value = '';
      return;
    }

    await readFile(file, (fileB64) => {
      ImageViewer.value = fileB64;
      profileInputRef.value.value = '';
      validationMessage.value = ''; 
    });
    // assign the actual File object to the form field so it is sent as multipart
    profile_image.value = file;
  } else {
    validationMessage.value = '';
  }
};
// Function to delete Images
const removeImage = ({ imageViewerBS64, changeFile }) => {
  imageViewerBS64.value = null
  changeFile.value = null
}

const removeLogo = () => removeImage({ imageViewerBS64: ImageViewer, changeFile: profile_image })

/*
 * Form Data & Validation & Handeling
 */
// Default FORM DATA
const defaultData = () => {
  ISREADONLY.value = false
  errorMessages.value = {}
  return {
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    mobile: '',
    password: '',
    confirm_password: '',
    gender: 'male',
    profile_image: '',
    // New optional fields
    profession: '',
    adresse: '',
    langue_parlee: [],
    adressage: '',
    motif_consultation: [],
    origine_patient: '',
    remarque_interne: '',
    custom_fields_data: {}
  }
}

//  Reset Form
const setFormData = (data) => {
  ImageViewer.value = data.profile_image
  ISREADONLY.value = true
  // Normalize incoming values to align with select option values
  const normalizeLangues = (val) => {
    if (Array.isArray(val)) return val.map(String);
    if (!val) return [];
    return [String(val)];
  }
  const normalizeMotifs = (val) => {
    if (Array.isArray(val)) return val.map(String);
    if (!val) return [];
    return [String(val)];
  }
  const normalizeSingle = (val) => (val == null ? '' : String(val))

  resetForm({
    values: {
      id: data.id,
      first_name: data.first_name,
      last_name: data.last_name,
      email: data.email,
      mobile: data.mobile,
      password: '',
      confirm_password: '',
      gender: data.gender,
      profile_image: data.profile_image,
      profession: normalizeSingle(data.profession),
      adresse: normalizeSingle(data.adresse),
      langue_parlee: normalizeLangues(data.langue_parlee),
      adressage: normalizeSingle(data.adressage),
      motif_consultation: normalizeMotifs(data.motif_consultation),
      origine_patient: normalizeSingle(data.origine_patient),
      remarque_interne: data.remarque_interne || '',
      custom_fields_data: data.custom_field_data
    }
  })
}

const reset_datatable_close_offcanvas = (res) => {
   IS_SUBMITED.value = false
  if (res.status) {
    window.successSnackbar(res.message)
    renderedDataTable.ajax.reload(null, false)
    bootstrap.Offcanvas.getInstance('#form-offcanvas').hide()
    setFormData(defaultData())
  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message
  }
}

  const numberRegex = /^\d+$/;
  let EMAIL_REGX = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;

  // Validations
  const validationSchema = yup.object({
    first_name: yup.string()
    .required('First Name is a required field')
    .test('is-string', 'Only strings are allowed', (value) => {
      // Regular expressions to disallow special characters and numbers
      const specialCharsRegex = /[!@#$%^&*(),.?":{}|<>\-_;'\/+=\[\]\\]/
      return !specialCharsRegex.test(value) && !numberRegex.test(value)
    }),

    last_name: yup.string()
    .required('Last Name is a required field')
    .test('is-string', 'Only strings are allowed', (value) => {
      // Regular expressions to disallow special characters and numbers
      const specialCharsRegex = /[!@#$%^&*(),.?":{}|<>\-_;'\/+=\[\]\\]/
      return !specialCharsRegex.test(value) && !numberRegex.test(value)
    }),
    email: yup.string().required('Email is a required field')
    .matches(EMAIL_REGX, 'Must be a valid email')
    .test('unique', 'Email must be unique', async function(value) {
      if (!EMAIL_REGX.test(value)) {
        return true;
      }
      const userId  = id.value;
          const isUnique = await storeRequest({ url: EMAIL_UNIQUE_CHECK, body: { email: value, user_id: userId }, type: 'file' });
          if (!isUnique.isUnique) {
              return this.createError({ path: 'email', message: 'email must be unique' });
              }
          return true;
        }),
        mobile: yup.string()
        .required('Phone Number is a required field').matches(/^(\+?\d+)?(\s?\d+)*$/, 'Phone Number must contain only digits'),
        password: yup.string()
        .min(8, 'Password must be at least 8 characters long')
        .when('id', {
          is: (val) => !val || val === 0,
          then: (schema) => schema.required('Password is required'),
          otherwise: (schema) => schema.notRequired()
        }),
      confirm_password: yup.string()
        .when('password', {
          is: (val) => !!val && val.length > 0,
          then: (schema) => schema.required('Confirm password is required').oneOf([yup.ref('password')], 'Passwords must match'),
          otherwise: (schema) => schema.notRequired()
        }),
      // Optional fields (no required rules)
      profession: yup.string().nullable(),
      adresse: yup.string().nullable(),
      langue_parlee: yup.array().of(yup.string()).nullable(),
      adressage: yup.string().nullable(),
      motif_consultation: yup.array().of(yup.string()).nullable(),
      origine_patient: yup.string().nullable(),
      remarque_interne: yup.string().nullable(),
  })


const { handleSubmit, errors, resetForm } = useForm({
  validationSchema,
})
const { value: id } = useField('id')
const { value: first_name } = useField('first_name')
const { value: last_name } = useField('last_name')
const { value: email } = useField('email')
const { value: gender } = useField('gender')
const { value: mobile } = useField('mobile')
const { value: profile_image } = useField('profile_image')
const { value: custom_fields_data } = useField('custom_fields_data')
const { value: password } = useField('password')
const { value: confirm_password } = useField('confirm_password')
// New fields
const { value: profession } = useField('profession')
const { value: adresse } = useField('adresse')
const { value: langue_parlee } = useField('langue_parlee')
const { value: adressage } = useField('adressage')
const { value: motif_consultation } = useField('motif_consultation')
const { value: origine_patient } = useField('origine_patient')
const { value: remarque_interne } = useField('remarque_interne')

// Options for multiselects
const languageOptions = [
  { value: 'fr', label: 'Français' },
  { value: 'ar', label: 'Arabe' },
  { value: 'ber', label: 'Berbère' },
  { value: 'en', label: 'Anglais' }
]
const adressageOptions = [
  { value: 'medecin', label: 'Médecin' },
  { value: 'de_passage', label: 'De passage' },
  { value: 'coiffeur', label: 'Coiffeur' },
  { value: 'estheticienne', label: 'Esthéticienne' },
  { value: 'pharmacien', label: 'Pharmacien' },
  { value: 'collegue', label: 'Collègue' },
  { value: 'proches', label: 'Proches' }
]
const motifOptions = [
  { value: 'cosmetique', label: 'Cosmétique' },
  { value: 'visage', label: 'Visage' },
  { value: 'cheveux', label: 'Cheveux' },
  { value: 'intime', label: 'Intime' },
  { value: 'silhouette', label: 'Silhouette' },
  { value: 'epilation', label: 'Epilation' }
]
const origineOptions = [
  { value: 'de_passage', label: 'De passage' },
  { value: 'site_web', label: 'Site web' },
  { value: 'parrinage', label: 'Parrinage' },
  { value: 'les_publicites', label: 'Les publicités' },
  { value: 'autre', label: 'Autre' }
]
const errorMessages = ref({})

// phone number
const handleInput = (phone, phoneObject) => {
  // Handle the input event
  if (phoneObject?.formatted) {
    mobile.value = phoneObject.formatted
  }
}

// Form Submit
const IS_SUBMITED = ref(false)
const formSubmit = handleSubmit((values) => {
  if(IS_SUBMITED.value) return false
  IS_SUBMITED.value = true
  // console.log(values);
  values.custom_fields_data = JSON.stringify(values.custom_fields_data)

  if (currentId.value > 0) {
    updateRequest({ url: UPDATE_URL, id: currentId.value, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  } else {
    storeRequest({ url: STORE_URL, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  }
})

useOnOffcanvasHide('form-offcanvas', () => setFormData(defaultData()))
</script>


<style>
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
.editor-container {
  height: 200px;
}

</style>