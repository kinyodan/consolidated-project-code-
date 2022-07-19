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
                <v-form>
                  <v-container>

                    <!--                      <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="updateSuccess">-->
                    <!--                        <span v-html="alertMsg"></span>-->
                    <!--                        <button type="button" class="close_alert" @click="updateSuccess = false" aria-label="Close">&times;-->
                    <!--                        </button>-->
                    <!--                      </div>-->

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
                          :counter="10"
                          label="School Name *"
                          required
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
                          :counter="10"
                          label="School Email *"
                          required
                          @input="$v.school_email.$touch()"
                          @blur="$v.school_email.$touch()"
                        ></v-text-field>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_phone"
                          :counter="10"
                          label="School Phone *"
                          required
                          @input="$v.school_phone.$touch()"
                          @blur="$v.school_phone.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_address"
                          :counter="10"
                          label="School Address *"
                          required
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
                          :counter="10"
                          label="School Physical Address *"
                          required
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
                          :counter="10"
                          label="school Website"
                          required
                          @input="$v.school_website_url.$touch()"
                          @blur="$v.school_website_url.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="discount_value"
                          :counter="10"
                          label="Discount Value"
                          required
                          @input="$v.discount_value.$touch()"
                          @blur="$v.discount_value.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="school_code"
                          :counter="10"
                          label="School Code"
                          required
                          @input="$v.school_code.$touch()"
                          @blur="$v.school_code.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-select
                          v-model="country_code"
                          :items="countries"
                          :error-messages="curriculumErrors"
                          label="Country"
                          item-text="name"
                          item-value="code"
                          required
                          @change="$v.curriculum_id.$touch()"
                          @blur="$v.curriculum_id.$touch()"
                        ></v-select>
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

Vue.use(vuelidate)

export default {
  name: "SchoolForm",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text"
  ],
  data() {
    return {
      dialog: false,
      curriculum_id: null,
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
    }
  },
  validations: {
    curriculum_id: {required},
    school_code: {required},
    country_code: {required},
    school_name: {required},
    school_email: {required},
    school_phone: {required},
    school_address: {required},
    school_physical_address: {required},
    school_website_url: {required},
    school_logo_url: {required},
    discount_value: {required},
  },
  created() {
    this.setCountryList()
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
    openDialog() {
      this.dialog = true;
    },
    openEditingDialog() {
      this.dialog = true
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
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError = true
        this.validationErrorMessage = "kindly fill required field "
      } else {
        let formData = new FormData()
        formData.append("curriculum_id", this.curriculum_id)
        formData.append("school_code", this.school_code)
        formData.append("country_code", this.country_code)
        formData.append("school_name", this.school_name)
        formData.append("school_email", this.school_email)
        formData.append("school_phone", this.school_phone)
        formData.append("school_address", this.school_address)
        formData.append("school_physical_address", this.school_physical_address)
        formData.append("school_website_url", this.school_website_url)
        formData.append("school_logo_url", this.school_logo_url)
        formData.append("discount_value", this.discount_value)

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
          if (!editing_id) {
            this.forDataFields()
          } else {
            setTimeout(() => {
              this.forDataFields()
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
    forDataFields() {
      let feilds = [
        this.curriculum_id = null,
        this.school_code = null,
        this.country_code = null,
        this.school_email = null,
        this.school_phone = null,
        this.school_address = null,
        this.school_physical_address = null,
        this.school_website_url = null,
        this.school_logo_url = null,
        this.discount_value = null,
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
      this.forDataFields()
      this.$emit("resetEditing", false)
    },
    setEditingData(data) {
      console.log(data)
      if (this.curriculums.length < 1) {
        this.listCurriculums()
      }
      let curriculum_index = this.curriculums.findIndex(item => item.curriculum_code === data.curriculum_code)
      this.curriculum_id = this.curriculums[curriculum_index]
      this.school_code = data.school_code
      this.country_code = data.country_code
      this.school_email = data.school_email
      this.school_phone = data.school_phone
      this.school_address = data.school_address
      this.school_physical_address = data.school_physical_address
      this.school_website_url = data.school_website_url
      this.school_logo_url = data.school_logo_url
      this.discount_value = data.discount_value
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
    }
  }
}
</script>
