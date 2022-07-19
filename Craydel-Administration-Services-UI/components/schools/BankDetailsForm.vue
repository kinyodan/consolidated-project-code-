<template>
  <div>
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

                <form
                 v-model="valid"
                 ref="form"
                >
                  <v-row>
                  <v-col
                      cols="12"
                  >
                  <v-text-field
                    v-model="account_name"
                    label="Account Name *"
                    :rules="[v => !!v || 'Item is required']"
                    required
                    @input="$v.account_name.$touch()"
                    @blur="$v.account_name.$touch()"
                  ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                  >
                  <v-text-field
                    v-model="account_number"
                    label="Account Number *"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @input="$v.account_number.$touch()"
                    @blur="$v.account_number.$touch()"
                  ></v-text-field>
                  </v-col>
                  <v-col
                      cols="12"
                  >
                  <v-text-field
                    v-model="bank_name"
                    label="Bank Name *"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @input="$v.bank_name.$touch()"
                    @blur="$v.bank_name.$touch()"
                  ></v-text-field>
                  </v-col>
                  <v-col
                      cols="6"
                      md="6"
                  >
                  <v-text-field
                    v-model="branch_name"
                    :counter="10"
                    label="Branch Name *"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @input="$v.branch_name.$touch()"
                    @blur="$v.branch_name.$touch()"
                  ></v-text-field>
                  </v-col>
                  <v-col
                      cols="5"
                      md="6"
                  >
                  <v-text-field
                    v-model="swift_code"
                    :counter="10"
                    label="Swift _code *"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @input="$v.swift_code.$touch()"
                    @blur="$v.swift_code.$touch()"
                  ></v-text-field>
                  </v-col>
                  <v-btn
                    class="mr-4"
                    @click="submitForm(checkedit)"
                  >
                    submit
                  </v-btn>
                  <v-btn @click="clear">
                    clear
                  </v-btn>
                  </v-row>
                </form>
              </template>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
  </div>
</template>

<script>
import {required} from "vuelidate/lib/validators";
import Vue from "vue";
import vuelidate from 'vuelidate'
import ClassesService from "@/services/ClassesService";
import CurriculumService from "@/services/CurriculumService";
import BankDetailsService from "@/services/BankDetailsService";
import SchoolService from "@/services/SchoolService";
import SchoolsService from "@/services/SchoolService";
Vue.use(vuelidate)

export default {
  name:"ClassesForm",
  props:[
    "dialogue_value",
    "editing",
    "itemData",
    'item_school_id',
    'item_school_code'
  ],
  data(){
    return{
      dialog: false,
      valid:true,
      account_name:null ,
      account_number:null,
      bank_name:null,
      branch_name:null,
      swift_code:null,
      school_id:null ,
      bank_detail_id:null,
      schools:[],
      curriculumErrors:[],
      classNameErrors:[],
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      validationError:false,
      validationErrorMessage:"",
      is_global: false,
      countries: [],
      checkedit:false,
      editingItem:this.editing||false,
    }
  },
  validations: {
    account_name:{ required } ,
    account_number:{ required },
    bank_name:{ required },
    branch_name:{ required },
    swift_code:{ required },
  },
  mounted() {
    this.listSchools();
    this.editingItem=this.editing
    if(this.editingItem){
      this.dialog=true
    }
  },
  computed:{
    setEditing(){
      this.editing=false
      return this.editing
    }
  },
  methods:{
    openDialog(){
      this.dialog=true;
    },
    openEditingDialog(){
      this.dialog=true
    },
    validate () {
      this.$refs.form
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    async submitForm(editing_id){
      // this.validate()
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "
      }else {
        let formData =  new FormData()
        formData.append("account_name", this.account_name)
        formData.append("account_number", this.account_number)
        formData.append("bank_name", this.bank_name)
        formData.append("branch_name", this.branch_name)
        formData.append("swift_code", this.swift_code)
        formData.append("school_code", this.item_school_code)

        let response =null
        if(editing_id){
          response =await BankDetailsService.updateBankDetails(this.item_school_code,this.bank_detail_id,formData)
        }else{
          response = await BankDetailsService.addBankDetails(this.item_school_code,formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess=true
          this.validationError=false
          this.responseError=false
          this.responseErrorMessage=""
          // this.resetValidation()
          this.valid=true
          if(!editing_id) {
            this.account_name= null
            this.account_number=null
            this.bank_name=null
            this.branch_name=null
            this.swift_code=null
            this.school_id=null
          }else{

          }
          this.$nuxt.$emit("refreshBankDetailsList",true)
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
    async listSchools() {
      let response = await SchoolService.listSchools();
      if (response.data.status){
        this.schools=response.data.data.items
      }
    },
    clear(){
      this.class_name=null
      this.curriculum_id=null
    },
    closeDialog(){
      this.dialog = false
      this.editingItem=false
      this.curriculum_id = null
      this.class_name = null
      this.$emit("resetEditing",false)
    },
    setEditingData(data){
      if(this.curriculums.length<1){
        this.listCurriculums()
      }
      this.class_name=data[0].class_name
      let curriculum_index= this.curriculums.findIndex(item=>item.curriculum_code===data[0].curriculum_code)
      if(curriculum_index!==-1){
        this.curriculum_id= this.curriculums[curriculum_index].id
      }
      this.class_id=data[0].id
      this.checkedit=true;
    },
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
