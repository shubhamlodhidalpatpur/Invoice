<template >
  <div class="add_users">
    <b-row>
      <b-col cols="12" md="6">
        <h4 class="card-title fontgry">
          <span >{{$route.name}}</span> {{$route.params.module_name}}
        </h4>
      </b-col>
    </b-row>
    <b-form
          class="row myform"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
          v-if="Fields.length > 0"
    >
      <b-card >
          <b-col md="12">
            <b-row>
              <b-col md="3" class="mt-2" v-for="field in Fields" :key="field.id">
                <label class="form-label required d-block">{{field.title}}</label>
                  <validation-provider #default="{ errors }" :name="field.title">
                    
                    <div v-if="field.field_type_slug == 'string'">
                    <b-form-input
                      :disabled="$route.name==='View'"
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                      @input="RemoveError('name')"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'bigInteger'">
                    <b-form-input
                      :disabled="$route.name==='View'"
                      type="number"
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                      @input="RemoveError('name')"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'float'">
                    <b-form-input
                     :disabled="$route.name==='View'"
                      type="number"
                      step=".01"
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                      @input="RemoveError('name')"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'date'">
                    <b-form-datepicker
                     :disabled="$route.name==='View'"
                      v-model="field.value"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'enum'">
                    <v-select
                      v-if="field.is_options==false"
                      :disabled="$route.name==='View'"
                      :options="field.field_value.split(',')"
                      v-model="field.value"
                      class="mb-1"
                    />
                    <v-select
                      v-if="field.is_options==true"
                      :disabled="$route.name==='View'"
                      :options="field.options"
                      v-model="field.value"
                      :reduce="(val) => val.value"
                      @input="RemoveError(field.slug)"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'file'" class="d-flex">
                      <a :href="field.image_link" target="_blank" v-if="field.field_type_slug == 'file'"><feather-icon class="custom-class" icon="EyeIcon" size="1.5x" /></a>
                    <b-form-file
                      :disabled="$route.name==='View'"
                      v-model="field.value"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'boolean'">
                      <b-form-checkbox
                        :disabled="$route.name==='View'"
                        checked="true"
                        class="custom-control-success"
                        name="check-button"
                        switch
                        v-model="field.value"
                      />
                    </div>
                    <div v-if="field.field_type_slug == 'datetime'">
                      <flat-pickr
                       :disabled="$route.name==='View'"
                        v-model="field.value"
                        class="form-control"
                        :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                      />
                    </div>
                  </validation-provider>
              </b-col>
            </b-row>
            <b-button v-if="$route.name==='Edit'" @click="submit">Submit</b-button>
          </b-col>
        </b-card>
    </b-form>
  </div>
</template>

<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import { required, alphaNum, email } from "@validations";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { useRouter } from "@core/utils/utils";
import axios from "@axios";
import vSelect from "vue-select";
import { ref } from "@vue/composition-api";
import Ripple from "vue-ripple-directive";
import { heightTransition } from "@core/mixins/ui/transition";
import { useToast } from "vue-toastification/composition";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import flatPickr from 'vue-flatpickr-component'
import {
  BRow,
  BCol,
  BLink,
  BButton,
  BFormGroup,
  BForm,
  BFormInput,
  BOverlay,
  BToast,
  BFormInvalidFeedback,
  BMedia,
  BAvatar,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormRadioGroup,BCard,VBToggle,
  BFormDatepicker,
  BFormFile,
  BFormCheckbox
} from "bootstrap-vue";
import formValidation from "@core/comp-functions/forms/form-validation";
import store from "@/store";
import { avatarText } from "@core/utils/filter";
import { quillEditor } from "vue-quill-editor";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent,
    BRow,
    BCol,
    BLink,
    BButton,
    BFormGroup,
    BForm,
    BFormInput,
    BOverlay,
    BToast,
    ToastificationContent,
    vSelect,
    BCard,
    flatPickr,
    BFormInvalidFeedback,
    BMedia,
    BAvatar,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    quillEditor,
    BFormRadioGroup,VBToggle,
    BFormDatepicker,
    BFormFile,
    BFormCheckbox
  },
  directives: {
      Ripple,
      'b-toggle': VBToggle,
  },
  mixins: [heightTransition],
  mixins: [togglePasswordVisibility],
  props: {
    // EmployeeId: {
    //   type: Number,
    //   required: false,
    // },
  },
  data() {
    return {
      required,
      DesignationOptions: [],
      visible: true,
      error: false,
      ShiftOptions: [], 
      EditId: 0,
      TeamDepartmentData: [],
    };
  },

  mounted(){
    this.fetchModuleForm();
    
  },
    computed: {
parseInteger(value) {
        // Check if the value is defined and not null
        if (value !== undefined && value !== null) {
            // Use parseInt to convert the string to an integer
            const intValue = parseInt(value);

            // Check if the result is a valid integer
            if (!isNaN(intValue)) {
                return intValue;
            }
        }

        // Return null or default value if parsing fails
        return null; // or return a default value as needed
    }
  },
methods:{

},
setup(props, { emit }) {
  const { route, router } = useRouter();

  const errors=ref([]);
  const userData =  JSON.parse(localStorage.getItem('userData'));

    const encodeBase64 = (data) => {
        return data? Buffer.from(data.toString()).toString("base64") : null;
    }; 
    const decodeBase64 = (data) => {
        return data? Buffer.from(data.toString(), "base64").toString("ascii") : null;
    };
   
   const RemoveError = (errorName) => { 
    errors.value[errorName]= " ";
   };

    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };
    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };

    // LoademployeeData();
    const toast = useToast();




    const Fields = ref([])
    const fetchModuleForm = () => {
      axios.get(`getFieldsData/${route.value.params.module_name}/${route.value.params.id}`).then(response =>  {
        Fields.value = response.data.data;
      });
    }

    // const roleOptions = ref([]);

        const submit = () => {
           const formData = new FormData();

    Fields.value.forEach((field, index) => {
      // Append each field's data as an array with an index
      formData.append(`fields[${index}][id]`, field.id);
      formData.append(`fields[${index}][field_type_id]`, field.field_type_id);
      formData.append(`fields[${index}][required]`, field.required);
      formData.append(`fields[${index}][field_type_slug]`, field.field_type_slug);
      formData.append(`fields[${index}][title]`, field.title);
      formData.append(`fields[${index}][slug]`, field.slug);

      // Append the value based on the field type
      switch (field.field_type_slug) {
        case 'file':
          formData.append(`fields[${index}][value]`, field.value);
          break;
        default:
          formData.append(`fields[${index}][value]`, field.value);
          break;
      }
    });
      axios.post(`UpdateModule/${route.value.params.module_name}/${route.value.params.id}`
        ,  formData,{
      headers: {
        'Content-Type': 'multipart/form-data'
      }
      } ).then(response => {
            toast({
               component: ToastificationContent,
               props: {
                 title: route.value.params.module_name+"Updated Successfully.",
                 icon: "CheckIcon",
                 variant: "success",
               },
             });
               router.push({name: route.value.params.module_name})
           })
           .catch((error) => {
             show.value = false;
             if (error.response.data.code == 422) {
               errors.value = error.response.data.errors;
             } else {
               toast(
                 {
                   component: ToastificationContent,
                   props: {
                     title: "Something went wrong Please try again later",
                     icon: "bellIcon",
                     variant: "danger",
                   },
                 },
                 { timeout: 3000 }
               );
             }
           });
        }

    return {
      // Sidebar
      errors,
      getErrors,
      hasErrors,
      encodeBase64,
      decodeBase64,
      Fields,
      submit,fetchModuleForm,RemoveError
      // roleOptions
    };
  },
};
</script>


<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/quill.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
#add-new-user-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}

.select-readonly {
  background: none;
  background-color: none;
}
</style>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";


</style>





