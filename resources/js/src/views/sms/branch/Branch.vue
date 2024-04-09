<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">

        <!-- card 1 -->
        <b-card>


          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Branch</p>
            </div>
            <div>

              <div class="d-flex justify-content-between mt-2 mt-md-0">
                <b-button v-if="$route.name == 'branch'" variant="primary" class="mb-0 ml-md-1 basicButton"
                  v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-toggle.collapse-1>
                  Filter
                </b-button>
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mb-0 ml-1 basicButton"
                  @click="OpenModel()">
                  Add
                </b-button>
              </div>
            </div>
          </div>


          <div>
            <b-collapse id="collapse-1" class="my-2">
              <b-card class="filterBox mb-0">
                <b-row>
                  <b-col md="6" xl="4" class="">

                    <!-- basic -->
                    <b-form-group label="branch Name" label-for="Name">
                      <b-form-input v-model="branchFilterData.name" id="basicInput" placeholder="Enter branch Name" />
                    </b-form-group>
                  </b-col>
                  <b-col md="6" xl="4" class="">

                    <!-- basic -->
                    <b-form-group label="Department" label-for="Department" class="branchFilterDepartment">
                      <v-select v-model="branchFilterData.BU" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="BUData" :reduce="(val) => val.value" :clearable="true" input-id="status"
                        placeholder="Select Department" />
                    </b-form-group>
                  </b-col>


                </b-row>
                <b-row>
                  <b-col md="6" xl="4">

                    <b-button @click="EmployeeFilterDataSubmit()" variant="primary mt-1 w-100">
                      Search
                    </b-button>

                  </b-col>
                  <b-col md="6" xl="4">

                    <b-button @click="ResetFilter()" variant="primary mt-1 w-100">
                      Reset
                    </b-button>

                  </b-col>
                </b-row>

              </b-card>
            </b-collapse>
          </div>
          <!-- <div class="mar10">
        <b-row>
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="perPage" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="perPageOptions"
              :clearable="true" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>
        </b-row>
      </div>       -->
          <b-table responsive stacked="sm" ref="refbranchListTable" :items="fetchbranch" :fields="fields"
            class="mb-2 staticTable" empty-text="No matching records found"
            :sort-by.sync="sortBy"
            :sort-desc.sync="isSortDirDesc">
            <template #cell(Name)="data">
              <span class="text-nowrap">
                {{ data.item.title }}
              </span>
            </template>
            <template #cell(BU)="data">
              <span class="text-nowrap">
                {{ data.item.bu_name }}
              </span>
            </template>

            <template #cell(Action)="data">
              <div class="icon_flx">
                <b-link v-b-modal.modal-select2 @click="branchData.id = data.item.id; editbranch(data.item.id)" title="Edit">
                  <feather-icon class="custom-class" icon="EditIcon" size="1.5x" />
                </b-link>


                <b-link :to="{ name: 'branch-view', params: { id: data.item.id } }" title="View"
                >
                  <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
                </b-link>
                <b-link @click="showDeleteConfirmation(); DeleteId = data.item.id" title="Delete"
                  >
                  <feather-icon class="custom-class" icon="TrashIcon" size="1.5x" />
                </b-link>
              </div>
            </template>
          </b-table>
          <div class="mx-2 mb-2">
            <div class="text-center"><span v-if="totalEmployees == 0">No Records Found</span></div>
            <b-row v-if="totalEmployees != 0">
              <b-col cols="12" md="4"
                class="d-flex align-items-center justify-content-center justify-content-md-start mb-1 mb-md-0">
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
              <b-col cols="12" sm="4" class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-end
            ">
                <b-pagination v-model="currentPage" :total-rows="totalEmployees" :per-page="perPage" first-number
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
    <b-modal v-model="showModal" title="Delete Confirmation" @ok="deletebranch">
      Are you sure you want to delete this branch?
    </b-modal>
    <!-- Sidebar Ends -->


    <!-- Modal Starts -->
    <!-- select 2 demo -->
    <b-modal :visible="showbranchModel" :title="EditbranchId ? 'Update branch' : 'Add branch'" :ok-title="EditbranchId ? 'Update' : 'Add'"
      @ok="handleOk" @show="Onshown" @hidden="resetModal" centered cancel-variant="outline-secondary">

      <b-form-group class="myform" label-for="BU">
        <label class="form-label required">BU</label>
        <v-select v-model="branchData.BU" @input="RemoveError('BU')"
          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="BUData" :reduce="(val) => val.value"
          :clearable="true" input-id="status" placeholder="Select BU" />
        <small class="text-danger">{{ errors[0] }}</small>
        <div class="text-danger" v-if="hasErrors('BU')">
          {{ getErrors("BU") }}
        </div>
      </b-form-group>

      <b-form @submit.stop.prevent="submitbranch">
        <b-form-group class="myform" label-for="branch Name">
          <label class="form-label required">branch Name</label>
          <b-form-input id="branch Name" @input="RemoveError('branch_name')" v-model="branchData.branch_name"
            placeholder="branch Name" />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('branch_name')">
            {{ getErrors("branch_name") }}
          </div>
        </b-form-group>
        <!-- <b-form-group
          label="branch Members"
          label-for="branch Members"
        >
        <v-select
              v-model="branchData.branch_members"
              multiple
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="branchMemberData"
              :reduce="(val) => val.value"
              :clearable="true"
              input-id="status"
          />
          <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('branch_members')">
                  {{ getErrors("branch_members") }}
                </div>
        </b-form-group> -->
      </b-form>
    </b-modal>
    <!-- Modal Ends -->

  </div>
</template>

<script>
import {
  BTable, BButton, BFormGroup, BAvatar, BBadge,
  BRow, BCol, BCard, BCollapse, VBToggle, BFormInput, BSidebar, BModal, VBModal, BForm, BPagination, BLink
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import Ripple from 'vue-ripple-directive'
import { ref, watch, computed } from "@vue/composition-api";
import axios from "@axios";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BTable,
    BButton,
    BFormGroup,
    BAvatar,
    BBadge,
    vSelect,
    BRow, BCol, BCard, BCollapse, BFormInput, BModal, VBModal, BForm,
    BSidebar,
    BPagination,
    BLink,
  },
  directives: {
    'b-toggle': VBToggle,
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      showModal: false,
      DeleteId: 0,
      selected: 'USA',
      option: ['USA', 'Canada', 'Maxico'],
      fields: [
        {key: 'Name', sortable: true},
        {key: 'BU', sortable: true},
        'Action',
      ],
      EmployeesData: [],
      DesignationOptions: [],
      repotingManager: [],
      BUData: [],
      branchMemberData: [],
      EditbranchId: 0,

    }
  },
  mounted() {
    axios.get('getBUData').then(response => {
      this.BUData = response.data.BU
    })

  },
  methods: {
    showDeleteConfirmation() {
      this.showModal = true;
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.submitbranch()
    },
    deletebranch() {
      axios.delete(`branch/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'branch Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
            // text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
          },
        })
      });

    },
    editbranch(id) {
      console.log("id", id)
      this.branchData.id = id
      this.EditbranchId = id
      console.log("editbranch", id, this.branchData, this.EditbranchId)
      this.showbranchModel = true
    },
    OpenModel() {
      this.EditbranchId = 0;
      this.showbranchModel = true
    },
    Onshown() {
      axios.get('getBUData').then(response => {
        this.BUData = response.data.BU
      })
      console.log("this.EditbranchId", this.EditbranchId, this.branchData)
      if (this.EditbranchId != 0) {
        axios.get(`branchedit/${this.EditbranchId}`).then(response => {
          this.branchData.branch_name = response.data.data.title
          this.branchData.id = response.data.data.id
          // this.branchData.reporting_manager = response.data.data.reporting_manage
          this.branchData.BU = response.data.data.bu_id
          // this.branchData.branch_members = response.data.data.branch_members
        })
      } else {
        this.branchData.branch_name = ''
        this.branchData.id = null
        // this.branchData.reporting_manager = ''
        this.branchData.BU = ''
        // this.branchData.branch_members = ''
      }
    },
    resetModal() {

      this.showbranchModel = false
      this.errors = [];

    },

  },
  setup(props, { emit }) {
    const toast = useToast();

    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]
    const fetchbranch = (ctx, callback) => {
      axios.get('fetchbranch', {
        params: {
          ...branchFilterData.value,
          page: currentPage.value,
          perPage: perPage.value,
          sortBy : sortBy.value,
          sortDesc : isSortDirDesc.value,
        }
      }
      ).then(response => {
        const branch = response.data.data
        // console.log("fetchUser response",task, total,response.data)

        callback(branch)
        totalEmployees.value = (branch[0] != null) ? branch[0].total_row_count : 0;
      })
        .catch(() => {
          toast({
            component: ToastificationContent,
            props: {
              title: 'Error fetching branch list',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
        })
    }
    const submitbranch = () => {
      console.log("branchData", branchData.value)
      if (branchData.value.id != null) {
        axios.post(`branchUpdate/${branchData.value.id}`, branchData.value)
          .then(() => {
            refetchData()
            showbranchModel.value = false

            toast({

              component: ToastificationContent,
              props: {
                title: "branch Updated Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
          })
          .catch((error) => {
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            }
            else {
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
        axios.post('addbranch', branchData.value)
          .then(() => {
            refetchData();
            emit('removeBlankbranchError');
            showbranchModel.value = false

            toast({

              component: ToastificationContent,
              props: {
                title: "branch Created Successfully.",
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
      }
    }
    const EmployeeFilterDataSubmit = () => {
      refetchData();
    };
    const blankbranchData = {
      id: null,
      branch_name: null,
      BU: null,
    };
    const errors = ref([]);
    const showbranchModel = ref(false);


    const RemoveError = (errorName) => {
      errors.value[errorName] = " ";
    };


    const branchData = ref(JSON.parse(JSON.stringify(blankbranchData)));

    const refbranchListTable = ref(null);
    const perPageOptions = [5, 10, 15]

    const refetchData = () => {
      refbranchListTable.value.refresh();
    }
    const totalClients = ref(0)

    const currentPage = ref(1);
    const perPage = ref(10);
    const totalEmployees = ref(0)
    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };

    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };
    const FilterData = ref(JSON.parse(JSON.stringify({
      id: null,
      name: "",
      BU: "",
    })))
    const branchFilterData = ref(JSON.parse(JSON.stringify({ FilterData })));
    const ResetFilter = () => {
      branchFilterData.value = JSON.parse(JSON.stringify({ FilterData }));
      refetchData();
    }
    const dataMeta = computed(() => {
      const localItemsCount = refbranchListTable.value ? refbranchListTable.value.localItems.length : 0
      return {
        from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
        to: perPage.value * (currentPage.value - 1) + localItemsCount,
        of: totalEmployees.value,
      }
    })
    watch([currentPage, perPage], () => {
      refetchData()
    })

    return {
      RemoveError,
      hasErrors,
      getErrors,
      errors,
      refbranchListTable,
      refetchData,
      fetchbranch,
      currentPage,
      perPage,
      branchFilterData,
      FilterData,
      ResetFilter,
      dataMeta,
      perPageOptions,
      branchData,
      blankbranchData,
      submitbranch,
      showbranchModel,
      totalEmployees,
      EmployeeFilterDataSubmit,
      sortBy,
      isSortDirDesc,
    }

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

