<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">

        <!-- card 1 -->
        <b-card>
          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Role</p>
            </div>
            <div>
              <div class="d-flex justify-content-between mt-2 mt-md-0">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mb-0 ml-md-1 basicButton"
                  @click="opanModal()">
                  Add
                </b-button>
                <b-button v-if="$route.name=='role'" variant="primary" class="mb-0 ml-1 basicButton" v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                  v-b-toggle.collapse-1>
                  Filter
                </b-button>
              </div>



            </div>
          </div>

          <!-- Add Form -->
          <div>
            <b-collapse id="collapse-1" class="my-2">
              <b-card class="filterBox mb-0">
                <b-row>
                  <b-col md="6" xl="4" class="">
                    <b-form-group label="Role Name" label-for="basicInput">
                      <b-form-input id="basicInput" v-model="Searchdata.role" placeholder="Enter Role " />
                    </b-form-group>
                  </b-col>
                  <b-col>
                    <b-col md="6" xl="4" class="">
                    <b-form-group label="Department" label-for="basicInput" class="teamFilterDepartment">
                      <v-select v-model="Searchdata.department_id"
                      @input="onChange('departmentforTeam', $event)"
                      :dir="$store.state.appConfig.isRTL ? 'rtl'
                      : 'ltr'"
                        :options="departmentData"
                        :reduce="(val) => val.value"
                        :clearable="true" input-id="status"
                         placeholder="Select Department" />
                    </b-form-group>
                       </b-col>
                  </b-col>
                  <!-- <b-col>
                    <b-form-group label="Team" label-for="basicInput1" class="teamFilterDepartment">
                      <v-select v-model="Searchdata.team_id"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="teamOptions"
                      :reduce="(val) => val.value"
                      :clearable="true" input-id="status" />
                    </b-form-group>
                  </b-col> -->

                </b-row>

                <b-row>
                  <b-col md="6" xl="4">

                    <b-button variant="primary mt-1 w-100" @click="ProjectFilterDataSubmit()">
                      Search
                    </b-button>

                  </b-col>
                  <b-col md="6" xl="4">
                    <b-button @click="ProjectFilterDataReset()" variant="primary mt-1 w-100">
                      Reset
                    </b-button>
                  </b-col>

                </b-row>

              </b-card>
            </b-collapse>

          </div>

          <b-table ref="refProjectListTable" responsive :items="fetchRole" :fields="fields"
            :sort-by.sync="sortBy"
            :sort-desc.sync="isSortDirDesc"
            class="mb-2 staticTable">
            <template #cell(role)="data">
              <span class="text-nowrap">
                {{ data.item.name }}
              </span>
            </template>
            <template #cell(Reporting_Role)="data">
              <span class="text-nowrap">
                {{ data.item.rol }}
              </span>
            </template>
            <template #cell(department)="data">
              <span class="text-nowrap">
                {{ data.item.title }}
              </span>
            </template>


            <template #cell(action)="data">
              <div class="icon_flx">
                <b-link v-b-modal.modal-select2 @click="editRole(data.item.id)" title="Edit" class="text-primary">
                  <feather-icon class="custom-class" icon="EditIcon" size="1.5x" />
                </b-link>
                <b-link @click="showDeleteConfirmation(); DeleteId=data.item.id" title="Delete" >
                  <feather-icon
                    class="custom-class"
                    icon="TrashIcon"
                    size="1.5x"
                  />
                </b-link>
              </div>

            </template>
          </b-table>
          <div class="mx-2 mb-2">
            <div class="text-center"><span v-if="totalProject==null">No Records Found</span></div>
            <b-row v-if="totalProject!=null">
              <b-col cols="12" md="4" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="perPage" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="perPageOptions"
              :clearable="false" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>
              <b-col cols="12" sm="4" class="
                  d-flex
                  align-items-center
                  justify-content-center justify-content-sm-start
                ">
                <span class="text-muted">Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
                  {{ dataMeta.of }} entries</span>
              </b-col>
              <!-- Pagination -->
              <b-col cols="12" sm="4" class="
                  d-flex
                  align-items-center
                  justify-content-center justify-content-sm-end
                ">
                <b-pagination v-model="currentPage" :total-rows="totalProject" :per-page="perPage" first-number
                  last-number class="mb-0 mt-1 mt-sm-0" prev-class="prev-item" next-class="next-item">
                  <template #prev-text>
                    <feather-icon icon="ChevronLeftIcon" size="18" />
                  </template>
                  <template #next-text>
                    <feather-icon icon="ChevronRightIcon" size="18" />
                  </template>
                </b-pagination>
              </b-col>
            </b-row>
          </div>



        </b-card>
      </b-col>
    </b-row>





    <!-- SideBar Starts -->

    <b-sidebar id="sidebar-right" bg-variant="white" right backdrop shadow>

    </b-sidebar>
    <b-modal v-model="showModal" title="Delete Confirmation" @ok="deleteRole">
      Are you sure you want to delete this role?
    </b-modal>

    <b-modal size="lg" :visible="showRoleModel" :title="RoleEdit ? 'Update Role' : 'Add Role'"
      :ok-title="RoleEdit ? 'Update' : 'Add'" @ok="handleOk" @hidden="resetModal" @show="Onshown" centered
      cancel-variant="outline-secondary">

      <b-form ref="form" @submit.stop.prevent="submitRole"  class="myform">

        <b-form-group label-for="basicInput">
          <label class="form-label required">Department</label>
          <v-select
          @input="onChange('department', $event);RemoveError('department')"
          v-model="Roledata.department"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="departmentData"
             :reduce="(val) => val.value"
              :clearable="true"
              input-id="status"
              placeholder="Select Department"/>
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('department')">
            {{ getErrors("department") }}
          </div>
        </b-form-group>



        <b-form-group  label-for="basicInput">
          <label class="form-label required">Reporting Role</label>
          <v-select
          v-model="Roledata.reporting_role"
          @input="RemoveError('reporting_role')"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="ReportigRoleOptions"
             :reduce="(val) => val.value"
              :clearable="true"
              input-id="status"
              placeholder="Select Reporting Role" />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('reporting_role')">
            {{ getErrors("reporting_role") }}
          </div>
        </b-form-group>

      <label class="form-label required">Role Name</label>
       <b-form-group  label-for="basicInput">
          <b-form-input id="basicInput" v-model="Roledata.name"   @input="RemoveError('name')"  placeholder="Enter Role Name" />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('name')">
            {{ getErrors("name") }}
          </div>
        </b-form-group>

       <b-form-group  label-for="basicInput">
          <b-form-checkbox :value="Roledata.master_page" checked="1">Make Master Page</b-form-checkbox>
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('name')">
            {{ getErrors("name") }}
          </div>
        </b-form-group>

        <div>
          <b-table :items="Roledata.roleFields" :fields="['title', 'type', 'required', 'action']">
            
            <template #cell(title)="data">
              <b-card-text class="font-weight-bold mb-25">
                <b-form-input id="basicInput" v-model="data.item.title" :disabled="data.item.is_default_field == 1" placeholder="Enter Field Name" />
              </b-card-text>
            </template>
            <template #cell(type)="data">
              <b-card-text class="text-nowrap">
                <v-select id="select-field-type-option" :disabled="data.item.is_default_field == 1" :options="RoleTypes" label="title" value="id" :reduce="val => val.id" v-model.number="data.item.field_type_id" />
                  <b-form-input id="basicInput" :disabled="data.item.is_default_field == 1" v-if="data.item.field_type_id == RoleTypes.filter(rt => rt.slug == 'enum')[0].id" v-model="data.item.field_value" placeholder="Enter Enum Values in Coma Saperated" />
              </b-card-text>
            </template>
            <template #cell(required)="data">
              <b-form-checkbox :value="1" unchecked-value="0" v-model="data.item.required" :disabled="data.item.is_default_field == 1" />
            </template>
            <template #cell(action)="data">
              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success" pill @click="addField(data.index)">
                <feather-icon icon="PlusIcon" size="18" />
              </b-button>
              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-if="data.item.role_id != 0" variant="danger" pill @click="removeField(data.index)">
                <feather-icon icon="MinusIcon" size="18" />
              </b-button>
              <!-- <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" pill>
                <feather-icon icon="EditIcon" size="18" />
              </b-button> -->

                <!-- <b-avatar
        size="16"
        :src="userData.avatar"
        variant="light-primary"
        badge
        class="badge-minimal"
        badge-variant="success"
      >
        <feather-icon
          icon="PlusIcon"
          size="16"
        />
      </b-avatar> -->
            </template>
          </b-table>

          <!-- <b-row v-for="field in Roledata.roleFields" :key="field.id" class="pt-1">
             {{field.validation_rule}}
          </b-row> -->
          <!-- <b-form-group label="Using sub-components:" v-slot="{ ariaDescribedby }">
            <b-form-checkbox-group
              id="checkbox-group-2"
              v-model="Roledata.roleFields"
              :aria-describedby="ariaDescribedby"
              name="flavour-2"
            >
              <b-form-checkbox v-for="field in Roledata.roleFields" :key="field.id" :value="field.id" checked="1">{{field.title}} - rules : {{field.validation_rule}}</b-form-checkbox>
            </b-form-checkbox-group>
          </b-form-group> -->
        </div>
        

        <!-- <b-form-group label="Team " label-for="basicInput">
          <v-select v-model="Roledata.team"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="teamOptions"
            label="name"
            :reduce="(val) => val.id"
            :clearable="true" input-id="status" />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('team')">
            {{ getErrors("team") }}
          </div>
        </b-form-group> -->

      </b-form>
    </b-modal>
    <!-- Modal Ends -->

  </div>
</template>

<script>
import {
  BFormCheckboxGroup,
  BFormCheckbox,
  BTable, BButton, BFormGroup, BAvatar, BBadge, BPagination,
  BRow, BCol, BCard, BCollapse, VBToggle, BFormInput, BSidebar, BModal, VBModal, BForm, BFormDatepicker,BLink,
  BCardText
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from "vee-validate";

import Ripple from 'vue-ripple-directive'
import axios from "@axios";
import { ref, computed, watch } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BTable,
    BButton,
    BFormGroup,
    BFormCheckboxGroup,
    BFormCheckbox,
    BAvatar,
    BBadge,
    vSelect,
    BPagination,
    BRow, BCol, BCard, BCollapse, BFormInput, BModal, VBModal, BForm,BLink,
    BSidebar,
    flatPickr,
    BFormDatepicker,
    ValidationProvider,
    ValidationObserver,
    BCardText
  },
  directives: {
    'b-toggle': VBToggle,
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      showModal: false,
      DeleteId:0,
      departmentData: [],
      ReportigRoleOptions: [],
      teamOptions: [],

      RoleEdit: 0,
      fields: [

        {key: 'Role', sortable: true},
        {key: 'Reporting_Role', sortable: true},
        {key: 'department', sortable: true},
        'action',

      ],
      name: {}
    }
  },
  methods: {
    showDeleteConfirmation() {
      this.showModal = true;
    },
    onChange(element, value) {
      if (element == "department") {
        this.teamOptions=[]
        this.Roledata.team=''
        this.Roledata.reporting_role=""
        axios.get(`/getRoleFromdepartmentId/${value}`).then((response) => {
          this.ReportigRoleOptions = response.data.data;
        });
      }
      if(element == "departmentforTeam"){
        axios.get(`/teamoption/${value}`).then((response) => {
          this.teamOptions = response.data
        });
      }
    },
    addField(index){
      let data = {
        created_at: null,
        field_type_id: null,
        required: 0,
        role_id: null,
        title:  null,
        updated_at: null,
        validation_rule: ""
      }
      this.Roledata.roleFields.splice(index+1, 0, data);

    },
    removeField(index){

      this.Roledata.roleFields.splice(index, 1);

    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.submitRole()
    },
    deleteRole(){
      axios.delete(`role/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Role Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
          },
        })
      });

    },
    editRole(id) {
      this.RoleEdit = id;
      this.showRoleModel = true
    },
    opanModal() {
      this.RoleEdit = 0;
      this.showRoleModel = true
    },
    resetModal() {

      this.showRoleModel = false
      this.errors = [];

    },
    Onshown() {
      axios.get("/getDepartment").then((response) => {
        this.departmentData = response.data.department
      });
      if (this.RoleEdit != 0) {
        axios.get(`/roleEdit/${this.RoleEdit}`).then((response) => {

          this.Roledata.name = response.data.data.name
          this.Roledata.department = response.data.data.department_id
          this.Roledata.reporting_role = response.data.data.parent_id
          this.Roledata.team = response.data.data.team
          this.Roledata.id = response.data.data.id
          this.Roledata.roleFields = response.data.data.roleFields
          axios.get(`/getRoleFromdepartmentId/${ this.Roledata.reporting_role,this.Roledata.department}`).then((response) => {
         this.ReportigRoleOptions = response.data.data;

        });
        });
      } else {
        this.resetRoledata();
      }
    },

  },
  mounted() {

    axios.get("/getDepartment").then((response) => {
        this.departmentData = response.data.department
    });
  },
  setup(props, { emit }) {
    const roleFields = ref([]);
    const submitRole = () => {
      if (Roledata.value.id != null) {
        axios.post(`/RoleUpdate/${Roledata.value.id}`, Roledata.value)
          .then(response => {
            refetchData()
            showRoleModel.value = false
            toast({
              component: ToastificationContent,
              props: {
                title: "Role Update Successfully!!",
                icon: "EditIcon",
                variant: "success",
              },
            });

          })
          .catch(error => {
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            }
          })
      } else {
        axios.post("/roleadd", Roledata.value).then((response) => {
          refetchData();
          emit('removeBlankRolerror');
          showRoleModel.value = false;
          toast({
            component: ToastificationContent,
            props: {
              title: "Role Added Successfully!!",
              icon: "EditIcon",
              variant: "success",
            },
          });

        })
          .catch(error => {

            if (error.response.data.code == 422) {

              errors.value = error.response.data.errors;
            }
          })
      }
    }
    const ProjectFilterDataSubmit = () => {
      refetchData();
    };
    const refetchData = () => {
      refProjectListTable.value.refresh()
    }
    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]

    const fetchRole = (ctx, callback) => {
      axios.get('fetchRole', {
        params: {
          ...Searchdata.value,
          page: currentPage.value,
          perPage: perPage.value,
          sortBy : sortBy.value,
          sortDesc : isSortDirDesc.value,
        }
      }
      ).then(response => {
        const projects = response.data

        callback(projects)
        totalProject.value = (projects[0] != null) ? projects[0].total_row_count : 0;

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
    }
    const FilterData = {
      role: null,
      department_id: null,
      team_id: null,

    }
    const Searchdata = ref(JSON.parse(JSON.stringify({ FilterData })));
    const ProjectFilterDataReset = () => {
      Searchdata.value = JSON.parse(JSON.stringify(FilterData));
      refetchData();
    };
    const toast = useToast();
    let BlankProjectData = {
      id: null,
      name: null,
      department: null,
      reporting_role: null,
      team: null,
      roleFields: [],
      master_page: 0
    };

    
    const showRoleModel = ref(false);
    const errors = ref([]);
    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };
    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };
    const perPage = ref(5)
    const perPageOptions = [5, 10, 100];
    const currentPage = ref(1)
    const Roledata = ref(JSON.parse(JSON.stringify(BlankProjectData)));
    const refProjectListTable = ref(null)
    const RoleTypes = ref([]);
    
    axios.get('getFieldTypes').then(response => {
      RoleTypes.value = response.data.data;
    })
    
    axios.get('getFieldsByRole').then(response =>  {
      BlankProjectData.roleFields = response.data.data;
      Roledata.value.roleFields = response.data.data;
    });

    const resetRoledata = () => { Roledata.value = JSON.parse(JSON.stringify(BlankProjectData))};

    const dataMeta = computed(() => {
      const localItemsCount = refProjectListTable.value ? refProjectListTable.value.localItems.length : 0
      return {
        from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
        to: perPage.value * (currentPage.value - 1) + localItemsCount,
        of: totalProject.value,
      }
    })
    const totalProject = ref();

    const RemoveError = (errorName) => {
     errors.value[errorName]= " ";
   };



    watch([currentPage, perPage,], () => {
      refetchData()
    })
    return {
      ProjectFilterDataSubmit,
      Roledata,
      submitRole,
      fetchRole,
      resetRoledata,
      toast,
      dataMeta,
      refetchData,
      BlankProjectData,
      refProjectListTable,
      currentPage,
      perPageOptions,
      perPage,
      Searchdata,
      totalProject,
      errors,
      hasErrors,
      getErrors,
      FilterData,
      ProjectFilterDataReset,
      showRoleModel,
      RemoveError,
      sortBy,
      isSortDirDesc,
      roleFields,
      RoleTypes
    };
  }
}
</script>

<style lang="scss">
.b-table-selectable {
  .feather {
    font-size: 1.3rem;
  }
}

</style>

