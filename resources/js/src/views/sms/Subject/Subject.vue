<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <!-- card 1 -->
        <b-card>
          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Subject</p>
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
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-0 ml-1 basicButton"
                
                @click="openModel()"
              >
                Add
              </b-button>
            </div>
            </div>
          </div>

          <div>
            <b-collapse id="collapse-1" class="my-2">
              <b-card class="filterBox mb-0">
                <b-row >
                  <b-col md="6" xl="4" class="">
                    <!-- basic -->
                    <b-form-group label="Subject Name" label-for="name">
                      <b-form-input
                        id="name"
                        placeholder="Enter Subject Name"
                        v-model="SearchFilter.name"
                      />
                    </b-form-group>
                  </b-col>
                 
                   <b-col
                    md="6"
                    xl="4"
                    class=""
                  >
                   <!-- <b-form-group
                      label="Hod"
                      label-for="Hod"
                    >
                       <v-select
                            v-model="SearchFilter.hod"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="UserData"
                            :reduce="(val) => val.value"
                            :clearable="true"
                            input-id="hod"
                        />
                    </b-form-group> -->
                   </b-col>

                </b-row>

                <b-col cols="12" md="12">
                  <b-button
                    variant="primary mt-1"
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    @click="BUFilterDataSubmit"
                  >
                    Search
                  </b-button>
                  <b-button
                    variant="primary mt-1"
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    @click="resetBUFilter()"
                  >
                    <span class="text-nowrap">Reset</span>
                  </b-button>
                </b-col>
              </b-card>
            </b-collapse>
          </div>

           <!-- <div class="mar10">
        <b-row>
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="perPage" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="perPageOptions"
              :clearable="false" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>
        </b-row>
      </div>       -->


          <b-table
            responsive
            stacked="sm"
            ref="BUListTable"
            :items="fetchSubject"
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

            <template #cell(action)="data">
              <div class="icon_flx">
                <b-link @click="editSubject(data.item.id)" title="Edit"  >
                  <feather-icon
                    class="custom-class"
                    icon="EditIcon"
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
              </div>
            </template>
           </b-table>
           
          <div class="mx-2 mb-2">
          <div class="text-center"><span v-if="totalBUs==0">No Records Found</span></div>
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
      Are you sure you want to delete this Subject?
    </b-modal>
  

   
     <!-- Modal Starts -->
    <b-modal
      :visible="ShowSubjectModal"
      :title="EditId ? 'Update Subject' : 'Add Subject'"
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
              v-model="SubjectData.name"
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
      fields: [{key: "name", sortable: true}, "action"],
      EditId: 0,
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
        this.ShowSubjectModal=false
         this.errors=[];
     
    },
    openModel() {
      console.log("hello");
      this.EditId = 0;
      this.ShowSubjectModal=true
      },
    editSubject(id) {
      this.EditId = id;
      this.ShowSubjectModal=true
    },
    deleteBU(){
      axios.delete(`Subject/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Subject Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
          },
        })
      });
      
    },
    BUShowon() {
      if (this.EditId != 0) {
        axios.get(`Subject/${this.EditId}/edit`).then((response) => {
          this.SubjectData.name = response.data.data.name;
          this.SubjectData.id = response.data.data.id;
        });
      } else {
        this.SubjectData.name = "";
        this.SubjectData.id = null;        
      }
    },
   
  },

  mounted() {
    // axios.get("userData").then((response) => {
    //   this.UserData = response.data;
    // });
  },
  setup(props, { emit }) {

    const toast = useToast();
    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]
    const fetchSubject = (ctx, callback) => {
     axios.get('Subject', {
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
      if (SubjectData.value.id != null) {
       axios.put(`Subject/${SubjectData.value.id}`, SubjectData.value)
          .then((response) => {
            refetchData()
              ShowSubjectModal.value=false
             toast({
               
              component: ToastificationContent,
              props: {
                title: "Subject Updated Successfully.",
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
          .post("Subject", SubjectData.value)
          .then((response) => {
              refetchData();
              ShowSubjectModal.value=false
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

    const blankSubjectData = {
      id: null,
      name: null,
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
    const ShowSubjectModal = ref(false);

    const SubjectData = ref(JSON.parse(JSON.stringify(blankSubjectData)));
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
      SubjectData,
      refetchData,
      blankSubjectData,
      BUFilterDataSubmit,
      resetBUFilter,
      BUListTable,
      fetchSubject,
      totalBUs,
      dataMeta,
      FilterData,
      perPageOptions,
      currentPage,
      perPage,
      ShowSubjectModal,
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
