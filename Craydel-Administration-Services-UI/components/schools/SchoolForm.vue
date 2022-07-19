<template>
  <v-container>
    <template>
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        Add School
      </v-btn>
    </template>
    <v-dialog
      v-model="dialog"
      persistent
      activator="parent"
      max-width="900px"
    >
      <v-card>
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
        </v-card-title>
        <v-card-text>
          <v-container>
            <template>
              <template>
                <v-form
                v-model="valid"
                ref="form"
                >
                  <v-container>
                    <div class="fieldset">
                      <div>
                        <span class="field-label">School Logo</span>
                      </div>
                      <div class="inline-field">
                        <v-img
                          :src="school_logo_url"
                          alt="School logo"
                          max-width="292"
                          min-height="85"
                          class="school-logo"
                        >
                          <template v-slot:placeholder>
                            <v-row
                              class="fill-height ma-0"
                              align="center"
                              justify="center"
                            >
                              <v-progress-circular
                                indeterminate
                                color="grey"
                              ></v-progress-circular>
                            </v-row>
                          </template>
                        </v-img>
                        <div class="file-upload">
                          <label class="file-upload__btn">
                            <input type="file" required @change="loadSchoolLogo" id="school_logo" accept="image/*">
                            <svg id="camera_icon" width="20" height="17.3" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 17.3">
                              <path d="M10,7c1.7,0,3,1.3,3,3s-1.3,3-3,3s-3-1.3-3-3S8.3,7,10,7z M17.3,2.7c1.5,0,2.7,1.2,2.7,2.7v9.3c0,1.5-1.2,2.7-2.7,2.7H2.7
	c-1.5,0-2.7-1.2-2.7-2.7V5.3c0-1.5,1.2-2.7,2.7-2.7H5l0.5-1.4C5.8,0.6,6.6,0,7.3,0h5.3c0.7,0,1.5,0.6,1.8,1.2L15,2.7H17.3z M10,14.7
	c2.6,0,4.7-2.1,4.7-4.7S12.6,5.3,10,5.3S5.3,7.4,5.3,10S7.4,14.7,10,14.7z"/>
                            </svg>
                            Replace logo
                          </label>
                        </div>
                      </div>
                    </div>
                    <v-row>
                      <v-col
                        cols="12"
                      >
                        <v-text-field
                          v-model="school_name"
                          label="School Name *"
                          required
                          :rules="[v => !!v || 'Item is required']"
                          @input="$v.school_name.$touch()"
                          @blur="$v.school_name.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                      >
                        <v-select
                          v-model="curriculum_id"
                          :items="curriculums"
                          :error-messages="curriculumErrors"
                          label="Curriculum *"
                          item-text="curriculum_name"
                          item-value="id"
                          required
                          :rules="[v => !!v || 'Item is required']"
                          @change="$v.curriculum_id.$touch()"
                          @blur="$v.curriculum_id.$touch()"
                        ></v-select>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_email"
                          label="School Email *"
                          required
                          type="email"
                          :rules="emailRules"
                          @input="$v.school_email.$touch()"
                          @blur="$v.school_email.$touch()"
                        ></v-text-field>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <vue-phone-number-input
                            v-model="school_phone"
                            :border-radius="10"
                            id="school_phone"
                            class="custom-phone"
                            default-country-code="KE"
                            required
                            :rules="phoneRules"
                            @input="$v.school_phone.$touch()"
                            @blur="$v.school_phone.$touch()"
                            @keyup.enter.native="validatePhone()"
                        ></vue-phone-number-input>
                        <div class="v-messages theme--light error--text">
                          <div class="v-messages__wrapper">
                            <span class="v-messages__message">{{phoneRules}}</span>
                          </div>
                        </div>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_address"
                          label="School Address *"
                          required
                          :rules="[v => !!v || 'Item is required']"
                          @input="$v.school_address.$touch()"
                          @blur="$v.school_address.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_physical_address"
                          label="School Physical Address *"
                          required
                          :rules="[v => !!v || 'Item is required']"
                          @input="$v.school_physical_address.$touch()"
                          @blur="$v.school_physical_address.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_website_url"
                          label="school Website"
                          required
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="discount_value"
                          label="Discount Value"
                        ></v-text-field>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-autocomplete
                            v-model="country_code"
                            :items="countries"
                            hide-details="auto"
                            label="Parent School (optional)"
                            item-text="name"
                            item-value="code"
                            solo
                            required
                            :rules="[v => !!v || 'Item is required']"
                            id="parent_school_id"
                        ></v-autocomplete>
                      </v-col>
                      <v-col
                          cols="12"
                          md="6"
                      >
                        <v-autocomplete
                            v-model="parent_school_id"
                            :items="schools"
                            hide-details="auto"
                            label="Parent School (optional)"
                            item-text="school_name"
                            item-value="id"
                            solo
                            required
                            :rules="[v => !!v || 'Item is required']"
                            id="parent_school_id"
                        ></v-autocomplete>
                      </v-col>
                      <v-col>
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
                          v-if="responseError"
                        >
                          {{ responseErrorMessage }}
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
                      </v-col>
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
                    </v-row>
                  </v-container>
                </v-form>
              </template>
            </template>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import {required} from "vuelidate/lib/validators";
import Vue from "vue";
import vuelidate from 'vuelidate'
import CurriculumService from "@/services/CurriculumService";
import SchoolService from "@/services/SchoolService";
import variousCountryListFormats from "@/variousCountryListFormats";
import VuePhoneNumberInput from 'vue-phone-number-input';

Vue.use(vuelidate)

export default {
  name: "SchoolForm",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    'schools'
  ],
  components:{VuePhoneNumberInput},
  data() {
    return {
      dialog: false,
      valid:true,
      curriculum_id: [],
      school_code: "",
      country_code: "",
      school_name: "",
      school_email: "",
      school_phone: "",
      school_address: "",
      school_physical_address: "",
      school_website_url: "",
      school_logo_url: "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png",
      school_inverse_logo_url: "",
      discount_value: "",
      curriculums: [],
      status: null,
      school_id: null,
      parent_school_id:null,
      curriculumErrors: [],
      classNameErrors: [],
      submitSuccess: false,
      successMessage: "",
      responseError: false,
      responseErrorMessage: "",
      validationError: false,
      validationErrorMessage: "",
      is_global: false,
      countries: [],
      checkedit: false,
      editingItem: this.editing || false,
      emailRules:[v => !!v || 'Item is required'],
      phoneRules:null
    }
  },
  validations: {
    curriculum_id: {required},
    country_code: {required},
    school_name: {required},
    school_email: {required},
    school_phone: {required},
    school_address: {required},
    school_physical_address: {required},
    school_logo_url: {required},
  },
  created() {
    this.setCountryList()
    this.setSchoolsList()
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
    setSchoolsList(){
      let firstItem={id:null,school_name:"Select parent school"}
    },
    openDialog() {
      this.dialog = true;
    },
    openEditingDialog() {
      this.dialog = true
      this.validate()
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
    validate () {
      this.$refs.form.validate()
      this.validatePhone()
    },
    validatePhone(){
      if (String(this.school_phone) ==="null"|| String(this.school_phone).length<1) {
        this.phoneRules = 'Item is required'
      }else{
        this.phoneRules=""
      }
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    async submitForm(editing_id) {
      this.validate()
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError = true
        this.validationErrorMessage = "kindly fill required field "
      } else {
        let formData = new FormData()
        // formData.append("curriculum_id", this.curriculum_id)
        formData.append("curriculum_id", this.curriculum_id)
        formData.append("country_code", this.country_code)
        formData.append("school_name", this.school_name)
        formData.append("school_email", this.school_email)
        formData.append("school_phone", this.school_phone)
        formData.append("school_address", this.school_address)
        formData.append("school_physical_address", this.school_physical_address)
        formData.append("school_website_url", this.school_website_url)
        formData.append("school_logo_url", this.school_logo_url)
        formData.append("discount_value", this.discount_value)
        formData.append("parent_school_id", this.parent_school_id)

        let response = null
        if (editing_id) {
          response = await SchoolService.updateSchool(this.school_id, formData)
        } else {
          response = await SchoolService.addSchool(formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess = true
          this.validationError = false
          this.responseError = false
          this.resetValidation()
          if (!editing_id) {
            this.resetDataFields()
          } else {
            setTimeout(() => {
              this.resetDataFields()
              this.dialog = false
            }, "4000")
          }
          this.$nuxt.$emit("refreshSchoolList", true)
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
    resetDataFields() {
      let feilds = [
        this.curriculum_id = null,
        this.school_name=null,
        this.school_code = null,
        this.country_code = null,
        this.school_email = null,
        this.school_phone = null,
        this.school_address = null,
        this.school_physical_address = null,
        this.school_website_url = null,
        this.school_logo_url= "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png",
        this.discount_value = null,
        this.parent_school_id=null
      ]
      return feilds
    },
    async listCurriculums() {
      let response = await CurriculumService.listCurriculum();
      if (response.data.status) {
        this.curriculums = response.data.data.items
      }
    },
    clear() {
      this.class_name = null
      this.curriculum_id = null
    },
    closeDialog() {
      this.dialog = false
      this.editingItem = false
      this.resetDataFields()
      this.$emit("resetEditing", false)
      if(!this.editing){
        this.resetValidation()
      }
    },
    setEditingData(data) {
      if (this.curriculums.length < 1) {
        this.listCurriculums()
      }
      if (this.countries.length < 1) {
        this.setCountryList()
      }
      this.curriculum_id = data.curriculum_id
      this.school_name=data.school_name
      this.school_code = data.school_code
      let country_index = this.countries.findIndex(item => item.code === data.country_code)
      if(country_index!==-1){ this.country_code = this.countries[country_index].code}
      this.school_email = data.school_email
      this.school_phone = data.school_phone
      this.school_address = data.school_address
      this.school_physical_address = data.school_physical_address
      this.school_website_url = data.school_website_url
      if(data.school_logo_url){
        this.school_logo_url = data.school_logo_url
      }else{
        this.school_logo_url= "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png"
      }
      this.discount_value = data.discount_value
      this.parent_school_id = data.parent_school_id
      this.checkedit = true;
      this.school_id = data.id
    }
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
    },
    school_email: function (mail) {
      if(this.dialog) {
        if (mail && mail !== '' && this.dialog) {
          this.emailRules = [v => (v.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) || 'Invalid Email address']|| "invalid entry"
        } else if (mail === '') {
          this.emailRules = [v => !!v || 'Item is required']
        } else {
          this.emailRules = []
        }
      }
    },
    school_phone: function (phone) {
      if (phone && String(phone)==="null") {
        this.phoneRules = 'Item is required'
      }else{
        this.phoneRules=""
      }
    }
  }
}
</script>
