<template >
  <div class="add_mentorship_program">
    <b-row>
      <b-col cols="12" md="6">
        <h4 class="card-title fontgry">
          <b-link :to="{ name: 'mentorship_program' }"
            >Swap</b-link
          >
          / Apply Swap
        </h4>
      </b-col>
    </b-row>
    <b-form>
      <!-- Tab 1 -->

      <validation-observer ref="accountRules" tag="form">
        <b-card no-body class="p-2">
        <b-col md="12">
          <b-row>
            <b-col md="6">
              <label class="form-label required">Request Date</label>
              <validation-provider
                #default="{ errors }"
                rules="required"
                name="Request Date"
              >
                 <b-form-datepicker placeholder="request date" v-model="SwapData.request_date"
                    :state="errors.length > 0 ? false : null" class="form-control"
                     @input="RemoveError('request_date')" />

                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('request_date')">
                  {{ getErrors("request_date") }}
                </div>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <label class="form-label required">In /Out Mode</label>
              <validation-provider
                #default="{ errors }"
                rules="required"
                name="in_out_mode"
              >
                <v-select
                  input-id="in_out_mode"
                  v-model="SwapData.in_out_mode"
                  @input="onChange('mode', $event);"
                  :options="InOutModeOptions"
                  :state="errors.length > 0 ? false : null"
                  placeholder="Select In Out Mode"
                  :reduce="(val) => val.value"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('in_out_mode')">
                  {{ getErrors("in_out_mode") }}
                </div>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <label class="form-label required">In Time </label>
             <validation-provider #default="{ errors }" rules="required" name="In Time">
                  <b-form-timepicker :disabled="in_time_disable" v-model="SwapData.in_time" :state="errors.length > 0 ? false : null"
                    class="form-control" @input="RemoveError('in_time')" />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('in_time')">
                    {{ getErrors("in_time") }}
                  </div>
                </validation-provider>
            </b-col>  
              <b-col md="6">
              <label class="form-label required">Out Time</label>
             <validation-provider #default="{ errors }" rules="required" name="Out time">
                  <b-form-timepicker :disabled="out_time_disable" v-model="SwapData.out_time" :state="errors.length > 0 ? false : null"
                    class="form-control" @input="RemoveError('out_time')" />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('out_time')">
                    {{ getErrors("out_time") }}
                  </div>
                </validation-provider>
            </b-col>  

            <b-col md="12">
              <label class="form-label required">Reason</label>
              <validation-provider
                #default="{ errors }"
                rules="required"
                name="Description"
              >
                <b-form-textarea
                  v-model="SwapData.reason"
                  id="reason"
                  placeholder="Enter reason"
                  :state="errors.length > 0 ? false : null"
                  @input="RemoveError('reason')"
                />

                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('reason')">
                  {{ getErrors("reason") }}
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
  BFormFile,BFormDatepicker,BFormTimepicker,BCard,
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
    BFormFile,BFormDatepicker,BFormTimepicker,BCard,
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
      in_time_disable:false,
      out_time_disable:false,
      // show:false,
      InOutModeOptions:[{'label':'In','value':'in'},{'label':'Out','value':'out'},{'label':'Both','value':'both'}],
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
     onChange(element, value) {
          if (value == "in") {
            this.out_time_disable=true;
            this.in_time_disable=false;
            this.SwapData.in_time="8:30:00"
          }else if(value == "out"){
            this.in_time_disable=true;
            this.out_time_disable=false;
            this.SwapData.out_time="7:30:00"

          }else{
            this.out_time_disable=false;
            this.in_time_disable=false;
            this.SwapData.in_time="8:30:00"
            this.SwapData.out_time="7:30:00"
          }
      },
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
        console.log('SwapData.value',SwapData.value)
        axios
          .post("swapRequest", SwapData.value)
          .then((response) => {
            toast({
              component: ToastificationContent,
              props: {
                title: "Swap Request Added successfully",
                icon: "BellIcon",
                variant: "success",
              },
            });
            router.replace({ name: "swap" });
          })
          .catch((error) => {
            show.value = false;
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            }
          });
        show.value = false;
    };
    const SwapData = ref({
      request_date: "",
      in_out_mode:"",
      in_time: "",
      out_time:"",
      reason:"",
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
    const errors = ref([]);
    return {
      formSubmitted,
      show,
      SwapData,
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