<template>
  <div>
    <b-row>
      <b-col md="12" lg="12">
        <!-- card 1 -->
        <b-card>
          <div
            class="d-md-flex justify-content-between align-items-center my-1"
          >
            <div class="border-left-primary border-left-3">
              <p class="card-title p-50 mb-0">Class Section Master</p>
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
                  @click="OpenClassModel()"
                >
                  Add Class
                </b-button>
                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="primary"
                  class="mb-0 ml-1 basicButton"
                  @click="OpenSection()"
                >
                  Add Section
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
                      <b-form-input
                        v-model="holidayFilterData.name"
                        id="basicInput"
                        placeholder="Enter holiday Name"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col md="6" xl="4">
                    <b-button
                      @click="EmployeeFilterDataSubmit()"
                      variant="primary mt-1 w-100"
                    >
                      Search
                    </b-button>
                  </b-col>
                  <b-col md="6" xl="4">
                    <b-button
                      @click="ResetFilter()"
                      variant="primary mt-1 w-100"
                    >
                      Reset
                    </b-button>
                  </b-col>
                </b-row>
              </b-card>
            </b-collapse>
          </div>
          <b-table
            ref="refholidayListTable"
            :items="fetchClassSection"
            :fields="fields"
            striped
            responsive
          >
            <template #row-details="row">
              <b-card>
                <b-row class="mb-2">
                  <b-table
                    responsive
                    :items="row.item.children"
                    :fields="SectionColumn"
                    class="mb-0"
                  >
                    <template #cell(sr)="row">
                      <div class="text-nowrap">
                        {{ row.index+1 }}
                      </div>
                    </template>
                    <template #cell(section)="row">
                      <div class="text-nowrap">
                        <span class="align-text-top text-capitalize">
                          {{ row.item.name }}
                        </span>
                      </div>
                    </template>
                    <template #cell(Action)="row">
                    <b-link  @click="showAssignSubjectModel=true; AssignClassId=row.item.id" title="Assign Subjects">
                      <feather-icon class="custom-class" icon="AwardIcon" size="1.5x" />
                    </b-link>
                    <b-link  @click="showAssignTeacherModel=true; AssignClassId=row.item.id" title="Assign Teachers">
                      <feather-icon class="custom-class" icon="UserIcon" size="1.5x" />
                    </b-link>
                    <b-link :to="{ name: 'class-section-time-scheduler', params: { id: row.item.id } }" title="Class Scheduler" >
                      <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
                    </b-link>
                      <div class="icon_flx">
                        <b-link
                          v-b-modal.modal-select2
                          @click="
                            ClassData.id = row.item.id;
                            editSection(row.item.id);
                          "
                          title="Edit"
                        >
                          <feather-icon
                            class="custom-class"
                            icon="EditIcon"
                            size="1.5x"
                          />
                        </b-link>
                        <b-link
                          @click="
                            showDeleteConfirmation();
                            DeleteId = row.item.id;
                          "
                          title="Delete"
                        >
                          <feather-icon
                            class="custom-class"
                            icon="TrashIcon"
                            size="1.5x"
                          />
                        </b-link>
                      </div>
                    </template>
                  </b-table>
                </b-row>
                <b-button
                  size="sm"
                  variant="outline-secondary"
                  @click="row.toggleDetails"
                >
                  Hide Details
                </b-button>
              </b-card>
            </template>
            <template #cell(sr)="data">
              <span class="text-nowrap">
                {{ data.index+1 }}
              </span>
            </template>
            <template #cell(class)="data">
              <span class="text-nowrap">
                {{ data.item.name }}
              </span>
            </template>
            <template #cell(section)="data">
              <div v-if="data.item.children.length!=0">
              <b-link  @click="data.toggleDetails" title="View Section">
                  <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
                </b-link>
              <span class="text-nowrap">
                {{'('+ data.item.children_count+')'}}
              </span>
              </div>
            </template>
            <template #cell(Action)="data">
              <div class="icon_flx">
                <b-link v-if="data.item.children.length==0" @click="showAssignSubjectModel=true ; AssignClassId=data.item.id" title="Assign Subjects">
                  <feather-icon class="custom-class" icon="AwardIcon" size="1.5x" />
                </b-link>
                <b-link v-if="data.item.children.length==0"  @click="showAssignTeacherModel=true; AssignClassId=data.item.id" title="Assign Teachers">
                      <feather-icon class="custom-class" icon="UserIcon" size="1.5x" />
                </b-link>
                <b-link  v-if="data.item.children.length==0" :to="{ name: 'class-section-time-scheduler', params: { id: data.item.id } }" title="Class Scheduler" >
                      <feather-icon class="custom-class" icon="EyeIcon" size="1.5x" />
                    </b-link>
                <b-link
                  v-b-modal.modal-select2
                  @click="
                    ClassData.id = data.item.id;
                    editholiday(data.item.id);
                  "
                  title="Edit"
                >
                  <feather-icon
                    class="custom-class"
                    icon="EditIcon"
                    size="1.5x"
                  />
                </b-link>
                
                <b-link
                  @click="
                    showDeleteConfirmation();
                    DeleteId = data.item.id;
                  "
                  title="Delete"
                >
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
            <div class="text-center">
              <span v-if="totalEmployees == 0">No Records Found</span>
            </div>
            <b-row v-if="totalEmployees != 0">
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
                <span class="text-muted"
                  >Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
                  {{ dataMeta.of }} entries</span
                >
              </b-col>
              <b-col
                cols="12"
                sm="4"
                class="d-flex align-items-center justify-content-center justify-content-sm-end"
              >
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalEmployees"
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
    <b-modal
      v-model="showModal"
      title="Delete Confirmation"
      @ok="deleteholiday"
    >
      Are you sure you want to delete ?
    </b-modal>
    <!-- Sidebar Ends -->
   <!-- Assign Subject Model -->
    <b-modal
      :visible="showAssignSubjectModel"
      title="Assign Subject"
      ok-title="Submit"
      @ok="AssignSubjecthandleOk"
      @show="AssignSubjectOnshown"
      @hidden="resetAssignSubjectModel"
      centered
      cancel-variant="outline-secondary"
    >
      <b-form @submit.stop.prevent="SubmitAssignSubject">
        <b-form-group class="myform" label-for="branch">
          <label class="form-label required">Class</label>
          <v-select
            v-model="AssignSubjectData.class"
            @input="RemoveError('branch')"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="ClassOptionData"
            disabled
            :reduce="(val) => val.value"
            input-id="status"
            placeholder="Select Class"
          />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('branch')">
            {{ getErrors("branch") }}
          </div>
        </b-form-group>
        <b-form-group class="myform" label-for="branch">
          <label class="form-label required">Subject</label>
          <v-select
            v-model="AssignSubjectData.subject"
            @input="RemoveError('subject')"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="SubjectData"
            multiple
            :reduce="(val) => val.value"
            :clearable="true"
            input-id="status"
            placeholder="Select Subject"
          />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('branch')">
            {{ getErrors("branch") }}
          </div>
        </b-form-group>
      </b-form>
    </b-modal>
       <!-- Assign Subject Model -->
    <b-modal
      :visible="showAssignTeacherModel"
      title="Assign Teacher"
      ok-title="Submit"
      @ok="AssignTeacherhandleOk"
      @show="AssignTeacherOnshown"
      @hidden="resetAssignTeacherModel"
      centered
      cancel-variant="outline-secondary"
    >
      <b-form @submit.stop.prevent="SubmitAssignSubject">
          <b-row>
            <b-col md="6">
              <b-form-group class="myform" label-for="branch">
                <label class="form-label required">Class</label>
                <v-select
                  class="selctboxdisable"
                  v-model="AssignTeacherData.class"
                  @input="RemoveError('class')"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="ClassOptionData"
                  disabled
                  :reduce="(val) => val.value"
                  input-id="status"
                  placeholder="Select Class"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('branch')">
                  {{ getErrors("branch") }}
                </div>
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group class="myform" label-for="branch">
                <label class="form-label required"> Class Teacher</label>
                <v-select
                  v-model="AssignTeacherData.class_teacher"
                  @input="RemoveError('subject')"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="TeacherData"
                  :reduce="(val) => val.user_id"
                  :clearable="true"
                  label="user_name"
                  value="user_id"
                  input-id="status"
                  placeholder="Select Teacher"
                />
                <small class="text-danger">{{ errors[0] }}</small>
                <div class="text-danger" v-if="hasErrors('branch')">
                  {{ getErrors("branch") }}
                </div>
              </b-form-group>
            </b-col> 
          </b-row>
          <div >
            <b-table responsive striped :items="AssignTeacherData.SubjctTeacherData" :fields="AssignTeacherTableFields">
            <template #cell(subject)="data">
              <v-select
                class="selctboxdisable"
                v-model="data.item.subject_id"
                @input="RemoveError('subject')"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="SubjectData"
                disabled
                :reduce="(val) => val.value"
                input-id="status"
                placeholder="Select Subject"
              />
            </template>
            <template #cell(subject_teacher)="data">
              <v-select
                v-model="data.item.subject_teacher_id"
                @input="RemoveError('subject')"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="TeacherData"
                label="user_name"
                value="user_id"
                :reduce="(val) => val.user_id"
                input-id="status"
                placeholder="Select Teacher"
              />
            </template>
          </b-table>
          </div>
      </b-form>
    </b-modal>





    <!-- Class Modal Starts -->
    <b-modal
      :visible="ShowClassModel"
      :title="EditClassId ? 'Update Class' : 'Add Class'"
      :ok-title="EditClassId ? 'Update' : 'Add'"
      @ok="handleOk"
      @show="Onshown"
      @hidden="resetModal"
      centered
      cancel-variant="outline-secondary"
    >
      <b-form @submit.stop.prevent="SubmitClass">
        <b-form-group class="myform" label-for="Name">
          <label class="form-label required">Name</label>
          <b-form-input
            id="Name"
            @input="RemoveError('name')"
            v-model="ClassData.name"
            placeholder="Name"
          />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('name')">
            {{ getErrors("name") }}
          </div>
        </b-form-group>
      </b-form>
    </b-modal>
    <!-- Modal Ends -->

    <!-- Section Modal Starts -->
    <b-modal
      :visible="ShowSectionModel"
      :title="EditSectionId ? 'Update Section' : 'Add Section'"
      :ok-title="EditSectionId ? 'Update' : 'Add'"
      @ok="SectionhandleOk"
      @show="SectionOnshown"
      @hidden="resetSectionModal"
      centered
      cancel-variant="outline-secondary"
    >
      <b-form @submit.stop.prevent="SubmitSection">
        <b-form-group class="myform" label-for="class">
          <label class="form-label required">class</label>
          <v-select
            v-model="SectionData.class"
            @input="RemoveError('class')"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="ClassOptionsData"
            :reduce="(val) => val.value"
            :clearable="true"
            input-id="class"
            placeholder="Select class"
          />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('class')">
            {{ getErrors("class") }}
          </div>
        </b-form-group>
        <b-form-group class="myform" label-for="Name">
          <label class="form-label required">Name</label>
          <b-form-input
            id="Name"
            @input="RemoveError('name')"
            v-model="SectionData.name"
            placeholder="Name"
          />
          <small class="text-danger">{{ errors[0] }}</small>
          <div class="text-danger" v-if="hasErrors('name')">
            {{ getErrors("name") }}
          </div>
        </b-form-group>
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
  BPagination,
  BLink,
  BFormDatepicker,BFormCheckbox
} from "bootstrap-vue";
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
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
    BRow,
    BCol,
    BCard,
    BCollapse,
    BFormInput,
    BModal,
    VBModal,
    BForm,
    BSidebar,
    BPagination,
    BLink,
    BFormDatepicker,
    BFormCheckbox
  },
  directives: {
    "b-toggle": VBToggle,
    "b-modal": VBModal,
    Ripple,
  },
  data() {
    return {
      showModal: false,
      DeleteId: 0,
      selected: "USA",
      option: ["USA", "Canada", "Maxico"],
      fields: [{ key: "sr" }, { key: "class", sortable: true },"section", "Action"],
      AssignTeacherTableFields:[{ key: "subject", sortable: true },{ key: "subject_teacher", sortable: true }],
      SectionColumn: [
        { key: "sr" },
        { key: "section", sortable: true },
        "Action",
      ],
      AssignClassId:0,
      ClassOptionsData: [],
      EmployeesData: [],
      DesignationOptions: [],
      repotingManager: [],
      BranchData: [],
      holidayMemberData: [],
      EditClassId: 0,
      EditSectionId: 0,
      ClassOptionData:[],
      SubjectData:[],
      TeacherData:[],
    };
  },
  mounted() {},
  methods: {
    showDeleteConfirmation() {
      this.showModal = true;
    },
    handleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.SubmitClass();
    },
    AssignSubjecthandleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.SubmitAssignSubject();
    },
    AssignTeacherhandleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.SubmitAssignTeacher();
    },
    SectionhandleOk(bvModalEvent) {
      // Prevent modal from closing
      bvModalEvent.preventDefault();
      // Trigger submit handler
      this.SubmitSection();
    },
    deleteholiday() {
      axios.get(`deleteClassSection/${this.DeleteId}`).then((response) => {
        this.refetchData();

        this.$toast({
          component: ToastificationContent,
          position: "top-right",
          props: {
            title: "Deleted Successfully",
            icon: "TrashIcon",
            variant: "danger",
            // text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
          },
        });
      });
    },
    editholiday(id) {
      console.log("id", id);
      this.ClassData.id = id;
      this.EditClassId = id;
      this.ShowClassModel = true;
    },
    editSection(id) {
      this.SectionData.id = id;
      this.EditSectionId = id;
      this.ShowSectionModel = true;
    },
    OpenClassModel() {
      this.EditClassId = 0;
      this.ShowClassModel = true;
    },
    OpenSection() {
      this.EditSectionId = 0;
      this.ShowSectionModel = true;
    },
    Onshown() {
      if (this.EditClassId != 0) {
        axios.get(`class/${this.EditClassId}/edit`).then((response) => {
          this.ClassData.name = response.data.data.name;
          this.ClassData.id = response.data.data.id;
        });
      } else {
        this.ClassData.name = "";
        this.ClassData.id = null;
      }
    },
    AssignSubjectOnshown() {
      axios.get("getClassSection").then((response) => {
        this.ClassOptionData = response.data.data;
        this.AssignSubjectData.class = this.AssignClassId;

      });
      axios.get("getSubject").then((response) => {
        this.SubjectData = response.data.data;
      });
      axios
          .get(`getSubjects/${this.AssignClassId}`)
          .then((response) => {
            this.AssignSubjectData.subject = response.data.data;
        });
    },
    AssignTeacherOnshown() {
      axios.get("getClassSection").then((response) => {
        this.ClassOptionData = response.data.data;
        this.AssignTeacherData.class = this.AssignClassId;

      });
      axios.get(`getClassTeacher/${this.AssignClassId}`).then((response) => {
        this.AssignTeacherData.class_teacher = response.data.class_teacher;
      });
      axios.get(`getSubjectsWithTeacher/${this.AssignClassId}`).then((response) => {
        const newDataArray = response.data.data.map((data) => {
          return {
            subject_id: data.subject_id,
            subject_teacher_id: data.subject_teacher_id 
          };
        });
        if (newDataArray.length > 0) {
          this.AssignTeacherData.SubjctTeacherData = newDataArray;
        }
      });
      axios.get("users").then((response) => {
        this.TeacherData = response.data.table_data;
      });
      axios.get("getSubject").then((response) => {
        this.SubjectData = response.data.data;
      });

    },
    SectionOnshown() {
      axios.get(`getClass`).then((response) => {
        this.ClassOptionsData = response.data.class;
      });
      if (this.EditSectionId != 0) {
        axios.get(`class/${this.EditSectionId}/edit`).then((response) => {
          this.SectionData.name = response.data.data.name;
          this.SectionData.id = response.data.data.id;
          this.SectionData.class = response.data.data.parent_id;
        });
      } else {
        this.SectionData.name = "";
        this.SectionData.id = null;
        this.SectionData.class = null;

      }
    },
    resetModal() {
      this.ShowClassModel = false;
      this.errors = [];
    },
    resetSectionModal(){
      this.ShowSectionModel = false;
      this.errors = [];
    },
    resetAssignSubjectModel(){
      this.showAssignSubjectModel=false;
    },
    resetAssignTeacherModel(){
      this.AssignTeacherData.id= null;
      this.AssignTeacherData.class= null;
      this.AssignTeacherData.class_teacher= null;
      this.AssignTeacherData.SubjctTeacherData=[];
      this.showAssignTeacherModel=false;
    }

  },
  setup(props, { emit }) {
    const toast = useToast();

    const [sortBy, isSortDirDesc] = [ref(null), ref(false)];
    const fetchClassSection = (ctx, callback) => {
      axios
        .get("ClassSections", {
          params: {
            ...holidayFilterData.value,
            page: currentPage.value,
            perPage: perPage.value,
            sortBy: sortBy.value,
            sortDesc: isSortDirDesc.value,
          },
        })
        .then((response) => {
          const holiday = response.data.data;
          // console.log("fetchUser response",task, total,response.data)

          callback(holiday);
          totalEmployees.value =
            holiday[0] != null ? holiday[0].total_row_count : 0;
        })
        .catch(() => {
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching holiday list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
        });
    };
    const SubmitClass = () => {
      if (ClassData.value.id != null) {
        axios
          .post(`ClassUpdate/${ClassData.value.id}`, ClassData.value)
          .then(() => {
            refetchData();
            ShowClassModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Class Updated Successfully.",
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
      } else {
        axios
          .post("ClassStore", ClassData.value)
          .then(() => {
            refetchData();
            emit("removeBlankholidayError");
            ShowClassModel.value = false;

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
    };
    const SubmitSection = () => {
      if (SectionData.value.id != null) {
        axios
          .post(`SectionUpdate/${SectionData.value.id}`, SectionData.value)
          .then(() => {
            refetchData();
            ShowSectionModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Section Updated Successfully.",
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
      } else {
        axios
          .post("SectionStore", SectionData.value)
          .then(() => {
            refetchData();
            emit("removeBlankholidayError");
            ShowSectionModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Section Created Successfully.",
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
    };
    const SubmitAssignSubject = () => {
        axios
          .post("AssignSubject", AssignSubjectData.value)
          .then(() => {
            refetchData();
            showAssignSubjectModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Subject Assign   Successfully.",
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
    const SubmitAssignTeacher = () => {
        axios
          .post("AssignTeacher", AssignTeacherData.value)
          .then(() => {
            refetchData();
            showAssignTeacherModel.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Teacher Assign Successfully.",
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
    const EmployeeFilterDataSubmit = () => {
      refetchData();
    };
    const blankClassData = {
      id: null,
      name: null,
    };
    const blankSectionData = {
      id: null,
      class: null,
      name: null,
    };
    const blankAssignSubjectData = {
      id: null,
      class: null,
      subject:[],
    };
    const blankAssignTeacherData = {
      id: null,
      class: null,
      class_teacher: null,
      SubjctTeacherData:[{subject_id:null},{subject_teacher_id:null}],
    };
    const AssignSubjectData = ref(JSON.parse(JSON.stringify(blankAssignSubjectData)));
    const AssignTeacherData = ref(JSON.parse(JSON.stringify(blankAssignTeacherData)));
    const errors = ref([]);
    const ShowClassModel = ref(false);
    const ShowSectionModel = ref(false);
    const showAssignSubjectModel = ref(false);
    const showAssignTeacherModel = ref(false);
    const RemoveError = (errorName) => {
      errors.value[errorName] = " ";
    };

    const ClassData = ref(JSON.parse(JSON.stringify(blankClassData)));
    const SectionData = ref(JSON.parse(JSON.stringify(blankSectionData)));

    const refholidayListTable = ref(null);
    const perPageOptions = [5, 10, 15];

    const refetchData = () => {
      refholidayListTable.value.refresh();
    };
    const totalClients = ref(0);

    const currentPage = ref(1);
    const perPage = ref(10);
    const totalEmployees = ref(0);
    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };

    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };
    const FilterData = ref(
      JSON.parse(
        JSON.stringify({
          id: null,
          name: "",
        })
      )
    );
    const holidayFilterData = ref(JSON.parse(JSON.stringify({ FilterData })));
    const ResetFilter = () => {
      holidayFilterData.value = JSON.parse(JSON.stringify({ FilterData }));
      refetchData();
    };
    const dataMeta = computed(() => {
      const localItemsCount = refholidayListTable.value
        ? refholidayListTable.value.localItems.length
        : 0;
      return {
        from:
          perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
        to: perPage.value * (currentPage.value - 1) + localItemsCount,
        of: totalEmployees.value,
      };
    });
    watch([currentPage, perPage], () => {
      refetchData();
    });

    return {
      RemoveError,
      hasErrors,
      getErrors,
      errors,
      refholidayListTable,
      refetchData,
      fetchClassSection,
      currentPage,
      perPage,
      holidayFilterData,
      FilterData,
      ResetFilter,
      dataMeta,
      perPageOptions,
      ClassData,
      blankClassData,
      SubmitSection,
      SectionData,
      blankSectionData,
      SubmitClass,
      ShowClassModel,
      ShowSectionModel,
      totalEmployees,
      EmployeeFilterDataSubmit,
      sortBy,SubmitAssignTeacher,AssignTeacherData,blankAssignTeacherData,
      isSortDirDesc,AssignSubjectData,blankAssignSubjectData,SubmitAssignSubject,showAssignSubjectModel,showAssignTeacherModel
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

.selctboxdisable{
  button{
    .feather-x{
      display: none;
    }

  }
    .vs__open-indicator{
      display: none;
    }
}


@import "~@core/scss/vue/libs/vue-wizard.scss";
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/quill.scss";
</style>


