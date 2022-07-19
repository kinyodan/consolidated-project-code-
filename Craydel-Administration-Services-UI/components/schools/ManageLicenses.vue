<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <v-app>
    <div class="container-fluid">
      <div class="row column1">
        <div data-app>
          <ClassesFormEditing v-if="editing" :schoolDetails="schoolDetails" :editing="editing" :itemData="itemData"></ClassesFormEditing>

          <LicenseForm :schoolDetails="schoolDetails" v-else :editing="false"></LicenseForm>

          <v-dialog
            v-model="classes_details_dialog"
            persistent
            activator="parent"
            max-width="600px"
          >
            <template>
              <v-container>
                <template>
                  <v-card >
                    <ShowClassDetails :classDetails="classDetails" ></ShowClassDetails>
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
              :server-items-length="totalRows"
              :page.sync="currentPage"
              show-select
              :hide-default-footer="true"
              class="elevation-1 wrapper-main school-data-table"
              :footer-props="{
                 'items-per-page-options': [5,10,15,30,45,60,75,100]
                 }"
              :items-per-page="5"
              @pagination="paginate"
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
              <template v-slot:item.class_name="{ item }">
                <a href="javascript:void(0)" @click.prevent="setCurrentItem(item.id)">{{
                    item.class_name
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
import ClassesService from "@/services/ClassesService";
import { ValidationObserver } from 'vee-validate';
import { ValidationProvider } from 'vee-validate';
import ClassesFormEditing from "@/components/settings/ClassesEditing"
import ClassesForm from "@/components/settings/ClassesForm"
import ShowClassDetails from "@/components/settings/ShowClassDetails"
import LicenceService from "@/services/LicenceService";
import LicenseForm from "@/components/licences/LicenseForm"
import SchoolService from "@/services/SchoolService";

export default {
  name: 'ManageLicenses',
  props:[
   'schoolDetails'
  ],
  layout:'Default',
  components:{ValidationObserver,ValidationProvider,ClassesFormEditing,ClassesForm,ShowClassDetails,LicenseForm},
  head(){},
  created() {
    this.listLicences();
    this.$nuxt.$on('refreshLicenceList', ($event) => this.listLicences())
  },
  data() {
    return {
      header_title:"Manage Licences",
      button_text: 'Add Licence',
      headers: [
        {
          text: 'School Name',
          align: 'start',
          sortable: false,
          value: 'school_name',
        },
        { text: 'Licences', value: 'allowed_license_count' },
        { text: 'Phone', value: 'school_phone' },
        // {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      listItems:[],
      status: null,
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
      class_item:[],
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      currentClassItem:[],
      classes_details_dialog:false,
      classDetails:[],
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
    async getDetails(class_id) {
      let response = await ClassesService.showClass(class_id)
      if (response.data.status) {
        this.classDetails=response.data.data[0]
        this.successMessage = response.data.message
        this.submitSuccess = true
      } else {
        this.responseError = true
        this.responseErrorMessage = response.data.message
      }
    },
    async listLicences(){
      this.loading=true
      let response =await SchoolService.listSchools()
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
        this.responseError=true
        this.responseErrorMessage=response.data.message
      }
    },
    editItem(item){
      this.editing=true
      this.itemData=[
        {class_name:item.class_name,curriculum_code: item.curriculum_code,id:item.id}
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
        await this.listLicences()
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
      this.classes_details_dialog=false
    },
    setCurrentItem(item){
      this.classes_details_dialog=true
      this.getDetails(item)
    },
    paginate(){

    }
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listLicences(val)
      }
    }
  }
}
</script>

<style scoped>

</style>
