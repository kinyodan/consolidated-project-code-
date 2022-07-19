<script>
/**
 * Basic table component
 */
import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import {BlockUI} from 'vue-blockui'

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
            title: "Subjects|",
            education_type_list:[],
            formData:{
                name:"",
                country:""
            },
            build_data:{
                subjects:[],
                education_types:[],
                countries:[],
            },  
            fields: [
                {
                  key: "check",
                  label: "",
                },
                {
                  key: "name",
                  label: ' name',
                  sortable: true
                },
                {
                  key: "actions",
                  label: 'Actions',
                  sortable: true
                },
            ],
            FormSubmittedSuccessText:"",
            FormSubmittedSuccess:false,
            FormSubmittedError:false,
            FormSubmittedErrorText:"",
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
      BlockUI
    },
    created(){
     this.getSubjects();
     this.build();
     if (this.$route.query.id){
        this.editing = true;
     }
    },
    middleware: "authentication",
    methods: {
        async build() {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

            //console.log("getting pathway data")
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
        formSubmit() {
          if (this.editing){
           this.updateEducationtypes();
          }else{
            this.createEducationtypes();
          }
        },
        async createEducationtypes() {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

            //console.log("getting pathway data")
            let response = await StudentManagementService.createSubject(this.formData.name);
            console.log(response);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccess = response.data.message; 
                this.getSubjects();   
                this.formData.name="";            

                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;

            }
        },
        async updateEducationtypes() {
            let self = this; 
            console.log("this is updateCluster processing..")
            console.log(this.clusterFormData.cluster_name)
           //start loading
            this.$nextTick(() => {
            });

            //console.log("getting pathway data")
            let response = await ClusterManagementService.updateCluster(this.current_cluster.id,this.clusterFormData.cluster_name);
            console.log(response);
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
            console.log("getting lessons")

           //start loading
            this.$nextTick(() => {
            });

            let response = await StudentManagementService.listSubjects();
            console.log(response)
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
        handlePageChange(){

        }
    },    

};
</script>

<template>
<div>

 <div class="row">

        <div class="col-xl-9 col-lg-8" style="right:0.5rem">
            <div class="card" style=";">
                <div class="card-body" >
                    <b-spinner class="m-2" variant="success" role="status" v-if="loading_in_modal"></b-spinner>
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                
                                    <h5>

                                     <span >
                                      <span class="pathway-nm-0"> Add New Education type </span>
                                     </span> 

                                    </h5>

                                    <ol class="breadcrumb p-0 bg-transparent mb-2">
                                    </ol>

                                </div>
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>

                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-inline float-md-right">
                                    <div class="search-box ml-2">
                                        <div class="position-relative">
                                            <input type="text" class="form-control bg-light border-light rounded" placeholder="Search..." />
                                            <i class="mdi mdi-magnify search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">

                            <li class="nav-item">
                                <a class="nav-link active tablinks" @click="tabs_control($event,'eligibility-creteria')" href="javascript:void(0)">Add Education type :</a>
                            </li>
                        </ul>

                        <div class="row">
                      
                           <div id="eligibility-creteria" class="tabcontent active">
                            <b-container class="bv-example-row">
                            <b-row>
                              <b-col class="form_sec0" sm="auto" >

                                 <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">


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
                                                    
                                                    <!-- end row -->
                                                </div>
                                            </div>
                                            <!-- end card -->
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->
                              </b-col>
                              <!--
                               ########### end left column ######## -->
                              <b-col sm="auto" class="list_sec1">
                                <div class="row">
                                  <div class="col-12">
                                    <!-- Table -->

                                    <div class="row mt-4">
                                      <div class="col-sm-12 "></div>
                                      Education type List 
                                    <div class="table-responsive mb-0">
                                      <b-table
                                        table-class="table table-centered datatable table-card-list"
                                        thead-tr-class="bg-transparent"
                                        :items="build_data.education_types"
                                        :fields="fields"
                                        responsive="sm"
                                        :filter="filter"
                                        :filter-included-fields="filterOn" @filtered="onFiltered">
                                        :sort-by.sync="sortBy"
                                        :sort-desc.sync="sortDesc"
                                        >
                                        <template v-slot:cell(name)="data">
                                          <div class="name-cont-1">
                                            <div class='container2 t-wrap'>
                                               <div class='container2 t-wrap'>{{data.item.education_type_name}}</div>
                                            </div>

                                          </div> 
                                        </template>

                                        <template v-slot:cell(actions)="data">
                                          <ul class="list-inline mb-0">

                                            <li class="list-inline-item">
                                              <nuxt-link
                                                :to="{path:'/subject-management/education-types', query:{id:data.item.id} }"
                                                class="px-2 text-primary" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                                              </nuxt-link>
                                            </li>

                                            <li class="list-inline-item actions-edt-del-1" >
                                              <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2" @click="pop_confirm(data.item.id)"
                                                 :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                                <i class="uil uil-trash-alt font-size-18"></i>
                                              </a></br>

                                            </li>

                                          </ul>

                                        </template>


                                     </b-table>
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

                         <div id="employers" class="tabcontent">      </div>


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
  max-width:40%;
  min-width:40%;  
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

.col-xl-9{
    min-width:95% !important;
}

</style>


