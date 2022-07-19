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
          <StreamsFormEditing v-if="editing" :editing="editing" :itemData="itemData"></StreamsFormEditing>
          <StreamsForm v-else :editing="false"></StreamsForm>
          <v-dialog
              v-model="stream_details_dialog"
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
                    </v-card-actions>
                    <ShowStreamDetails :streamDetails="streamDetails" ></ShowStreamDetails>
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
                :page.sync="currentPage"
                show-select
                :hide-default-footer="true"
                class="elevation-1 wrapper-main school-data-table"
                :footer-props="{
                 'items-per-page-options': [5,10,15,30,45,60,75,100]
                 }"
                :items-per-page="5"
            >
              <template v-slot:top>
                <v-dialog v-model="dialogDelete" v-if="deleting" max-width="500" content-class="dialog">
                  <v-card>
                    <v-card-title class="text-h6 text-center">Are you sure you want to delete this record?</v-card-title>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                      <v-btn color="error" text @click="deleteItemConfirm(stream_item)">OK</v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </template>
              <template v-slot:item.stream_name="{ item }">
                <a href="javascript:void(0)" @click.prevent="setCurrentItem(item)">{{
                    item.stream_name
                  }}</a>
              </template>
              <template v-slot:item.school_id="{ item }">
                {{schoolName(item)}}
              </template>
              <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">
                  mdi-pencil
                </v-icon>
                <v-icon small @click="deleteStream(item)" title="Delete Class">
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
import variousCountryListFormats from '@/variousCountryListFormats'
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import streamsService from "@/services/StreamsService";
import StreamsService from "@/services/StreamsService";
import StreamsFormEditing from "@/components/settings/StreamEditing"
import StreamsForm from "@/components/settings/StreamsForm"
import ShowStreamDetails from "@/components/settings/ShowStreamDetails"
import ClassesService from "@/services/ClassesService";
import SchoolsService from "@/services/SchoolService";
import CurriculumService from "@/services/CurriculumService";

export default {
  name: 'manage-streams',
  layout:'Default',
  components:{
    StreamsFormEditing,
    StreamsForm,
    ShowStreamDetails
  },
  head(){},
  created() {
    this.setCountryList()
    this.listStreams()
    this.listSchools()
  },
  data() {
    return {
      header_title:"Manage Streams",
      button_text: 'Add Streams',
      stream_name: "",
      page:1,
      school_id: null,
      stream_item:null,
      submitSuccess:false,
      successMessage:"",
      loading:false,
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
      selected:[],
      singleSelect: false,
      expanded: [],
      dialogDelete:false,
      deleting:false,
      stream_details_dialog:false,
      editing:false,
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
          text: 'Stream Name',
          align: 'start',
          sortable: false,
          value: 'stream_name',
        },
        { text: 'School', value: 'school_id' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      listItems:[],
      streamDetails:[],
      itemData:[],
      schools:[],
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
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },
    async listSchools(){
      let response =await SchoolsService.listSchools()
      if(response.data.status){
        this.schools=response.data.data.items
      }
    },
    async showStream(school_code,stream_id){
      let response =await StreamsService.showStream(school_code,stream_id)
      console.log(response)
      if(response.status){
        this.streamDetails=response.data.data.items
        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    async listStreams(page){
      let response =await StreamsService.listStreams("AG",page)
      if(response.status){
        this.listItems = response.data.data.items
        this.items_per_page=response.data.data.items_per_page
        this.current_page=response.data.data.current_page
        this.previous_page=response.data.data.previous_page
        this.next_page=response.data.data.next_page
        this.number_of_pages=response.data.data.number_of_pages
        this.items_count=response.data.data.items_count

        this.successMessage = response.data.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    schoolName(listitem){
      let school_index= this.schools.findIndex(p => p.id === listitem.school_id )
      if(school_index!==-1){
        return this.schools[school_index].school_name
      }else{
        return ""
      }
    },
    async deleteItemConfirm(item){
      let response =await StreamsService.deleteStream(item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        await this.listStreams()
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
    deleteStream(item){
      this.deleting=true
      this.stream_item=item
    },
    closeDelete(){
      this.deleting=false
      this.stream_item=[]
    },
    closeShowDetails(){
      this.stream_details_dialog=false
    },
    setCurrentItem(item){
      console.log("item---------====")
      console.log(item)
      console.log("item----------====")

      this.stream_details_dialog=true
      this.showStream("AG",item.id)
    },
    paginate(){

    }
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listStreams(val)
      }
    }
  }
}
</script>

<style scoped>

</style>
