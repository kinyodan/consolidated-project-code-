<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <v-app>
    <div class="container-fluid">
      <div class="row column1">
        <div data-app>
          <StudentEditing v-if="editing" :editing="editing" :button_text="button_text" :propSchoolCode="school_code" :itemData="itemData"></StudentEditing>
          <StudentForm v-else :button_text="button_text" :propSchoolCode="school_code" :editing="false"></StudentForm>
          <v-dialog
              v-model="student_details_dialog"
              persistent
              activator="parent"
              width="900px"
          >
            <template>
              <v-container>
                <template>
                  <v-card >
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" class="float-right" text @click="closeShowDetails">Close</v-btn>
                    </v-card-actions>
                    <ShowStudentDetails :studentDetails="studentDetails[0]" ></ShowStudentDetails>
                  </v-card>
                </template>
              </v-container>
            </template>
          </v-dialog>
        </div>
        <template>
          <v-container>
            <template >
              <v-alert
                  border="left"
                  dense
                  outlined
                  type="success"
                  v-if="deleteSuccess"
              >
                {{ deleteSuccessMessage }}--1
              </v-alert>
              <v-alert
                  border="left"
                  dense
                  outlined
                  type="error"
                  v-if="deleteResponseError"
              >
                {{ deleteResponseErrorMessage }}--
              </v-alert>
            </template>
            <v-alert
                border="left"
                dense
                outlined
                type="error"
                v-if="listResponseError"
            >
              {{ listResponseErrorMessage }}--0
            </v-alert>
            <v-data-table
                :headers="headers"
                :items="listItems"
                :options.sync="options"
                item-key="id"
                :search="search"
                v-model="selected"
                :single-select="singleSelect"
                :loading="loading"
                :expanded.sync="expanded"
                show-select
                :hide-default-footer="true"
                class="elevation-1 wrapper-main school-data-table"
                :footer-props="{
                 'items-per-page-options': [10,15,30,45,60,75,100]
                 }"
                :items-per-page="15"
            >
              <template v-slot:top>
                <v-dialog v-model="dialogDelete" v-if="deleting" max-width="500" content-class="dialog">
                  <v-card>
                    <v-card-title class="text-h6 text-center">Are you sure you want to delete this record?</v-card-title>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                      <v-btn color="error" text @click="deleteItemConfirm(class_item)">OK</v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </template>
              <template v-slot:item.student_name="{ item }">
                <a href="javascript:void(0)" @click.prevent="setCurrentItem(item)">{{
                    item.student_name
                  }}</a>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">
                  mdi-pencil
                </v-icon>
                <v-icon small @click="deleteClass(item)" title="Delete Class">
                  mdi-delete
                </v-icon>
              </template>
              <template v-slot:footer  class="float-right">
                <div class="float-right ">
                  <v-pagination
                      v-model="page"
                      :length="number_of_pages"
                  ></v-pagination>
                </div>
              </template>
            </v-data-table>
          </v-container>
        </template>
      </div>
    </div>
  </v-app>
</template>

<script>
import { ValidationObserver } from 'vee-validate';
import { ValidationProvider } from 'vee-validate';
import StudentEditing from "/components/students/StudentEditing"
import StudentForm from "/components/students/StudentForm"
import StudentService from "@/services/StudentService";
import ShowStudentDetails from "@/components/students/ShowStudentDetails"
import ClassesService from "@/services/ClassesService";
import StreamsService from "@/services/StreamsService";

export default {
  name: 'ManageStudents',
  layout:'Default',
  components:{ValidationObserver,ValidationProvider,StudentEditing,StudentForm,ShowStudentDetails},
  head(){},
  created() {
    this.listStudents()
    this.$nuxt.$on('refreshStudentList', ($event) => this.listStudents())
  },
  data() {
    return {
      header_title:"Manage Students",
      button_text: 'Add Student',
      headers: [
        {
          text: 'Student Name',
          align: 'start',
          sortable: false,
          value: 'student_name',
        },
        { text: 'Email', value: 'student_email' },
        { text: 'Phone', value: 'student_phone' },
        { text: 'Curriculum', value: 'curriculum.curriculum_code' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      listItems:[],
      status: null,
      options: {},
      is_global: false,
      page:1,
      search:null,
      dialogDelete:[],
      selected:[],
      singleSelect: false,
      expanded: [],
      loading:true,
      editing:false,
      progressBar:true,
      itemData:[],
      deleting:false,
      school_item:[],
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      listResponseError:false,
      listResponseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      currentClassItem:[],
      student_details_dialog:false,
      studentDetails:[],
      classes:[],
      school_code:"MQ",
      items_per_page:5,
      current_page:1 ,
      previous_page:1,
      next_page: 2,
      number_of_pages:1,
      items_count:0,
    }
  },
  computed:{
    totalRows(){
      return this.items_count
    },
    currentPage(){
      return this.current_page
    }
  },
  methods: {
    async getDetails(school_code,student_id) {
      let response = await StudentService.showStudent(school_code,student_id)
      if (response.data.status) {
        this.studentDetails=response.data.data.items
        this.successMessage = response.data.message
        this.submitSuccess = true
      } else {
        this.responseError = true
        this.responseErrorMessage = response.data.message
      }
    },
    async listStudents(){
      this.loading=true
      let response =await StudentService.listStudents(this.school_code)
      if(response.data.status){
        this.listItems=response.data.data.items
        this.items_per_page=response.data.data.items_per_page
        this.current_page=response.data.data.current_page
        this.previous_page=response.data.data.previous_page
        this.next_page=response.data.data.next_page
        this.number_of_pages=response.data.data.number_of_pages
        this.items_count=response.data.data.items_count
        this.successMessage = response.data.message
        this.submitSuccess=true
        this.loading=false
      }else{
        this.listResponseError=true
        this.listResponseErrorMessage= response.data.message
      }
    },
    editItem(item){
      this.editing=true
      this.button_text="Edit Admin"
      this.itemData= {
        curriculum_id: item.curriculum_id,
        school_code: item.school_code,
        country_code: item.code,
        school_email: item.school_email,
        school_phone: item.school_phone,
        school_address: item.school_address,
        school_physical_address: item.school_physical_address,
        school_website_url: item.school_website_url,
        school_logo_url: item.school_logo_url,
        discount_value: item.discount_value,
        id: item.id,
      }
    },
    resetEditing(msg){
      this.editing=msg
    },
    async deleteItemConfirm(item){
      let response =await StudentService.deleteStudent(item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        await this.listStudents()
        setTimeout(() => {
          this.deleteSuccess=false
        }, "4000")
      }else{
        this.deleteResponseError=true
        this.deleteResponseErrorMessage=response.data.message
        setTimeout(() => {
          this.deleteResponseError=false
        }, "4000")
      }
      this.deleting=false
    },
    deleteClass(item){
      this.deleting=true
      this.class_item=item
    },
    closeDelete(){
      this.deleting=false
      this.class_item=[]
    },
    closeShowDetails(){
      this.student_details_dialog=false
    },
    setCurrentItem(item){
      this.student_details_dialog=true
      this.getDetails(item.school.school_code,item.id)

    }
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listStudents(val)
      }
    }
  }
}
</script>

<style scoped>

</style>
