<template>
  <v-container>
    <template >
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        Add Curriculum
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
          <span class="text-h5">User Profile</span>
        </v-card-title>
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
                  <v-text-field
                    v-model="curriculum_name"
                    :error-messages="curriculumNameErrors"
                    :counter="10"
                    label="Curriculum Name"
                    required
                    @input="$v.curriculum_name.$touch()"
                    @blur="$v.curriculum_name.$touch()"
                  ></v-text-field>
                  <v-select
                    v-model="country_code"
                    :items="countries"
                    item-text="name"
                    item-value="code"
                    :error-messages="countryCodeErrors"
                    label="Item"
                    required
                    @change="$v.country_code.$touch()"
                    @blur="$v.country_code.$touch()"
                  ></v-select>
                  <v-text-field
                    v-model="curriculum_code"
                    :error-messages="curriculumCodeErrors"
                    :counter="10"
                    label="Curriculum Code"
                    required
                    @input="$v.curriculum_code.$touch()"
                    @blur="$v.curriculum_code.$touch()"
                  ></v-text-field>
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
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue darken-1"
            text
            class="pa-5"
            @click="closeDialog"
          >
            Close
          </v-btn>
        </v-card-actions>
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
Vue.use(vuelidate)

export default {
  name:"CurriculumForm",
  props:[
    "dialogue_value",
    "editing",
    "itemData",
  ],
  data(){
    return{
      dialog: false,
      curriculum_name: "",
      country_code: null,
      curriculum_code:null,
      curriculum_id:null,
      curriculumErrors:[],
      classNameErrors:[],
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

    }
  },
  validations: {
    curriculum_name: { required },
    country_code: { required },
    curriculum_code:{ required },
  },
  mounted() {
    this.listCurriculums()
    this.setCountryList()
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
    curriculumNameErrors () {
      const errors = []
      // if (!this.$v.curriculum_name.$dirty) return errors
      // !this.$v.curriculum_name.required && errors.push('Curriculum Name is required.')
      return errors
    },
    curriculumCodeErrors () {
      const errors = []
      // if (!this.$v.curriculum_id.$dirty) return errors
      // !this.$v.curriculum_id.required && errors.push('Curriculum is required')
      return errors
    },
    countryCodeErrors(){
      const errors = []
      // if (!this.$v.country_code.$dirty) return errors
      // !this.$v.country_code.required && errors.push('Country is required')
      return errors
    }
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
        this.submitSuccess = true
        this.successMessage = "Curriculum submitted successfully"
        let formData =  new FormData()
        formData.append("curriculum_name", this.curriculum_name)
        formData.append("curriculum_code", this.curriculum_code)
        formData.append("country_code", this.country_code)
        formData.append("is_global", this.is_global)
        let response =null
        if(editing_id){
          response = await CurriculumService.updateCurriculum(this.curriculum_id,formData)
        }else{
          response = await CurriculumService.addCurriculum(formData)
        }
        if (response.status) {
          this.successMessage = response.data.message
          this.submitSuccess=true
          this.validationError=false
          this.responseError=false
          if(!editing_id) {
            this.curriculum_code = null
            this.curriculum_name = null
            this.country_code = null
          }else{
            setTimeout(() => {
              this.curriculum_code = null
              this.curriculum_name = null
              this.country_code = null
              this.dialog=false
            }, "4000")
          }
          this.$nuxt.$emit("refreshCurriculumList",true)
          setTimeout(() => {
            this.submitSuccess=false
            this.successMessage=""
          }, "4000")

        } else {
          this.responseError = true
          this.responseErrorMessage = response.data.message
          setTimeout(() => {
            this.responseError=false
            this.responseErrorMessage=""
          }, "4000")
        }
      }
    },
    async listCurriculums() {
      let response = await CurriculumService.listCurriculum();
      if (response.data.status){
        this.list=response.data.data.items
      }
    },
    clear(){
      this.curriculum_name=null
      this.curriculum_code=null
      this.country_code=null
    },
    closeDialog(){
      this.dialog = false
      this.editingItem=false
      this.$emit("resetEditing",false)
    },
    setCountryList(){
      this.countries=variousCountryListFormats.setCountryWithCodeList()
    },
    setEditingData(data){
      this.checkedit=true;
      this.curriculum_name=data[0].curriculum_name
      this.curriculum_code= data[0].curriculum_code
      this.country_code= data[0].country_code
      this.curriculum_id=data[0].id
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
