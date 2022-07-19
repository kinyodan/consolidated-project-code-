<template>
  <v-container>
    <template >
      <v-btn
        color="primary"
        dark
        v-on:click="openDialog"
      >
        {{button_text}}
      </v-btn>
    </template>
    <v-dialog
      v-model="dialog"
      persistent
      activator="parent"
      max-width="600px"
    >
      <v-card>
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
        <v-card-title>
          <span class="text-h5">{{ button_text }}</span>
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
                    v-model="stream_name"
                    label="Stream Name *"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @input="$v.stream_name.$touch()"
                    @blur="$v.stream_name.$touch()"
                  ></v-text-field>
                  <v-select
                    v-model="school_code"
                    :items="schools"
                    :error-messages="curriculumErrors"
                    label="School *"
                    item-text="school_name"
                    item-value="school_code"
                    required
                    :rules="[v => !!v || 'Item is required']"
                    @change="$v.school_code.$touch()"
                    @blur="$v.school_code.$touch()"
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
import StreamsService from "@/services/StreamsService";
import SchoolsService from "@/services/SchoolService";
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
      button_text: "Add Stream",
      stream_name: "",
      school_code:null,
      schools:[],
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
    stream_name: { required },
    school_code: { required },
  },
  mounted() {
    this.listCurriculums()
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
        formData.append("stream_name", this.stream_name)
        let response =null
        if(editing_id){
          response =await StreamsService.updateStream(this.stream_id,this.school_code,formData)
        }else{
          response = await StreamsService.addStream(this.school_code,formData)
        }
        if (response.data.status) {
          this.successMessage = response.data.message
          this.submitSuccess=true
          this.validationError=false
          this.responseError=false
          if(!editing_id) {
            this.stream_name = null
            this.school_code = null
          }else{
            setTimeout(() => {
              this.stream_name = null
              this.school_code = null
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
      this.stream_name=null
      this.school_code=null
    },
    closeDialog(){
      this.dialog = false
      this.editingItem=false
      this.stream_name = null
      this.school_code = null
      this.$emit("resetEditing",false)
    },
    setEditingData(data){
      this.button_text="Edit Stream"
      if(this.curriculums.length<1){
        this.listCurriculums()
      }
      this.stream_name=data[0].stream_name
      let curriculum_index= this.curriculums.findIndex(item=>item.curriculum_code===data[0].curriculum_code)
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
