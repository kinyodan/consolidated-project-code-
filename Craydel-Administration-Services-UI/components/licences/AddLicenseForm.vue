<template>
  <v-container>
        <v-card-text>
          <v-container>
              <template>
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
                <form>
                  <span>New number of licences is ( {{allowed_license_count}}--{{this.currentItemLicenceCount}} )</span>
                  <v-text-field
                      v-model="new_allowed_license_count"
                      label="NUmber of Licences *"
                      required
                      :rules="[v => !!v || 'Item is required']"
                      type="number"
                      @input="$v.new_allowed_license_count.$touch()"
                      @blur="$v.new_allowed_license_count.$touch()"
                  ></v-text-field>
                  <div class="text-center">
                  </div>
                  <v-row align="center">
                    <v-col
                        cols="12"
                        sm="6"
                    >

                    </v-col>
                  </v-row>
                  <v-btn
                    class="mr-4"
                    @click="submitForm(checkedit)"
                  >
                    submit
                  </v-btn>
                </form>
              </template>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
  </v-container>
</template>

<script>
import {required} from "vuelidate/lib/validators";
import Vue from "vue";
import vuelidate from 'vuelidate'
import CurriculumService from "@/services/CurriculumService";
import variousCountryListFormats from "@/variousCountryListFormats";
import LicenceService from "@/services/LicenceService";
import SchoolsService from "@/services/SchoolService";
Vue.use(vuelidate)

export default {
  name:"LicenceForm",
  props:[
    "dialogue_value",
    "editing",
    "itemData",
    'schoolDetails'
  ],
  data(){
    return{
      dialog: false,
      button_text:"Add License",
      school_code:this.schoolDetails.school_code||null,
      allowed_license_count:this.schoolDetails.allowed_license_count||0,
      new_allowed_license_count:0,
      currentItemLicenceCount:0,
      isDisabled:true,
      isDisabledSubmit:true,
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      validationError:false,
      validationErrorMessage:"",
      is_global: 1,
      countries: [],
      checkedit:false,
      editingItem:this.editing||false,
      schools:[],


    }
  },
  validations: {
    school_code: { required },
    new_allowed_license_count:{ required },
  },
  mounted() {
    this.listSchools()
    this.editingItem=this.editing
    if(this.editingItem){
      this.dialog=true
    }
  },
  computed:{
    setEditing(){
      this.editing=false
      return this.editing
    },
  },
  methods:{
    openDialog(){
      this.dialog=true;
    },
    openEditingDialog(){
      this.dialog=true
    },
    async submitForm(editing_id){
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "

      }else {
        this.allowed_license_count=parseInt(this.new_allowed_license_count)+parseInt(this.new_allowed_license_count)
        this.submitSuccess = true
        this.successMessage = "Licence submitted successfully"
        let formData =  new FormData()
        formData.append("allowed_license_count", this.allowed_license_count)
        formData.append("school_code", this.schoolDetails.school_code)

        let response =null
        if(editing_id){
          response = await LicenceService.addLicence(this.schoolDetails.school_code,formData)
        }else{
          response = await LicenceService.addLicence(this.schoolDetails.school_code,formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess=true
          this.validationError=false
          this.responseError=false
          this.allowed_license_count=0
          this.currentItemLicenceCount=0
          this.new_allowed_license_count=0
          this.school_code=this.schoolDetails.school_code||0
          this.isDisabled=true
          this.isDisabledSubmit=true
          // this.getCurrentLicenseCount()
          if(!editing_id) {
            this.school_code = this.schoolDetails.school_code||null
          }else{
            setTimeout(() => {
              this.school_code = null
              this.dialog=false
            }, "4000")
          }
          this.$nuxt.$emit("refreshLicenceListChild",true)
          setTimeout(() => {
            this.submitSuccess=false
            this.successMessage=""
          }, "4000")

        } else {
          this.responseError = true
          this.responseErrorMessage = response.data.message
        }
      }
    },
    async listSchools(){
      let response =await SchoolsService.listSchools()
      if(response.data.status){
        this.schools=response.data.data.items
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    clear(){
      this.school_code=null
    },
    closeDialog(){
      this.dialog = false
      this.editingItem=false
      this.$emit("resetEditing",false)
    },
    setEditingData(data){
      this.checkedit=true;
      this.button_text="Edit Curriculum"
      this.school_code=data[0].school_code
    }
  },
  watch: {
    editing: {
      immediate: true,
        handler (val, oldVal) {
        if(this.editing){
          this.dialog=true
          this.setEditingData(this.itemData)
          return this.setEditing
        }
      }
    }
  }
}
</script>
