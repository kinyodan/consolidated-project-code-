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
                    <v-row>
                      <v-col
                          cols="12"
                      >
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
                        <v-text-field
                            v-model="admin_name"
                            label="Admin Name *"
                            required
                            :rules="[v => !!v || 'Item is required']"
                            @input="$v.admin_name.$touch()"
                            @blur="$v.admin_name.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-select
                            v-model="school_code"
                            :items="schools"
                            label="School"
                            item-text="school_name"
                            item-value="school_code"
                            required
                            :rules="[v => !!v || 'Item is required']"
                            @change="$v.school_code.$touch()"
                            @blur="$v.school_code.$touch()"
                        ></v-select>
                      </v-col>
                      <v-col
                          cols="12"
                          md="6"
                      >
                        <v-select
                            v-model="admin_role"
                            :items="roles"
                            label="Admin Role"
                            item-text="name"
                            item-value="name"
                            required
                            :rules="[v => !!v || 'Item is required']"
                            @change="$v.admin_role.$touch()"
                            @blur="$v.admin_role.$touch()"
                        ></v-select>
                      </v-col>

                      <v-col
                        cols="12"
                        md="6"
                      >
                        <v-text-field
                          v-model="admin_email"
                          label="Admin Email *"
                          required
                          :rules="[v => !!v || 'Item is required']"
                          @input="$v.admin_email.$touch()"
                          @blur="$v.admin_email.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col
                          cols="12"
                          md="6"
                      >
                        <vue-phone-number-input
                            v-model="admin_phone"
                            :border-radius="10"
                            id="school_phone"
                            class="custom-phone"
                            default-country-code="KE"
                            required
                            :rules="phoneRules"
                            @input="$v.admin_phone.$touch()"
                            @blur="$v.admin_phone.$touch()"
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
import AdminService from "@/services/AdminService";
import variousCountryListFormats from "@/variousCountryListFormats";
import SchoolsService from "~/services/SchoolService";
import VuePhoneNumberInput from 'vue-phone-number-input';

Vue.use(vuelidate)

export default {
  name: "SchoolForm",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    "schools"
  ],
  components:{
    VuePhoneNumberInput
  },
  data() {
    return {
      dialog: false,
      valid:true,
      school_code:null,
      admin_name:null,
      admin_email:null,
      admin_phone:null,
      admin_address:null,
      admin_role:null,
      curriculums: [],
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
      is_global: false,
      countries: [],
      roles:[{name:"role1"},{name:"role2"},{name:"role3"},{name:"role4"},{name:"role5"}],
      checkedit: false,
      editingItem: this.editing || false,
      phoneRules:null
    }
  },
  validations: {
    school_code:{required},
    admin_name:{required},
    admin_email:{required},
    admin_phone:{required},
    admin_role:{required},
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
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    validatePhone(){
      if (String(this.school_phone) ==="null"|| String(this.school_phone).length<1) {
        this.phoneRules = 'Item is required'
      }else{
        this.phoneRules=""
      }
    },
    async submitForm(editing_id) {
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError = true
        this.validationErrorMessage = "kindly fill required field "
      } else {
        let formData = new FormData()
        formData.append("school_code", this.school_code)
        formData.append("admin_name", this.admin_name)
        formData.append("admin_email", this.admin_email)
        formData.append("admin_phone", this.admin_phone)
        formData.append("admin_address", this.admin_address)
        formData.append("admin_role", this.admin_role)

        let response = null
        if (editing_id) {
          response = await AdminService.updateAdmin(this.school_code,this.admin_id, formData)
        } else {
          response = await AdminService.addAdmin(this.school_code,formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess = true
          this.validationError = false
          this.responseError = false
          this.responseErrorMessage= ""
          this.validationError= false
          this.validationErrorMessage= ""
          this.resetValidation()
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
          this.validationError= false
          this.validationErrorMessage= ""
          this.responseError = true
          this.responseErrorMessage = response.data.message
        }
      }
    },
    forDataFields() {
      let feilds = [
        this.school_code= null,
        this.admin_name= null,
        this.admin_email= null,
        this.admin_phone= null,
        this.admin_role= null,
        this.admin_address=null,
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
      this.resetValidation()
      this.button_text="Add Admin"
      this.$emit("resetEditing", false)
    },
    setEditingData(data) {
      let schoolIndex=this.schools.findIndex(i=>i.id===data.school_id)
      this.school_code=this.schools[schoolIndex].school_code || null
      this.school_id= data.school_id
      this.admin_name= data.admin_name
      this.admin_email= data.admin_email
      this.admin_phone= data.admin_phone
      this.admin_role= data.admin_role
      this.admin_address=data.admin_address
      this.checkedit = true;
      this.admin_id = data.id
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
