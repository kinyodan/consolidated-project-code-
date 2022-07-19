<script>

import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import CoursePathwaysManagementService from "@/helpers/services/CoursePathwaysManagementService";
import { ToggleButton } from 'vue-js-toggle-button'
import {BlockUI} from 'vue-blockui'
import { TimeAgo } from 'vue2-timeago'
import 'vue2-timeago/dist/vue2-timeago.css'
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'


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
            title: "Course Pathway Linking",
            subject_list:[],
            add_form:false,
            new_item:false,
            form_header_text:"New", 
            confirm_action:false,
            current_delete_item:null,
            loading_items:true,
            edit_item:false,
            editing_item:false,
            current_subject:"",
            linked_disciplines:"",
            academicDisciplines:[],
            formData:{
                academic_disciplines:null,
                pathway:""
            },
            build_data:{
                pathways:[],
                academic_disciplines:[],
            }, 
            FormSubmittedSuccessText:"",
            FormSubmittedSuccess:false,
            FormSubmittedError:false,
            FormSubmittedErrorText:"",
            editing:false,
            items: [{
                    text: "Course Pathway Linking"
                },
                {
                    text: "Course Pathway Linking",
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

                    key: "Course",
                    label: 'Course name',
                    sortable: true
                },
                {
                    key: "pathway",
                    label: 'Pathway',
                    sortable: true
                },
                /*{
                    key: "is_published",
                    label: 'is_published',
                    sortable: true
                },*/
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
      ToggleButton,
      Treeselect
    },
    created(){
     this.getData("");
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

            let response = await CoursePathwaysManagementService.build();
            if (response.data.status) {
               var init_build_data = response.data.data.disciplines;

                init_build_data.forEach((discipline) => {
                 var item = { "id": discipline.id, "label": discipline.discipline_name }
                 self.build_data.academic_disciplines.push({ "id": discipline.id, "label": discipline.discipline_name } )
                });
          
                this.build_data.pathways=response.data.data.pathways;
                //this.build_data.countries=response.data.data.countries;
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
                this.updateItem(this.formData);
            }else{
                this.createItem(this.formData);
            }
        },
        async createItem() {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

            let response = await CoursePathwaysManagementService.createItem(this.formData);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message; 
                this.editing=false
                this.formData.academic_disciplines = "";
                this.formData.pathways = "";
                this.form_header_text="New";
                this.getData();   

                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;

            }
        },
        async updateItem(subject) {
            let self = this; 
            let response = await CoursePathwaysManagementService.updateItem(this.current_subject,subject.subject_name,subject.country_id);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message;  
                this.editing=false
                this.formData.academic_disciplines = "";
                this.formData.pathways = "";
                this.form_header_text="New";

                this.getData();              
                this.ormData=[];
                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }
        },
        async getData(page) {
            let self = this; 
            
            //start loading
            this.$nextTick(() => {
            });

            let response = await CoursePathwaysManagementService.listItems(page);
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
        newPopup(){
           this.new_item=true;
        },
        close(){
           this.new_item=false; 
           this.editing_item=false;
           this.form_heder_text="New";
           this.FormSubmittedSuccessText="";
           this.FormSubmittedSuccess=false;
           this.FormSubmittedError=false;
           this.FormSubmittedErrorText="";
           this.editing=false;
        },
        async getToEDit(item){
            let response = await CoursePathwaysManagementService.getToEdit(item.id);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message;  
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
        editPopup(item){
           this.editing_item=true;
           this.editing=true;
           //this.formData.subject_name = subject.subject_name;
           //this.formData.country_id = subject.country_id;
           this.form_header_text="Edit";
           //this.current_subject=subject.id;
        },
        async delete_this(item) {
            let self = this; 
            let response = await CoursePathwaysManagementService.deleteItem(item.courses_careers_pathways_id);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccessText = response.data.message;  
                this.getData();
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
          this.getData(value)
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
                    <h4 class="card-title">Linked courses and pathways</h4>
                          <div class="">
                          </br>
                              <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="newPopup()" >
                                  <span class="bck-to-0">&nbsp;&nbsp;new Course pathways link &nbsp;&nbsp;</span>
                              </a>
                          </div>

                          <BlockUI  v-if="new_item">
                              <div class="alert alert-danger" v-if="FormSubmittedError">
                                <p> {{FormSubmittedErrorText}} </p>
                              </div>

                              <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                <p>{{FormSubmittedSuccessText}} </p>
                              </div>

                              <span class="close" @click="close()"></span>
                              <h4> {{ form_header_text }} Course pathways link  </h4>
                                
                                      <div class="row">
                                          <div class="col-12">
                                        <form v-on:submit.prevent="formSubmit" class="form-horizontal" role="form">
                                            <label class="pd-hld move-left" id="current_position" >pathways</label>
                                            <b-form-select   
                                            v-model="formData.pathway"" 
                                            :options="build_data.pathways"
                                            class="mb-3 select_ecp"
                                            value-field="id"
                                            text-field="career_pathway_name"
                                            disabled-field="notEnabled"
                                            placeholder="Country"
                                            >
                                            <b-form-select-option >
                                             Pathways</b-form-select-option>
                                            </b-form-select>
                                          
                                            <div  v-if="editing" class="item_holderx">
                                            <label class="pd-hld move-left" id="current_position" >Academic disciplines</label>
                                              <b-form-select   
                                              v-model="formData.academic_disciplines"" 
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

                                            <div v-else class="item_holderx">
                                              <treeselect v-model="formData.academic_disciplines" :multiple="true" 
                                               :options="build_data.academic_disciplines" />
                                            </div> 

                                             <hr>
                                            <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-right">
                                              Submit
                                            </button>
                                        </form>
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
                              <h4> {{ form_header_text }} Course pathways link  </h4>
                                
                                      <div class="row">
                                          <div class="col-12">
                                        <form v-on:submit.prevent="formSubmit" class="form-horizontal" role="form">
                                            <label class="pd-hld move-left" id="current_position" >pathways</label>
                                            <b-form-select   
                                            v-model="formData.pathway"" 
                                            :options="build_data.pathways"
                                            class="mb-3 select_ecp"
                                            value-field="id"
                                            text-field="career_pathway_name"
                                            disabled-field="notEnabled"
                                            placeholder="Country"
                                            >
                                            <b-form-select-option >
                                             Pathways</b-form-select-option>
                                            </b-form-select>
                                          
                                            <div  v-if="editing" class="item_holderx">
                                            <label class="pd-hld move-left" id="current_position" >Academic disciplines</label>
                                              <b-form-select   
                                              v-model="formData.academic_disciplines"" 
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

                                            <div v-else class="item_holderx">
                                              <treeselect v-model="formData.academic_disciplines" :multiple="true" 
                                               :options="build_data.academic_disciplines" />
                                            </div> 

                                             <hr>
                                            <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-right">
                                              Submit
                                            </button>
                                        </form>
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

                              <template v-slot:cell(Course)="data">
                                  <div class="details_field px-2">  
                                      <div style='margin-left:10px;'>
                                        {{ data.item.discipline_name }} </br></br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(pathway)="data">
                                  <div class="details_field px-2">
                                      <div style='margin-left:10px;'>
                                          {{ data.item.career_pathway_name}}</br>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                             <!-- <template v-slot:cell(is_published)="data">
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
                              </template> -->

                              <template v-slot:cell(created_at)="data">
                                  <div class="details_field px-2">
                                      <div style='margin-left:10px;'>
                                        <time-ago :refresh="60" :datetime="data.item.created_at" locale="" tooltip></time-ago>
                                         <div style="font-size:.6em"></div>
                                      </div>
                                 </div>
                              </template>

                              <template v-slot:cell(actions)="data">
                                  <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="editPopup(data.item)" v-b-tooltip.hover title="Edit">
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
