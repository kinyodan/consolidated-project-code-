<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <v-app>
    <div class="container-fluid">
      <div class="row column_title">
        <div class="col-md-12">
          <div class="page_title">
            <h2>{{ header_title }}</h2>
          </div>
        </div>
      </div>
      <div class="row column1">
        <div data-app>
          <CurriculumFormEditing v-if="editing" :editing="editing" :itemData="itemData"></CurriculumFormEditing>
          <CurriculumForm v-else :editing="false"></CurriculumForm>
          <v-dialog
            v-model="curriculum_details_dialog"
            persistent
            activator="parent"
            max-width="600px"
          >
            <template>
              <v-container>
                <template>
                  <v-card >
                    <ShowCurriculumDetails :curriculumDetails="curriculumDetails" ></ShowCurriculumDetails>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" text @click="closeShowDetails">Close</v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
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
                {{ deleteSuccessMessage }}
              </v-alert>
              <v-alert
                border="left"
                dense
                outlined
                type="success"
                v-if="deleteResponseError"
              >
                {{ deleteResponseErrorMessage }}
              </v-alert>
            </template>
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
              show-expand
              class="elevation-1 wrapper-main school-data-table"
              :footer-props="{
                 'items-per-page-options': [10,15,30,45,60,75,100]
                 }"
              :items-per-page="15"
            >
          <template v-slot:top>
            <v-dialog v-model="dialogDelete" v-if="deleting" max-width="500" content-class="dialog">
              <v-card>
                <v-card-title class="text-h6 text-center">Are you sure you want to delete this student?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="error" text @click="deleteItemConfirm(curriculum_item)">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>
          <template v-slot:item.curriculum_name="{ item }">
            <a href="javascript:void(0)" @click.prevent="setCurrentItem(item.id)">{{
                item.curriculum_name
              }}</a>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteCurriculum(item)" title="Delete Class">
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
      </v-container>
    </template>
    </div>
  </div>
  </v-app>
</template>

<script>
import CurriculumService from "@/services/CurriculumService";
import CurriculumForm from "@/components/settings/CurriculumForm"
import ClassesService from "@/services/ClassesService";
import CurriculumFormEditing from "@/components/settings/CurriculumFormEditing"
import ShowCurriculumDetails from "@/components/settings/ShowCurriculumDetails"

export default {
  name: 'ManageCurriculum',
  layout: 'Default',
  head() {
  },
  created() {
    this.listCurriculums()
    this.$nuxt.$on('refreshCurriculumList', ($event) => this.listCurriculums())
  },
  components: {
    CurriculumForm,
    CurriculumFormEditing,
    ShowCurriculumDetails
  },
  data() {
    return {
      header_title:"ManageCurriculum",
      button_text: "Add CCurriculum",
      headers: [
        {
          text: 'Curriculum Name',
          align: 'start',
          sortable: false,
          value: 'curriculum_name',
        },
        { text: 'Curriculum', value: 'curriculum_code' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      options:{},
      listItems:[],
      successMessage:"",
      submitSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      validationError:false,
      validationErrorMessage:"",
      deleteSuccess:false,
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      is_global:false,
      page:1,
      search:null,
      selected:[],
      singleSelect: false,
      expanded: [],
      itemData:[],
      editing:false,
      loading:false,
      dialogDelete:false,
      deleting:false,
      progressBar:true,
      curriculum_item:[],
      curriculumDetails:[],
      curriculum_details_dialog:false,
    }
  },
  methods: {
    async showCurriculum(curriculum_id){
      let response =await CurriculumService.showCurriculum(curriculum_id)
      console.log(response)
      if(response.status){
        this.curriculumDetails=response.data.data[0]
        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    editItem(item){
      this.editing=true
      this.itemData=[
        {curriculum_name:item.curriculum_name,country_code: item.country_code,curriculum_code:item.curriculum_code,id:item.id}
      ]
    },
    resetEditing(msg){
      this.editing=msg
    },
    async deleteItemConfirm(item){
      let response =await ClassesService.deleteClass(item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        await this.listCurriculums()
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
    deleteCurriculum(item){
      this.deleting=true
      this.curriculum_item=item
      this.dialogDelete=true
    },
    closeDelete(){
      this.deleting=false
      this.curriculum_item=[]
    },
    async listCurriculums(){
      let response =await CurriculumService.listCurriculum()
      if(response.status){
        this.listItems = response.data.data.items
        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    closeShowDetails(){
      this.curriculum_details_dialog=false
    },
    setCurrentItem(item){
      this.curriculum_details_dialog=true
      this.showCurriculum(item)
    }
  }
}
</script>
  <style scoped>

  </style>
