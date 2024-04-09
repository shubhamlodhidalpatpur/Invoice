<template v-if="$can('do', 'add_user_admin_access')">
  <div class="add_users">
    <b-row>
      <b-col cols="12" md="6">
        <h4 class="card-title fontgry">
          <b-link :to="{ name: 'admin-employees' }">Employee</b-link> / 
          <span v-if="$route.name == 'admin-add-employees'"> Add</span>
          <span v-else-if="$route.name == 'admin-view-employee'"> View</span>
          <span v-else>Edit</span> Employee
        </h4>
      </b-col>
    </b-row>

    <b-card>
      <div class="">
        <b-row>
          <b-col md="3">
            <label class="form-label required d-block">Profile Type</label>
              <validation-provider #default="{ errors }" name="Profile Type">
                <v-select
                  v-if="$route.name != 'admin-view-employee'"
                  :class="{'select-readonly' : $route.name == 'admin-view-employee' }"
                  @input="updateEmployeeForm($event)"
                  input-id="profile_type"
                  v-model.number="employeeData.role_id"
                  :reduce="(val) => val.id"
                  value="id"
                  label="name"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="JSON.parse(JSON.stringify(RoleOptions))"
                  :clearable="true"
                  :state="errors.length > 0 ? false : null"
                  placeholder="Please Select Profile Type"
                  />
                  <span v-if="$route.name == 'admin-view-employee'">{{ employeeData.profile_type }}</span>
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('profile_type')">
                  {{ getErrors("profile_type") }}
                </div>
              </validation-provider>

          </b-col>

          <!-- <b-col md="3">
            <label class="form-label required d-block">Profile</label>
              <validation-provider #default="{ errors }" name="Profile">
                <v-select
                  v-if="$route.name != 'admin-view-employee'"
                  :class="{'select-readonly' : $route.name == 'admin-view-employee' }"
                  @input="RemoveError('profile');updateBranchOptions($event)"
                  input-id="profile"
                  v-model.number="employeeData.profile"
                  :reduce="(val) => val.id"
                  value="id"
                  label="name"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="JSON.parse(JSON.stringify(profileOptions))"
                  :clearable="true"
                  :state="errors.length > 0 ? false : null"
                  placeholder="Please Select Profile"
                />
                <span v-if="$route.name == 'admin-view-employee'">{{ employeeData.profile }}</span>
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('profile')">
                  {{ getErrors("profile") }}
                </div>
              </validation-provider>
          </b-col> -->
          <!-- <b-col md="3">
            <label class="form-label required d-block">Profile</label>
              <validation-provider #default="{ errors }" name="Profile">
                    <v-select
                      v-if="$route.name != 'admin-view-employee'"
                      :class="{'select-readonly' : $route.name == 'admin-view-employee' }"
                  input-id="branch"
                  v-model="employeeData.branch"
                  @input="RemoveError('branch')"
                  :reduce="(val) => val.id"
                  label="name"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="JSON.parse(JSON.stringify(branchOptions))"
                  :clearable="true"
                  placeholder="Select Branch"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('profile')">
                  {{ getErrors("profile") }}
                </div>
              </validation-provider>
          </b-col> -->
        </b-row>
      </div>
    </b-card>


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
                    
                    <div v-if="field.field_type_slug == 'string' && $route.name != 'admin-view-employee'">
                    <b-form-input
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                      @input="RemoveError('name')"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'integer'">
                    <b-form-input
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
                      v-model="field.value"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'enum'">
                    <v-select
                      :options="field.field_value.split(',')"
                      v-model="field.value"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'file'">
                    <b-form-file
                      v-model="field.value"
                      class="mb-1"
                    />
                    </div>
                    <div v-if="field.field_type_slug == 'boolean'">
                      <b-form-checkbox
                        checked="true"
                        class="custom-control-success"
                        name="check-button"
                        switch
                        v-model="field.value"
                      />
                    </div>
                    <div v-if="field.field_type_slug == 'datetime'">
                      <flat-pickr
                        v-model="field.value"
                        class="form-control"
                        :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                      />
                    </div>

                    
                    <!-- <span v-if="$route.name == 'admin-view-employee'">{{ employeeData.name }}</span>
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('name')">
                      {{ getErrors("name") }}
                    </div> -->
                  </validation-provider>
              </b-col>
            </b-row>
            <b-button @click="submit">Submit</b-button>
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
    const GenderOption = [
      { text: "Male", value: "Male" },
      { text: "Female", value: "Female" },
      { text: "Other", value: "Other" },
    ];
    return {
      required,
      GenderOption,
      DesignationOptions: [],
      visible: true,
      error: false,
      ShiftOptions: [], 
      EditId: 0,
      TeamDepartmentData: [],
    };
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === "password" ? "EyeIcon" : "EyeOffIcon";
    },
  },
  mounted() {
  },
  created() {
    window.addEventListener("resize", this.initTrHeight);
  },
  destroyed() {
    window.removeEventListener("resize", this.initTrHeight);
  },
  methods: {
    DepartmentShowon() {
        if(this.userData.isSuperAdmin){
          this.DepartmentData.profile_type = null;
          this.DepartmentData.organization_id = null;
          this.DepartmentData.branch = null;
          this.profileOptions = [];
        }
        this.branchOptions = [];
        this.DepartmentData.name = "";
        this.DepartmentData.id = null;
        if(this.DepartmentData.profile_type != null){
          this.updateProfileOptions(this.DepartmentData.profile_type)
        }
        if(this.DepartmentData.organization_id != null){
          this.updateBranchOptions(this.DepartmentData.organization_id)
        }
    },
    TeamOnshown() {
        axios.get("departments",  { params: this.teamFilterData } ).then((response) => {
          this.TeamDepartmentData = response.data.responseData.departments
        });
        this.TeamData.team_name = "";
        this.TeamData.id = null;
        // this.TeamData.reporting_manager = ''
        this.TeamData.Department = "";
        // this.TeamData.team_members = ''
        // this.updateDepartmentOptions(this.TeamData.branch)
    },
    RoleOnShowon() {
          this.profileType = null;
          this.RoleData.id = null;
          this.RoleData.title = null;
          this.RoleData.department = null;
          this.RoleData.parent_role = null;
          this.RoleData.name = null;
          this.RoleData.profile_master= this.userData.isSuperAdmin? null : this.userData.profile_master_id;
          this.RoleData.profile= (this.userData.isSuperAdmin)? null : (this.userData.organization_id? this.userData.organization_id : this.userData.id);
          this.RoleData.branch= (this.userData.isSuperAdmin)? null : (this.userData.branch? this.userData.branch : this.userData.id);
          this.profileOptions = []
          this.branchOptions = []
          this.departmentOptions = []
          this.parentRoleOptions = []
          if(this.RoleData.branch != null){
            this.updateDepartmentOptions(this.RoleData.branch)
          }
    },

    resetData(){
     this.errors=[];
      this.employeeData.employee_name=""
      this.employeeData.department=""
      this.employeeData.team_id=''
      this.employeeData.gender=null
      this.employeeData.designation_id=""
      this.employeeData.dob=null
      this.employeeData.email=""
      this.employeeData.role=""
      this.employeeData.joiningDate=null
      this.employeeData.mobile=""
      this.employeeData.reporting_role="",
      this.employeeData.reporting_to="",
      this.employeeData.checker="",
      this.employeeData.approver="",
      this.employeeData.id=null
      this.employeeData.shift=""
      this.employeeData.EmployeeAccess=""
      this.employeeData.salary=""
     // this.employeeData.Rotational_Shift=""
      
   },
    initTrHeight() {
      this.trSetHeight(null);
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight);
      });
    },
    resetModal() {
      this.name = "";
      // this.hod = null;
      this.showDepartmentModal = false;
      this.errors = [];
    },
    openModal() {
      this.EditId = 0;
      this.showDepartmentModal = true;
      this.$refs["DepartmentModal"].show();
    },
    OpenTeamModel() {
      this.EditTeamId = 0;
      this.showTeamModel = true;
    },
    openRoleModal() {
      this.EditId = 0;
      this.showRoleModal = true;
      this.$refs["RoleModal"].show();
    },
    TeamhandleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.submitTeam();
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      
      this.handleSubmit();
    },

    RolehandleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.RolehandleSubmit();
    },
    resetRoleModal() {
      this.title = "";
      // this.hod = null;
      this.showRoleModal = false;
      this.errors = [];
    },
  },
  mounted(){
    axios.get('getDepartment').then(response => {
    this.DepartmentOptions = response.data.data
    })
    axios.get('getDesignation').then(response => {
    this.DesignationOptions = response.data.data
    })
    //   axios.get('getRole').then(response => {
    // this.ReportingRoleOptions = response.data.data
    // })
     axios.get('getShiftList').then(response => {
    this.ShiftOptions = response.data.data
    })
    //   axios.get('getUser').then(response => {
    //   this.UserOptions = response.data.data
    //        })
    
  },

setup(props, { emit }) {

  const userData =  JSON.parse(localStorage.getItem('userData'));

    const encodeBase64 = (data) => {
        return data? Buffer.from(data.toString()).toString("base64") : null;
    }; 
    const decodeBase64 = (data) => {
        return data? Buffer.from(data.toString(), "base64").toString("ascii") : null;
    };

    const blankemployeeData = {
        id: null,
        employee_name: null,
        department: null,
        team_id:null,
        designation_id: null,
        joiningDate: null,
        email: null,
        mobile: null,
        dob: null,
        gender: null,
        role: null,
        reporting_role:null,
        reporting_to:null,
        checker:null,
        approver:null,
        shift:null,
        EmployeeAccess:null,
        salary:null,
        profile_type: userData.isSuperAdmin? null : userData.profile_master_id,
        profile: (userData.isSuperAdmin)? null : (userData.organization_id? userData.organization_id : userData.id),
        branch: (userData.isSuperAdmin)? null : (userData.branch? userData.branch : 0),
       // Rotational_Shift:null,

    };
    const EmployeeAccess_slug =ref(null)
    const EmployeeAccessOptions = ref([])
    const TeamOptions = ref([]);
    const UserOptions = ref([])
    const ReprotingOptions = ref([]);
    const RoleOptions = ref([]);
    const ReportingRoleOptions = ref([]);
    const errors = ref([]);
    const project = ref([]);
    const ContectOptions =ref([]);
    const accountOptions =ref([]);
    const BranchOptions =ref([]);
    const DepartmentOptions =ref([]);
    const countryOptions = ref([])
    const countryName = ref([])
    const StateOptions = ref([])
    const CityOptions = ref([])
    const show = ref(false)

    const employeeData = ref(JSON.parse(JSON.stringify(blankemployeeData)));
    const resetuserData = () => {
      employeeData.value = JSON.parse(JSON.stringify(blankemployeeData));
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


    const profileTypeOptions = ref([])
    axios.get("getOrgnizationProfileMasters").then(response => {
      profileTypeOptions.value = response.data
    })

  
    axios.get(`getCountryData`).then(response => {
      countryOptions.value = response.data.data.country
    })

    const updateBranchOptions = (organization_id) => {
      axios.get(`fetchBranchByOrganization/${organization_id}`).then(response => {
        BranchOptions.value = response.data
        branchOptions.value = response.data

      })
    }
    updateBranchOptions(employeeData.value.profile);

    const updateDepartmentOptions = (branch) => {
      console.log("branch------", branch)
      DepartmentOptions.value = []
      // parentRoleOptions.value = []

      // RoleData.value.department = null
      // RoleData.value.parent_role = null
      axios.get(`departments`, {params: {branch: encodeBase64(branch)}}).then(response => {
        DepartmentOptions.value = response.data.responseData.departments
        departmentOptions.value=response.data.responseData.departments
      })
    }
    const updateRoleOptions = (department_id) => {
      axios.get(`getRolesByDepartment/${department_id}`).then(response => {
        RoleOptions.value = response.data.data;
        parentRoleOptions.value = response.data.data;
      })
    }
    axios.get('getAccessOptions').then(response => {
      EmployeeAccessOptions.value = response.data.data
    })
    
    const LoademployeeData=()=>{
    //     UserOptions.value=[];
    //     // UserOptions.value.push({"id":1,"name":'Super Admin'});
    //     if(route.value.params.id!=0 && route.value.params.id != undefined){
    //       axios.get(`EditEmployee/${props.EmployeeId}`).then(response => {
    //         UserOptions.value = response.data.data.UserOptions;
    //         UserOptions.value.push({"id":1,"name":'Super Admin'});
    //         employeeData.value = response.data.data.employee
    //         employeeData.value.EmployeeAccess=response.data.data.EmployeeAccess
    //         EmployeeAccess_slug.value=employeeData.value.EmployeeAccess.slug
    //         employeeData.value.team_id =  response.data.data.team;
    //         employeeData.value.dob =  response.data.data.dob;
    //       // employeeData.value.Rotational_Shift = response.data.data.employee.rotational_shift
        
    //       //   if(EmployeeAccess_slug.value=='organization'){
    //       //     RoleOptions.value=[];
    //       //     ReportingRoleOptions.value=[];
    //       //     RoleOptions.value=[];
    //       //     RoleOptions.value.push({id: 1, name: "Super Admin", slug: "super-admin"})
    //       //     ReportingRoleOptions.value.push({value: 1, label: "Super Admin", slug: "super-admin"})
    //       //     RoleOptions.value.push({id: 1, label: "Super Admin", slug: "super-admin"})
    //       //   }else{
    //       //     axios.get(`/getEmployeeFromdepartmentId/${employeeData.value.department}`).then((response) => {
    //       //     RoleOptions.value = JSON.parse(JSON.stringify(response.data.data));
    //       //     axios.get(`/getTeamFromdepartmentId/${employeeData.value.department}`).then((response) => {
    //       //     TeamOptions.value = JSON.parse(JSON.stringify(response.data.data)); 
    //       //   });
    //       // });
    //       // axios.get(`/getReportingtoFromRoleId/${employeeData.value.reporting_role}`).then((response) => {
    //       //   ReprotingOptions.value = response.data.data;
    //       // });
    //       // axios.get(`getReportingRoleFromdepartmentId/${employeeData.value.department}`).then(response => {
    //       //   ReportingRoleOptions.value = response.data.data
    //       // });
    //     // }
      
    //         // employeeData.value.joiningDate = response.data.data.user.date_of_joining
    //         // employeeData.value.role = response.data.data.user.role_id
    //         // employeeData.value.email = response.data.data.user.email
    //         // employeeData.value.mobile = response.data.data.user.phone
    // })
    //     }else{
    //       updateBranchOptions(employeeData.value.profile);    
    //     }
    }

    // LoademployeeData();
    const toast = useToast();

    const { route, router } = useRouter();
    const onSubmit = () => {
      show.value = true;
      if (employeeData.value.id != null) {
     axios.put(`/employees/${route.value.params.id}`, employeeData.value)
          .then(() => {
               emit('refatch-data');
               emit('close-sidebar');
              
            toast({
               
              component: ToastificationContent,
              props: {
                title: "Employee Updated Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
            show.value = false;
            router.push({name: 'admin-employees'})
          })
          .catch((error) => {
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            } else {
              show.value = false;
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
      } else {
       axios.post('/employees', employeeData.value)
          .then(() => {
               emit('refatch-data');
               emit('close-sidebar');
               show.value = false;
            toast({
               
              component: ToastificationContent,
              props: {
                title: "Employee Created Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
              router.push({name: 'admin-employees'})
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
      
    };
    
    
    axios.get("getCountryData").then((response) => {
      countryOptions.value = response.data.data.country;
      employeeData.value.country = response.data.data.country[0].id;
      axios.get(`getStateFromCountryId/${employeeData.value.country}`).then((response) => {
          StateOptions.value = JSON.parse(JSON.stringify(response.data.data));
        });
    });


    const onChange = (element, value) => {
    // alert("element onChange");
    console.log("onChnage", "element", element, "\nvalue", value, "\nEmployeeAccess_slug", EmployeeAccess_slug.value);
    console.log('1');
    if(element == 'accessLeval'){
        console.log('2');
        
        if(EmployeeAccess_slug.value=='organization' || EmployeeAccess_slug.value == 'branch'){
          console.log('2');
          RoleOptions.value=[];
          // ReportingRoleOptions.value=[];
          RoleOptions.value=[];
          axios.get(`fetchOrgAdminRole/${employeeData.value.profile}`).then(response => {
            console.log('3');
            console.log(response.data.responseData.roles);
            RoleOptions.value = response.data.responseData.roles
            ReportingRoleOptions.value = response.data.responseData.roles
          })
        }
console.log("employeeData----------", employeeData.value)
        updateDepartmentOptions(employeeData.value.branch)

      }
      if (element == "branch") {
      employeeData.value.team_id = "";
      employeeData.value.role = "";
      employeeData.value.reporting_to = "";



      if(EmployeeAccess_slug.value == 'branch'){
          RoleOptions.value=[];
          // ReportingRoleOptions.value=[];
          RoleOptions.value=[];
          axios.get(`fetchOrgAdminRole/${employeeData.value.profile}`).then(response => {
            console.log(response.data.responseData.roles);
            RoleOptions.value = response.data.responseData.roles
            ReportingRoleOptions.value = response.data.responseData.roles
          })
        }

      RoleOptions.value=[];
      axios.get(`fetchOrgAdminRole/${employeeData.value.profile}`).then(response => {
        console.log(response.data.responseData.roles);
        RoleOptions.value = response.data.responseData.roles
        ReportingRoleOptions.value = response.data.responseData.roles
      })

      updateDepartmentOptions(value)
          
        // axios.get(`getEmployees/${value}`,{params:{EmployeeId:route.value.params.id}}).then(response => {
        //   employeeData.value.approver=""
        //   employeeData.value.checker =""
        //   UserOptions.value=[];
        //   UserOptions.value=response.data.data;
        //   // UserOptions.value.push({"id":1,"name":'Super Admin'});

        // })
        // axios.get(`/getTeamFromdepartmentId/${value}`).then((response) => {
        //  TeamOptions.value = response.data.data;
        // });
        //  axios.get(`/getEmployeeFromdepartmentId/${value}`).then((response) => {
        //      employeeData.value.reporting_role = "";
        //   RoleOptions.value = response.data.data;
        // });
      
        // axios.get(`getReportingRoleFromdepartmentId/${value}`).then(response => {
        // // ReportingRoleOptions.value = response.data.data
        // })
      }
      if (element == "department") {
        console.log('element -----------', element, "value", value)
      employeeData.value.team_id = "";
      employeeData.value.role = "";
      employeeData.value.reporting_to = "";


      axios
        .get("teams", {
          params: {
            Department: value ,
          },
        })
        .then((response) => {
          TeamOptions.value = response.data.responseData.teams;
          // console.log("fetchUser response",task, total,response.data)

        })
        .catch(() => {
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching Team list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
        });

      axios.get(`getRolesByDepartment/${value}`).then(response => {
        RoleOptions.value = response.data.data
      })
        // axios.get(`getEmployees/${value}`,{params:{EmployeeId:EmployeeId.value}}).then(response => {
        //   employeeData.value.approver=""
        //   employeeData.value.checker =""
        //   UserOptions.value=[];
        //   UserOptions.value=response.data.data;
        //   UserOptions.value.push({"id":1,"name":'Super Admin'});

        // })
        // axios.get(`/getTeamFromdepartmentId/${value}`).then((response) => {
        //  TeamOptions.value = response.data.data;
        // });
        //  axios.get(`/getEmployeeFromdepartmentId/${value}`).then((response) => {
        //      employeeData.value.reporting_role = "";
        //   RoleOptions.value = response.data.data;
        // });
      
        // // axios.get(`getReportingRoleFromdepartmentId/${value}`).then(response => {
        // // // ReportingRoleOptions.value = response.data.data
        // // })
      }
      if (element == "Role") {
        employeeData.value.reporting_role = "";
        axios.get(`/getReportingtoFromemployeeId/${value}`).then((response) => {
          ReportingRoleOptions.value = response.data.data
        });
      }
      if (element == "reportingRole") {
          employeeData.value.reporting_to = "";
        axios.get(`/getReportingtoFromRoleId/${value}`).then((response) => {
          ReprotingOptions.value = response.data.data;
        });
      }

      if (element == "country") {
        axios.get(`getStateFromCountryId/${value}`).then((response) => {
          employeeData.value.state = null;
          employeeData.value.city = null;
          StateOptions.value = JSON.parse(JSON.stringify(response.data.data));
        });
      }
      if (element == "state") {
        axios.get(`getCityFromStateId/${value}`).then((response) => {
          employeeData.value.city = null;
          CityOptions.value = JSON.parse(JSON.stringify(response.data.data));
        });
      }
      
    }






















    

    if(route.value.params.id ){
      
      if(route.value.name == "employee-edit"){
        axios.get(`users/${route.value.params.id}/edit`).then((response) => {
          // const emp = response.data.responseData.employee;


        //   axios.get(`getStateFromCountryId/${emp.country_id}`).then((response) => {
        //     StateOptions.value = JSON.parse(JSON.stringify(response.data.data));
        //   });
        //   axios.get(`getCityFromStateId/${emp.state_id}`).then((response) => {
        //     CityOptions.value = JSON.parse(JSON.stringify(response.data.data));
        //   });
        //   // updateProfileOptions(emp.profile_type)
        //   updateDepartmentOptions(emp.branch)
        //   // updateDesignationOptions(emp.department)
        //   // updateRoleOptions(emp.department)
          
        //   employeeData.value = {
        //     id: emp.id,
        //     address: emp.address,
        //     age: emp.age,
        //     dob: emp.dob,
        //     reporting_to: emp.reporting_to,
        //     reporting_role: emp.reporting_role,
        //     branch: emp.branch,
        //     EmployeeAccess: emp.EmployeeAccess,
        //     city: emp.city_id,
        //     country: emp.country_id,
        //     designation: emp.designation_id,
        //     department: emp.department,
        //     team: emp.teams,
        //     email: emp.email,
        //     name: emp.name,
        //     gender: emp.gender,
        //     mobile: emp.mobile,
        //     pincode: emp.pincode,
        //     profile_type: emp.profile_type,
        //     profile: emp.profile_id,
        //     role: emp.role_id,
        //     state: emp.state_id,
        //   };
        //   EmployeeAccess_slug.value = emp.EmployeeAccess.slug
        //   console.log("employeeData", employeeData)
        //   updateProfileOptions(emp.profile_type)
        //   updateBranchOptions(emp.profile_id)
        //   if(emp.branch != null){
        //     onChange('branch', emp.branch)
        //     updateDepartmentOptions(emp.branch)
        //   }
        //   if(emp.department != null){
        //     onChange('department', emp.department)
        //   }
        //   // employeeData.value.branch = emp.branch;
        //   // employeeData.value.department = emp.department;
        //   // // if(employeeData.value.team != null){
        //   //   //   onChange('team', {slug: 'team'})
        //   //   //   employeeData.value.department: emp.department;
        //   //   // }
        //   if(emp.role_id != null){
        //     onChange('Role', emp.role_id)
        //   }
        //   //   employeeData.value.role = emp.role_id;
        //   if(emp.reporting_role != null){
        //     onChange('reportingRole', emp.reporting_role)
        //   }

        //   employeeData.value.role = emp.role_id;
        //   employeeData.value.reporting_to = emp.reporting_to;
        //   employeeData.value.reporting_role = emp.reporting_role;
        //   employeeData.value.branch = emp.branch;
        //   employeeData.value.department = emp.department;



        //   // if(EmployeeAccess_slug.value=='organization'){
        //   //       RoleOptions.value=[];
        //   //       ReportingRoleOptions.value=[];
        //   //       RoleOptions.value=[];
        //   //       RoleOptions.value.push({id: 1, name: "Super Admin", slug: "super-admin"})
        //   //       ReportingRoleOptions.value.push({value: 1, label: "Super Admin", slug: "super-admin"})
        //   //       RoleOptions.value.push({id: 1, label: "Super Admin", slug: "super-admin"})
        //   //     }else{
        //   //       axios.get(`/getEmployeeFromdepartmentId/${employeeData.value.department}`).then((response) => {
        //   //           RoleOptions.value = JSON.parse(JSON.stringify(response.data.data));
        //   //           axios.get(`/getTeamFromdepartmentId/${employeeData.value.department}`).then((response) => {
        //   //             TeamOptions.value = JSON.parse(JSON.stringify(response.data.data)); 
        //   //           });
        //   //       });
        //   //       axios.get(`/getReportingtoFromRoleId/${employeeData.value.reporting_role}`).then((response) => {
        //   //         ReprotingOptions.value = response.data.data;
        //   //       });
        //   //       axios.get(`getReportingRoleFromdepartmentId/${employeeData.value.department}`).then(response => {
        //   //         ReportingRoleOptions.value = response.data.data
        //   //       });
        //   //     }
          
          });
        }
        else if(route.value.name == "admin-view-employee"){
          axios.get(`employees/${route.value.params.id}`).then((response) => {
            employeeData.value = response.data.responseData.employee;
          });
        }
      }
      const formValidator = (vModel = null, field= null) => {
      
          if(vModel != null){
            if(DepartmentData.value[vModel] == null || DepartmentData.value[vModel] == ''){
              errors.value[vModel] = [field+' is required'];
            }
            else{
              delete errors.value[vModel];
            }
          }
          else{
            let err = [];
            let vModels = [ 'profile_type', 'organization_id', 'branch', 'name'];
            let fields = [ 'Profile Type', 'Organization', 'Branch', 'Department Name'];
            errors.value = [];
            for(let i = 0; i < vModels.length; i++){
              if(DepartmentData.value[vModels[i]] == null || DepartmentData.value[vModels[i]] == ''){
                err[vModels[i]] = [fields[i]+' is required'];
              }
            }
            errors.value = err;
          }
          return errors.value.length == 0 && Object.keys(errors.value).length == 0;
      }
      const handleSubmit = () => {
      // bvModalEvent.preventDefault();
      
      if(formValidator()){
        axios
          .post("departments", { 
              profile_type: encodeBase64(DepartmentData.value.profile_type),
              organization_id: encodeBase64(DepartmentData.value.organization_id),
              branch: encodeBase64(DepartmentData.value.branch),
              id: encodeBase64(DepartmentData.value.id),
              name: DepartmentData.value.name,
          })
          .then((response) => {
            showDepartmentModal.value = false;
            updateDepartmentOptions(employeeData.value.branch)
            console.log("reloading")

            toast({
              component: ToastificationContent,
              props: {
                title: "Department Created Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
            showDepartmentModal.value = false; 
          })
          .catch((error) => {
            if (error.response.status == 422) {
              errors.value = error.response.data.errors;
              console.log(errors);
            }
            else{
              toast({
              component: ToastificationContent,
              props: {
                title: error.response.data.message,
                icon: "CheckIcon",
                variant: "danger",
              },
            });
            }
          });
      }
    };
    const blankDepartmentData = {
      profile_type: userData.isSuperAdmin? null : userData.profile_master_id,
      organization_id: (userData.isSuperAdmin)? null : (userData.organization_id? userData.organization_id : 0),
      branch: (userData.isSuperAdmin)? null : (userData.branch? userData.branch : 0),
      id: null,
      name: null,
    };
    const showDepartmentModal = ref(false);
    const DepartmentData = ref(JSON.parse(JSON.stringify(blankDepartmentData)));
    const blankTeamData = {
      id: null,
      team_name: null,
      reporting_manager: null,
      Department: null,
      team_members: null,
    };
    const TeamData = ref(JSON.parse(JSON.stringify(blankTeamData)));
    const submitTeam = () => {
        axios
          .post("teams", {
            team_name: encodeBase64(TeamData.value.team_name),
            reporting_manager: encodeBase64(TeamData.value.reporting_manager),
            Department: encodeBase64(TeamData.value.Department),
            team_members: encodeBase64(TeamData.value.team_members),
          })
          .then(() => {
            emit("removeBlankTeamError");
            showTeamModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Team Created Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
          })
          .catch((error) => {
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
    };
    const RolehandleSubmit = () => {
      axios
          .post("roles", {
            id: encodeBase64(RoleData.value.id),
            profile_master: encodeBase64(RoleData.value.profile_master),
            profile: encodeBase64(RoleData.value.profile),
            branch: encodeBase64(RoleData.value.branch),
            department: encodeBase64(RoleData.value.department),
            parent_role: encodeBase64(RoleData.value.parent_role),
            title: encodeBase64(RoleData.value.title),
          })
          .then((response) => {
            showRoleModal.value = false;
            updateRoleOptions(RoleData.value.department)
            console.log("reloading")

            toast({
              component: ToastificationContent,
              props: {
                title: "Role Created Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
            showRoleModal.value = false; 
          })
          .catch((error) => {
            if (error.response.status == 422) {
              errors.value = error.response.data.errors;
              console.log(errors);
            }
            else{
              toast({
              component: ToastificationContent,
              props: {
                title: error.response.data.message,
                icon: "CheckIcon",
                variant: "danger",
              },
            });
            }
          });
    };
    const blankRoleData = {
      id: null,
      profile_master: userData.isSuperAdmin? null : userData.profile_master_id,
      profile: (userData.isSuperAdmin)? null : (userData.organization_id? userData.organization_id : userData.id),
      branch: (userData.isSuperAdmin)? null : (userData.branch? userData.branch : userData.id),
      department: null,
      parent_role: null,
      name: null
    };
    const RoleData = ref(JSON.parse(JSON.stringify(blankRoleData)));

    const showRoleModal = ref(false);
    const parentRoleOptions = ref([])
    const profileOptions = ref([])
    const showTeamModel = ref(false);
    const updateProfileOptions = (profile_id) => {
      RemoveError('profile_type')
       
      //  RemoveError('profile_master')
      axios.get(`getProfileByProfileMasters/${profile_id}`).then(response => {
        profileOptions.value = response.data
      })
    }
    const FilterData = {
          id: null,
          profile_type: userData.isSuperAdmin? null : userData.profile_master_id,
          profile: (userData.isSuperAdmin)? null : (userData.organization_id? userData.organization_id : userData.id),
          name: "",
          Department: "",
    };
    const teamFilterData = ref(JSON.parse(JSON.stringify(FilterData)));
    const branchOptions = ref([])
    const departmentOptions = ref([])



    const Fields = ref([])
    const updateEmployeeForm = (role_id) => {
      axios.get(`getFieldsByRole/${role_id}`).then(response =>  {
        Fields.value = response.data.data;
      });
    }

    // const roleOptions = ref([]);

    // ...Searchdata.value,
    axios.get('fetchRole').then(response => {
        RoleOptions.value = response.data
      })
        .catch(() => {
          toast({
            component: ToastificationContent,
            props: {
              title: 'Error fetching Role list',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
        })

        const submit = () => {
          axios.post('submitUserForm', Fields.value).then(response => {
            toast({
               component: ToastificationContent,
               props: {
                 title: "Employee Created Successfully.",
                 icon: "CheckIcon",
                 variant: "success",
               },
             });
               router.push({name: 'employee'})
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
      employeeData,
      onSubmit,
      LoademployeeData,
      TeamOptions,
      UserOptions,
      ReprotingOptions,
      RoleOptions,
      RemoveError,
      ReportingRoleOptions,
      EmployeeAccessOptions,
      EmployeeAccess_slug,
      BranchOptions,
      updateDepartmentOptions,
      DepartmentOptions,
      countryOptions,
      StateOptions,
      updateRoleOptions,
      userData,
      showDepartmentModal,DepartmentData,handleSubmit,
      showRoleModal,RolehandleSubmit,RoleData,parentRoleOptions,
      CityOptions,
      show,
      updateProfileOptions,
      profileOptions,
      profileTypeOptions,
      updateBranchOptions,
      encodeBase64,
      decodeBase64,
      onChange,showTeamModel,TeamData,teamFilterData,submitTeam,branchOptions,departmentOptions,
      Fields,
      updateEmployeeForm,
      submit
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





