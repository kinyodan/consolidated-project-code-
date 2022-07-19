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
          <SchoolFormEditing v-if="editing" :editing="editing" :schools="listItems" :button_text="button_text" :itemData="itemData"></SchoolFormEditing>
          <SchoolForm v-else :button_text="button_text" :schools="listItems" :editing="false"></SchoolForm>
          <v-dialog
            v-model="school_details_dialog"
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
                    <ShowSchoolDetails :schoolDetails="schoolDetails" ></ShowSchoolDetails>
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
              <template v-slot:item.school_name="{ item }">
                <a href="javascript:void(0)" @click.prevent="setCurrentItem(item.id)">{{
                    item.school_name
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
import SchoolFormEditing from "/components/schools/SchoolFormEditing"
import SchoolForm from "/components/schools/SchoolForm"
import SchoolsService from "@/services/SchoolService";
import ShowSchoolDetails from "@/components/schools/ShowSchoolDetails"
import BankDetailsService from "@/services/BankDetailsService";

export default {
  name: 'ManageSchools',
  layout:'Default',
  components:{ValidationObserver,ValidationProvider,SchoolFormEditing,SchoolForm,ShowSchoolDetails},
  head(){},
  created() {
    this.listSchools();
    this.$nuxt.$on('refreshSchoolList', ($event) => this.listSchools())
  },
  data() {
    return {
      header_title:"Manage Schools",
      button_text: 'Add School',
      headers: [
        {
          text: 'School Name',
          align: 'start',
          sortable: false,
          value: 'school_name',
        },
        { text: 'Email', value: 'School_email' },
        { text: 'Phone', value: 'school_phone' },
        { text: 'Address', value: 'school_address' },
        { text: 'Country', value: 'country' },
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
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
      school_item:[],
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      currentClassItem:[],
      school_details_dialog:false,
      schoolDetails:[],
      schoolProplistItems:[],
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
    async getDetails(school_id) {
      let response = await SchoolsService.showSchool(school_id)
      if (response.data.status) {
        this.schoolDetails=response.data.data[0]
        this.successMessage = response.data.message
        this.submitSuccess = true
      } else {
        this.responseError = true
        this.responseErrorMessage = response.data.message
      }
    },
    async listSchools(){
      this.loading=true
      let response =await SchoolsService.listSchools()
      if(response.data.status){
        this.listItems=response.data.data.items
        this.items_per_page=response.data.data.items_per_page
        this.current_page=response.data.data.current_page
        this.previous_page=response.data.data.previous_page
        this.next_page=response.data.data.next_page
        this.number_of_pages=response.data.data.number_of_pages
        this.items_count=response.data.data.items_count
        this.schoolProplistItems=response.data.data.items
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
      this.button_text="Edit School"
      this.itemData= {
        curriculum_id: item.curriculum_id,
        school_code: item.school_code,
        school_name: item.school_name,
        parent_school_id: item.parent_school_id,
        country_code: item.country_code,
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
      let response =await SchoolsService.deleteSchool(item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        await this.listSchools()
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
      this.school_details_dialog=false
    },
    setCurrentItem(item){
      this.school_details_dialog=true
      this.getDetails(item)
    },
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listSchools(val)
        // this.page=val
      }
    }
  }
}
</script>

<style scoped>

</style>
