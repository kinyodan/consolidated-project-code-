<script>
/**
 * Basic table component
 */
import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import CoursePathwaysManagementService from "@/helpers/services/CoursePathwaysManagementService";
import PathwaysService from "@/helpers/services/PathwaysService";
import {BlockUI} from 'vue-blockui'
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'


import axios from "axios";

export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    data() {
        return {
            pathwayId: "",
            title: "Pathway|",
            spec_items: [],
            alternatives:[],
            cluster_subjects:[],
            built_clusters:[],
            current_cluster:null,
            editingSubject:false,
            subject_form:false,
            loading_items:true,
            loading_in_modal:true, 
            current_delete_item:null,
            confirm_action:false,
            built_countries_list:[],
            form_data:{
                cluster_id:"",
                subject_id:"",
                education_type_id:"",
                country_id:"",
                is_primary:"",
                career_pathway_id:"",
                course_code:"",
                grade:"",
                academic_disciplines:""
            },
            cluster_fields: [
                {
                  key: "check",
                  label: "",
                },
                {
                  key: "Name",
                  label: 'name',
                  sortable: true
                },
                /*{
                  key: "Country",
                  label: 'country',
                  sortable: true
                },*/
                {
                  key: "type",
                  label: 'Type',
                  sortable: true
                },
                {
                  key: "actions",
                  label: 'Actions',
                  sortable: true
                },
            ],
            build_data:{
                subjects:[],
                education_types:[],
                countries:[],
                academic_disciplines:[],
                grades:[
                    {grade:"A"},
                    {grade:"A-"},
                    {grade:"B+"},
                    {grade:"B"},
                    {grade:"B-"},
                    {grade:"C+"},
                    {grade:"C"},
                    {grade:"C-"},
                    {grade:"D+"},
                    {grade:"D"},
                    {grade:"D-"},
                    {grade:"E"}
                ],
                is_primary:[
                    {id: 1,value:"is_primary"},
                    {id: 2,value:"is_altervative"},
                ]
            },  
            clusters:[],
            FormSubmittedSuccessText:"",
            FormSubmittedSuccess:false,
            FormSubmittedError:false,
            FormSubmittedErrorText:"",
            totalRows:1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100],
            filter: null,
            filterOn: [],
            sortBy: "id",
            sortDesc: false,
            loading_in_modal: false,
            sliderPrice: 800,
            items: [{
                    text: "Subject Managemen"
                },
                {
                    text: "Subject Clustering | ",
                    active: true
                }
            ]
        };
    },
    components: {
      BlockUI,
      Treeselect
    },
    computed: {
        /**
         * Total no. of records
         */
        rows() {
          return this.totalRows;
        }
    },
    created(){
      this.getCluster();
      this.build();
      this.buildGrades();
      if (this.$route.query.id){
        this.editing = true;
      }
    },
    middleware: "authentication",
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
                this.build_data.education_types=response.data.data.education_types;
                this.build_data.countries=response.data.data.countries;

                var init_build_data = response.data.data.subjects;
                console.log(init_build_data);
                init_build_data.forEach((subject) => {
                 var item = { "id": subject.id, "label": subject.subject_name }
                 self.build_data.subjects.push({ "id": subject.id, "label": subject.subject_name } )
                });

                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }

            let response_i = await CoursePathwaysManagementService.build();
            if (response_i.data.status) {
                var init_build_data = response_i.data.data.disciplines;
                init_build_data.forEach((discipline) => {
                 var item = { "id": discipline.id, "label": discipline.discipline_name }
                 self.build_data.academic_disciplines.push({ "id": discipline.id, "label": discipline.discipline_name } )
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }
        },
        async buildGrades(){
            let self = this;
            let response = await ClusterManagementService.buildGrades();
            if (response.data.status) {
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        async submitClusterSubject() {
            this.form_data.cluster_id = this.current_cluster.clusters_id
            this.form_data.career_pathway_id = 1

            let self = this; 
           
            let response = await ClusterManagementService.createClustersSubjects(this.form_data);
            console.log(response);
            console.log(this.form_data.subject_ids);

        },
        async getCluster() {
            let self = this; 
           //start loading
            this.$nextTick(() => {
            });

            let response = await ClusterManagementService.listClusters();
            if (response.data.status) {
                self.clusters = response.data.data.items;
                self.loading_items=false;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        populate_cluster_data(cluster){
            this.current_cluster = cluster
            this.get_cluster_data(cluster.clusters_id,cluster.country_id,this.current_page)
        },  
        get_cluster_data(cluster_id, country_id,page){
            console.log("the current_page is"+page)
            let self = this;
            this.loading_in_modal=true;
            this.current_cluster_selected = true;
            this.FormSubmittedSuccess = false;               
            this.FormSubmittedError=false;
            this.FormSubmittedErrorText="";

            ClusterManagementService.getClustersSubjects(cluster_id,country_id,page).then(response => {
                console.log(response)
                if (response.data.status) {
                    this.cluster_subjects = response.data.data.items;
                    this.loading_in_modal=false;
                    this.totalRows = response.data.data.items_count;
                    this.perPage = response.data.data.items_per_page;
                    this.currentPage = response.data.data.current_page
                    this.form_data.country_id = country_id;
                    //start loading
                    this.$nextTick(() => {
                    });
                }

            });
        },
        currentClusterSelected(){
            if (this.current_cluster_selected==true){
                return true;
            }else{
                return false;
            }
        },
        newSubjectPopup(){
           this.subject_form=true;
        },
        newSubjectClose(){
            this.subject_form=false; 
            this.form_data.cluster_id=""
            this.form_data.subject_id=""
            this.form_data.education_type_id=""
            this.form_data.country_id=""
            this.form_data.is_primary=""
            this.form_data.career_pathway_id=""
            this.form_data.course_code=""
        },
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.items;
          //this.currentPage = this.current_page;
        },
        async delete_this(item) {
          let self = this; 
          let response = await ClusterManagementService.deleteClustersSubjects(item.cluster_subjects_id);
          if (response.data.status) {
              this.FormSubmittedSuccess = response.data.status;               
              this.FormSubmittedSuccessText = response.data.message; 
              this.get_cluster_data(item.cluster_id,item.country_id,this.current_page);
              this.confirm_action=false;           
              this.formData=[];
              //start loading
              this.$nextTick(() => {
              });
          }else{
              self.FormSubmittedError=response.data.data.status;
              self.FormSubmittedErrorText=response.data.data.message;
          }
        },
        handlePageChange(page) {
           console.log("the current_page on handlePageChange is"+page)
           console.log(this.current_cluster);
          //get the data
          this.get_cluster_data(this.current_cluster.clusters_id, this.current_cluster.country_id,page)
        },
        confirmSubmitAction(item){
          this.current_delete_item=item;
          this.confirm_action=true;
        },
        cancelSubmitActions(){
          this.confirm_action=false;
        },
    },    

};
</script>

<template>
<div>

 <div class="row">

        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent border-bottom">
                    <a href="/subject-management/manage-clusters" class="px-2 move-right" v-b-tooltip.hover title="Add Subject Clusters">
                     <i class="uil uil-plus-square font-size-20"></i>
                    </a></br>
                    <h5 class="mb-0">Clusters</h5>
                    <b-spinner label="Loading..." v-if="loading_items" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>
                </div>

                <div class="p-4">
                    <div class="custom-accordion" v-for="country in clusters" :key='country.country'>
                        <a class="text-body font-weight-semibold pb-2 d-block" data-toggle="collapse" href="javascript: void(0);" role="button" aria-expanded="false" v-b-toggle="`categories-collapse${country.country}`">
                            <i class="mdi mdi-chevron-up accor-down-icon text-primary mr-1"></i>
                            {{country.country}}
                        </a>
                        <b-collapse :id="`categories-collapse${country.country}`">
                            <div class="card p-2 border shadow-none">
                                <ul class="list-unstyled categories-list mb-0">
                                    <li v-for="cluster in country.clusters" >
                                        <a href="javascript: void(0);" @click="populate_cluster_data(cluster)" >
                                            <i class="mdi mdi-circle-medium mr-1"></i> {{cluster.cluster_name}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </b-collapse>
                    </div>
                </div>
                <div class="custom-accordion"></div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8" style="width:58%;right:0.5rem">
            <div class="card" style=";">

                <div class="card-body" >

                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <h5>
                                     <nuxt-link
                                     :to="{path:'/subject-management/manage-clusters', query:{id:current_cluster.clusters_id} }"
                                      class="px-2 text-primary" v-b-tooltip.hover title="Edit" v-if="current_cluster!=null">
                                       <i class="uil uil-pen font-size-18"></i>
                                     </nuxt-link> 

                                     <span v-if="current_cluster!= null">
                                      <span class="pathway-nm-0"> {{current_cluster.cluster_name}} </span>
                                     </span>| details 
                                    </h5>
                                </div>
                            </div>

                            <div v-if="current_cluster" class="move-right">
                                <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="newSubjectPopup()" >
                                    <span class="bck-to-0">&nbsp;&nbsp;Add new Subject&nbsp;&nbsp;</span>
                                </a>
                            </div>

                            <BlockUI  v-if="subject_form">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>
                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>

                                <span class="close" @click="newSubjectClose()"></span>
                                <h4> Add Subjects to cluster</h4>
                                <form v-on:submit.prevent="submitClusterSubject" class="form-horizontal" role="form">                          
                                
                                    <div  class="item_holderx">
                                      <label class="pd-hld" id="current_position" >Subjects</label>
                                      <treeselect v-model="form_data.subject_ids" :multiple="true" 
                                       :options="build_data.subjects" />
                                    </div> 

                                  <div class="item_holderx">
                                  <label class="pd-hld" id="current_position" >Educations type</label>
                                    <b-form-select    
                                    v-model="form_data.education_type_id"
                                    :options="build_data.education_types"
                                    class="mb-3 select_ecp"
                                    value-field="id"
                                    text-field="education_type_name"
                                    disabled-field="notEnabled"
                                    placeholder="Education types"
                                    >
                                    <b-form-select-option >
                                     Educations type</b-form-select-option>
                                    </b-form-select>
                                   </div> 

                                  <div class="item_holderx">
                                   <label class="pd-hld" id="current_position" >Grade</label>
                                    <b-form-select    
                                    v-model="form_data.grade"
                                    :options="build_data.grades"
                                    class="mb-3 select_ecp"
                                    value-field="grade"
                                    text-field="grade"
                                    disabled-field="notEnabled"
                                    placeholder="Grades"
                                    >
                                    <b-form-select-option >
                                     Select grade</b-form-select-option>
                                    </b-form-select>
                                   </div> 

                                  <div class="item_holderx">
                                   <label class="pd-hld" id="current_position" >Subject type</label>
                                    <b-form-select    
                                    v-model="form_data.is_primary"
                                    :options="build_data.is_primary"
                                    class="mb-3 select_ecp"
                                    value-field="id"
                                    text-field="value"
                                    disabled-field="notEnabled"
                                    placeholder="Grades"
                                    >
                                    <b-form-select-option >
                                     Select subject type</b-form-select-option>
                                    </b-form-select>
                                   </div>
                                  <div class="form-group position-relative">
                                    

                                    <div  class="item_holderx">
                                    <label class="pd-hld move-left" id="current_position" >Academic disciplines</label>
                                      <b-form-select   
                                      v-model="form_data.course_code"
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


                                  </div>

                                   <hr>
                                  <div  class="col-xl-3">

                                  <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-left">
                                      Submit
                                  </button>
                                  </div>

                                </form>
                                </br>
                            </BlockUI>
                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">
                            <li class="nav-item">
                                <a class="nav-link active tablinks" @click="tabs_control($event,'eligibility-creteria')" href="javascript:void(0)">Cluster Subjects:</a>
                            </li>
                        </ul>

                        <div class="row">                  
                           <div id="eligibility-creteria" class="tabcontent active">
                            <b-container class="bv-example-row">
                            <b-row>
                              <b-col sm="auto" class="list_sec1">
                                  <div class="col-12">
                                    <!-- Table -->
                                    <div class="row mt-4">
                                       <span v-if="current_cluster==null" > </br></br>Nothing to show right now </br>Select a cluster on the list to your left!!</span>
                                       <div  v-else class="table-responsive mb-0 table_holder">

                                       <b-spinner label="Loading..." v-if="loading_in_modal" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>
                                       <!-- Table -->
                                        <div class="table-responsive mb-0 " >
                                            <b-table 
                                            :items="cluster_subjects"
                                            :fields="cluster_fields" 
                                            responsive="sm" :per-page="perPage" 
                                            :current-page="currentPage" 
                                            :sort-by.sync="sortBy" 
                                            :sort-desc.sync="sortDesc" 
                                            :filter="filter" 
                                            :filter-included-fields="filterOn" 
                                            @filtered="onFiltered">
                                                  <template v-slot:cell(name)="data">
                                                      <div class="details_field px-2">  
                                                          <div style='margin-left:10px;'>
                                                            {{ data.item.subject }}</br></br>
                                                             <div style="font-size:.6em"></div>
                                                          </div>
                                                     </div>
                                                  </template>

                                                  <template v-slot:cell(country)="data">
                                                      <div class="details_field px-2">
                                                          <div style='margin-left:10px;'>
                                                              {{ data.item.country}}</br>
                                                             <div style="font-size:.6em"></div>
                                                          </div>
                                                     </div>
                                                  </template>

                                                  <template v-slot:cell(type)="data">
                                                      <div class="details_field px-2">
                                                          <div v-if="data.item.is_primary==1" style='margin-left:10px;'>
                                                              Manadtory</br>
                                                             <div style="font-size:.6em"></div>
                                                          </div>
                                                          <div v-else="data.item.is_primary==0" style='margin-left:10px;'>
                                                               Alternative</br>
                                                             <div style="font-size:.6em"></div>
                                                          </div>

                                                     </div>
                                                  </template>

                                                  <template v-slot:cell(actions)="data">
                                                     <!-- <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="editPopup(data.item)" v-b-tooltip.hover title="Edit">
                                                         <i class="uil uil-pen font-size-18"></i>
                                                      </a> -->

                                                      <a href="javascript:void(0);" role="button" class="px-2 " @click="confirmSubmitAction(data.item)" v-b-tooltip.hover title="delete">
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
                                                <b-button variant="primary" class="btn btn-primary waves-effect waves-light mb-3" @click="cancelSubmitActions" >Cancel
                                                </b-button>
                                                <button  type="submit" @click="delete_this(current_delete_item)" id="submit_alumni" class="btn confirm-002 waves-effect waves-light mb-3">
                                                  I know what im doing
                                                </button>
                                              </div>
                                            </BlockUI>

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
                             </b-col>
          
                            </b-row>
                          </b-container>

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

.form_sec0{
  max-width:100%;
  min-width:100%;  
}

.search_header_wrapper{
    min-height:3rem;
    width:100%;
}
.move-right{
    width:50%;
}

.dynamic_input{
    padding:1rem;
}

.item_holderx{
    width:45%;
    float:left;
    padding-left:1rem;
    margin-top:0px !important;
}

.list_sec1{
    width:100% !important;
}

.close {
  position: absolute;
  right: 32px;
  top: 10px;
  width: 20px;
  height: 20px;
  opacity: 0.3;
}
.close:hover {
  opacity: 1;
}
.close:before, .close:after {
  position: absolute;
  left: 15px;
  content: ' ';
  height: 20px;
  width: 2px;
  background-color: #333;
}
.close:before {
  transform: rotate(45deg);
}
.close:after {
  transform: rotate(-45deg);
}

.move-left{
  float:left;
  margin-left:1rem;
}

.btn-primary{
   max-width:90%; 
}
</style>
