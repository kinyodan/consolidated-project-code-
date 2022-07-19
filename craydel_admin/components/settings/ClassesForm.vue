<template>
  <v-container>
    <template >
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        Add Class
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
          <span class="text-h5">Add Class</span>
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
                    v-model="class_name"
                    :error-messages="classNameErrors"
                    :counter="10"
                    label="Class Name *"
                    required
                    @input="$v.class_name.$touch()"
                    @blur="$v.class_name.$touch()"
                  ></v-text-field>
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
                  <v-btn
                    class="mr-4"
                    @click="submitForm(checkedit)"
                  >
                    submit
                  </v-btn>
                  <v-btn @click="clear">
                    clear
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
import ClassesService from "@/services/ClassesService";
import CurriculumService from "@/services/CurriculumService";
Vue.use(vuelidate)

export default {
  name:"ClassesForm",
  props:[
    "dialogue_value",
    "editing",
    "itemData",
  ],
  data(){
    return{
      dialog: false,
      class_name: "",
      class_id:null,
      curriculum_id: null,
      curriculum_code:null,
      curriculums:[],
      status: null,
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
    class_name: { required },
    curriculum_id: { required },
  },
  mounted() {
    this.listCurriculums();
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
    async submitForm(editing_id){
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "
      }else {
        let formData =  new FormData()
        formData.append("class_name", this.class_name)
        formData.append("curriculum_id", this.curriculum_id)
        let response =null
        if(editing_id){
          response =await ClassesService.updateClass(this.class_id,formData)
        }else{
          response = await ClassesService.addClass(formData)
        }
        if (response.status) {
          this.successMessage = response.data.message
          this.submitSuccess=true
          this.validationError=false
          this.responseError=false
          if(!editing_id) {
            this.curriculum_id = null
            this.class_name = null
          }else{
            setTimeout(() => {
              this.curriculum_id = null
              this.class_name = null
              this.dialog=false
            }, "4000")
          }
          this.$nuxt.$emit("refreshClassList",true)
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
        this.curriculums=response.data.data.items
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
      console.log(data[0].curriculum_code)
      console.log(this.curriculums)
      if(this.curriculums.length<1){
        this.listCurriculums()
      }
      this.class_name=data[0].class_name
      let curriculum_index= this.curriculums.findIndex(item=>item.curriculum_code===data[0].curriculum_code)
      console.log(curriculum_index)
      if(curriculum_index!==-1){
        this.curriculum_id= this.curriculums[curriculum_index].id
      }
      this.class_id=data[0].id
      this.checkedit=true;
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
