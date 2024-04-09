<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">

        <!-- card 1 -->
        <b-card>


          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">holiday</p>
            </div>
            <div>

              <div class="d-flex justify-content-between mt-2 mt-md-0">
                <b-button v-if="$route.name == 'holiday'" variant="primary" class="mb-0 ml-md-1 basicButton"
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
                    <b-form-group label="holiday Name" label-for="Name">
                      <b-form-input v-model="holidayFilterData.name" id="basicInput" placeholder="Enter holiday Name" />
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
          <b-table responsive stacked="sm" ref="refholidayListTable" :items="fetchholiday" :fields="fields"
            class="mb-2 staticTable" empty-text="No matching records found"
            :sort-by.sync="sortBy"
            :sort-desc.sync="isSortDirDesc">
            <template #cell(holiday_name)="data">
              <span class="text-nowrap">
                {{ data.item.holiday_name }}
              </span>
            </template>
            <template #cell(date)="data">
              <span class="text-nowrap">
                {{ data.item.date }}
              </span>
            </template>

            <template #cell(Action)="data">
              <div class="icon_flx">
                <b-link v-b-modal.modal-select2 @click="holidayData.id = data.item.id; editholiday(data.item.id)" title="Edit">
                  <feather-icon class="custom-class" icon="EditIcon" size="1.5x" />
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
    <b-modal v-model="showModal" title="Delete Confirmation" @ok="deleteholiday">
      Are you sure you want to delete this holiday?
    </b-modal>
    <!-- Sidebar Ends -->


    <!-- Modal Starts -->
    <!-- select 2 demo -->
    <b-modal :visible="showholidayModel" :title="EditholidayId ? 'Update holiday' : 'Add holiday'" :ok-title="EditholidayId ? 'Update' : 'Add'"
      @ok="handleOk" @show="Onshown" @hidden="resetModal" centered cancel-variant="outline-secondary">

      <b-form @submit.stop.prevent="submitholiday">
        <b-form-group class="myform" label-for="holiday Name">
          <label class="form-label required">holiday Name</label>
          <b-form-input id="holiday Name" @input="RemoveError('holiday_name')" v-model="holidayData.holiday_name"
            placeholder="holiday Name" />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('holiday_name')">
            {{ getErrors("holiday_name") }}
          </div>
        </b-form-group>
        <b-form-group class="myform" label-for="Date">
         <label class="form-label required">Date</label>
                  <b-form-datepicker  v-model="holidayData.date" :state="errors.length > 0 ? false : null"
                    class="form-control" @input="RemoveError('date')" />
                  <small class="text-danger">{{ errors[0] }}</small>
                  <div class="text-danger" v-if="hasErrors('date')">
                    {{ getErrors("date") }}
                  </div>
        </b-form-group>
      </b-form>
    </b-modal>
    <!-- Modal Ends -->

  </div>
</template>

<script>
import {
  BTable, BButton, BFormGroup, BAvatar, BBadge,
  BRow, BCol, BCard, BCollapse, VBToggle, BFormInput, BSidebar, BModal, VBModal, BForm, BPagination, BLink,BFormDatepicker
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
    BLink,BFormDatepicker,
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
        {key: 'holiday_name', sortable: true},
        {key: 'date', sortable: true},
        'Action',
      ],
      EmployeesData: [],
      DesignationOptions: [],
      repotingManager: [],
      BranchData: [],
      holidayMemberData: [],
      EditholidayId: 0,

    }
  },
  mounted() {

  },
  methods: {
    showDeleteConfirmation() {
      this.showModal = true;
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault()
      // Trigger submit handler
      this.submitholiday()
    },
    deleteholiday() {
      axios.delete(`holidays/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'holiday Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
            // text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
          },
        })
      });

    },
    editholiday(id) {
      console.log("id", id)
      this.holidayData.id = id
      this.EditholidayId = id
      console.log("editholiday", id, this.holidayData, this.EditholidayId)
      this.showholidayModel = true
    },
    OpenModel() {
      this.EditholidayId = 0;
      this.showholidayModel = true
    },
    Onshown() {
      if (this.EditholidayId != 0) {
        axios.get(`holidays/${this.EditholidayId}/edit`).then(response => {
          this.holidayData.holiday_name = response.data.data.holiday_name
          this.holidayData.id = response.data.data.id
          this.holidayData.date = response.data.data.date
        })
      } else {
        this.holidayData.holiday_name = ''
        this.holidayData.id = null
        this.holidayData.date = ''
      }
    },
    resetModal() {

      this.showholidayModel = false
      this.errors = [];

    },

  },
  setup(props, { emit }) {
    const toast = useToast();

    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]
    const fetchholiday = (ctx, callback) => {
      axios.get('holidays', {
        params: {
          ...holidayFilterData.value,
          page: currentPage.value,
          perPage: perPage.value,
          sortBy : sortBy.value,
          sortDesc : isSortDirDesc.value,
        }
      }
      ).then(response => {
        const holiday = response.data.data
        // console.log("fetchUser response",task, total,response.data)

        callback(holiday)
        totalEmployees.value = (holiday[0] != null) ? holiday[0].total_row_count : 0;
      })
        .catch(() => {
          toast({
            component: ToastificationContent,
            props: {
              title: 'Error fetching holiday list',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
            },
          })
        })
    }
    const submitholiday = () => {
      console.log("holidayData", holidayData.value)
      if (holidayData.value.id != null) {
        axios.put(`holidays/${holidayData.value.id}`, holidayData.value)
          .then(() => {
            refetchData()
            showholidayModel.value = false

            toast({

              component: ToastificationContent,
              props: {
                title: "holiday Updated Successfully.",
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
        axios.post('holidays', holidayData.value)
          .then(() => {
            refetchData();
            emit('removeBlankholidayError');
            showholidayModel.value = false

            toast({

              component: ToastificationContent,
              props: {
                title: "holiday Created Successfully.",
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
    const blankholidayData = {
      id: null,
      holiday_name: null,
      date: null,
    };
    const errors = ref([]);
    const showholidayModel = ref(false);


    const RemoveError = (errorName) => {
      errors.value[errorName] = " ";
    };


    const holidayData = ref(JSON.parse(JSON.stringify(blankholidayData)));

    const refholidayListTable = ref(null);
    const perPageOptions = [5, 10, 15]

    const refetchData = () => {
      refholidayListTable.value.refresh();
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
      date: "",
    })))
    const holidayFilterData = ref(JSON.parse(JSON.stringify({ FilterData })));
    const ResetFilter = () => {
      holidayFilterData.value = JSON.parse(JSON.stringify({ FilterData }));
      refetchData();
    }
    const dataMeta = computed(() => {
      const localItemsCount = refholidayListTable.value ? refholidayListTable.value.localItems.length : 0
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
      refholidayListTable,
      refetchData,
      fetchholiday,
      currentPage,
      perPage,
      holidayFilterData,
      FilterData,
      ResetFilter,
      dataMeta,
      perPageOptions,
      holidayData,
      blankholidayData,
      submitholiday,
      showholidayModel,
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

