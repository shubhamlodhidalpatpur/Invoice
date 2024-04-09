<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <!-- card 1 -->
        <b-card>
          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Leave Type</p>
            </div>
            <div>
            <b-row>
      <b-col cols="12" md="12" class="marbalign">
        <div class="d-flex align-items-center justify-content-end">
          <b-button
            variant="primary"
            :to="{ name: 'add-leave-type' }"
            class="d-inline-block mr-1"
          >
            <span class="text-nowrap">Add Leave Type</span>
          </b-button>
        </div>
      </b-col>
    </b-row>
            </div>
          </div>
        <b-table
            responsive
            stacked="sm"
            ref="BUListTable"
            :items="fetchLeaveType"
            :fields="fields"
            class="mb-2 staticTable"
            show-empty
            empty-text="No matching records found"
            :sort-by.sync="sortBy"
            :sort-desc.sync="isSortDirDesc"
        >
            <template #cell(name)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.name"
                  >{{ data.item.name }}</span
                >
              </div>
            </template>
            <template #cell(deduction)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.deduction"
                  >{{ data.item.deduction }}</span
                >
              </div>
            </template>
            <template #cell(allotment)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.allotment"
                  >{{ data.item.allotment }}</span
                >
              </div>
            </template>
                <template #cell(allotment_type)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.allotment_type"
                  >{{ data.item.allotment_type }}</span
                >
              </div>
            </template>
                <template #cell(max_allotment_count)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.max_allotment_count"
                  >{{ data.item.max_allotment_count }}</span
                >
              </div>
            </template>
             <template #cell(action)="data">
              <b-link 
                title="Leave Type Edit"
                :to="{
                  name: 'edit-leave-type',
                  params: { id: data.item.id },
                }"
                ><edit-icon size="1.5x" class="custom-class"></edit-icon>
              </b-link>
              <b-link
                :to="{ name: 'admin-view-employee', params: { id: data.item.id } }"
                title="View"
                >
                <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
              </b-link>
              <b-link
                @click="showDeleteConfirmation(); DeleteId = data.item.id"
                title="Delete"
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
          <b-row v-if="totalBUs!=0">
            
          <b-col cols="12" md="4" class="d-flex align-items-center justify-content-center justify-content-md-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="perPage" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="perPageOptions"
              :clearable="false" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>
          <b-col
            cols="12"
            sm="4"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-start
            "
          >
            <span class="text-muted"
              >Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
              {{ dataMeta.of }} entries</span
            >
          </b-col>
          <b-col
            cols="12"
            sm="4"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-end
            "
          >

        <b-pagination
              v-model="currentPage"
              :total-rows="totalBUs"
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
      <b-modal v-model="showModal" title="Delete Confirmation" @ok="deleteleaveType">
      Are you sure you want to delete this Leave Type?
    </b-modal>
  </div>
</template>

<script>
import {
  BTable,
  BButton,
  BFormGroup,
  BAvatar,
  BBadge,
  BLink,
  BRow,
  BCol,
  BCard,
  BCollapse,
  VBToggle,
  BFormInput,
  BSidebar,
  BModal,
  VBModal,
  BForm,
  BPagination,BSpinner,BOverlay
} from "bootstrap-vue";
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
import axios from "@axios";
import { ref,computed,watch, } from "@vue/composition-api";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { EditIcon } from "vue-feather-icons";

export default {
  components: {
    BTable,
    BButton,
    BFormGroup,
    BAvatar,
    BBadge,
    vSelect,
    BRow,
    BCol,
    BCard,
    BCollapse,
    BFormInput,
    BModal,
    VBModal,
    BForm,
    BLink,
    BSidebar,
    BPagination,BSpinner,BOverlay,EditIcon
  },
  directives: {
    "b-toggle": VBToggle,
    "b-modal": VBModal,
    Ripple,
  },
  data() {
    return {
      showModal: false,
      DeleteId:0,
      UserData: [],
      hod: "",
      option: [""],
      fields: [{key: "name", sortable: true},{key: "deduction", sortable: true},{key: "laps", sortable: true},{key: "max_allotment_count", sortable: true},{key: "laps", sortable: true},'action'],
      EditId: 0,
      CheckInButton:false,
      CheckOutButton:false,

    };
  },

  methods: {
     showDeleteConfirmation() {
      this.showModal = true;
    },
     deleteleaveType() {
      axios.delete(`LeaveTypes/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Leave Type Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
            // text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
          },
        })
      });

    },
    
      
  },

  mounted() {
    axios.get("CHeckTodayAttendance").then((response) => {
        this.CheckOutButton=true;
        if(response.data.data.checkin_time){
          this.CheckInButton=true;
          this.CheckOutButton=false;
        }
        if(response.data.data.checkout_time){
          this.CheckOutButton=true;
          this.CheckInButton=false;
        }
    });
  },
  setup(props, { emit }) {

    const toast = useToast();
    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]
    const fetchLeaveType = (ctx, callback) => {
     axios.get('LeaveTypes', {
                 params: {
                    ...SearchFilter.value,
                    page:currentPage.value,
                    perPage:perPage.value,
                    sortBy : sortBy.value,
                    sortDesc : isSortDirDesc.value,
                }}
       ) .then((response) => {
         const  BU = response.data.data
       // console.log("fetchUser response",task, total,response.data)

        callback(BU)
        totalBUs.value = (BU[0] != null )? BU[0].total_row_count : 0;
        })
        .catch((response) => {});
    };

    const BUFilterDataSubmit = () => {
      refetchData();
    };
      const resetBUFilter = () => {
      SearchFilter.value = JSON.parse(JSON.stringify(SearchFilter));
      refetchData();
    };
     const refetchData = () => {
        BUListTable.value.refresh();
      }
    const totalBUs = ref(0)

    const RemoveError = (errorName) => {
    errors.value[errorName]= " ";
   };
    

    const BUListTable = ref(null);
      const perPageOptions = [5, 10, 15]

   
    const FilterData = {
      id: null,
      name: null,
      hod: null,
    };

   const SearchFilter =ref(JSON.parse(JSON.stringify({ FilterData }))) ;
       const ResetFilter=()=>{
         employeeFilterData.value= ref(JSON.parse(JSON.stringify({FilterData}))) ;
         refetchData();
       }


     const currentPage = ref(1);
      const perPage = ref(10);


    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };

    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    }; 


    const errors = ref([]);
    const showBUModal = ref(false);

     const dataMeta = computed(() => {
    const localItemsCount = BUListTable.value ? BUListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalBUs.value,
    }
  })

   watch([currentPage, perPage], () => {
    refetchData()
  })

    return {
      hasErrors,
      getErrors,
      errors,
      onsubmit,
      SearchFilter,
      refetchData,
      BUFilterDataSubmit,
      resetBUFilter,
      BUListTable,
      fetchLeaveType,
      totalBUs,
      dataMeta,
      FilterData,
      perPageOptions,
      currentPage,
      perPage,
      showBUModal,
      RemoveError,
      sortBy,
      isSortDirDesc
    };
  },
};
</script>

<style lang="scss">
.b-table-selectable {
  .feather {
    font-size: 1.3rem;
  }
}

</style>
