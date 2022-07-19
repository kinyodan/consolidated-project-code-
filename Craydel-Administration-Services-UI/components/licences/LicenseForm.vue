<template>
  <v-container>
    <template >
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        {{ button_text }}
      </v-btn>
    </template>

    <template >
      <v-btn
          color="primary"
          dark
          v-on:click="openDialogRemove"
      >
        {{ button_text_remove }}
      </v-btn>
    </template>
    <v-dialog
      v-model="dialog"
      persistent
      activator="parent"
      max-width="600px"
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
        <AddLicenseForm :schoolDetails="schoolDetails"></AddLicenseForm>
      </v-card>
    </v-dialog>

    <v-dialog
        v-model="dialog_remove"
        persistent
        activator="parent"
        max-width="600px"
    >
      <v-card>

        <v-card-title>
          <span class="text-h5">{{ button_text_remove }}</span>
          <v-spacer></v-spacer>
          <v-btn
              color="blue darken-1"
              text
              class="pa-5"
              @click="closeDialogRemove"
          >
            Close
          </v-btn>
        </v-card-title>
        <RemoveLicenseForm :schoolDetails="schoolDetails"></RemoveLicenseForm>
      </v-card>
    </v-dialog>

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
import RemoveLicenseForm from "@/components/licences/RemoveLicenseForm"
import AddLicenseForm from "@/components/licences/AddLicenseForm"

Vue.use(vuelidate)

export default {
  name:"LicenceForm",
  props:[
    "dialogue_value",
    "editing",
    "itemData",
    'schoolDetails'
  ],
  components:{
    AddLicenseForm,
    RemoveLicenseForm
  },
  data(){
    return{
      dialog: false,
      dialog_remove:false,
      button_text:"Add License",
      button_text_remove:"Remove Licenses",
      school_code:this.schoolDetails.school_code||null,
      allowed_license_count:0,
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
  created() {
    this.$nuxt.$on('refreshLicenceListChild', ($event) => this.listLicences())
  },
  mounted() {
    this.listSchools()
    this.getCurrentLicenseCount()
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
      this.dialog_remove=false;
    },
    closeDialog(){
      this.dialog = false
      this.editingItem=false
      this.$emit("resetEditing",false)
    },

    openDialogRemove(){
      this.dialog_remove=true;
      this.dialog=false;

    },
    closeDialogRemove(){
      this.dialog_remove = false
      this.editingItem=false
      this.$emit("resetEditing",false)
    },
    getCurrentLicenseCount(school_code){
      let currentSchoolItemIndex=this.schools.findIndex(school => school.school_code===this.school_code)
      if(currentSchoolItemIndex!==-1){
        this.currentItemLicenceCount = this.schools[currentSchoolItemIndex].allowed_license_count
        this.allowed_license_count=0
        this.isDisabled=false
      }
    },
    setLicenseCount(add,reduce){
      if(add){
        this.allowed_license_count=parseInt(this.currentItemLicenceCount)+parseInt(this.new_allowed_license_count)
        this.isDisabledSubmit=false
      }
      if(reduce){
        this.allowed_license_count=parseInt(this.currentItemLicenceCount)-parseInt(this.new_allowed_license_count)
        this.isDisabledSubmit=false
      }
      if(this.allowed_license_count<1||null){
        this.allowed_license_count=0
        this.currentItemLicenceCount=this.allowed_license_count
      }else{
        this.currentItemLicenceCount=this.allowed_license_count
      }
    },
    async submitForm(editing_id){
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "

      }else {
        this.submitSuccess = true
        this.successMessage = "Licence submitted successfully"
        let formData =  new FormData()
        formData.append("allowed_license_count", this.allowed_license_count)

        let response =null
        if(editing_id){
          response = await LicenceService.addLicence(this.school_code,formData)
        }else{
          response = await LicenceService.addLicence(this.school_code,formData)
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
          this.getCurrentLicenseCount()
          if(!editing_id) {
            this.school_code = this.schoolDetails.school_code||null
          }else{
            setTimeout(() => {
              this.school_code = null
              this.dialog=false
            }, "4000")
          }
          this.$nuxt.$emit("refreshLicenceList",true)
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
    setEditingData(data){
      this.checkedit=true;
      this.button_text="Edit Curriculum"
      this.school_code=data[0].school_code
    },
    listLicences(){
      this.$nuxt.$emit("refreshLicenceList",true)
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
