<template>
  <v-container>
    <template>
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        {{ button_text }}
      </v-btn>
    </template>
    <v-dialog
      v-model="dialog"
      persistent
      activator="parent"
      max-width="900px"
    >
      <v-card min-height="800">
        <v-card-title>
          <span class="text-h5">{{ button_text }}</span>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            class="pa-5"
            @click="closeDialog"
          >
            Close
          </v-btn>
        </v-card-title >
                <v-form
                    v-model="valid"
                    ref="form"
                >
                        <div class="pa-3" id="add_new_user" tabindex="-1">
                          <div class="pa-2 create_new_event">
                            <div class="modal-content">
                              <div class="modal-content-inner">
                                <div class="modal_header">
                                </div>
                                <form action="" @submit.prevent="submitForm">
                                  <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="inviteSuccess">
                                    <span v-html="alertMsg"></span>
                                    <button type="button" class="close_alert" @click="inviteSuccess = false" aria-label="Close">&times;
                                    </button>
                                  </div>

                                  <v-expansion-panels focusable class="accordion-form" v-model="panel">
                                    <v-expansion-panel>
                                      <v-expansion-panel-header>Student Details</v-expansion-panel-header>
                                      <v-expansion-panel-content>
                                        <div class="full_width">
                                          <div class="form_group text_center margin-bottom_md">

                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="student_name"
                                                  label="Student Names *"
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="student_name"
                                                  type="text"
                                                  @input="$v.student_name.$touch()"
                                                  @blur="$v.student_name.$touch()"
                                              ></v-text-field>
                                              <!--                                              <input type="text" required v-model="student_name" id="student_name">-->
                                            </div>
                                          </div>
                                          <div class="right_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="student_email"
                                                  label="Student Email *"
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="student_email"
                                                  type="email"
                                                  @input="$v.student_email.$touch()"
                                                  @blur="$v.student_email.$touch()"
                                              ></v-text-field>
                                            </div>
                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="date_of_birth"
                                                  label="Date of Birth *"
                                                  required
                                                  id="date_of_birth"
                                                  type="date"
                                                  :rules="[v => !!v || 'Item is required']"
                                                  @input="$v.date_of_birth.$touch()"
                                                  @blur="$v.date_of_birth.$touch()"
                                              ></v-text-field>
                                              <!--                                              <input type="date" v-model="date_of_birth" id="student_dob">-->
                                            </div>
                                          </div>

                                          <div class="right_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="gender"
                                                  :items="genders"
                                                  hide-details="auto"
                                                  label="Select Gender(Optional)"
                                                  solo
                                                  id="student_gender"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="nationality"
                                                  :items="countries"
                                                  hide-details="auto"
                                                  label="Select Nationality(Optional)"
                                                  solo
                                                  item-text="name"
                                                  item-value="code"
                                                  id="student_nationality"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>

                                          <div class="right_form ma-2">
                                            <div class="form_group">
                                              <label class="form_label" for="student_phone">Student Phone</label>
                                              <vue-phone-number-input
                                                  v-model="student_phone"
                                                  :border-radius="10"
                                                  id="student_phone"
                                                  class="custom-phone"
                                                  default-country-code="KE"
                                                  label="Student phone"
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                              ></vue-phone-number-input>
                                              <div class="v-messages theme--light error--text">
                                                <div class="v-messages__wrapper">
                                                  <span class="v-messages__message">{{phoneRules}}</span>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                        <v-divider></v-divider>
                                        <div class="right_form pa-2 ma-2">
                                          <div class="form_group">
                                            <v-text-field
                                                v-model="student_address"
                                                :border-radius="10"
                                                id="student_phone"
                                                required
                                                :rules="[v => !!v || 'Item is required']"
                                                label="Student Address"
                                            ></v-text-field>
                                          </div>
                                        </div>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                    <v-expansion-panel @click="validate && validatePhone">
                                      <v-expansion-panel-header>Academic Details</v-expansion-panel-header>
                                      <v-expansion-panel-content>
                                        <v-divider></v-divider>
                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="school_code"
                                                  :items="schools"
                                                  hide-details="auto"
                                                  label="Select School"
                                                  item-text="school_name"
                                                  item-value="school_code"
                                                  solo
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="school_branch"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>

                                          <div class="left_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="branch_id"
                                                  :items="schools"
                                                  hide-details="auto"
                                                  label="Select School Branch"
                                                  solo
                                                  required
                                                  item-text="school_name"
                                                  item-value="id"
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="school_branch"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>

                                          <div class="right_form">
                                            <div class="form_group autocomplete">
                                                <v-select
                                                    v-model="curriculum_id"
                                                    :items="curriculums"
                                                    item-text="curriculum_name"
                                                    item-value="id"
                                                    label="Select"
                                                    chips
                                                    :rules="[v => !!v || 'Item is required']"
                                                    hint="Select a curriculum"
                                                    persistent-hint
                                                ></v-select>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>
                                        </div>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="class_id"
                                                  :items="studentClasses"
                                                  item-text="class_name"
                                                  item-value="id"
                                                  hide-details="auto"
                                                  label="Select Class"
                                                  solo
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="student_class"
                                              >
                                                <template v-slot:append-item>
                                                  <v-divider class="mb-2"></v-divider>
                                                  <v-list-item>
                                                    <v-list-item-content>
                                                      <v-list-item-action-text>

                                                      </v-list-item-action-text>
                                                    </v-list-item-content>
                                                  </v-list-item>
                                                </template>
                                              </v-autocomplete>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>

                                          <div class="right_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="stream_id"
                                                  :items="studentStreams"
                                                  item-text="stream_name"
                                                  item-value="id"
                                                  hide-details="auto"
                                                  label="Select Stream*"
                                                  solo
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="student_stream"
                                              >
                                                <template v-slot:append-item>
                                                  <v-divider class="mb-2"></v-divider>
                                                  <v-list-item>
                                                    <v-list-item-content>
                                                      <v-list-item-action-text>

                                                      </v-list-item-action-text>
                                                    </v-list-item-content>
                                                  </v-list-item>
                                                </template>
                                              </v-autocomplete>
                                            </div>
                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="date_enrolled"
                                                  label="Enrollment Date *"
                                                  required
                                                  id="student_enrollment_date(optional)"
                                                  type="date"
                                                  :rules="[v => !!v || 'Item is required']"
                                                  @input="$v.date_enrolled.$touch()"
                                                  @blur="$v.date_enrolled.$touch()"
                                              ></v-text-field>
                                              <!--                                              <input type="date" v-model="date_enrolled" id="student_enrollment_date">-->
                                            </div>
                                          </div>
                                          <div class="right_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="year_id"
                                                  :items="graduations"
                                                  hide-details="auto"
                                                  label="Select Graduation*"
                                                  item-text="years"
                                                  item-value="id"
                                                  solo
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  id="student_graduation"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                        </div>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                    <v-expansion-panel>
                                      <v-expansion-panel-header>Guardian Details</v-expansion-panel-header>
                                      <v-expansion-panel-content>
                                        <div class="full_width">
                                          <div class="form_group text_center margin-bottom_md">

                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="guardian_name"
                                                  label="Guardian Name *"
                                                  required
                                                  id="guardian_name"
                                                  type="text"
                                                  :rules="[v => !!v || 'Item is required']"
                                                  @input="$v.guardian_name.$touch()"
                                                  @blur="$v.guardian_name.$touch()"
                                              ></v-text-field>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="date_enrolled"
                                                  label="Date Enrolled *"
                                                  required
                                                  id="student_enrollment_date"
                                                  type="date"
                                                  :rules="[v => !!v || 'Item is required']"
                                                  @input="$v.date_enrolled.$touch()"
                                                  @blur="$v.date_enrolled.$touch()"
                                              ></v-text-field>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>
                                          <div class="right_form">
                                            <div class="form_group">
                                              <vue-phone-number-input
                                                  v-model="guardian_mobile_number"
                                                  :border-radius="10"
                                                  id="guardian_phone"
                                                  class="custom-phone"
                                                  label="Guardian Phone"
                                                  default-country-code="KE"
                                                  required
                                                  :rules="[v => !!v || 'Item is required']"
                                                  @input="$v.guardian_mobile_number.$touch()"
                                                  @blur="$v.guardian_mobile_number.$touch()"
                                              ></vue-phone-number-input>
                                            </div>
                                          </div>
                                        </div>
                                        <v-divider></v-divider>

                                        <div class="full_width">
                                          <div class="left_form">
                                            <div class="form_group">
                                              <v-text-field
                                                  v-model="guardian_email"
                                                  label="Guardian Email *"
                                                  required
                                                  id="student_enrollment_date"
                                                  type="email"
                                                  :rules="[v => !!v || 'Date Enrolled is required']"
                                                  @input="$v.guardian_email.$touch()"
                                                  @blur="$v.guardian_email.$touch()"
                                              ></v-text-field>
                                            </div>
                                          </div>
                                          <v-divider></v-divider>

                                          <div class="right_form">
                                            <div class="form_group autocomplete">
                                              <v-autocomplete
                                                  v-model="guardian_studet_relationship"
                                                  :items="relationships"
                                                  hide-details="auto"
                                                  label="Select Relationship"
                                                  solo
                                                  required
                                                  :rules="[v => !!v || 'Date Enrolled is required']"
                                                  id="guardian_relationship"
                                              ></v-autocomplete>
                                            </div>
                                          </div>
                                        </div>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                  </v-expansion-panels>
                                  <v-alert
                                      border="left"
                                      dense
                                      outlined
                                      type="success"
                                      v-if="submitSuccess"
                                  >
                                    {{ successMessage }}
                                  </v-alert>

                                  <v-alert
                                      border="left"
                                      dense
                                      outlined
                                      type="error"
                                      v-if="validationError"
                                  >
                                    {{ validationErrorMessage }}
                                  </v-alert>
                                  <v-alert
                                      border="left"
                                      dense
                                      outlined
                                      class="pa-3 ma-2"
                                      type="error"
                                      v-if="responseError"
                                  >
                                    {{ responseErrorMessage }}
                                  </v-alert>
                                  <small>*indicates required field</small>
                                  <div class="action_btns modal_footer">
<!--                                    <input type="submit" value="Create Student">-->
                                    <v-col
                                        cols="12"
                                    >
                                      <v-btn
                                          class="mr-4"
                                          @click="submitForm(checkedit)"
                                      >
                                        submit
                                      </v-btn>
                                      <v-btn @click="clear">
                                        clear
                                      </v-btn>
                                    </v-col>
<!--                                    <a href="" class="cancel_btn" @click.prevent="closeModal">Cancel</a>-->
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                </v-form>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import {required} from "vuelidate/lib/validators";
import Vue from "vue";
import vuelidate from 'vuelidate'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import {Cropper} from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.classic.css';
import CurriculumService from "@/services/CurriculumService";
import AdminService from "@/services/AdminService";
import variousCountryListFormats from "@/variousCountryListFormats";
import SchoolsService from "~/services/SchoolService";
import ClassesService from "@/services/ClassesService";
import StreamsService from "@/services/StreamsService";
import streams_list from "@/services/streams_list";
import StudentService from "@/services/StudentService";
import GraduationsService from "@/services/GraduationsService";

Vue.use(vuelidate)

export default {
  name: "SchoolForm",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    "propSchoolCode"
  ],
  components: {VuePhoneNumberInput, Cropper},
  data() {
    return {
      dialog: false,
      valid:true,
      student_code:null,
      student_name :null,
      student_email :null,
      date_of_birth :null,
      gender :null,
      nationality :null,
      student_phone :null,
      student_phone_country_code :"KE",
      student_address :null,
      school_id :null,
      school_code:null,
      curriculum_id:null,
      class_id :null,
      stream_id :null,
      branch_id:null,
      date_enrolled :null,
      year_id:null,
      guardian_name :null,
      guardian_mobile_number :null,
      guardian_mobile_number_country_code :"KE",
      guardian_email :null,
      guardian_studet_relationship :null,
      student_id:null,
      studentStreams:[],
      schoolBranches: ['Westlands', 'Parklands', 'Mombasa', 'Thika', 'General Mathenge', 'Eldoret'],
      nationalities: ['Kenya', 'Nigeria', 'United Kingdom', 'United States of America'],
      curriculums: ['KCSE', 'IGCSE', 'IB'],
      genders: ['Male', 'Female', 'I would rather not say'],
      relationships: ['Father', 'Mother', 'Brother', 'Sister', 'Uncle', 'Aunt', 'Other'],
      graduations: [],
      panel: 0,
      studentClasses:[],
      inviteSuccess: false,
      alertClass: 'alert-success',
      issue_license :null,
      status: null,
      admin_id: null,
      curriculumErrors: [],
      classNameErrors: [],
      submitSuccess: false,
      successMessage: "",
      responseError: false,
      responseErrorMessage: "",
      validationError: false,
      validationErrorMessage: "",
      alertMsg:"",
      is_global: false,
      countries: [],
      schools:[],
      checkedit: false,
      editingItem: this.editing || false,
      phoneRules:null
    }
  },
  validations: {
    student_name:{required},
    student_email:{required},
    date_of_birth:{required},
    student_phone:{required},
    student_address:{required},
    curriculum_id:{required},
    class_id:{required},
    stream_id:{required},
    date_enrolled :{required},
    year_id:{required},
    guardian_name:{required},
    guardian_mobile_number:{required},
    guardian_email:{required},
    guardian_studet_relationship:{required},
    branch_id:{required},
    school_code:{required},
  },
  created() {
    this.setCountryList()
    this.listSchools()
    this.listClasses()
    this.listStreams()
    this.listGraduations()
  },
  mounted() {
    this.listCurriculums();
    this.editingItem = this.editing
    if (this.editingItem) {
      this.dialog = true
    }
  },
  computed: {
    setEditing() {
      this.editing = false
      return this.editing
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountryWithCodeList()
    },
    async listClasses(){
      let response =await ClassesService.listClasses()
      if(response.data.status){
        this.studentClasses=response.data.data.items
        this.successMessage = response.data.message
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async listStreams(){
      let response =await StreamsService.listStreams(this.propSchoolCode,1)
      if(response.data.status){
        this.studentStreams =response.data.data.items
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async listGraduations(){
      let response =await GraduationsService.listGraduations(this.propSchoolCode,1)
      if(response.data.status){
        this.graduations =response.data.data.items
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    openDialog() {
      this.dialog = true;
      // $('#add_new_user').modal('Show')
    },
    closeDialog() {
      this.dialog = false
      this.editingItem = false
      this.forDataFields()
      // $('#add_new_user').modal('hide')
      this.$emit("resetEditing", false)
    },
    openEditingDialog() {
      this.dialog = true
      // $('#add_new_user').modal('Show')
    },
    loadSchoolLogo(event) {
      const input = event.target;
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          this.school_logo_url = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },
    async submitForm(editing_id) {
      this.validate()
      this.validatePhone()
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError = true
        this.validationErrorMessage = "kindly fill required field "
      } else {
        let formData = new FormData()
        formData.append("student_image", this.student_image)
        formData.append("guardian_profile_photo", this.guardian_profile_photo)
        formData.append("student_code", this.student_code)
        formData.append("student_name", this.student_name)
        formData.append("student_email", this.student_email)
        formData.append("date_of_birth", this.date_of_birth)
        formData.append("gender", this.gender)
        formData.append("nationality", this.nationality)
        formData.append("student_phone", this.student_phone)
        formData.append("student_phone_country_code", this.student_phone_country_code)
        formData.append("country_code", this.student_phone_country_code)
        formData.append("school_id", this.school_id)
        formData.append("curriculum_id", this.curriculum_id)
        formData.append("class_id", this.class_id)
        formData.append("stream_id", this.stream_id)
        formData.append("date_enrolled", this.date_enrolled)
        formData.append("year_id", this.year_id)
        formData.append("guardian_name", this.guardian_name)
        formData.append("guardian_mobile_number", this.guardian_mobile_number)
        formData.append("guardian_mobile_number_country_code", this.guardian_mobile_number_country_code)
        formData.append("guardian_email", this.guardian_email)
        formData.append("guardian_studet_relationship", this.guardian_studet_relationship)
        formData.append("issue_license", this.issue_license)

        let response = null
        if (editing_id) {
          response = await StudentService.updateStudent(this.propSchoolCode,this.student_id, formData)
        } else {
          response = await StudentService.addStudent(this.propSchoolCode,formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess = true
          this.validationError = false
          this.responseError = false
          this.inviteSuccess = true
          if (!editing_id) {
            this.forDataFields()
          } else {
            setTimeout(() => {
              this.forDataFields()
              this.dialog = false
            }, "4000")
          }
          this.$nuxt.$emit("refreshAdminList", true)
          setTimeout(() => {
            this.submitSuccess = false
            this.successMessage = ""
          }, "4000")

        } else {
          this.responseError = true
          this.responseErrorMessage = response.data.message
        }
      }
    },
    forDataFields() {
      let feilds = [
          this.student_image = null,
          this.guardian_profile_photo = null,
          this.guardian_cropped_photo="",
          this.student_code= null,
          this.student_name = null,
          this.student_email = null,
          this.date_of_birth = null,
          this.gender = null,
          this.nationality = null,
          this.student_phone = null,
          this.student_phone_country_code = "KE",
          this.student_address = null,
          this.school_id = null,
          this.curriculum_id= null,
          this.class_id = null,
          this.stream_id = null,
          this.date_enrolled = null,
          this.year_id= null,
          this.guardian_name = null,
          this.guardian_mobile_number = null,
          this.guardian_mobile_number_country_code = "KE",
          this.guardian_email = null,
          this.guardian_studet_relationship = null,
      ]
      return feilds
    },
    validate () {
      this.$refs.form.validate()
      this.validatePhone()
    },
    validatePhone(){
      if (String(this.student_phone) ==="null"|| String(this.student_phone).length<1) {
        this.phoneRules = 'Item is required'
      }else{
        this.phoneRules=""
      }
    },
    async listCurriculums() {
      let response = await CurriculumService.listCurriculum();
      if (response.data.status) {
        this.curriculums = response.data.data.items
      }
    },
    async listSchools(){
      this.loading=true
      let response =await SchoolsService.listSchools()
      if(response.data.status) {
        this.schools = response.data.data.items
        this.loading = false
      }
    },
    clear() {
      this.class_name = null
      this.curriculum_id = null
    },
    setEditingData(data) {
      if (this.curriculums.length < 1) {
        this.listCurriculums()
      }
        this.student_image = data.student_image
        this.guardian_profile_photo = data.guardian_profile_photo
        this.guardian_cropped_photo= data.guardian_cropped_photo
        this.student_code= data.student_code
        this.student_name = data.student_name
        this.student_email = data.student_email
        this.date_of_birth = data.date_of_birth
        this.gender = data.gender
        this.nationality = data.nationality
        this.student_phone = data.student_phone
        this.student_phone_country_code = data.student_phone_country_code
        this.student_address = data.student_address
        this.school_id = data.school_id
        this.curriculum_id= data.curriculum_id
        this.class_id = data.class_id
        this.stream_id = data.stream_id
        this.date_enrolled = data.date_enrolled
        this.year_id= data.year_id
        this.guardian_name = data.guardian_name
        this.guardian_mobile_number = data.guardian_mobile_number
        this.guardian_mobile_number_country_code = data.guardian_mobile_number_country_code
        this.guardian_email = data.guardian_email
        this.guardian_studet_relationship = data.guardian_studet_relationship
    },
    cropStudent() {
      const {coordinates, canvas} = this.$refs.cropper.getResult();
      this.coordinates = coordinates;
      this.student_cropped_photo = canvas.toDataURL();
      this.student_photo = null;
    },
    loadStudentImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        //Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.student_photo) {
          URL.revokeObjectURL(this.student_photo);
        }

        const reader = new FileReader();
        reader.onload = e => {
          this.student_photo = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },
    cropGuardian() {
      const {coordinates, canvas} = this.$refs.cropper.getResult();
      this.coordinates = coordinates;
      this.guardian_cropped_photo = canvas.toDataURL();
      this.guardian_photo = null;
    },
    loadGuardianImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        //Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.guardian_photo) {
          URL.revokeObjectURL(this.guardian_photo);
        }

        const reader = new FileReader();
        reader.onload = e => {
          this.guardian_photo = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },
  },
  watch: {
    editing: {
      immediate: true,
      handler(val, oldVal) {
        if (this.editing) {
          this.dialog = true
          this.setEditingData(this.itemData)
          return this.setEditing
        }
      }
    }
  }
}
</script>
<style scoped>
.modal-dialog {
  position: relative;
  width:900px !important;
  max-width:900px !important;
  margin: 0.5rem;
  pointer-events: none;
}
</style>
