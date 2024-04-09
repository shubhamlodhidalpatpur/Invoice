    <template>
  <b-card class="right-aligned-card">
    <!-- <b-card-body> -->
    <b-row>
      <b-col md="6">
        <b-form-group class="myform" label-for="branch">
          <label class="form-label required">Class</label>
          <v-select
            class="selctboxdisable"
            v-model="lectureSchedule.class"
            @input="RemoveError('class')"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="ClassOptionData"
            :reduce="(val) => val.value"
            input-id="status"
            placeholder="Select Class"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <!-- </b-card-body> -->
    <b-tabs v-model="activeTab" @input="saveActiveTab">
      <b-tab>
        <template #title>
          <feather-icon icon="HomeIcon" /> <span>Scheduler Setting</span>
        </template>
        <div class="d-flex justify-content-between mt-2 mt-md-0">
            <b-button
            @click="addLectureRow"
            v-if="Editable"
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
              class="mb-0 ml-md-1 basicButton"
            >Add Row</b-button
          >
            <b-button
                @click="Editable=!Editable"
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-0 ml-1 basicButton"
                >{{Editable?'View':"Edit"}}
            </b-button>
        </div>            
        <div>
          <b-table
            striped
            hover
            :items="lectureSchedule.LectureItems"
            :fields="tableFields"
          >
            <template #cell(lectureNo)="data">
              <span>{{ data.index + 1 }}</span>
            </template>
            <template #cell(startTime)="data">
              <b-form-timepicker
                v-model="data.item.startTime"
                class="form-control"
                :disabled="!Editable"
                @input="RemoveError('conduct_time')"
              />
            </template>
            <template #cell(endTime)="data">
              <b-form-timepicker
                v-model="data.item.endTime"
                :disabled="!Editable"
                class="form-control"
                @input="RemoveError('conduct_time')"
              />
            </template>
            <template #cell(subject_and_teacher)="data">
               <v-select
                  v-model="data.item.subject_and_teacher"
                  @input="RemoveError('subject')"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="SubjectTeacherData"
                  :disabled="!Editable"
                  :reduce="(val) => val.id"
                  :clearable="true"
                  label="subject_with_teacher"
                  value="id"
                  input-id="status"
                  placeholder="Select Teacher"
                />
            </template>
            <template #cell(Action)="data">
              <b-button
                v-if="Editable"
                v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                variant="outline-danger"
                class="mt-0 mt-md-2"
                @click="removeItem(data.index)"
              >
                <feather-icon icon="XIcon" class="mr-25" />
                <span>Delete</span>
              </b-button>
            </template>
          </b-table>
          <b-row class="mt-1">
            <b-col sm="12" class="text-center">
              <b-button
                v-if="Editable"
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="primary"
                @click="formSubmitted"
                class="btn d-inline-block mr-1 btn-primary"
              >
                <span>SUBMIT</span>
              </b-button>
            </b-col>
          </b-row>
        </div>
      </b-tab>

      <b-tab>
        <template #title>
          <feather-icon icon="TargetIcon" /> <span>Period / Lectures</span>
        </template>  
  <div>
    <b-table-simple
      hover
      small
      caption-top
      responsive
    >
      <colgroup>
        <col>
        <col v-for="day in days" :key="day">
      </colgroup>

      <b-thead head-variant="light">
        <b-tr>
          <b-th>Lecture</b-th>
          <b-th v-for="lac in LectureNumber" :key="lac">{{ lac }}</b-th>
        </b-tr>
      </b-thead>

      <b-tbody>
        <b-tr>
          <b-td>Timing</b-td>
          <b-td v-for="time in LectureTimeing" :key="time">{{time}}</b-td>
        </b-tr>
        <b-tr v-for="(subject, index) in subjects" :key="index">
          <b-td>{{ subject.day }}</b-td>
          <b-td v-for="lacNum in LectureNumber" :key="lacNum">
            <v-select
                  v-model="class_teacher"
                  @input="RemoveError('subject')"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="SubjectTeacherData"
                  :reduce="(val) => val.user_id"
                  :clearable="true"
                  label="subject_with_teacher"
                  value="id"
                  input-id="status"
                  placeholder="Select Teacher"
            />
          </b-td>
        </b-tr>
      </b-tbody>

    </b-table-simple>
    <b-row class="mt-1">
            <b-col sm="12" class="text-center">
              <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                :disabled="show"
                variant="primary"
                @click="formSubmitted"
                class="btn d-inline-block mr-1 btn-primary"
              >
                <span>SUBMIT</span>
              </b-button>
            </b-col>
    </b-row>
  </div>
</b-tab>
    </b-tabs>
  </b-card>
</template>

<script>
import store from "@/store";
import { ref, onUnmounted } from "@vue/composition-api";
import {
  BRow,
  BCol,
  BButton,
  BAlert,
  BLink,
  BTabs,
  BTab,
  BCardText,
  BCard,
  BTable,
  BProgressBar,
  BProgress,
  BCardBody,
  BCardTitle,
  BCardSubTitle,
  BAvatar,
  VBTooltip,
  VBPopover,
  BPopover,
  BPagination,
  BSpinner,
  BOverlay,
  BFormTimepicker,BTableSimple,
    BThead,
    BTr,
    BTh,
    BTd,
    BTbody,
    BTfoot,
} from "bootstrap-vue";
import axios from "@axios";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import { computed, watch } from "@vue/composition-api";
import moment from "moment";
import { useRouter } from "@core/utils/utils";
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
export default {
  components: {
    BRow,
    BCol,
    BAlert,
    BLink,
    BCardText,
    BTabs,
    BTab,
    BCard,
    BTable,
    BProgressBar,
    BProgress,
    BButton,
    BFormTimepicker,
    vSelect,
    BTableSimple, BThead, BTr, BTh, BTd, BTbody, BTfoot,
    },

  directives: {
    Ripple,
    "b-popover": VBPopover,
    "b-tooltip": VBTooltip,
  },

  data() {
    return {
      fields: ["project", "totaltask", "progress", "hour"],
      value: null,
      max: null,
      profildata: [],
      Editable: false,
      activeTab: 0,
      class_teacher:null,
      tableFields: ["lectureNo", "startTime", "endTime","subject_and_teacher","Action"],
      ClassOptionData: [],
      days: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
      lacture: ['1', '2', '3', '4', '5', '6', '7'],
      subjects: [
        { day: 'M', subject: 'Physics - AK sir' },
        { day: 'T', subject: '' },
        { day: 'W', subject: '' },
        { day: 'T', subject: '' },
        { day: 'F', subject: '' },
        { day: 'S', subject: '' },
        { day: 'S', subject: '' },
      ],
      LectureNumber:[],
      LectureTimeing:[],
      SubjectTeacherData:[],
    };
  },
  methods: {
    saveActiveTab() {
      localStorage.setItem("activeTab", this.activeTab);
    },
    GetLectureData() {
        axios.get(`getLectureScheduleData/${this.$route.params.id}`).then((response) => {
         this.LectureNumber=response.data.data.lecture_number;
         this.LectureTimeing=response.data.data.lecture_time;
         this.SubjectTeacherData=response.data.data.subjectWithTeacher;
        
        });

    },
    addLectureRow() {
      const newLecture = {
        startTime: "",
        endTime: "",
        subject_and_teacher:null
      };
      this.lectureSchedule.LectureItems.push(newLecture);
    },
    removeItem(index) {
      this.lectureSchedule.LectureItems.splice(index, 1);
    },
    formSubmitted() {
        console.log(this.lectureSchedule,'data')
      axios
        .post("updateClassLacture", this.lectureSchedule)
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Lectures Updated Successfully",
              icon: "CheckIcon",
              variant: "success",
            },
          });
        });
    },
  },
  mounted() {
    axios.get("getClassSection").then((response) => {
      this.ClassOptionData = response.data.data;
      this.lectureSchedule.class = this.$route.params.id;
    });
    axios.get(`getLectureData/${this.$route.params.id}`).then((response) => {
      const newDataArray = response.data.data.map((data) => {
          return {
            startTime: data.lecture_start_time,
            endTime: data.lecture_end_time,
            subject_and_teacher:data.subject_teacher_id
          };
        });
        if (newDataArray.length > 0) {
         this.lectureSchedule.LectureItems = newDataArray;
        }
    });
    this.GetLectureData()
  },
  setup(props, { emit }) {
    const { route, router } = useRouter();
    const toast = useToast();
    const blanklectureSchedule = {
      class: null,
      LectureItems: [{ startTime: "10:00", endTime: "10:45",subject_and_teacher:null }],
    };
    const lectureSchedule = ref(
      JSON.parse(JSON.stringify(blanklectureSchedule))
    );
    const RemoveError = (errorName) => {
      // alert(errorName)
      // console.log(errors)
      errors.value[errorName] = " ";
    };
    const errors = ref([]);
    const Editable = ref(false);
    return {
      blanklectureSchedule,
      lectureSchedule,RemoveError,errors,Editable
    };
  },
};
</script>