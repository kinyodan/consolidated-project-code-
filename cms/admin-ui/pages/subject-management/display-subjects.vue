<script>

import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import { ToggleButton } from 'vue-js-toggle-button'
import {BlockUI} from 'vue-blockui'
import { TimeAgo } from 'vue2-timeago'
import 'vue2-timeago/dist/vue2-timeago.css'

import axios from "axios";

/**
 * Advanced-table component
 */
export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    data() {
        return {
            title: "Subjects Display",
            subject_list:[],
            add_form:false,
            new_subject:false,
            form_header_text:"New",
            confirm_action:false, 
            current_delete_item:null,
            loading_items:true,
            editing_item:false,
            current_subject:"",
            subject_inputs:[],
            form_data:{
                subject_title:"",
                subject_title_description:"",
                academic_disciplines_id:"",
                display_order:"",
                education_type_id:"",
                country_id:""
            },
            build_data:{
                subjects:[],
                education_types:[],
                countries:[],
                academic_disciplines:[]
            }, 
            FormSubmittedSuccessText:"",
            FormSubmittedSuccess:false,
            FormSubmittedError:false,
            FormSubmittedErrorText:"",
            editing:false,
            items: [{
                    text: "Subject Managemen"
                },
                {
                    text: "Subjects",
                    active: true
                }
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100],
            filter: null,
            filterOn: [],
            sortBy: "age",
            sortDesc: false,
            fields: [{

                    key: "Subject_name",
                    label: 'Subject name',
                    sortable: true
                },
                {
                    key: "country",
                    label: 'country',
                    sortable: true
                },
                {
                    key: "is_published",
                    label: 'is_published',
                    sortable: true
                },
                /*{
                    key: "created_at",
                    sortable: true
                },*/
                {
                    key:"actions",
                    label: 'Actions',
                    sortable: true

                }
            ]
        };
    },
    computed: {
        /**
         * Total no. of records
         */
        rows() {
            return this.subject_list.length;
        }
    },
    mounted() {
        // Set the initial number of items
        this.totalRows = this.items.length;
    },
    components: {
      BlockUI,
      TimeAgo,
      ToggleButton
    },
    created(){
     this.getSubjects("");
     this.build();
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

            let response = await ClusterManagementService.build();
            if (response.data.status) {
                this.build_data.subjects = response.data.data.subjects;
                this.build_data.education_types=response.data.data.education_types;
                this.build_data.countries=response.data.data.countries;
                this.build_data.academic_disciplines=response.data.data.academic_disciplines;


                this.loading_items=false;
                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;

            }
        },
        formSubmit(){
            if (this.editing){
                this.updateSubject(this.formData);
            }else{
                this.createSubject(this.formData);
            }
        },
        async createSubject() {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

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
        async updateSubject(subject) {
            let self = this; 
            let response = await StudentManagementService.updateSubjects(this.current_subject,subject.subject_name,subject.country_id);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message;  
                this.editing=false
                this.formData.subject_name = "";
                this.formData.country_id = "";
                this.form_header_text="New";
                this.current_subject="";

                this.getSubjects();              
                this.ormData=[];
                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;

            }
        },
        async getSubjects(page) {
            let self = this; 
            
            //start loading
            this.$nextTick(() => {
            });

            let response = await StudentManagementService.listSubjects(page);
            console.log(response)
            if (response.data.status) {
                self.subject_list = response.data.data.items;
                self.loading_items=false;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        /**
         * Search the table data with search input
         */
        tabs_control(evt,el_name){

          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }

          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
          }

          document.getElementById(el_name).style.display = "block";
          evt.currentTarget.className += " active";
        },
        /**
         * Search the table data with search input
         */
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        check_state(state){
          if(state==1){
            return true;
          }else{
            return false;
          }
        },
        newSubjectPopup(){
           this.new_subject=true;
        },
        new_subject_class_form(item){
           this.subject_inputs.push(item)
        },
        remove_form_box(index){
          var inputs_list = this.subject_inputs
          delete inputs_list[index];
          console.log(inputs_list);
        },
        close(){
           this.new_subject=false; 
           this.form_heder_text="New";
           this.FormSubmittedSuccessText="";
           this.FormSubmittedSuccess=false;
           this.FormSubmittedError=false;
           this.FormSubmittedErrorText="";
           this.editing=false;
           this.editing_item=false;
           this.formData.subject_name = null;
           this.formData.country_id = null;
        },
        editSubjectPopup(subject){
           this.new_subject=true;
           this.editing=true;
           this.editing_item=true;
           this.formData.subject_name = subject.subject_name;
           this.formData.country_id = subject.country_id;
           this.form_header_text="Edit";
           this.current_subject=subject.id;
        },
        async delete_this(subject) {
            let self = this; 
            let response = await StudentManagementService.deleteSubject(subject.id);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message;  
                this.getSubjects();
                this.confirm_action=false;               
                this.ormData=[];
                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }
        },
        handlePageChange(value) {
          //get the data
          this.getSubjects(value)
        },
        confirmSubmitAction(item){
          this.current_delete_item=item;
          this.confirm_action=true;
        },
        cancelSubmitActions(){
          this.confirm_action=false;
        },
    },
    middleware: "authentication"
};
</script>

<template>
<div>
    <PageHeader :title="title" :items="items" />
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Subjects</h4>
                            <div class="">
                              </br>
                              <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="newSubjectPopup()" >
                                  <span class="bck-to-0">&nbsp;&nbsp;new Subject&nbsp;&nbsp;</span>
                              </a>
                            </div>

                            <BlockUI  v-if="new_subject">
                              <div class="alert alert-danger" v-if="FormSubmittedError">
                                <p> {{FormSubmittedErrorText}} </p>
                              </div>

                              <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                <p>{{FormSubmittedSuccessText}} </p>
                              </div>

                              <span class="close" @click="close()"></span>
                              <h4> New Subject </h4>                                
                              <div class="row">
                                  <div class="col-12">

                                   <div class="sb_form_wrapper">
                                      <form v-on:submit.prevent="formSubmit" class="form-horizontal" role="form">                                             

                                          <div class="form-group position-relative">                                         
                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Education type id</label>
                                              <b-form-select   
                                              v-model="form_data.education_type_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Country</label>
                                              <b-form-select   
                                              v-model="form_data.country_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Academic disciplines</label>
                                              <b-form-select   
                                              v-model="form_data.academic_disciplines_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                                <label class="pd-hld move-left" id="current_position" >Display order</label>
                                                <input v-model="form_data.display_order" type="text"
                                                 class="form-control"
                                                 placeholder="Name" name="Subject name"/>
                                            </div>  

                                            <div class="item_holderx">
                                                <div class="sb_inner_wrapper" v-for="(input,index) in subject_inputs" >
                                                    <label class="pd-hld move-left" id="current_position" >Subject title</label>
                                                    <input v-model="form_data.subject_title" type="text"
                                                     class="form-control"
                                                     placeholder="Subject classification Name" name="Subject-classification"/>

                                                    <label class="pd-hld move-left" id="current_position" >Subject title description</label>
                                                    <input v-model="form_data.subject_title_description" type="text"
                                                     class="form-control"
                                                     placeholder="Subject classification Name" name="Subject-classification"/>

                                           <a href="javascript:void(0);" role="button" class="px-2 move-right " @click="remove_form_box(index)" v-b-tooltip.hover title="delete">
                                            <i class="uil uil-trash-alt font-size-18"></i>
                                            </a>

                                                    </br>  

                                                <hr>
                                                </div></br>
                                                <a href="javascript:void(0)" @click="new_subject_class_form()" class="px-2 move-right" v-b-tooltip.hover title="Add Subject Clusters">
                                                 <i class="uil uil-plus-square font-size-20"></i>Add new
                                                </a></br></br>
                                            </div> 
                                          </div>
                                          <hr>
                                          <div  class="col-xl-3">
                                            <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-left">
                                              Submit
                                            </button>
                                          </div>
                                      </form>
                                      </div>
                                      </br>

                                  </div>
                              </div>
                            </BlockUI>


                            <BlockUI  v-if="editing_item">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>

                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                 <p>{{FormSubmittedSuccessText}} </p>
                                </div>

                                <span class="close" @click="close()"></span>
                                <h4> {{ form_header_text }} Subject </h4>
                                
                                <div class="row">
                                    <div class="col-12">
                                      <div class="sb_form_wrapper">
                                        <form v-on:submit.prevent="submitClusterSubject" class="form-horizontal" role="form">                          

                                          <div class="form-group position-relative">                                         
                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Education type</label>
                                              <b-form-select   
                                              v-model="form_data.education_type_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Country</label>
                                              <b-form-select   
                                              v-model="form_data.country_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                              <label class="pd-hld move-left" id="current_position" >Academic disciplines</label>
                                              <b-form-select   
                                              v-model="form_data.academic_disciplines_id"
                                              :options="build_data.academic_disciplines"
                                              class="mb-3 select_ecp"
                                              value-field="id"
                                              text-field="label"
                                              disabled-field="notEnabled"
                                              placeholder="Country"
                                              >
                                              <b-form-select-option >
                                               Academic disciplines</b-form-select-option>
                                              </b-form-select>
                                            </div>  

                                            <div  class="item_holderx">
                                                <label class="pd-hld move-left" id="current_position" >Display order</label>
                                                <input v-model="form_data.display_order" type="text"
                                                 class="form-control"
                                                 placeholder="Name" name="Subject name"/>
                                            </div>  

                                            <div class="item_holderx">
                                                <div class="sb_inner_wrapper" v-for="(input,index) in subject_inputs" >
                                                    <label class="pd-hld" id="current_position" >Subject title</label>
                                                    <input v-model="form_data.subject_title" type="text"
                                                     class="form-control"
                                                     placeholder="Subject classification Name" name="Subject-classification"/>

                                                    <label class="pd-hld" id="current_position" >Subject Title_description</label>
                                                    <input v-model="form_data.subject_title_description" type="text"
                                                     class="form-control"
                                                     placeholder="Subject classification Name" name="Subject-classification"/>
                                                    </br>  
                                                </div></br>
                                                <hr>
                                                <a href="javascript:void(0)" @click="new_subject_class_form()" class="px-2 move-right" v-b-tooltip.hover title="Add Subject Clusters">
                                                 <i class="uil uil-plus-square font-size-20"></i>Add new
                                                </a></br></br>
                                            </div> 
                                          </div>
                                          <hr>
                                          <div  class="col-xl-3">
                                            <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-left">
                                              Submit
                                            </button>
                                          </div>
                                        </form>
                                       </div>

                                      </br>
                                    </div>
                                </div>
                            </BlockUI>


                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-6">
                            <div id="tickets-table_length" class="dataTables_length">
                                <label class="d-inline-flex align-items-center">
                                    Show&nbsp;
                                    <b-form-select v-model="perPage" size="sm" :options="pageOptions"></b-form-select>&nbsp;entries
                                </label>
                            </div>
                        </div>
                        <!-- Search -->
                        <div class="col-sm-12 col-md-6">
                            <div id="tickets-table_filter" class="dataTables_filter text-md-end">
                                <label class="d-inline-flex align-items-center">
                                    Search:
                                    <b-form-input v-model="filter" type="search" class="form-control form-control-sm ml-2"></b-form-input>
                                </label>
                            </div>
                        </div>
                        <!-- End search -->
                        <b-spinner label="Loading..." v-if="loading_items" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive mb-0 " >
                        <b-table :items="subject_list" :fields="fields" 
                        responsive="sm" :per-page="perPage" 
                        :current-page="currentPage" 
                        :sort-by.sync="sortBy" 
                        :sort-desc.sync="sortDesc" 
                        :filter="filter" 
                        :filter-included-fields="filterOn" 
                        @filtered="onFiltered">

                              <template v-slot:cell(subject_name)="data">
                                  <div class="details_field px-2">  
                                      <div style='margin-left:10px;'>
                                        {{ data.item.subject_name }} </br></br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(country)="data">
                                  <div class="details_field px-2">
                                      <div style='margin-left:10px;'>
                                          {{ data.item.country_name}}</br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(is_published)="data">
                                  <div class="details_field px-2">  
                                         <div style='margin-left:10px;'>
                                          <toggle-button @change="" :value="check_state(data.item.is_published)"
                                                 color="green"
                                                 :sync="true"
                                                 :labels="{checked: 'On', unchecked: 'Off'}"/>
                                          </br></br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(created_at)="data">
                                  <div class="details_field px-2">
                                      <div style='margin-left:10px;'>
                                        <time-ago :refresh="60" :datetime="data.item.created_at" locale="" tooltip></time-ago>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(actions)="data">
                                  <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="editSubjectPopup(data.item)" v-b-tooltip.hover title="Edit">
                                     <i class="uil uil-pen font-size-18"></i>
                                  </a>

                                  <a href="javascript:void(0);" role="button" class="px-2 " @click=" confirmSubmitAction(data.item)" v-b-tooltip.hover title="delete">
                                    <i class="uil uil-trash-alt font-size-18"></i>
                                  </a>
                              </template>
                        </b-table>
                        <BlockUI  v-if="confirm_action">
                          <p class="block-iu-in-smgtex0">
                            <i class="uil-exclamation-triangle font-size-100"></i></h3> </br>
                            <strong>You are about to do a Delete action on selected item,</br>
                            </br>
                            are you sure you wish to complete this action!! </strong></br>
                            
                          </p>

                          <div class="in-alert-cbtn_wrapper">
                            <b-button variant="primary" class="mr-3" @click="cancelSubmitActions" >Cancel
                            </b-button>
                            <button  type="submit" @click="delete_this(current_delete_item)" id="submit_alumni" class="btn confirm-002 w-md move-right crect">
                              I know what im doing
                            </button>
                          </div>
                        </BlockUI>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="dataTables_paginate paging_simple_numbers float-end">
                                <ul class="pagination pagination-rounded mb-0">
                                    <!-- pagination -->
                                    <b-pagination @input="handlePageChange" v-model="currentPage" :total-rows="rows" :per-page="perPage"></b-pagination>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>

.sb_form_wrapper{
    min-height:400px;
    max-height:400px;
    min-width:400px;
    overflow:scroll;
    overflow-x:hidden;
}

.sb_inner_wrapper .move-right{
  width:20% !important;

}
</style>
