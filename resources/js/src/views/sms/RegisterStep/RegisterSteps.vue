<template>
  <div class="ADD_cli registerSteps">
    <!-- <b-row>
      <b-col cols="12" md="6"
        ><h4 class="card-title fontgry">
          <b-link :to="{ name: 'admin-employee' }">Organization</b-link> / Organization

        </h4></b-col
      >
    </b-row> -->

    <!-- <b-link class="brandLogo">
      <img src="@/assets/images/logo/sms.png" class="imgOne">
      <img src="@/assets/images/logo/sms.png" class="imgTwo">

    </b-link> -->
    <form-wizard color="#7367F0" :title="null" :subtitle="null" shape="square" finish-button-text="Submit"
      back-button-text="Previous" class="mb-3 hidsubbtn w-80 mx-auto" @on-complete="formSubmitted"
      @on-change="(prevIndex, nextIndex) => activeTabIndex = nextIndex">


      <tab-content title="Overview" @loading="activeTab = 'overviewForm'" @shown="activeTab = 'overviewForm'" @change="activeTab = 'overviewForm'" :beforeChange="validationForm">
        <validation-observer ref="overviewForm" tag="form">
          <b-col md="12">
            <b-row>
              <b-col md="12">
                <p class="headSteps p-50 pb-1 text-center">Company Overview</p>
              </b-col>
              <b-col md="4">
                <b-form-group label="Company Name" label-for="company_name" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Company Name"
                    rules="required"
                  >
                    <b-form-input
                      id="first_name"
                      v-model="form.overviewForm.company_name"
                       @input="RemoveError('company_name')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Company Name"
                      autocomplete="off"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('company_name')">
                      {{ getErrors("company_name") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <b-col md="4">
                <b-form-group label="Phone" label-for="phone" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Phone"
                    rules="required"
                   >
                    <b-form-input
                      id="phone"
                      v-model="form.overviewForm.phone"
                         @input="RemoveError('phone')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Phone Number"
                      autocomplete="off"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('phone')">
                      {{ getErrors("phone") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col md="4">
                <b-form-group label="Email" label-for="email" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Email"
                    rules="required|email"
                  >
                    <b-form-input
                      id="email"
                      v-model="form.overviewForm.email"
                      @input="RemoveError('email')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Email"
                      autocomplete="off"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('email')">
                      {{ getErrors("email") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>


               <b-col md="4">
                <b-form-group label="Street" label-for="street" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Street"
                    rules="required"
                  >
                    <b-form-input
                      id="emp_code"
                      v-model="form.overviewForm.street"
                        @input="RemoveError('street')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Street"
                      autocomplete="off"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('street')">
                      {{ getErrors("street") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>

               <b-col md="4">
                <b-form-group label="Country" label-for="country" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Country"
                    rules="required"
                  >
                    <v-select
                      @input="onChange('country', $event);RemoveError('country')"
                      input-id="country"
                      v-model.number="form.overviewForm.country"
                      :reduce="(val) => val.id"
                      value="id"
                      label="country_name"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="JSON.parse(JSON.stringify(countryName))"
                      :clearable="true"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Select Country"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('country')">
                      {{ getErrors("country") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group label="State" label-for="state" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="State"
                    rules="required"
                  >
                    <v-select
                      @input="onChange('state', $event);RemoveError('state')"
                      input-id="state"
                      v-model.number="form.overviewForm.state"
                      label="name"
                      value="id"
                      :disabled="!form.overviewForm.country"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="
                        JSON.parse(JSON.stringify(StateOptions))
                      "
                      :clearable="true"
                      :state="errors.length > 0 ? false : null"
                      :reduce="(val) => val.id"
                      placeholder="Select State"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('state')">
                      {{ getErrors("state") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group label="City" label-for="city" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="City"
                    rules="required"
                  >
                    <v-select
                      v-model="form.overviewForm.city"
                       @input="RemoveError('city')"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="JSON.parse(JSON.stringify(CityOptions))"
                      label="name"
                      value="id"
                      :disabled="!form.overviewForm.state"
                      :reduce="(val) => val.id"
                      :clearable="true"
                      input-id="city"
                      placeholder="Select City"
                    />

                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('city')">
                      {{ getErrors("city") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <b-col md="4">
                <b-form-group label="Zip Code" label-for="zip" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Zip Code"
                    rules="required"

                  >
                    <b-form-input
                      id="Zip"
                      v-model="form.overviewForm.pincode"
                         @input="RemoveError('pincode')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Zip Code"
                      autocomplete="off"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('pincode')">
                      {{ getErrors("pincode") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>


                <b-col md="4">
                <b-form-group label=" Employee Strength" label-for="employee_strength" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Employee Strength"
                    rules="required"
                  >
                    <b-form-input
                      id="employee_strength"
                      v-model="form.overviewForm.employee_strength"
                        @input="RemoveError('employee_strength')"
                      :state="errors.length > 0 ? false : null"
                      placeholder="Enter Employee Strength"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('employee_strength')">
                      {{ getErrors("employee_strength") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col md="4">
                <b-form-group label="Industry Type" label-for="industry_type" label-class="required">
                  <validation-provider
                    #default="{ errors }"
                    name="Industry Type"
                    rules="required"
                  >
                  <v-select
                      v-model="form.overviewForm.industry_type"
                         @input="RemoveError('Industry_Type')"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="JSON.parse(JSON.stringify(industryTypeOption))"
                       label="name"
                       value="id"
                      :reduce="(val) => val.id"
                      :clearable="true"
                      input-id="Industry_Type"
                      placeholder="Select Industry Type"
                    />

                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('Industry_Type')">
                      {{ getErrors("Industry_Type") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>



            </b-row>


          </b-col>
        </validation-observer>
      </tab-content>




      <tab-content title="Setup" @change="activeTab = 'systemForm'" id="setupCheckbox" :before-change="validationForm">
        <validation-observer ref="systemForm" tag="form">
          <b-col md="12">
            <b-row>
              <b-col md="12">
                <p class="headSteps p-50 pb-1 text-center">System Setup</p>
              </b-col>
            </b-row>
            <b-row class="mb-2 shiftBox">
              <b-col md="12" class="qterBox pt-50">
                <b-form-group label="Working Day" label-for="working_day" class="mb-0" label-class="required">
                  <validation-provider #default="{ errors }" name="Working Day" rules="required" class="text-center">
                    <div class="d-md-flex d-sm-inline justify-content-between ">
                      <div v-for="item in WeekdayOption" :key="item.value" class="mainBorderedFrame mt-50">

                        <b-form-checkbox v-model="form.systemForm.working_day" :value="item.value"
                          class="disinblkchk custom-control-primary" checked="true" name="check-button" switch>
                          <span class="switch-icon-left">
                            <feather-icon icon="CheckIcon" />
                          </span>
                          <span class="switch-icon-right">
                            <feather-icon icon="XIcon" />
                          </span>
                        </b-form-checkbox>
                        <div class="checkboxLabel">
                          {{ item.text }}
                        </div>




                      </div>
                    </div>

                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('working_day')">
                      {{ getErrors("working_day") }}
                    </div>
                  </validation-provider>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row class="my-2 shiftBox">

              <b-col md="12" class="mt-1 mb-2 d-md-flex d-sm-block align-items-center">
                <b-form-checkbox v-model="form.systemForm.swift_base"
                  @change="form.systemForm.shift = [{ title: `Working hours`, from: null, to: null }]" value="A"
                  class="custom-control-primary mr-2">
                  Is your company working on shift base?
                </b-form-checkbox>

                <b-form-group v-if="form.systemForm.swift_base" label-for="company_name" class="mb-0" label-class="required">
                  <validation-provider #default="{ errors }" name="Number Of Swift" rules="required|integer">
                    <b-form-input id="Number_Of_Swift" v-model="form.systemForm.no_of_swift"
                      @input="updateSetupForm(form.systemForm.no_of_swift)" :state="errors.length > 0 ? false : null"
                      placeholder="Enter Number Of Swift" autocomplete="off" class="shiftField"/>
                    <small class="text-danger">{{ errors[0] }}</small>
                    <div class="text-danger" v-if="hasErrors('no_of_swift')">
                      {{ getErrors("no_of_swift") }}
                    </div>
                  </validation-provider>
                </b-form-group>

              </b-col>
              <b-col md="12">

              <b-row class="px-1">


                <b-col md="3" class="shiftSchedule" v-for="item in form.systemForm.shift" :key="item.item">
                  <b-row class="px-50">
                    <b-col class=" px-25">
                  <label class="required">{{ item.title }}</label>
                  </b-col>
                  </b-row>
                  <b-row class="px-50">
                    <b-col md="6" class=" px-25">
                      <b-form-group label="From" :label-for="item.title">
                        <validation-provider #default="{ errors }" :name="'from ' + item.title + ' time'" rules="required">
                          <!-- <b-form-timepicker :id="'from' + item.title" v-model="item.from"
                            :state="errors.length > 0 ? false : null" placeholder="Starts"
                            autocomplete="off" class="mb-25 pb-0 font12" /> -->
                            <b-form-input
                                    :id="'from' + item.title"
                                    size="sm"
                                    autocomplete="off"
                                    :value="item.from"
                                    v-model="item.from"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Starts"
                                />
                                <b-popover
                                :target="'from' + item.title"
                                triggers="focus"
                                placement="top"
                                id="baseTimeSelection"
                                >
                                <template #title>
                                    <span>From </span>
                                </template>
                                <span class="popDates">
                                    <b-row>
                                        <b-col sm="12" md="6" xl="6" class="my-50">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="w-100 bg-gray-300 h-50 dark:bg-gray-700 base-magnatude">
                                                <input type="range" min="0" max="24"
                                                    step="1"
                                                    v-model="hourDial1"
                                                    @input="hourDialSlider1; item.from = `${hourDial1 < 10? '0'+hourDial1 : hourDial1}:${minitDial1 < 10? '0'+minitDial1 : minitDial1}:00`"
                                                    :style="[{backgroundSize: hourDialSlider1}]"
                                                    class="magnatude" id="hourDialSliderID1">
                                                    <h6 class="mb-0 font-weight-bolder text-center text-primary mt-1 smallestFont">
                                                    Hours </h6>
                                            </div>
                                        </div>
                                        </b-col>
                                        <b-col sm="12" md="6" xl="6" class="my-50">
                                        <div class="d-flex align-items-center justify-content-center">
                                                <div class="w-100 bg-gray-300 h-50 dark:bg-gray-700 base-magnatude">
                                                    <input type="range" min="0" :max="hourDial2 < 24 ? '59' : '0'"
                                                        step="5"
                                                        v-model="minitDial1"
                                                        @input="minitDialSlider1; item.from = `${hourDial1 < 10? '0'+hourDial1 : hourDial1}:${minitDial1 < 10? '0'+minitDial1 : minitDial1}:00`"
                                                        :style="[{backgroundSize: minitDialSlider1}]"
                                                        class="magnatude" id="minitDialSliderID1">
                                                        <h6 class="mb-0 font-weight-bolder text-center text-primary mt-1 smallestFont">
                                                        Minutes</h6>
                                                </div>
                                            </div>
                                        </b-col>
                                    </b-row>
                                </span>
                            </b-popover>
                          <small class="text-danger">{{ errors[0] }}</small>
                          <div class="text-danger" v-if="hasErrors('no_of_swift')">
                            {{ getErrors("no_of_swift") }}
                          </div>
                        </validation-provider>
                      </b-form-group>
                    </b-col>
                    <b-col md="6" class=" px-25">
                      <b-form-group label="To" :label-for="item.title">
                      <validation-provider #default="{ errors }" :name="'To ' + item.title + ' time'" rules="required">
                        <!-- <b-form-timepicker :id="'to' + item.title" v-model="item.to"
                          :state="errors.length > 0 ? false : null" placeholder="Ends" autocomplete="off"  class="mb-25 pb-0 font12"/> -->
                          <b-form-input
                              :id="'to' + item.title"
                              size="sm"
                              autocomplete="off"
                              :value="item.to"
                              v-model="item.to"
                              :state="errors.length > 0 ? false : null"
                              placeholder="Ends"
                          />
                                <b-popover
                                :target="'to' + item.title"
                                triggers="focus"
                                placement="top"
                                id="baseTimeSelection"
                                >
                                <template #title>
                                    <span>To </span>
                                </template>
                                <span class="popDates">
                                    <b-row>
                                        <b-col sm="12" md="6" xl="6" class="my-50">
                                        <!-- <label class="form-label required invisible">Hours</label> -->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="w-100 bg-gray-300 h-50 dark:bg-gray-700 base-magnatude">
                                                <input type="range" min="0" max="24"
                                                    step="1"
                                                    v-model="hourDial2"
                                                    @input="hourDialSlider2; item.to = `${hourDial2 < 10? '0'+hourDial2 : hourDial2}:${minitDial2 < 10? '0'+minitDial2 : minitDial2}:00`"
                                                    :style="[{backgroundSize: hourDialSlider2}]"
                                                    class="magnatude" id="hourDialSliderID2">
                                                    <h6 class="mb-0 font-weight-bolder text-center text-primary mt-1 smallestFont">
                                                    Hours </h6>
                                            </div>
                                        </div>
                                        </b-col>
                                        <b-col sm="12" md="6" xl="6" class="my-50">
                                        <!-- <label class="form-label required invisible">Minutes</label> -->
                                        <div class="d-flex align-items-center justify-content-center">
                                                <div class="w-100 bg-gray-300 h-50 dark:bg-gray-700 base-magnatude">
                                                    <input type="range" min="0" :max="hourDial2 < 24 ? '59' : '0'"
                                                        step="5"
                                                        v-model="minitDial2"
                                                        @input="minitDialSlider2; item.to = `${hourDial2 < 10? '0'+hourDial2 : hourDial2}:${minitDial2 < 10? '0'+minitDial2 : minitDial2}:00`"
                                                        :style="[{backgroundSize: minitDialSlider2}]"
                                                        class="magnatude" id="minitDialSliderID2">
                                                        <h6 class="mb-0 font-weight-bolder text-center text-primary mt-1 smallestFont">
                                                        Minutes</h6>
                                                </div>
                                            </div>
                                        </b-col>
                                    </b-row>
                                </span>
                            </b-popover>
                        <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors('no_of_swift')">
                          {{ getErrors("no_of_swift") }}
                        </div>
                      </validation-provider>
                    </b-form-group>
                    </b-col>
                  </b-row>


                </b-col>

              </b-row>
              </b-col>
            </b-row>



            <b-row class="my-2 shiftBox p-md-2 p-lg-2 p-xl-2">
              <b-col md="12" class="qterBox">
                <div class="col-12 pt-1 pl-0">
                  <label class="form-label required">Quarter</label>
                </div>
                <validation-provider #default="{ errors }" name="Quarter" rules="required">
                <div class="row mx-0">
                  <div class="col-4 col-md-4 position-relative pr-50 pl-0" v-for="ps in QuarterOptions" :key="ps.value">
                      <input
                        class="checkbox-budget" type="radio" name="freq-radios" :id="ps.value"
                          v-model="form.systemForm.quarter"
                          :options="ps"
                          @change="QuarterChange(form.systemForm.quarter)"
                          :value="ps.value"/>
                      <label class="for-checkbox-budget" :for="ps.value">
                        <span :data-hover="ps.text">
                          {{ ps.text }}</span>
                      </label>

                  </div>


                </div>
                <small class="text-danger">{{ errors[0] }}</small>
                        <div class="text-danger" v-if="hasErrors('quarter')">
                          {{ getErrors("quarter") }}
                        </div>
                    </validation-provider>











                <!-- <b-form-group label="Quarter" label-for="Quarter" class="mb-0 " label-class="required">
                  <validation-provider #default="{ errors }" name="Quarter" rules="required">

                    <b-form-radio-group
                    id="gender"
                    v-model="form.systemForm.quarter"
                    :options="QuarterOptions"
                    @change="QuarterChange(form.systemForm.quarter)"
                    value="male" class="mt-50 diffRadio custom-control-primary"
                    switch />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group> -->
                <b-col sm="12" md="12" xl="12" class="mt-2 px-0" v-if="form.systemForm.quarter != null">
                  <b-table class="mb-2 staticTable bg-white" ref="refKeyResultTable"
                    :items="form.systemForm.QuarterTableData" :fields="['title', 'from_date', 'to_date']" responsive />
                </b-col>
              </b-col>
            </b-row>




          </b-col>
        </validation-observer>
      </tab-content>


  <tab-content title="BU" @change="activeTab = 'BUForm'" :before-change="validationForm">
        <validation-observer ref="BUForm" tag="form">
            <div class="text-danger" v-if="hasErrors('BlankBU')">
              {{ getErrors("BlankBU") }}
            </div>
        <bu @removeBlankDepartmentError="RemoveError('BlankBU')" />

        </validation-observer>
  </tab-content>

   <tab-content title="Branch" @change="activeTab = 'BranchForm'" :before-change="validationForm">
        <validation-observer ref="BranchForm" tag="form">
            <div class="text-danger" v-if="hasErrors('BlankBranch')">
              {{ getErrors("BlankBranch") }}
            </div>
        <branch @removeBlankDepartmentError="RemoveError('BlankBranch')" />

        </validation-observer>
  </tab-content>



      <!-- department -->

     <tab-content title="Departments" @change="activeTab = 'departmentForm'" :before-change="validationForm">
        <validation-observer ref="departmentForm" tag="form">
            <div class="text-danger" v-if="hasErrors('BlankDepartment')">
              {{ getErrors("BlankDepartment") }}
            </div>
        <department @removeBlankDepartmentError="RemoveError('BlankDepartment')" />

        </validation-observer>
      </tab-content>
      <!-- Team -->
      <tab-content title="Teams" @click="activeTab = 'teamForm'" @loading="activeTab = 'teamForm'"
        @shown="activeTab = 'teamForm'" @change="activeTab = 'teamForm'" :before-change="validationForm">
        <validation-observer ref="teamForm" tag="form">
            <div class="text-danger" v-if="hasErrors('BlankTeam')">
              {{ getErrors("BlankTeam") }}
            </div>
         <team @removeBlankTeamError="RemoveError('BlankTeam')" />
        </validation-observer>
      </tab-content>

        <!-- Role -->
      <tab-content title="Roles" @click="activeTab = 'roleForm'" @loading="activeTab = 'roleForm'"
        @shown="activeTab = 'roleForm'" @change="activeTab = 'roleForm'" :before-change="validationForm">
        <validation-observer ref="roleForm" tag="form">
            <div class="text-danger" v-if="hasErrors('BlankRole')">
              {{ getErrors("BlankRole") }}
            </div>
         <role @removeBlankRolerror="RemoveError('BlankRole')" />
        </validation-observer>
      </tab-content>


      <tab-content title="Finish" @click="activeTab = 'finishForm'" @loading="activeTab = 'finishForm'"
        @shown="activeTab = 'finishForm'" @change="activeTab = 'finishForm'" :before-change="validationForm">
        <validation-observer ref="finishForm" tag="form">
          <b-col md="12">
            <b-row>
              <b-col md="12" class="my-3">


                <svg class="w-24 h-24 m-auto rounded-full congoIcon" fill="#58bb58" viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
                </svg>
                <p class="congratulationHeading p-50 mb-0 text-center">Welcome Onboard!!!</p>
                <p class="congratulationText p-50 mb-0 text-center"><span>Congratulations!!!</span> Your registration
                  request has been successfully submitted. We will get back to you shortly.</p>

              </b-col>
            </b-row>
          </b-col>
        </validation-observer>
      </tab-content>

      <b-col sm="12" md="12" xl="12" class="text-center myform1 smallFont" v-if="activeTabIndex != 5">
        (Note: <label class="form-label requireds smallFont"> mark are mandatory field and must be completed</label>)
      </b-col>
    </form-wizard>
  </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { useToast } from "vue-toastification/composition";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import {
  BButton,
  BRow,
  BOverlay,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BMedia,
  BAvatar,
  BToast,
  BFormCheckbox,
  BAlert,
  BCardText,
  VBPopover,
  BPopover,
  BLink,
  BFormRadioGroup,
  BFormTags, BInputGroup, BInputGroupAppend, BCard, BFormTimepicker, BTable
} from "bootstrap-vue";
// import { quillEditor } from "vue-quill-editor";
import { required, email } from "@validations";
// import { avatarText } from "@core/utils/filter";


import store from "@/store";
import { ref, computed, watch } from "@vue/composition-api";
// import { useRouter } from "@core/utils/utils";
import axios from "@axios";
import flatPickr from "vue-flatpickr-component";

import Ripple from "vue-ripple-directive";
import moment from "moment";
import department from "../department/Department.vue"
import branch from "../branch/Branch.vue"
import bu from "../BU/BU.vue"
import team from "../team/Team.vue"
import role from "../role/Role.vue"
export default {
  components: {
    role,
    team,
    department,bu,branch,
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent,
    BButton,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    vSelect,
    BFormInvalidFeedback,
    ToastificationContent,
    BMedia,
    BAvatar,
    // quillEditor,
    BToast,
    BFormCheckbox,
  BPopover,
    BAlert,
    BLink,
    BOverlay,
    flatPickr,
    BFormRadioGroup,
    BFormTags, BInputGroup, BInputGroupAppend, BCard, BFormTimepicker, BTable
  },
  directives: {
    'b-popover': VBPopover,
    Ripple,
  },
  data() {

    return {
      hourDial1: 0,
      minitDial1: 0,
      hourDial2: 0,
      minitDial2: 0,
      frequencyTime2: null,
      form: {
        overviewForm: {
          formType: 'overview',
          company_name: null,
          phone: null,
          email: null,
          street: null,
          country: null,
          state: null,
          city: null,
          pincode: null,
          employee_strength: null,
          industry_type: null

        },
        systemForm: {
          formType: 'systemForm',
          working_day: [],
          swift_base: false,
          no_of_swift: null,
          shift: [{ title: `Working hours`, from: null, to: null }],
          quarter: null,
          QuarterTableData: [],


        },
        BUForm: {
          formType: 'BUForm',
          BU: null,
        },
        BranchForm: {
          formType: 'BranchForm',
          Branch: null,
        },

        departmentForm: {
          formType: 'departmentForm',
          departments: null,
        },
        teamForm: {
          formType: 'teams',
          department: null,
          teams: null,
        },
        roleForm: {
          formType: 'roles',
          department: null,
          roles:null
        }

      },
      departmentOptions: [],
      selected: [],
      required,
      email,
      DepartmentDemo: ['Sales', 'Marketing', 'Accounts', 'Human Resource', 'Project Delivery'],
      TeamDemo: ['Domestic Sales Team', 'International Sales Team'],
      RoleDemo: ['Sales manager', 'Assistant sales managers', 'Account executives', 'Customer success representatives', 'Sales specialists'],
      //   IndustryOptions: [
      //   { text: 'True', value: 'true' },
      //   { text: 'False', value: 'false' },

      // ],
      countryName: [],


      // avatarText,
      acountOption: [],
      userOptions: [],
      userListOptions: [],
      rolesOptions: [],
      EmployeeCountryOptions: [],
      StateOptions: [],
      CityOptions: [],
      industryTypeOption: [],
      WeekdayOption: [],
      DayOptions: [
        { label: 'Sunday', value: '1' },
        { label: 'Monday', value: '2' },
        { label: 'Tuesday', value: '3' },
        { label: 'Wednesday', value: '4' },
        { label: 'Thursday', value: '5' },
        { label: 'Friday', value: '6' },
        { label: 'Saturday', value: '7' },
      ],
      QuarterOptions: [
        { text: 'January To December ', value: 'JtoD' },
        { text: 'April To March', value: 'AtoM' },
      ],
      currentForm: null,
      activeTab: 'overviewForm',
      activeTabIndex: 0,
      tabRefs: [
        'overviewForm',
        'systemForm',
        'BUForm',
        'BranchForm',
        'departmentForm',
        'teamForm',
        'roleForm',
        'finishForm',
      ]
    };
  },
  methods: {
    hourDialSlider1(e) {
        let clickedElement = e.target,
        min = clickedElement.min,
        max = clickedElement.max,
        val = clickedElement.value;
        if( this.hourDial1 == 24){
          this.minitDial1 = 0;
        }
        // this.hourDial1 = ((val - min) * 24 / (max - min)).toFixed(0);
        const ratingBox = document.getElementById("hourDialSliderID1");
    },
    minitDialSlider1(e) {
        let clickedElement = e.target,
            min = clickedElement.min,
            max = clickedElement.max,
            val = clickedElement.value;
        // this.minitDial1 = ((val - min) * 60 / (max - min)).toFixed(0);
        const ratingBox = document.getElementById("minitDialSliderID1");
    },
    hourDialSlider2(e) {
        let clickedElement = e.target,
        min = clickedElement.min,
        max = clickedElement.max,
        val = clickedElement.value;
        if( this.hourDial2 == 24){
          this.minitDial2 = 0;
        }
        // this.hourDial2 = ((val - min) * 24 / (max - min)).toFixed(0);
        const ratingBox = document.getElementById("hourDialSliderID2");
    },
    minitDialSlider2(e) {
        let clickedElement = e.target,
            min = clickedElement.min,
            max = clickedElement.max,
            val = clickedElement.value;
        // this.minitDial2 = ((val - min) * 60 / (max - min)).toFixed(0);
        const ratingBox = document.getElementById("minitDialSliderID2");
    },
    validationForm() {
      console.log("this.activeTab", this.activeTabIndex, this.tabRefs[this.activeTabIndex], this.form[this.tabRefs[this.activeTabIndex]])
      return new Promise((resolve, reject) => {
        this.$refs[this.tabRefs[this.activeTabIndex]].validate().then((success) => {
          if (success) {
            axios.post(`submitRegistrationForm`, this.form[this.tabRefs[this.activeTabIndex]]).then(response => {

              if (this.tabRefs[this.activeTabIndex + 1] == 'teamForm') {
                axios.get('Department').then(response => {
                  console.log("department", response.data.data)
                  this.departmentOptions = response.data.data
                })
              }
              resolve(true);
            })
              .catch(error => {
                if (error.response.data.code == 422) {
                  this.errors = error.response.data.errors;
                }
                reject();
              })

          } else {
            reject();
          }
        });
      });
    },

    formSubmitted() {
      // axios.get('setupCompleted').then(() => {
        this.$router.replace('/home').then(() => {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: `Welcome`,
              icon: 'CoffeeIcon',
              variant: 'success',
              text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
            },
          })
        });
      // })
      // .catch(err => {
      //   alert("Something went wrong! Please try again later.")
      // })
    },
    handleTabChanged(prevIndex, nextIndex) {
      this.activeTabIndex = nextIndex;
    },
    onChange(element, value) {
      if (element == "country") {
        this.StateOptions = []
        this.form.overviewForm.state = [];
        this.CityOptions = [];
        this.form.overviewForm.city = [];
        axios.get(`/getStateFromCountryId/${value}`).then((response) => {

          this.StateOptions = JSON.parse(
            JSON.stringify(response.data.data)
          );
        });
      }


      if (element == "state") {
        this.CityOptions = []
        this.form.overviewForm.city = [];
        axios.get(`/getCityFromStateId/${value}`).then((response) => {
          this.CityOptions = JSON.parse(
            JSON.stringify(response.data.data)
          );
        });
      }

    },
    updateSetupForm(number) {
      this.form.systemForm.shift = [];
      if (number) {
        this.form.systemForm.shift = [];
        var i = 1;
        for (i = 1; i <= number; i = i + 1) {
          this.form.systemForm.shift.push({ title: `Shift ${i}`, from: null, to: null })
        }

      }
      console.log("test", this.form.systemForm.shift);
    },
    QuarterChange(value) {
      this.form.systemForm.QuarterTableData = [];
      if (value == 'JtoD') {
        this.form.systemForm.QuarterTableData.push(
          { title: `Quarter 1 `, from_date: moment().format("YYYY")+'-01-01' ,to_date: moment().format("YYYY")+'-03-31'},
          { title: `Quarter 2`, from_date: moment().format("YYYY")+'-04-01' , to_date: moment().format("YYYY")+'-06-30'},
          { title: `Quarter 3`, from_date: moment().format("YYYY")+'-07-01' , to_date: moment().format("YYYY")+'-09-30'},
          { title: `Quarter 4`, from_date: moment().format("YYYY")+'-10-01' , to_date: moment().format("YYYY")+'-12-31'},
        )
      } else {
         if (moment().format("M") <= 3) {
            this.form.systemForm.QuarterTableData.push(
          { title: `Quarter 1`, from_date: moment().subtract(1,'Y').format("YYYY")+'-04-01' , to_date: moment().subtract(1,'Y').format("YYYY")+'-06-30'},
          { title: `Quarter 2`, from_date: moment().subtract(1,'Y').format("YYYY")+'-07-01' , to_date: moment().subtract(1,'Y').format("YYYY")+'-09-30' },
          { title: `Quarter 3`, from_date: moment().subtract(1,'Y').format("YYYY")+'-10-01' , to_date: moment().subtract(1,'Y').format("YYYY")+'-12-31' },
          { title: `Quarter 4`, from_date: moment().format("YYYY")+'-01-01' , to_date: moment().format("YYYY")+'-03-31' },
        )
    } else {
         this.form.systemForm.QuarterTableData.push(
          { title: `Quarter 1`, from_date: moment().format("YYYY")+'-04-01' , to_date: moment().format("YYYY")+'-06-30'},
          { title: `Quarter 2`, from_date: moment().format("YYYY")+'-07-01' , to_date: moment().format("YYYY")+'-09-30' },
          { title: `Quarter 3`, from_date: moment().format("YYYY")+'-10-01' , to_date: moment().format("YYYY")+'-12-31' },
          { title: `Quarter 4`, from_date: moment().add(1,'Y').format("YYYY")+'-01-01' , to_date: moment().add(1,'Y').format("YYYY")+'-03-31' },
        )
    }
      }
    }
  },
  mounted() {
    axios.get(`/getStateFromCountryId/${101}`).then((response) => {
      this.StateOptions = JSON.parse(
        JSON.stringify(response.data.data)
      );
    });
    axios.get("country").then((response) => {
      this.countryName = response.data.data;
    });
    axios.get("state").then((response) => {
      this.StateOptions = response.data.data;
    });
    axios.get("city").then((response) => {
      this.CityOptions = response.data.data;
    });
    axios.get("industryType").then((response) => {
      this.industryTypeOption = response.data.data['industry'];
      this.WeekdayOption = response.data.data['weekday'];
    });

  },
  setup() {

    const Submit = () => {
      console.log("console", RegisterData);
      axios.post('/registersteps', RegisterData)
        .then(response => {
          if (response.data.status == 'success') {


          }
        })
        .catch(error => {
          if (error.response.data.code == 422) {
            errors.value = error.response.data.errors;
          }
        })
    };



    // UnRegister on leave

    const hasErrors = (fieldName) => {
      return fieldName in errors.value;
    };
    const getErrors = (fieldName) => {
      return errors.value[fieldName][0];
    };
     const RemoveError = (errorName) => {
      console.log('hello');
      console.log(errorName)
    errors.value[errorName]= " ";
   };

    const toast = useToast();
    // const { route, router } = useRouter();

    const show = ref(false);

    const organizeData = ref({

      company_name: null,
      phone: null,
      email: null,
      country: null,
      state: null,
      city: null,
      street: null,
      Employee_Strength: null,
      Industry_Type: null,



    });

    const errors = ref([]);



    const encodeBase64 = (data) => {
      data = data.toString()
      return Buffer.from(data).toString('base64');
    };
    const decodeBase64 = (data) => {
      return Buffer.from(data, 'base64').toString('ascii');
    };


    return {
      encodeBase64,
      decodeBase64,
      hasErrors,
      getErrors,
      RemoveError,
      organizeData,
      show,
      errors,
    };
  },
};


</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-wizard.scss";
@import "~@core/scss/vue/libs/vue-select.scss";
// @import "~@core/scss/vue/libs/quill.scss";
</style>
