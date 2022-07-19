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
<!--          <ClassesFormEditing v-if="editing" :editing="editing" :itemData="itemData"></ClassesFormEditing>-->
<!--          <ClassesForm v-else :editing="false"></ClassesForm>-->
          <v-dialog
            v-model="details_dialog"
            persistent
            activator="parent"
            max-width="600px"
          >
            <template>
              <v-container>
                <template>
                  <v-card >
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" class="float-right" text @click="closeShowDetails">Close</v-btn>
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
              :server-items-length="totalRows"
              show-select
              :hide-default-footer="true"
              class="elevation-1 wrapper-main school-data-table"
              :footer-props="{
                 'items-per-page-options': [5,10,15,30,45,60,75,100]
                 }"
              :items-per-page="5"
            >
              <template v-slot:item.actions="{ item }">
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
import variousCountryListFormats from '@/variousCountryListFormats'
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import GraduationsService from "@/services/GraduationsService";


export default {
  name: 'Graduations',
  layout:'Default',
  head(){},
  components: {
  },
  data() {
    return {
      header_title:"Manage Graduations",
      button_text: 'Add Graduations',
      curriculum_name: "",
      country_code: null,
      curriculum_code: null,
      dataList:[],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: [],
        sortDesc: [],
        groupBy: [],
        groupDesc: [],
        multiSort: false,
        mustSort: false
      },
      successMessage:"",
      year_id:null,
      submitSuccess:false,
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      classes_details_dialog:false,
      details_dialog:false,
      is_global: 1,
      countries: [],
      page:1,
      search:null,
      headers: [
        {
          text: 'Year',
          align: 'start',
          sortable: false,
          value: 'years',
        },
        { text: 'Created at', value: 'created_at' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      listItems:[],
      selected:[],
      singleSelect: false,
      expanded: [],
      loading:false,
      editing:false,
      progressBar:true,
      items_per_page:5,
      current_page:1 ,
      previous_page:1,
      next_page: 2,
      number_of_pages:1,
      items_count:0,
    }
  },
  created() {
    this.listGraduations()
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
    async listGraduations(page){
      let response = await GraduationsService.listGraduations(page)
      if(response.data.status){
        this.listItems=response.data.data.items
        this.items_per_page=response.data.data.items_per_page
        this.current_page=response.data.data.current_page
        this.previous_page=response.data.data.previous_page
        this.next_page=response.data.data.next_page
        this.page=this.current_page
        this.number_of_pages=response.data.data.number_of_pages
        this.items_count=response.data.data.items_count

        this.successMessage = response.data.message
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async updateGraduationYear(){
      let formData =  new FormData()
      formData.append("year", this.year)
      formData.append("description", this.description)
      formData.append("is_global", this.is_global)

      let response =await GraduationsService.updateGraduations(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async deleteGraduationYears(year_id){
      let response =await GraduationsService.deleteGraduations(year_id)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async submitForm(){
      let formData =  new FormData()
      formData.append("year", this.year)
      formData.append("description", this.description)
      formData.append("is_global", this.is_global)

      let response =await GraduationsService.addGraduations(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    closeDelete(){
      this.deleting=false
      this.class_item=[]
    },
    closeShowDetails(){
      this.classes_details_dialog=false
    },
    setCurrentItem(item){
      this.classes_details_dialog=true
      this.getDetails(item)
    },
    paginate(){
      console.log("paginate")
    },
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listGraduations(val)
        console.log(oldVal+"hhhhh")
        // this.page=val
      }
    }
  }
}
</script>
<style scoped>

</style>
