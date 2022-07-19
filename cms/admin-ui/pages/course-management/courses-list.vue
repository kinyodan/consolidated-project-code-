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
                            <router-link class="btn btn-success waves-effect waves-light mb-3 mr-3" to="/course-management/course-add"><i
                              class="mdi mdi-plus mr-1"></i> Add Single Course
                            </router-link>
                            <router-link class="btn btn-primary waves-effect waves-light mb-3" to="/course-management/course-upload-bulk">
                              <i
                                class="mdi mdi-plus mr-1"></i> Bulk Upload Courses
                            </router-link> 
                          </div>

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
                                     :items="tableData"
                                     :fields="fields"
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
                              <template v-slot:cell(course_code)="data">
                                  <div class="details_field px-2">
                                    <router-link class=" text-primary " v-b-tooltip.hover title="Open to show"
                                                 :to="{path:'/course-management/course-edit/', query:{code:data.item.course_code} }">      
                                        <div>
                                          <img v-if="data.item.course_image" :src="data.item.course_image" alt class="avatar-xs iconDetails_linked mr-2"/>   
                                          <img v-else="data.item.course_image" :src="require('@/assets/images/6640292.jpg')" alt class="avatar-xs iconDetails_linked mr-2"/>   
                                        </div>
                                    </router-link>
                                    <router-link class=" text-primary " v-b-tooltip.hover title="Open to show"
                                                 :to="{path:'/course-management/course-edit/', query:{code:data.item.course_code} }">      
                                      <div style='margin-left:10px;'>
                                        <strong>{{ data.item.course_name }}</strong></br></br>
                                        <strong>Institution:</strong><i> {{ data.item.institution_summary.institution_name }}</i></br>
                                        <strong>Course code:</strong> {{ data.item.course_code }}</br>                                     
                                         <div style="font-size:.6em"></div>
                                      </div>
                                    </router-link>
                                 </div>
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
                             <!-- <template v-slot:cell(institution_summary.institution_name)="data">
                              </template> -->
                              <template v-slot:cell(course_name)="data">
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
                              <template v-slot:cell(standard_fee_payable)="data">
                                <div class="mainaction_wrapper">
                                  <div> 
                                    <strong>Publish</strong>&nbsp;
                                    <toggle-button @change="onChangeEventHandler(data.item.course_code,data.item.is_published )" :value="check_state(data.item.is_published)"
                                                 color="green"
                                                 :sync="true"
                                                 :labels="{checked: 'On', unchecked: 'Off'}"/>
                                      <span class="publish-indicator">
                                       <b-spinner class="m-2" role="status" v-if="loadingIndicator(data.item)"></b-spinner>
                                      </span>
                                  </div>
                                  <hr>
                                  <strong>Billing Type:</strong> {{ data.item.standard_fee_billing_type }}</br>
                                  <strong>Currency: <i>{{ data.item.currency }}</i></strong></br>
                                  <strong>Standard fee: </br></strong>{{ data.item.standard_fee_payable }}</br></br>
                                  <strong>Foreign fee: </br></strong>{{ data.item.foreign_student_fee_payable }}
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
import CoursesService from "~/helpers/course-services/CoursesService";
import { ToggleButton } from 'vue-js-toggle-button'
import ConfirmDialogue from '~/components/ConfirmDialogue.vue'
import {BlockUI} from 'vue-blockui'

export default {
  head() {
    return {
      title: `${this.title} | Courses`
    };
  },
  data() {
    return {
      // Parameters that change depending on the type of dialogue
      title: undefined,
      message: undefined, // Main text content
      okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
      cancelButton: 'Go Back', // text for cancel button
      // Private variables
      resolvePromise: undefined,
      rejectPromise: undefined,
      tableData: [],
      checked_selected:[],
      institutions: [],
      loadingData: false,
      filterData: {
        batch:null,
        course_type: null,
        institution_code: null,
        search_term: ""
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
      title: "Courses",
      items: [{
        text: "Course Management"
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
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100],
      filter: null,
      filterOn: [],
      sortBy: "id",
      sortDesc: false,
      fields: [
        {
          key: "check",
          label: "",
        },
        {
          key: "course_code",
          label: 'Details',
          sortable: true
        },
        {
          key: "standard_fee_payable",
          label: 'Fee Details',
          sortable: true
        },
 
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
    this.getData("");
    //get the builder
    this.getCourseBuilder()
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
    getCourseBuilder() {
      let self = this;
      CoursesService.getCourseBuild().then(response => {
        let data = response.data
        if (data.status) {
          //assign the data
          self.courseTypes = data.data.types
          self.institutions = data.data.institutions
        }
      });      
    },
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.items;
      this.currentPage = 1;
    },
    getData(page) {
      let courseFilterData = new FormData();
      for (let key in this.filterData) {
        courseFilterData.append(key, this.filterData[key] != null ? this.filterData[key] : "");
      }
      this.loadingData = true
      let self = this;
      CoursesService.getCourses(page, courseFilterData).then(response => {
        let data = response.data
        if (data.status) {
          self.tableData = data.data.items;
          self.totalRows = data.data.items_count;
          self.perPage = data.data.items_per_page;
          self.currentPage = data.data.current_page
          self.loadingData = false
        }
      })
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
    convertJsonToString(value) {
      try {
        let jsonObj = JSON.parse(value);
        if (typeof jsonObj === 'object') {
          return jsonObj.join(",")
        }
      } catch (e) {
        return value
      }
    },
    setFeatured(data) {
      let course_code = data.course_code
      let self = this
      CoursesService.setFeatured(course_code).then(response => {
        if (response.data.status) {
          location.reload()
        }else{
        }
      });
    },
    check_state(publish_state){
      if(publish_state==1){
        return true;
      }else{
        return false;
      }
    },
    bulk_action(action){
      if (this.selectedbulk_action!=="Select bulk-actions" && this.checked_list.length !== 0){
        this.bulkUpdating = true;
        this.no_action_chosen = false;
      }
      return this.selectedbulk_action;
    },
    delete_course(course_code){
      let self = this;
      CoursesService.deleteCourse(course_code).then(response => {
        if (response.data.status) {
          this.handlePageChange(self.currentPage);
        } else {
          this.handlePageChange(self.currentPage);
        }
      });
    },
    bulk_submitted(item, checked_list){
      this.bulkItemsSubmited = true;
      if(item == "Select Bulk-actions"){
        this.no_action_chosen = true;
      }else{
        this.no_action_chosen = false;
      }
      return true;
    },
    onChangeEventHandler(course_code, current_status){
      this.current_publishing = course_code;
      if (current_status==0){
        CoursesService.publishCourse(course_code).then(response => {
          if (response.data.status) {
            this.handlePageChange(this.currentPage);
            this.current_publishing = null;
          } else {
            this.current_publishing = null;
          }
        });
      }else{
        CoursesService.unpublishCourse(course_code).then(response => {
          if (response.data.status) {
            this.handlePageChange(this.currentPage);
            this.current_publishing = null;
          }else{
            this.current_publishing = null;          
          }
        });
      }
    },
    onChangeCheckeditem(item ,selectedbulk_action){

      this.current_publishing = item.course_code; 
      //###### CONFIRM IF ITEM IS PRESENT IN CHECKED LIST=> if so remove it and uncheck it ..............
      if (this.checked_list.includes(item)){
        var existing_item=this.checked_list.indexOf(item);
        this.checked_list.splice(existing_item,1)
        this.selected_count = this.checked_list.length;

        if(this.checked_list.length == 0){
          this.bulkUpdating = false;
        }else{
          //...........ENSURE BULK ACTION IS SELECTED.....................
            if(selectedbulk_action==="Select Bulk-actions"){
              this.bulkUpdating = false;
              this.no_action_chosen = true;
            }else{
              this.bulkUpdating = true;          
            }
          //..............................................................
        }
      //###### ELSE----CONFIRM IF ITEM IS PRESENT IN CHECKED LIST=> if not add it its checked.. ..............
      }else{
        this.checked_list.push(item);
        this.selected_count = this.checked_list.length;

        if(this.checked_list.length == 0){
          this.bulkUpdating = false;
        }else{
          //...........ENSURE BULK ACTION IS SELECTED.....................
            if(selectedbulk_action==="Select bulk-actions"){
              this.bulkUpdating = false;
              this.no_action_chosen = true;
            }else{
              this.bulkUpdating = true;          
            }
          //..............................................................
        }
      }
    },
    confirmSubmitBulkActions(){
      this.confirm_action=true;
    },
    cancelSubmitBulkActions(){
      this.confirm_action=false;
    },
    submitBulkActionsItems(bulk_action,checked_list){
      var bulkactiondata = new FormData();
      if(bulk_action=="Select Bulk-actions"){

      }else{
        var stringifiedCheckedList =JSON.stringify(checked_list);
        var json_checkedlist =[];
        bulkactiondata.append("course_codes", stringifiedCheckedList);

        for (var value of bulkactiondata.values()) {
          json_checkedlist = value;
        }

        // SUBMIT ACTIONS TO RELEVANT SERVICE API....................... 
          if(bulk_action==="Bulk-publish"){
            this.submitBulkPublishAction(json_checkedlist ,bulk_action);

          }else if(bulk_action==="Bulk-unpublish"){
            this.submitBulkUnpublishAction(json_checkedlist,bulk_action);

          }else if(bulk_action==="Bulk-Setfeatured"){
            this.submitBulkSetfeaturedAction(json_checkedlist,bulk_action);

          }else if(bulk_action==="Bulk-Delete"){
            this.submitBulkDeleteAction(json_checkedlist,bulk_action);

          }else{

          }
        // end SUBMIT ACTIONS TO RELEVANT SERVICE API..................
        this.no_action_chosen = false;
        this.selectedbulk_action="Select Bulk-actions"
        this.confirm_action=false;
      }
    },
    submitBulkPublishAction(checked_list,bulk_action){
      var string_checked_list = JSON.parse(JSON.stringify(checked_list))
      CoursesService.bulkPublishCourse(string_checked_list).then(response => {
        if (response.data.status) {
          this.handlePageChange(this.currentPage);
          this.clear_values();
          this.displaySuccess(response.data.message)
        } else {
          this.displayError(response.data.message)
        }
      });
    },
    submitBulkUnpublishAction(checked_list,bulk_action){
      var string_checked_list = JSON.parse(JSON.stringify(checked_list))
      CoursesService.bulkUnpublishCourse(string_checked_list).then(response => {
        if (response.data.status) {
          this.handlePageChange(this.currentPage);
          this.clear_values();
          this.displaySuccess(response.data.message)
        } else {
          this.displayError(response.data.message)
        }
      });
    },
    submitBulkSetfeaturedAction(checked_list,bulk_action){
      var string_checked_list = JSON.parse(JSON.stringify(checked_list))
      CoursesService.bulkUnpublishCourse(string_checked_list).then(response => {
        if (response.data.status) {
          this.handlePageChange(this.currentPage);
          this.clear_values();
          this.displaySuccess(response.data.message)
        } else {
          this.displayError(response.data.message)
        }
      });
    },
    submitBulkDeleteAction(checked_list,bulk_action){
      var string_checked_list = JSON.parse(JSON.stringify(checked_list))
      CoursesService.bulkDeleteCourses(string_checked_list).then(response => {
        if (response.data.status) {
          this.handlePageChange(this.currentPage);
          this.clear_values();
          this.displaySuccess(response.data.message)
        } else {
          this.displayError(response.data.message)
        }
      });
    },
    clear_values(){
      this.bulkUpdating = false;
      this.no_action_chosen = true;
      this.checked_list = [];
      this.checked_selected=[];
    },
    selectAll(items){
      this.selectall_done = true;
      items.forEach(item => {
       this.checked_selected.push(item.id)
      })
      this.selected_count = this.checked_selected.length;
      this.bulkUpdating = true;
    },
    unselectAll(items){
      this.selectall_done = false;
      this.checked_selected=[];
      this.bulkUpdating = false;
    },
    displaySuccess(message){
      this.FormSubmittedSuccess = true;
      this.FormSubmittedSuccessText = message

      //timout the success message
      setTimeout(function(scope) {
          scope.FormSubmittedSuccess = false;
      }, 7000, this);
    },
    displayError(message){
      this.FormSubmittedSuccess = false;
      this.FormSubmittedSuccessText = message

      //timout the success message
      setTimeout(function(scope) {
          scope.FormSubmittedError = false;
      }, 7000, this);
    },
    loadingIndicator(item){
      if(this.current_publishing == item.course_code){
        return true;
      }else{
        return false
      }    
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
    height:200px;
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
  width:30%;
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
