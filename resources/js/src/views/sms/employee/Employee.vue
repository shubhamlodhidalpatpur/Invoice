<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <b-card>
          <div
            class="d-md-flex justify-content-between align-items-center my-1"
          >
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Employees</p>
            </div>
            <div>
              <div class="d-flex justify-content-between mt-2 mt-md-0">
                <b-button
                  variant="primary"
                  class="mb-0 ml-md-1 basicButton"
                  v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                  v-b-toggle.collapse-1
                >
                  Filter
                </b-button>
                  <b-button v-if="$can('do', 'add_employee')"
                    variant="primary"
                    :to="{ name: 'employee-add' }"
                    class="d-inline-block mr-1"
                  >
                    <span  class="text-nowrap">Add Employee</span>
                  </b-button>
              </div>
            </div>
          </div>

          <div>
            <b-collapse id="collapse-1" class="my-2">
              <b-card class="filterBox mb-0">
                <b-row>
                  <b-col md="6" xl="4" class="">
                    <b-form-group label="Employee Name" label-for="name">
                      <b-form-input
                        id="name"
                        placeholder="Enter Employee Name"
                        v-model="SearchFilter.name"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>

                <b-col cols="12" md="12">
                  <b-button
                    variant="primary mt-1"
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    @click="fetchEmployeesList"
                  >
                    Search
                  </b-button>
                  <b-button
                    variant="primary mt-1"
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    @click="refetchData()"
                  >
                    <span class="text-nowrap">Reset</span>
                  </b-button>
                </b-col>
              </b-card>
            </b-collapse>
          </div>

          <b-table
            ref="refEmployeesListTable"
            class="position-relative tableFixHead"
            :items="EmployeesListTableData"
            responsive
            stacked="sm"
            primary-key="id"
            :fields="EmployeesTableColumn"
            show-empty
            empty-text="No matching records found"
            :per-page="perPage"
            :current-page="currentPage"
          >
          <template #cell(sr_no)="data">
            <div class="text-nowrap">
              {{ data.index + 1 }}
            </div>
          </template>
          
          <!-- 
            <template v-for="(field, index) in EmployeesTableColumn" v-slot:[`cell(${field.label})`]="{ item }" >
              <div class="text-nowrap" :key="index">
                <span class="align-text-top text-capitalize">{{ item[field.label] }}</span>
              </div>
            </template>
          -->

            <template #cell(action)="data">
              <b-link 
                title="Employees Edit"
                :to="{
                  name: 'employee-edit',
                  params: { id: encodeBase64(data.item.user_id) },
                }"
                ><edit-icon size="1.5x" class="custom-class"></edit-icon>
              </b-link>
              <b-link
                :to="{ name: 'admin-view-employee', params: { id: encodeBase64(data.item.user_id) } }"
                title="View"
                >
                <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
              </b-link>
              <b-link
                @click="deleteUser(data.item.user_id)"
                title="Delete"
                v-if="$can('do', 'delete_employee')"
              >
                <feather-icon
                  class="custom-class"
                  icon="TrashIcon"
                  size="1.5x"
                />
              </b-link>
            </template> 
          </b-table>

          <div class="mx-2 mb-2">
            <b-row>
              <b-col
                cols="12"
                md="4"
                class="d-flex align-items-center justify-content-center justify-content-md-start mb-1 mb-md-0"
              >
                <label>Show</label>
                <v-select
                  v-model="perPage"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="perPageOptions"
                  :clearable="false"
                  class="per-page-selector d-inline-block mx-50"
                />
                <label>entries</label>
              </b-col>
              <b-col
                cols="12"
                sm="4"
                class="d-flex align-items-center justify-content-center justify-content-sm-start"
              >
              Showing {{ rangeStart }} to {{ rangeEnd }} of {{ totalrows }} entries
               
              </b-col>
              <b-col
                cols="12"
                sm="4"
                class="d-flex align-items-center justify-content-center justify-content-sm-end"
              >
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalrows"
                  :per-page="perPage"
                  first-number
                  last-number
                  class="mb-0 mt-1 mt-sm-0"
                  prev-class="prev-item"
                  next-class="next-item"
                >
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
  </div>
</template>
<script>
import {
  BRow,
  BCol,
  BButton,
  BTable,
  BTab,
  BTabs,
  BCard,
  BSpinner,
  BOverlay,
  BLink,
  BCollapse,
  BFormGroup,
  BFormInput,
  VBToggle
} from "bootstrap-vue";

import vSelect from "vue-select";
import store from "@/store";
import { ref, onUnmounted, computed } from "@vue/composition-api";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import axios from "@axios";
import { useRouter } from "@core/utils/utils";
import { EditIcon } from "vue-feather-icons";
import Ripple from "vue-ripple-directive";
export default {
  components: {
    BRow,
    BCol,
    BButton,
    BTable,
    BTab,
    BTabs,
    BCard,
    BSpinner,
    BOverlay,
    EditIcon,
    BLink,
    BCollapse,
    BFormGroup,
    BFormInput,
    ToastificationContent,vSelect
  },
  directives: {
    "b-toggle": VBToggle,
    Ripple,
  },
  data() {
    return {
      // EmployeesTableColumn: [
      //   { key: "sr_no" },
      //   { key: "name", sortable: true },
      //   { key: "profile", sortable: true },
      //   // { key: "department", sortable: true },
      //   { key: "employee_role", sortable: true },
      //   { key: "action" },
      // ],
      index: 0,
      perPage: 10,
      currentPage: 1,
    };
  },
  computed: {
    rangeStart() {
      if (this.totalrows === 0) {
        return 0;
      } else {
        return (this.currentPage - 1) * this.perPage + 1;
      }
    },
    rangeEnd() {
      if (this.currentPage * this.perPage >= this.totalrows) {
        return this.totalrows;
      } else {
        return this.currentPage * this.perPage;
      }
    },
  },
  mounted() {},
  setup() {
    const totalEmployees = ref(0);

    const encodeBase64 = (data) => {
        return data? Buffer.from(data.toString()).toString("base64") : null;
    }; 
    const decodeBase64 = (data) => {
        return data? Buffer.from(data.toString(), "base64").toString("ascii") : null;
    };

    const fetchEmployeesList = (ctx, callback) => {
      axios.get("users", {
          params: {
            profile: encodeBase64(SearchFilter.value.profile),
            name: encodeBase64(SearchFilter.value.name),
            page: currentPage.value,
            perPage: perPage.value,
          },
        }).then((response) => {
          EmployeesListTableData.value = response.data.table_data
          EmployeesTableColumn.value = response.data.table_column
          // totalrows.value = response.data.responseData.employees.total;
          // EmployeesListTableData.value = response.data.responseData.employees.data;
          // totalEmployees.value = fetchEmployeesList[0] != null ? fetchEmployeesList[0].total_row_count : 0;
        // callback(EmployeesListTableData.value);
      });
    };
    const toast = useToast();
    const totalrows = ref(0);
    const perPageOptions = [5, 10, 15];
    const refEmployeesListTable = ref(null);
    const EmployeesListTableData = ref([]);
    const EmployeesTableColumn = ref([]);
    const userData =  JSON.parse(localStorage.getItem('userData'));
    const FilterData = {
      profile: (userData.isSuperAdmin)? null : (userData.organization_id? userData.organization_id : userData.id),
      name: null,
    };

    const SearchFilter = ref(JSON.parse(JSON.stringify(FilterData)));
    const currentPage = ref(1);
    const perPage = ref(10);
    const dataMeta = computed(() => {
      const localItemsCount = refEmployeesListTable.value
        ? refEmployeesListTable.value.localItems.length
        : 0;

        console.log("localItemsCount", localItemsCount);
      return {
        from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
        to: perPage.value * (currentPage.value - 1) + localItemsCount,
        of: totalEmployees.value,
      };
    });

    const refetchData = () => {
      SearchFilter.value = JSON.parse(JSON.stringify(FilterData));
      fetchEmployeesList()
    };
    const deleteUser = (id) => {
      axios.post(`user/delete/${id}`).then((response) => {
        refetchData();
        toast({
          component: ToastificationContent,
          props: {
            title: "User Deleted Successfully.",
            icon: "TrashIcon",
            variant: "danger",
          },
        });
      });
    };
    fetchEmployeesList()
    return {
      refEmployeesListTable,
      deleteUser,
      refetchData,
      fetchEmployeesList,
      SearchFilter,
      dataMeta,
      totalEmployees,
      currentPage,
      perPage,
      EmployeesListTableData,
      encodeBase64,
      decodeBase64,
      totalrows,
      perPageOptions,
      EmployeesTableColumn
    };
  },
};
</script>
<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>