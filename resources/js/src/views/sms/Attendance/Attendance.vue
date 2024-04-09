<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <!-- card 1 -->
        <b-card>
          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Attendace</p>
            </div>
            <div>
              <div class="d-flex justify-content-between mt-2 mt-md-0">
            <b-button
              variant="primary"
              class="mb-0 ml-md-1 basicButton"
              v-ripple.400="'rgba(113, 102, 240, 0.15)'"
              :disabled="CheckInButton"
              @click="checkin()"
            >
              Check In
            </b-button>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-0 ml-1 basicButton"
               :disabled="CheckOutButton"
               @click="checkout()"
              >
                Check Out
              </b-button>
            </div>
            </div>
          </div>
        <b-table
            responsive
            stacked="sm"
            ref="BUListTable"
            :items="fetchAttendance"
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
                  >{{ data.item.user_name }}</span
                >
              </div>
            </template>
            <template #cell(check_in)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.checkin_time"
                  >{{ data.item.checkin_time }}</span
                >
              </div>
            </template>
            <template #cell(check_out)="data">
              <div class="text-nowrap">
                <span
                  class="align-text-top text-capitalize"
                  :title="data.item.name"
                  >{{ data.item.checkout_time }}</span
                >
              </div>
            </template>

            <template #cell(action)="data">
              <!-- <div class="icon_flx">
                <b-link @click="editBU(data.item.id)" title="Edit"  >
                  <feather-icon
                    class="custom-class"
                    icon="EditIcon"
                    size="1.5x"
                  />
                </b-link>

                <b-link :to="{name: 'BU-view', params: { id: data.item.id } }" title="View" v-if="$can('do', 'view_BU')">
                  <feather-icon
                    class="custom-class"
                    icon="EyeIcon"
                    size="1.5x"
                  />
                </b-link>
                 <b-link @click="showDeleteConfirmation(); DeleteId=data.item.id" title="Delete" v-if="$can('do', 'view_BU')">
                  <feather-icon
                    class="custom-class"
                    icon="TrashIcon"
                    size="1.5x"
                  />
                </b-link>
              </div> -->
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

     <b-sidebar id="sidebar-right" bg-variant="white" right backdrop shadow>
   
    </b-sidebar>
    <b-modal v-model="showModal" title="Delete Confirmation" @ok="deleteBU">
      Are you sure you want to delete this BU?
    </b-modal>
  

   
     <!-- Modal Starts -->
    <b-modal
      :visible="showBUModal"
      :title="EditId ? 'Update BU' : 'Add BU'"
      @show="BUShowon()"
      @hidden="resetModal"
      @ok="handleOk"
      centered
      cancel-variant="outline-secondary"
    >
   
      <b-form ref="form" @submit.stop.prevent="handleSubmit" class="myform">
        <b-form>
          <label class="form-label required">Name</label>
          <b-form-group  label-for="name">
            <b-form-input
              id="name"
               @input="RemoveError('name')"
              v-model="BUData.name"
              placeholder="Enter BU name"
              rules="required"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('name')">
                  {{ getErrors("name") }}
                </div>
          </b-form-group>
        
        </b-form>
      </b-form>
    </b-modal>
    <!-- Modal Ends -->
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
    BPagination,BSpinner,BOverlay
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
      fields: [{key: "name", sortable: true},{key: "check_in", sortable: true},{key: "check_out", sortable: true}, "action"],
      EditId: 0,
      CheckInButton:false,
      CheckOutButton:false,

    };
  },

  methods: {
    showDeleteConfirmation() {
      this.showModal = true;
    },
      handleOk(bvModalEvent) {
        // Prevent modal from closing
        bvModalEvent.preventDefault()
        // Trigger submit handler
        this.handleSubmit()
      },
    resetModal() {
      this.name = "";
      // this.hod = null;
        this.showBUModal=false
         this.errors=[];
     
    },
    checkin() {
        axios.post(`CheckinAttendance`).then((response) => {
        this.CheckInButton=true; 
        this.CheckOutButton=false;   
        this.refetchData()
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Checkin Successfully',
            icon: 'CheckIcon',
            variant: 'success',
          },
        })
      });
     
    },
    checkout() {
        axios.post(`CheckOutAttendance`).then((response) => {
        this.CheckOutButton=true;  
        this.CheckInButton=false; 
        this.refetchData()
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Check Out Successfully',
            icon: 'CheckIcon',
            variant: 'success',
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
    const fetchAttendance = (ctx, callback) => {
     axios.get('Attendances', {
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

    const handleSubmit = () => {
      if (BUData.value.id != null) {
       axios .post(`BU/update/${BUData.value.id}`, BUData.value)
          .then((response) => {
            refetchData()
              showBUModal.value=false
             toast({
               
              component: ToastificationContent,
              props: {
                title: "BU Updated Successfully.",
                icon: "CheckIcon",
                variant: "success",
              },
            });
         
          })
          .catch((error) => {
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            } 
        
          });
      } else {
        axios
          .post("addBU", BUData.value)
          .then((response) => {
              refetchData();
              showBUModal.value=false
              emit('removeBlankBUError');
              toast({
                  
                  component: ToastificationContent,
                  props: {
                    title: "BU Created Successfully.",
                    icon: "CheckIcon",
                    variant: "success",
                  },
                });
              
          })
          .catch((error) => {
            if (error.response.data.code == 422) {
              errors.value = error.response.data.errors;
            } 
              });
        
        
      }
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

    const blankBUData = {
      id: null,
      name: null,
      hod: null,
    };

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

    const BUData = ref(JSON.parse(JSON.stringify(blankBUData)));
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
      handleSubmit,
      BUData,
      refetchData,
      blankBUData,
      BUFilterDataSubmit,
      resetBUFilter,
      BUListTable,
      fetchAttendance,
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
