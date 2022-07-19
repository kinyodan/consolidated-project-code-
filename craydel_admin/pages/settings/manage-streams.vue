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
                    <ShowClassDetails :classDetails="streamDetails" ></ShowClassDetails>
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
              <!--              <template v-slot:top>-->
              <!--                <v-dialog v-model="dialogDelete" content-class="dialog">-->
              <!--                  <v-card>-->
              <!--                    <v-card-title class="text-h6 text-center">Are you sure you want to delete this student?</v-card-title>-->
              <!--                    <v-card-actions>-->
              <!--                      <v-spacer></v-spacer>-->
              <!--                      <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>-->
              <!--                      <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>-->
              <!--                      <v-spacer></v-spacer>-->
              <!--                    </v-card-actions>-->
              <!--                  </v-card>-->
              <!--                </v-dialog>-->
              <!--              </template>-->
              <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">
                  mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)" title="Delete Class">
                  mdi-delete
                </v-icon>
              </template>
              <!--              <template v-slot:top>-->
              <!--                <v-text-field-->
              <!--                  v-model="search"-->
              <!--                  label="Search (UPPER CASE ONLY)"-->
              <!--                  class="mx-2"-->
              <!--                ></v-text-field>-->
              <!--              </template>-->
            </v-data-table>
          </v-container>
        </template>
        <!--        <div class="row column3"> </div>-->
        <!--        <div class="row column4 graph"></div>-->
      </div>
    </div>
  </v-app>
</template>

<script>
import variousCountryListFormats from '@/variousCountryListFormats'
import CurriculumService from "@/services/CurriculumService";
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import streamsService from "@/services/StreamsService";
import StreamsService from "@/services/StreamsService";
import GraduationsService from "@/services/GraduationsService";

export default {
  name: 'manage-streams',
  layout:'Default',
  head(){},
  created() {
    this.setCountryList()
    this.listStreams()
  },
  data() {
    return {
      header_title:"Manage Streams",
      button_text: 'Add Streams',
      stream_name: "",
      page:1,
      school_id: null,
      submitSuccess:false,
      successMessage:"",
      loading:false,
      options:{},
      selected:[],
      singleSelect: false,
      expanded: [],
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      details_dialog:false,
      is_global: false,
      countries: [],
      search:null,
      headers: [
        {
          text: 'Year',
          align: 'start',
          sortable: false,
          value: 'years',
        },
        { text: 'Created at', value: 'create_at' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},

        // { text: 'Status', value: 'status' },
      ],
      listItems:[],
      streamDetails:[]
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },
    async updateGraduationYear(){
      let formData =  new FormData()
      formData.append("year", this.year)
      formData.append("description", this.description)
      formData.append("is_global", this.is_global)

      let response =await StreamsService.updateStreams(formData)
      if(response.status){
        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async deleteGraduationYears(year_id){
      let response =await StreamsService.deleteStreams(year_id)
      if(response.status){
        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async listStreams(){
      let response =await StreamsService.listStreams("AG")
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
      this.details_dialog=false
    },
  }
}
</script>

<style scoped>

</style>
