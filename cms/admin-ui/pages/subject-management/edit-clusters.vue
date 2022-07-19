<script>
/**
 * Basic table component
 */
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import PathwaysService from "@/helpers/services/PathwaysService";

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
            cluster_list:[],
            build_data:[],
            clusterFormData:{
              cluster_name:"",
            },
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
            cluster_fields: [
                {
                  key: "check",
                  label: "",
                },
                {
                  key: "cluster_name",
                  label: 'Cluster name',
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
    created(){
     this.getClusters();
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
            console.log(response);
            if (response.data.status) {
                console.log("data built")
                self.build_data=response.data
                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;

            }
        },
        async createCluster() {
            let self = this; 
            console.log("this is create cluster.. processing..")
            console.log(this.clusterFormData.cluster_name)
           //start loading
            this.$nextTick(() => {
            });

            //console.log("getting pathway data")
            let response = await ClusterManagementService.createCluster(this.clusterFormData.cluster_name);
            console.log(response);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;               
                self.FormSubmittedSuccess = response.data.message;  
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
        async getClusters() {
            let self = this; 
            console.log("getting clusters")

           //start loading
            this.$nextTick(() => {
            });

            let response = await ClusterManagementService.listClusters();
            console.log(response)
            if (response.data.status) {
                self.cluster_list = response.data.data.items;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        async getCLusterSubjects(cluster_id) {
            let self = this; 
            console.log("getting cluster subjects")

           //start loading
            this.$nextTick(() => {
            });

            let response = await ClusterManagementService.listClusters();
            console.log(response)
            if (response.data.status) {
                self.cluster_list = response.data.data.items;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        /**
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
                                      <span class="pathway-nm-0"> Add New Clusters </span>
                                     </span> 

                                    </h5>

                                    <ol class="breadcrumb p-0 bg-transparent mb-2">
                                    </ol>

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
                                <a class="nav-link active tablinks" @click="tabs_control($event,'eligibility-creteria')" href="javascript:void(0)">Add Clusters:</a>
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

                                                    <h4 class="card-title"> Add a New Cluster </h4>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <form v-on:submit.prevent="createCluster" class="form-horizontal" role="form">
                                                           
                                                                  <label  class="pd-hld">Cluster Name</label>
                                                                  <input v-model="clusterFormData.cluster_name" type="text"
                                                                   class="form-control"
                                                                   placeholder="Name" name="Cluster_name"/>

                                                                  <div class="form-group position-relative">                                                            
                                                                  </div>
                                                                   <hr>
                                                                  <button type="submit" id="submit_alumni" class="btn btn-primary w-md">
                                                                    Submit
                                                                  </button>
                                                            </form>
                                                            </br>
                                                            <div class="alert alert-danger" v-if="FormSubmittedError">
                                                              <p> FormSubmittedErrorText </p>
                                                            </div>

                                                            <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                                              <p>FormSubmittedSuccessText </p>
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
                                      Cluster List 
                                    <div class="table-responsive mb-0">
                                      <b-table
                                        table-class="table table-centered datatable table-card-list"
                                        thead-tr-class="bg-transparent"
                                        :items="cluster_list"
                                        :fields="cluster_fields"
                                        responsive="sm"
                                        :filter="filter"
                                        :filter-included-fields="filterOn" @filtered="onFiltered">
                                        :sort-by.sync="sortBy"
                                        :sort-desc.sync="sortDesc"
                                        >
                                        <template v-slot:cell(name)="data">
                                        {{data}}
                                          <div class="name-cont-1">
                                            <div class='container2 t-wrap'>
                                               <div class='container2 t-wrap'></div>
                                            </div>

                                          </div> 
                                        </template>

                                        <template v-slot:cell(action)="data">
                                          <ul class="list-inline mb-0">

                                            <li class="list-inline-item">
                                              <nuxt-link
                                                :to="{path:'/pathways/pathways/new-pathway', query:{eid:data.item.id} }"
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
                                              <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"  v-if="confirm_check==data.item.id">

                                                <span class="px-2 text-primary" v-b-tooltip.hover title="Cancel delete"  @click="delete_cancel()" >
                                                  <span class="active-st-1"><i class="uil uil-check font-size-18"></i> Cancel </span>            
                                                </span></br></br>

                                                <span class="px-2 text-primary" :data-delete="`${data.item.name}`" v-b-tooltip.hover title="Confirm delete" @click="delete_this_pathway(data.item.id)">  
                                                  <span class="active-st-0"> <i class="uil uil-trash-alt font-size-17"></i>
                                                    confirm 
                                                    <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_del"></b-spinner>
                                                  </span>                            
                                                </span>

                                              </span>
                                        </template>

                                     </b-table>
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
  max-width:55%;
  min-width:55%;  
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


