<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <!-- card 1 -->
        <b-card>
          <div class="d-md-flex justify-content-between align-items-center my-1">
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">{{$route.name}}</p>
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
            variant="primary"
            :to="{
                    name: 'add_module',
                    params: { module_name: $route.name },
                }"
            class="d-inline-block mr-1"
          >
            <span class="text-nowrap">Add</span>
          </b-button>
        </div>
            </div>
          </div>
                 <div>
            <b-collapse id="collapse-1" class="my-2">
              <b-card class="filterBox mb-0">
                   <b-row>
              <b-col md="3" class="mt-2" v-for="field in FilterFields" :key="field.id">
                <label class="form-label required d-block">{{field.title}}</label>
                  <validation-provider #default="{ errors }" :name="field.title">
                    
                    <div v-if="field.field_type_slug == 'string'">
                    <b-form-input
                      v-model="field.value"
                       @input="RemoveError(field.slug)"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                    />
                        <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'bigInteger'">
                    <b-form-input
                      type="number"
                      @input="RemoveError(field.slug)"
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                    />
                        <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'float'">
                    <b-form-input
                      type="number"
                      step=".01"
                      v-model="field.value"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Name"
                      autocomplete="off"
                      @input="RemoveError(field.slug)"
                    />
                        <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'date'">
                    <b-form-datepicker
                      v-model="field.value"
                      class="mb-1"
                      @input="RemoveError(field.slug)"
                    />
                     <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'enum'">
                    <v-select
                      :options="field.field_value.split(',')"
                      v-model="field.value"
                      @input="RemoveError(field.slug)"
                      class="mb-1"
                    />
                     <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'file'">
                    <b-form-file
                      v-model="field.value"
                      @input="RemoveError(field.slug)"
                      class="mb-1"
                    />
                     <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'boolean'">
                      <b-form-checkbox
                        checked="true"
                        class="custom-control-success"
                        @input="RemoveError(field.slug)"
                        name="check-button"
                        switch
                        v-model="field.value"
                      />
                       <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                    <div v-if="field.field_type_slug == 'datetime'">
                      <flat-pickr
                        v-model="field.value"
                        class="form-control"
                        @input="RemoveError(field.slug)"
                        :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
                      />
                       <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors(field.slug)">
                            {{ getErrors(field.slug) }}
                        </div>
                    </div>
                  </validation-provider>
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
            <template #cell(sr_no)="data">
              <span class="text-nowrap">
                {{ data.index+1 }}
              </span>
            </template>
             <template #cell(action)="data">
              <b-link 
                title="Edit"
                :to="{
                  name: 'Edit',
                  params: { id: data.item.id,module_name:$route.name},
                }"  
                ><edit-icon size="1.5x" class="custom-class"></edit-icon>
              </b-link>
              <b-link
                :to="{ name: 'View',params: { id: data.item.id,module_name:$route.name} }"
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
      Are you sure you want to delete ?
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
  BSidebar,
  BModal,
  VBModal,
  BForm,
  BPagination,BSpinner,BOverlay,BFormInput,   BFormRadioGroup,VBToggle,
    BFormDatepicker,
    BFormFile,
    BFormCheckbox

} from "bootstrap-vue";
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
import axios from "@axios";
import { ref,computed,watch,onMounted} from "@vue/composition-api";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { EditIcon } from "vue-feather-icons";
import { useRouter } from "@core/utils/utils";
import formValidation from "@core/comp-functions/forms/form-validation";
import { required, alphaNum, email } from "@validations";
import { ValidationProvider, ValidationObserver } from "vee-validate";
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
    BModal,
    VBModal,
    BForm,
    BLink,
    BSidebar,
    BPagination,BSpinner,BOverlay,EditIcon,
    BFormInput,   BFormRadioGroup,
    BFormDatepicker,
    BFormFile,
    BFormCheckbox, ValidationProvider,
    ValidationObserver,

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
      axios.delete(`deleteModule/${this.$route.name}/${this.DeleteId}`).then((response) => {
        this.refetchData()

        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Deleted Successfully',
            icon: 'TrashIcon',
            variant: 'danger',
            // text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
          },
        })
      });

    },
    
      
  },
  mounted() {
    this.FetchTableFilds();
    this.fethFilterFields();
  },
  setup(props, { emit }) {
    const { route, router } = useRouter();
    const fields =ref([])
    const toast = useToast();
    const [sortBy, isSortDirDesc] = [ ref(null), ref(false)]
    const fetchLeaveType = (ctx, callback) => {
      FilterFields.value.forEach((item) => {
      console.log("data", item);
      SearchFilter.value.push({'field':item.slug,'value':(item.value)?item.value:null})
      // You can perform any logic or operations here
    });
     axios.get(`getModuleData/${route.value.name}`, {
                 params: {
                    'filter':SearchFilter.value,
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
       SearchFilter.value=[];
       FilterFields.value.forEach((item) => {
        item.value = null;
      });
      refetchData();
    };
     const refetchData = () => {
        BUListTable.value.refresh();
      }
    const totalBUs = ref(0)

    const RemoveError = (errorName) => {
    errors.value[errorName]= " ";
   };
   const FetchTableFilds =()=>{
        axios.get(`getListingFields/${route.value.name}`).then((response) => {
        fields.value=response.data.data;
    });
   }
    const FilterFields = ref([])
    const fethFilterFields = () => {
      axios.get(`getFields/${route.value.name}`,{params:{filter:true}}).then(response =>  {
        FilterFields.value = response.data.data;
      });
    }
    

    const BUListTable = ref(null);
      const perPageOptions = [5, 10, 15]

   
    const FilterData = {
     
    };

    const SearchFilter =ref([]) ;
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

   watch([currentPage, perPage,route], () => {
    FetchTableFilds();
    refetchData();
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
      isSortDirDesc,fields,FetchTableFilds,fethFilterFields,FilterFields
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
