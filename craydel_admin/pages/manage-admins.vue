<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <v-app>
    <div class="container-fluid">
      <div class="row column_title">
        <div class="col-md-12">
          <div class="page_title">
            <h2>{{ header_title }}---</h2>
          </div>
        </div>
      </div>
      <div class="row column1">
        <div data-app>
          <SchoolFormEditing v-if="editing" :editing="editing" :button_text="button_text" :itemData="itemData"></SchoolFormEditing>
          <SchoolForm v-else :button_text="button_text" :editing="false"></SchoolForm>
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
                    <ShowSchoolDetails :adminDetails="adminDetails" ></ShowSchoolDetails>
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
import AdminService from "@/services/AdminService";
import ShowAdminDetails from "@/components/admins/ShowAdminDetails"

export default {
  name: 'ManageSchools',
  layout:'Default',
  components:{ValidationObserver,ValidationProvider,SchoolFormEditing,SchoolForm,ShowAdminDetails},
  head(){},
  created() {
    this.listAdmins();
    this.$nuxt.$on('refreshAdminList', ($event) => this.listAdmins())
  },
  data() {
    return {
      header_title:"Manage Admins",
      button_text: 'Add Admin',
      headers: [
        {
          text: 'Admin Name',
          align: 'start',
          sortable: false,
          value: 'school_name',
        },
        { text: 'Email', value: 'School_email' },
        { text: 'Phone', value: 'school_phone' },
        { text: 'Address', value: 'school_address' },
        { text: 'Country code', value: 'country_code' },
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
      deleteSuccessMessage:"",
      deleteResponseError:false,
      deleteResponseErrorMessage:"",
      currentClassItem:[],
      school_details_dialog:false,
      adminDetails:[]
    }
  },
  methods: {
    async getDetails(school_id) {
      let response = await AdminService.showAdmin(school_id)
      console.log("adminDetails----")
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
      let response =await AdminService.listAdmins()
      console.log("----listAdmins-----")
      if(response.data.status){
        this.listItems=response.data.data.items
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
      let response =await AdminService.deleteAdmin(item.id)
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
    }
  }
}
</script>

<style scoped>

</style>
