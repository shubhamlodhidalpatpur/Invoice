<template >
  <div class="add_mentorship_program">
    <b-row>
      <b-col cols="12" md="6">
        <h4 class="card-title fontgry">
          <b-link :to="{ name: 'leave-type' }">Leave Type</b-link>/
          <span v-if="$route.name == 'add-leave-type'"> Add Leave Type</span>
          <span v-else>Edit Leave Type </span>
        </h4>
      </b-col>
    </b-row>
    <b-form>
      <!-- Tab 1 -->

      <validation-observer ref="accountRules" tag="form">
        <b-card no-body class="p-2">
          <b-col md="12">
            <b-row>
              <b-col md="4">
                <label class="form-label required">Name</label>
                <validation-provider
                  #default="{ errors }"
                  rules="required"
                  name="Name"
                >
                  <b-form-input
                    placeholder="Name"
                    v-model="LeaveTypeData.name"
                    :state="errors.length > 0 ? false : null"
                    class="form-control"
                    @input="RemoveError('name')"
                  />

                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('name')">
                    {{ getErrors("name") }}
                  </div>
                </validation-provider>
              </b-col>
              <b-col md="4">
                <label class="form-label required">Deduction</label>
                <validation-provider
                  #default="{ errors }"
                  rules="required"
                  name="deduction"
                >
                  <v-select
                    input-id="deduction"
                    v-model="LeaveTypeData.deduction"
                    :options="DeductionOptions"
                    :state="errors.length > 0 ? false : null"
                    placeholder="Select deduction"
                    :reduce="(val) => val.value"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('deduction')">
                    {{ getErrors("deduction") }}
                  </div>
                </validation-provider>
              </b-col>
              <b-col md="4">
                <label class="form-label required">Allotment</label>
                <validation-provider
                  #default="{ errors }"
                  name="allotment"
                >
                  <b-form-input
                    placeholder="allotment"
                    v-model="LeaveTypeData.allotment"
                    :state="errors.length > 0 ? false : null"
                    class="form-control"
                    @input="RemoveError('allotment')"
                  />

                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('allotment')">
                    {{ getErrors("allotment") }}
                  </div>
                </validation-provider>
              </b-col>
              <b-col md="4">
                <label class="form-label required">Allotment Type</label>
                <validation-provider
                  #default="{ errors }"
                  rules="required"
                  name="allotment_type"
                >
                  <v-select
                    input-id="allotment_type"
                    v-model="LeaveTypeData.allotment_type"
                    :options="AllotmentTypeModeOptions"
                    :state="errors.length > 0 ? false : null"
                    placeholder="Select allotment_type"
                    :reduce="(val) => val.value"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('allotment_type')">
                    {{ getErrors("allotment_type") }}
                  </div>
                </validation-provider>
              </b-col>
              <b-col md="4">
                <label class="form-label required">Laps</label>
                <validation-provider
                  #default="{ errors }"
                  rules="required"
                  name="laps"
                >
                  <v-select
                    input-id="laps"
                    v-model="LeaveTypeData.laps"
                    :options="LapsOptions"
                    :state="errors.length > 0 ? false : null"
                    placeholder="Select laps"
                    :reduce="(val) => val.value"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('laps')">
                    {{ getErrors("laps") }}
                  </div>
                </validation-provider>
              </b-col>
                        <b-col md="4">
            <label class="form-label required">Max Allotment Count</label>
            <validation-provider
              #default="{ errors }"
              rules="required"
              name="max_allotment_count"
            >
              <b-form-input
                placeholder="max_allotment_count"
                v-model="LeaveTypeData.max_allotment_count"
                :state="errors.length > 0 ? false : null"
                class="form-control"
                @input="RemoveError('max_allotment_count')"
              />

              <small class="text-danger">{{ errors[0] }}</small>
              <div class="text-danger" v-if="hasErrors('max_allotment_count')">
                {{ getErrors("max_allotment_count") }}
              </div>
            </validation-provider>
          </b-col>
            </b-row>
          </b-col>

          <b-row class="mt-1">
            <b-col sm="12" class="text-center">
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                :disabled="show"
                variant="primary"
                @click="formSubmitted"
                class="btn d-inline-block mr-1 btn-primary"
              >
                <span>SUBMIT</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card>
      </validation-observer>
    </b-form>
  </div>
</template>
    
    
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import { required } from "@validations";
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
  BFormTextarea,
  BFormFile,
  BFormDatepicker,
  BFormTimepicker,
  BCard,
} from "bootstrap-vue";

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
    BFormTextarea,
    BFormFile,
    BFormDatepicker,
    BFormTimepicker,
    BCard,
  },
  directives: {
    Ripple,
  },
  mixins: [heightTransition],
  mixins: [togglePasswordVisibility],
  data() {
    return {
      required,
      nextTodoId: 0,
      in_time_disable: false,
      out_time_disable: false,
      DeductionOptions: [
        { label: "0%", value: "0%" },
        { label: "50%", value: "50%" },
        { label: "100%", value: "100%" },
      ],
      AllotmentTypeModeOptions: [
        { label: "Monthly", value: "Monthly" },
        { label: "Yeary", value: "Yeary" },
      ],
      LapsOptions: [
        { label: "yes", value: "yes" },
        { label: "no", value: "no" },
      ],
    };
  },
  computed: {},
  mounted() {
    axios.get("getplans").then((response) => {
      this.PlanOptions = response.data.responseData.data;
    });
  },
  created() {},
  destroyed() {},
  methods: {
    validationForm() {
      return new Promise((resolve, reject) => {
        this.$refs.leadstagesRules.validate().then((success) => {
          if (success) {
            resolve(true);
          } else {
            reject();
          }
        });
      });
    },

    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight);
      });
    },
  },

  setup() {
    const statusOptions = [
      { label: "Active", value: "A" },
      { label: "Inactive", value: "I" },
    ];
    const toast = useToast();
    const { route, router } = useRouter();
    const show = ref(false);
    let documentUpload = ref(false);
    const formSubmitted = () => {
      if (LeaveTypeData.value.id != null) {
         axios
        .put(`/LeaveTypes/${route.value.params.id}`, LeaveTypeData.value)
        .then((response) => {
          toast({
            component: ToastificationContent,
            props: {
              title: "Leave Type Updated successfully",
              icon: "BellIcon",
              variant: "success",
            },
          });
          router.replace({ name: "leave-type" });
        })
        .catch((error) => {
          show.value = false;
          if (error.response.data.code == 422) {
            errors.value = error.response.data.errors;
          }
        });
      show.value = false;
      }else{

 axios
        .post("LeaveTypes", LeaveTypeData.value)
        .then((response) => {
          toast({
            component: ToastificationContent,
            props: {
              title: "Leave Type Added successfully",
              icon: "BellIcon",
              variant: "success",
            },
          });
          router.replace({ name: "leave-type" });
        })
        .catch((error) => {
          show.value = false;
          if (error.response.data.code == 422) {
            errors.value = error.response.data.errors;
          }
        });
      show.value = false;
      }
     
    };
    const LeaveTypeData = ref({
      id:null,
      name: "",
      deduction: "",
      allotment: "",
      allotment_type: "",
      laps: "",
      max_allotment_count: "",
    });
    const RemoveError = (errorName) => {
      errors.value[errorName] = " ";
    };
    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };
    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };
     if(route.value.params.id){
      
      if(route.value.name == "edit-leave-type"){
        axios.get(`LeaveTypes/${route.value.params.id}/edit`).then((response) => {
          const emp = response.data.data;
          LeaveTypeData.value = {
            id: emp.id,
            name: emp.name,
            deduction: emp.deduction,
            allotment: emp.allotment,
            allotment_type: emp.allotment_type,
            laps: emp.laps,
            max_allotment_count: emp.max_allotment_count,
          };
          
        });
      }
      }
    const errors = ref([]);
    return {
      formSubmitted,
      show,
      LeaveTypeData,
      hasErrors,
      getErrors,
      errors,
      RemoveError,
      statusOptions,
      documentUpload,
    };
  },
};
</script>
    
    <style lang="scss">
.repeater-form {
  overflow: hidden;
  transition: 0.35s height;
}
.vue-form-wizard .wizard-navigation .wizard-nav {
  padding: 0px !important;
}

@import "~@core/scss/vue/libs/vue-wizard.scss";
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/quill.scss";
</style>