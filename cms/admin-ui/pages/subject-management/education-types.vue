<template>
  <div>

    <div class="row">

      <div class="col-12">

        <div class="card">

          <div class="card-body">
   
                      <PageHeader :title="title" :items="items"/>
                      <div class="row">
                        <div class="col-12">
                          <div>
                            <div>
                              <a href="javascript:void(0);" class="btn btn-success waves-effect waves-light mb-3 mr-3"  @click="newClusterPopup()" 
                                  v-b-tooltip.hover title="New Subject">New Cluster                
                              </a>
                            </div>
                          </div>

                          <BlockUI  v-if="add_form">
                              <div class="alert alert-danger" v-if="FormSubmittedError">
                                <p> {{FormSubmittedErrorText}} </p>
                              </div>

                              <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                <p>{{FormSubmittedSuccessText}} </p>
                              </div>

                              <span class="close" @click="newSubjectClose()"></span>
                              <h4> Add Subjects to cluster</h4>
                               <div class="row">
                                  <div class="col-12">
                                          <form v-on:submit.prevent="formSubmit" class="form-horizontal" role="form">
                                                           
                                              <label  class="pd-hld">Education type name </label>
                                              <input v-model="formData.name" type="text"
                                               class="form-control"
                                               placeholder="Name" name="Name"/>

                                               <div class="form-group position-relative">   

                                               <div class="item_holderx">
                                               <label class="pd-hld" id="current_position" >Country</label>
                                                <b-form-select   
                                                v-model="formData.country"" 
                                                :options="build_data.countries"
                                                class="mb-3 select_ecp"
                                                value-field="id"
                                                text-field="name"
                                                disabled-field="notEnabled"
                                                placeholder="Country"
                                                >
                                                <b-form-select-option >
                                                 countries</b-form-select-option>
                                                </b-form-select>
                                                </div> 
                                     
                                              </div>
                                               <hr>
                                              <button type="submit" id="submit_alumni" class="btn btn-primary w-md">
                                                Submit
                                              </button>
                                          </form>
                                            </br>
                                            <div class="alert alert-danger" v-if="FormSubmittedError">
                                              <p> {{FormSubmittedErrorText}} </p>
                                            </div>

                                            <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                              <p>{{FormSubmittedSuccessText}} </p>
                                            </div>

                                            </div>
                                        </div>

                              </br>

                          </BlockUI>

                          <div class="row top_rowmt" >
                            <div class="card w-100">
                              <div class="card-body">
                                <h4 class="card-title">Filters</h4>
                                <div>
                                  <b-form inline>
                                    <b-form-select class="mr-3" v-model="filterData.batch" value="filterData.batch">
                                      <b-form-select-option :value="null">Select Batch</b-form-select-option>
                                      <b-form-select-option v-for="batch_ in batches" :key="batch.id" :value="type.id">{{
                                          batch_number.name
                                        }}
                                      </b-form-select-option>
                                    </b-form-select>
                                    <b-form-select class="mr-3" v-model="filterData.course_type" value="filterData.course_type">
                                      <b-form-select-option :value="null">Select a course type</b-form-select-option>
                                      <b-form-select-option v-for="type in courseTypes" :key="type.id" :value="type.id">{{
                                          type.name
                                        }}
                                      </b-form-select-option>
                                    </b-form-select>
                                    <b-form-select class="mr-3 md-size-01" v-model="filterData.institution_code" value="filterData.institution_code">
                                      <b-form-select-option :value="null" selected="selected">Select an institution</b-form-select-option>
                                      <b-form-select-option v-for="institution in institutions" :key="institution.institution_code"
                                                            :value="institution.institution_code">{{ institution.institution_name }}
                                      </b-form-select-option>
                                    </b-form-select>
                                    <b-button variant="primary" class="mr-3" @click="handleFilters" :disabled="loadingData">Filter
                                    </b-button>
                                    <b-button variant="secondary" class="mr-3" @click="handleClearFilters" :disabled="loadingData">Clear
                                    </b-button>

                                    <b-spinner class="m-2" role="status" v-if="loadingData"></b-spinner>
                                  </b-form>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row ">
                            <div class="col-sm-12 col-md-6">
                                  <div class="action1_wrapper">
                                      <b-form-select class="mr-3" v-model="selectedbulk_action" value="bulk_action(selectedbulk_action)" @change="bulk_action(selectedbulk_action)" >
                                        <b-form-select-option :value="selectedbulk_action" selected="selected">Select Bulk-actions</b-form-select-option>
                                        <b-form-select-option v-for="bulk_action in bulk_action_list" :key="bulk_action.id"
                                                            :value="bulk_action.name">{{ bulk_action.name }}
                                        </b-form-select-option>
                                      </b-form-select>
                                      <div v-if="no_action_chosen">
                                        <span v-if="no_action_chosen" class="choose_notify"> &nbsp;<i class="uil-exclamation-triangle font-size-18"></i> Choose an action</span>
                                      </div>
                                      <div v-else>
                                        <button  v-if="bulkUpdating" type="submit" @click="confirmSubmitBulkActions(selectedbulk_action, checked_list )" id="submit_alumni" class="btn btn-primary w-md move-right crect">
                                          Submit items &nbsp;({{selected_count}})
                                        </button>
                                      </div>  
                                  </div>

                                  <BlockUI  v-if="confirm_action">
                                    <p class="block-iu-in-smgtex">
                                      <i class="uil-exclamation-triangle font-size-100"></i></h3> </br>
                                      <strong>You are about to do a BULK ACTION on selected items,</br>
                                      the result affects multiple records at a time, </br>
                                      are you sure you wish to complete this action!! </strong></br>
                                      Total (<strong><i>{{selected_count}}</i>)</strong> items will be affected
                                    </p>

                                    <div class="in-alert-cbtn_wrapper">
                                      <b-button variant="primary" class="mr-3" @click="cancelSubmitBulkActions" >Cancel
                                      </b-button>
                                      <button  type="submit" @click="submitBulkActionsItems(selectedbulk_action, checked_list )" id="submit_alumni" class="btn confirm-002 w-md move-right crect">
                                        I know what im doing
                                      </button>
                                    </div>
                                  </BlockUI>

                                  <div class="alert alert-danger" v-if="FormSubmittedError">
                                    <p>{{ FormSubmittedErrorText }}</p>
                                  </div>
                                  <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                    <p>{{ FormSubmittedSuccessText }}</p>
                                  </div>   
                            </div>
                            <!-- Search -->
                            <div class="col-sm-12 col-md-6">
                              <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                                <label class="d-inline-flex align-items-center">
                                  Search:
                                  <b-form-input v-model="filter" type="search" placeholder="Search..."
                                                class="form-control form-control-sm ml-2"></b-form-input>
                                </label>
                              </div>
                            </div>
                            <!-- End search -->
                          </div>

                          <!-- Table -->

                          <div class="table-responsive mb-0">
                            <hr>
                            <span  v-if="selectall_done" class="btn btn-primary waves-effect waves-light s_all_o1"  @click="unselectAll()" >
                             unselect all
                            </span>
                            <span v-else class="btn btn-primary waves-effect waves-light s_all_o1"  @click="selectAll(tableData)" >
                             Select all
                            </span>
                            <b-table table-class="table table-centered datatable table-card-list" thead-tr-class="bg-transparent"
                                     :items="subject_list"
                                     :fields="subject_fields"
                                     responsive="sm"
                                     :sort-by.sync="sortBy"
                                     :sort-desc.sync="sortDesc"
                                     :filter="filter"
                                     :filter-included-fields="filterOn"
                                     @filtered="onFiltered">
                              <template v-slot:cell(check)="data">
                                <div class="custom-control custom-checkbox text-center">
                                  <input type="checkbox" v-model="checked_selected" :value="data.item.id" @change="onChangeCheckeditem(data.item.course_code,selectedbulk_action)"  class="custom-control-input" :id="`contacusercheck${data.item.id}`"/>
                                  <label class="custom-control-label" :for="`contacusercheck${data.item.id}`"></label>
                                </div>
                              </template>
                              <template v-slot:cell(name)="data">
                                  <div class="details_field px-2">

                                    <router-link class=" text-primary " v-b-tooltip.hover title="Open to show"
                                                 :to="{path:'/course-management/manage-subjects', query:{id:data.item.id} }">      
                                      <div style='margin-left:10px;'>
                                        <strong>{{ data.item.subject_name }}</strong></br></br>
                                        <strong>Country:</strong><i> {{ data.item.country_id }}</i></br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                    </router-link>
                                 </div>


                              </template>
                             <!-- <template v-slot:cell(institution_summary.institution_name)="data">
                              </template> -->
                              <template v-slot:cell(actions)="data">
                                 {{data.item.course_image}}
                                <img v-if="data.item.course_image" :src="data.item.course_image" alt
                                     class="avatar-xs rounded-circle mr-2"/>
                                <router-link class="text-body"
                                             :to="{path:'/course-management/course-add/', query:{code:data.item.course_code} }">
                                </router-link>
                              </template>
                              <!--     <template v-slot:cell(attendance_type)="data">
                                     {{ data.item.attendance_type }}
                                   </late>
                              <template v-slot:cell(learning_mode)="data">
                                {{ data.item.learning_mode ? convertJsonToString(data.item.learning_mode) : "" }}
                              </template>-->
                              <template v-slot:cell(standard_fee_billing_type)="data">
                                {{ data.item.standard_fee_billing_type }}
                              </template>
                              <template v-slot:cell(currency)="data">
                                {{ data.item.currency }}
                              </template>
                              <template v-slot:cell(actions)="data">
                                 <div class="card action0_wrapper">
                                      <div></div>
                                      <div>
                                          <router-link :to="{path:'/course-management/course-edit/', query:{code:data.item.course_code} }"
                                             class="px-2 text-primary" v-b-tooltip.hover title="Edit">
                                             <i class="uil uil-pen font-size-18"></i></router-link>
                                      </div>
                                      <div>
                                          <a href="javascript:void(0);" class="px-2" @click="setFeatured(data.item)"
                                             :class="{'text-success':data.item.is_featured}" v-b-tooltip.hover title="Set Featured">
                                            <i class="uil uil-star font-size-18"></i>
                                          </a>
                                      </div>
                                      <div>
                                          <a href="javascript:void(0);" class="px-2" @click="delete_course(data.item.course_code)"
                                             :class="text-success" v-b-tooltip.hover title="delete">
                                            <i class="uil uil-trash-alt font-size-18"></i>
                                          </a>
                                      </div>  
                                  </div>
                              </template>

                            </b-table>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="dataTables_paginate paging_simple_numbers float-right">
                                <ul class="pagination pagination-rounded">
                                  <!-- pagination -->
                                  <b-pagination @input="handlePageChange" v-model="currentPage" :total-rows="rows"
                                                :per-page="perPage"></b-pagination>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import {BlockUI} from 'vue-blockui';
import { ToggleButton } from 'vue-js-toggle-button';
import ConfirmDialogue from '~/components/ConfirmDialogue.vue';

export default {
  head() {
    return {
      title: `${this.title} | Courses`
    };
  },
  data() {
    return {
      pathwayId: "",
      title: "Subjects|",
      subject_list:[],
      add_form:false,
      // Parameters that change depending on the type of dialogue
      title: undefined,
      message: undefined, // Main text content
      okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
      cancelButton: 'Go Back', // text for cancel button
      // Private variables
      resolvePromise: undefined,
      rejectPromise: undefined,
      checked_selected:[],
      institutions: [],
      editing_item:false,
      loadingData: false,
      filterData: {
        batch:null,
        course_type: null,
        institution_code: null,
        search_term: ""
      },
      build_data:{
          subjects:[],
          education_types:[],
          countries:[],
      }, 
      selectedbulk_action:"Select bulk-actions",
      no_action_chosen:false,
      bulk_action_list: [
        {
        id:1,
        name: "Bulk-publish",
        },
        {
        id:2,
        name: "Bulk-unpublish",
        },
        {
        id:4,
        name:"Bulk-Delete",
        }
      ],
      selected_count:0,
      selectall_done: false,
      bulkUpdating:false,
      bulkItemsSubmited:false,
      FormSubmittedError:false,
      FormSubmittedErrorText:"",
      FormSubmittedSuccess:false,
      FormSubmittedSuccessText:"",
      confirm_action:false,
      checked_list:[],
      current_publishing:null,
      batches:[],
      courseTypes: [],
      title: "Subjects",
      items: [{
        text: "Subject Management"
      },
        {
          text: "Courses",
          active: true
        }
      ],
      successMsg: false,
      text:"",
      success:"",
      successMsgText: "",
      totalRows: 1,
      editing:false,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100],
      filter: null,
      filterOn: [],
      sortBy: "id",
      sortDesc: false,
      loading_in_modal: false,
      sliderPrice: 800,
      subject_fields: [
          {
            key: "check",
            label: "",
          },
          {
            key: "name",
            label: 'Subject name',
            sortable: true
          },
          {
            key: "actions",
            label: 'Actions',
            sortable: true
          },
      ],
      items: [{
              text: "Tables"
          },
          {
              text: "Subject name | ",
              active: true
          }
      ]

    };

  },
  components: {
    Multiselect,
    ToggleButton,
    ConfirmDialogue,
    BlockUI
  },
  computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.totalRows;
    },
  },
  created() {
    //get the actual data
    this.rollbar_init();
    this.build();
    this.getSubjects();
    //get the builder
  },
  methods: {
    rollbar_init(){
      // include and initialize the rollbar library with your access token
      var Rollbar = require('rollbar')
      var rollbar = new Rollbar({
        accessToken: process.env.ROLLBAR_TOKEN,
        captureUncaught: true,
        captureUnhandledRejections: true,
      })
      // record a generic message and send it to Rollbar
      rollbar.log(console.error(""));
      rollbar.log(console.trace());
    },
    async build() {
      let self = this; 

       //start loading
      this.$nextTick(() => {
      });

      //getting pathway data
      let response = await ClusterManagementService.build();
      if (response.data.status) {
          this.build_data.subjects = response.data.data.subjects;
          this.build_data.education_types=response.data.data.education_types;
          this.build_data.countries=response.data.data.countries;

          //start loading
          this.$nextTick(() => {
          });
      }else{
          self.FormSubmittedError=response.data.status;
          self.FormSubmittedErrorText=response.data.message;
      }
    },
    formSubmit(){
      if (editing){
          this.updateSubject();
      }else{
          this.createSubject();
      }
    },
    async createSubject() {
        let self = this; 

       //start loading
        this.$nextTick(() => {
        });

        //getting pathway data
        let response = await StudentManagementService.createSubject(this.formData);
        if (response.data.status) {
            self.FormSubmittedSuccess = response.data.status;               
            self.FormSubmittedSuccessText = response.data.message; 
            this.getSubjects();   
            this.formData.subject_name="";            

            //start loading
            this.$nextTick(() => {
            });
        }else{
            self.FormSubmittedError=response.data.status;
            self.FormSubmittedErrorText=response.data.message;
        }
    },
    async updateSubject() {
        let self = this; 
        //getting pathway data
        let response = await StudentManagementService.updateSubjects(this.current_cluster.id,this.formData.subject_name,this.FormData.country_id);
        if (response.data.status) {
            self.FormSubmittedSuccess = response.data.status;               
            self.FormSubmittedSuccessText = response.data.message;  
            this.getClusters();              
            this.clusterFormData.cluster_name=""
            //start loading
            this.$nextTick(() => {
            });
        }else{
            self.FormSubmittedError=response.data.status;
            self.FormSubmittedErrorText=response.data.message;
        }
    },
    async getSubjects() {
      let self = this; 

     //start loading
      this.$nextTick(() => {
      });

      let response = await StudentManagementService.listSubjects();
      if (response.data.status) {
        self.subject_list = response.data.data.items;
        //start loading
        this.$nextTick(() => {
        });
      }
    },
    /**
     * Search the table data with search input
     */  
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.items;
      this.currentPage = 1;
    },
    handlePageChange(value) {
      this.getData(value)
    },
    handleFilters() {
      this.getData("")
    },
    handleClearFilters() {
      //clear the filters
      this.filterData.institution_code = null
      this.filterData.course_type = null
      //refresh the data
      this.getData("")
    },
    confirmSubmitBulkActions(){
      this.confirm_action=true;
    },
    cancelSubmitBulkActions(){
      this.confirm_action=false;
    },
  },
  middleware: "authentication",
  name: "CourseList",
}
</script>

<style scoped>
.details_field{
    display: grid;
    grid-template-columns: 25% 70%;
    overflow:hidden;
    border-radius:10px;
}
td a{
  color:#495057 !important;
}

.mainaction_wrapper{
  width:200px;
}
.action0_wrapper{
  width:50%;
  float:right;
  display: grid;
  grid-template-columns: 30% 20% 20% 20%; 
  margin-bottom: 0 !important;
  vertical-alighn:bottom;
  padding:5px;
}

.action1_wrapper{
  display: grid;
  grid-template-columns: 32% 30% 55%; 
  margin-bottom: 0 !important;
  vertical-alighn:bottom;
}

.current_action0{
  padding-top:5px;
}

.selectpicker{
  width:150px;
  height:50px;
  border-radius:10px;
  font-size:15px;
}

.selectpicker:after{
   content:"";
    width:0;
    height:0;
    border:5px solid transparent;
    border-color:black transparent transparent transparent;
    position:absolute;
    top:9px;
    right:6px;
}

.publish{
  background:#1C3367 !important;
}

unpublish{
 
}
.avatar-xs{
  height:100%;
  width:100%;
  border-radius:6px;
}
.w-md{
  min-width: 25px !important;
  padding:0.27rem 0.45rem !important;
  height: 2.56rem;
  margin-left:5px;
}

.top_rowmt{
  margin-top:3px !important;
}

.bulk_confirm_bx0{
  width:100%;
  height:180px;
  margin-left:11px;
  border-radius:5px;
  border-style:solid;
  border-color:#cccccc;
  border-width:thin;
}

.bulk_confirm_bx0 p{
  padding-left:9px;
}

.corner_point{
  width:16.5px;
  height:16.5px;
  margin-top:0px;
  margin-left:-10px;
  margin-top:10px;
  transform: rotate(45deg);
  border-style:solid;
  border-color:#cccccc;
  border-width:thin;
  border-right:none;
  border-top:none;
  z-index:1000;
  background:#ffffff;
}

.table-centered td{
  padding:0.2rem !important;
}

.actions-edt-del-in01 {
    width:100%;
    padding: 5px;
    padding-left: 10px;
    background: #ffffff;
    border-radius: 4px;
    border-width: thin;
}

.modal-dialog{
  display:block !important;
}

.choose_notify{
  color:red;
}

.mr-3{
  margin-right:3px !important;
}

hr{
  margin-top:0.7rem;
  margin-bottom:0.2rem;
}

.s_all_o1{
  cursor:pointer;
  float:right
}

.publish-indicator .publish-indicator{
  height:8px !important;
  overflow:hidden;
}
</style>
