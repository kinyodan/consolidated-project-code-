<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <v-app>
    <div class="container-fluid">
      <div class="row column1">
        <div data-app>
          <AdminEditing v-if="editing" :editing="editing" :button_text="button_text" :schools="schools" :itemData="itemData"></AdminEditing>
          <AdminForm v-else :button_text="button_text" :schools="schools" :editing="false"></AdminForm>
          <v-dialog
            v-model="admin_details_dialog"
            persistent
            activator="parent"
            width="600px"
          >
            <template>
              <v-container>
                <template>
                  <v-card >
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" class="float-right" text @click="closeShowDetails">Close</v-btn>
                    </v-card-actions>
                    <ShowAdminDetails :adminDetails="adminDetails" :schools="schools"></ShowAdminDetails>
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
                type="error"
                v-if="deleteResponseError"
              >
                {{ deleteResponseErrorMessage }}
              </v-alert>
            </template>
            <v-alert
                border="left"
                dense
                outlined
                type="error"
                v-if="listResponseError"
            >
              {{ listResponseErrorMessage }}
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
              <template v-slot:item.admin_name="{ item }">
                <a href="javascript:void(0)" @click.prevent="setCurrentItem(item.id)">{{
                    item.admin_name
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
import AdminEditing from "/components/admins/AdminEditing"
import AdminForm from "/components/admins/AdminForm"
import AdminService from "@/services/AdminService";
import ShowAdminDetails from "@/components/admins/ShowAdminDetails"
import {required} from "vuelidate/lib/validators";
import SchoolsService from "@/services/SchoolService";

export default {
  name: 'ManageAdmins',
  layout:'Default',
  components:{ValidationObserver,ValidationProvider,AdminEditing,AdminForm,ShowAdminDetails},
  head(){},
  data() {
    return {
      header_title:"Manage Admins",
      button_text: 'Add Admin',
      headers: [
        {
          text: 'Admin Name',
          align: 'start',
          sortable: false,
          value: 'admin_name',
        },
        { text: 'Email', value: 'admin_email' },
        { text: 'Phone', value: 'admin_phone' },
        { text: 'Role', value: 'admin_role' },
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
      admin_item:[],
      deleteSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      listResponseError:false,
      listResponseErrorMessage:"",
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      currentClassItem:[],
      admin_details_dialog:false,
      adminDetails:[],
      school_code:"MQ",
      schools:[],
      items_per_page:5,
      current_page:1 ,
      previous_page:1,
      next_page: 2,
      number_of_pages:1,
      items_count:0,
    }
  },
  created() {
    this.listAdmins();
    this.$nuxt.$on('refreshAdminList', ($event) => this.listAdmins())
  },
  mounted() {
    this.listSchools()
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
    async getDetails(school_code,school_id) {
      let response = await AdminService.showAdmin(school_code,school_id)
      if (response.data.status) {
        this.adminDetails=response.data.data[0]
        this.successMessage = response.data.message
        this.submitSuccess = true
      } else {
        this.responseError = true
        this.responseErrorMessage = response.data.message
      }
    },
    async listAdmins(){
      this.loading=true
      let response =await AdminService.listAdmins(this.school_code)
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
        this.listResponseErrorMessage=response.data.message
      }
    },
    async listSchools(){
      this.loading=true
      let response =await SchoolsService.listSchools()
      if(response.data.status) {
        this.schools = response.data.data.items
        this.loading = false
      }
    },
    editItem(item){
      this.editing=true
      this.button_text="Edit Admin"
      this.itemData= {
        admin_name: item.admin_name,
        admin_email: item.admin_email,
        admin_phone: item.admin_phone,
        admin_role: item.admin_role,
        school_id: item.school_id,
        id: item.id,
      }
    },
    resetEditing(msg){
      this.editing=msg
    },
    async deleteItemConfirm(item){
      let response =await AdminService.deleteAdmin(this.school_code,item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        await this.listAdmins()
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
      this.admin_details_dialog=false
    },
    setCurrentItem(item){
      this.admin_details_dialog=true
      this.getDetails(this.school_code,item)
    },
    paginate(){
    }
  },
  watch: {
    page: {
      immediate: true,
      handler (val, oldVal) {
        this.listAdmins(val)
      }
    }
  }
}
</script>

<style scoped>

</style>
